<?php


namespace App\Http\Controllers\Account;

use App\Http\Requests\ReplyMessageRequest;
use App\Models\User;
use App\Models\Message;
use App\Notifications\ReplySent;
use Torann\LaravelMetaTags\Facades\MetaTag;
use Illuminate\Http\Request;
use App\Models\City;
use App\UserSource;
use App\UserRating;
use App\TrackedEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Post;
use App\Models\Permission;
use App\Models\Resume;
use Barryvdh\Debugbar\Facade as Debugbar;

class ConversationsController extends AccountBaseController
{
	private $perPage = 10;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->perPage = (is_numeric(config('settings.listing.items_per_page'))) ? config('settings.listing.items_per_page') : $this->perPage;
	}
	
	/**
	 * Conversations List
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		$data = [];
		// Set the Page Path
		view()->share('pagePath', 'conversations');
		// Get the Conversations
		$data['conversations'] = $this->conversations->paginate($this->perPage);
		// dd($data['conversations']);
		$data['conversations']->each(function ($item, $key) {
			$fromUserId = $item->from_user_id;
			if($fromUserId != 0)
			{
				$senderJobRole = User::where('id',$fromUserId)->first('job_role');
				if(!empty($senderJobRole))
				    $senderJobRole = $senderJobRole->getAttribute('job_role');
			}
			else
				$senderJobRole = '';
			$item->setAttribute('job_role',$senderJobRole);
		});



		// Meta Tags
		MetaTag::set('title', t('Conversations Received'));
		MetaTag::set('description', t('Conversations Received on :app_name', ['app_name' => config('settings.app.app_name')]));
		
		return view('account.conversations', $data);
	}
	
	/**
	 * Conversation Messages List
	 *
	 * @param $conversationId
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function messages($conversationId)
	{
		$data = [];
		
		// Set the Page Path
		view()->share('pagePath', 'conversations');
		
		// Get the Conversation
		$conversation = Message::where('id', $conversationId)
			->byUserId(auth()->user()->id)
			->firstOrFail();
		if((is_object($conversation) && $conversation->count() == 0) || (empty($conversation) && !empty(auth()->user()->company_id)))
		{
		    $conversation = Message::where('id', $conversationId)
    		    ->where('company_id',auth()->user()->company_id)
    		    ->firstOrFail();
		}
		view()->share('conversation', $conversation);
		
		// Get the Conversation's Messages
		$data['messages'] = Message::where('parent_id', $conversation->id)
			->byUserId(auth()->user()->id)
			->orderByDesc('id');
		
		if((is_object($data['messages']) && $data['messages']->count() == 0) || (empty($data['messages']) && !empty(auth()->user()->company_id)))
		{
		    $data['messages'] = Message::where('parent_id', $conversation->id)
    		    ->where('company_id',auth()->user()->company_id)
    		    ->orderByDesc('id');
		}
		$data['countMessages'] = $data['messages']->count();
		$data['messages'] = $data['messages']->paginate($this->perPage);
		
		// Mark the Conversation as Read
		if ($conversation->is_read != 1) {
			if ($data['countMessages'] > 0) {
				// Check if the latest Message is from the current logged user
				if ($data['messages']->has(0)) {
					$latestMessage = $data['messages']->get(0);
					if ($latestMessage->from_user_id != auth()->user()->id) {
						$conversation->is_read = 1;
						$conversation->save();
					}
				}
			} else {
				if ($conversation->from_user_id != auth()->user()->id) {
					$conversation->is_read = 1;
					$conversation->save();
				}
			}
		}

		$data['cvText'] = Resume::getCvText($conversation->filename);
		$data['postId'] = $conversation->post_id;

		// Meta Tags
		MetaTag::set('title', t('Messages Received'));
		MetaTag::set('description', t('Messages Received on :app_name', ['app_name' => config('settings.app.app_name')]));
		
		return view('account.messages', $data);
	}
	
	/**
	 * @param $conversationId
	 * @param ReplyMessageRequest $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function reply($conversationId, ReplyMessageRequest $request)
	{
		// Get Conversation
		$conversation = Message::findOrFail($conversationId);
		
		// Get Recipient Data
		if ($conversation->from_user_id != auth()->user()->id && auth()->user()->user_type_id == 1
		    || (!empty($conversation->company_id) && !empty(auth()->user()->company_id) && auth()->user()->company_id == $conversation->company_id)) {
			$toUserId = $conversation->from_user_id;
			$toName = $conversation->from_name;
			$toEmail = $conversation->from_email;
			$toPhone = $conversation->from_phone;
		} else {
			$toUserId = $conversation->to_user_id;
			$toName = $conversation->to_name;
			$toEmail = $conversation->to_email;
			$toPhone = $conversation->to_phone;
		}
		
		// Don't reply to deleted (or non exiting) users
		if (config('settings.single.guests_can_post_ads') != 1 && config('settings.single.guests_can_contact_ads_authors') != 1) {
			if (User::where('id', $toUserId)->count() <= 0) {
				flash(t("This user no longer exists.") . ' ' . t("Maybe the user's account has been disabled or deleted."))->error();
				return back();
			}
		}
		
		// New Message
		$message = new Message();
		$input = $request->only($message->getFillable());
		foreach ($input as $key => $value) {
			$message->{$key} = $value;
		}
		
		$message->post_id = $conversation->post->id;
		$message->parent_id = $conversation->id;
		$message->from_user_id = auth()->user()->id;
		$message->from_name = auth()->user()->name;
		$message->from_email = auth()->user()->email;
		$message->from_phone = auth()->user()->phone;
		$message->to_user_id = $toUserId;
		$message->to_name = $toName;
		$message->to_email = $toEmail;
		$message->to_phone = $toPhone;
		$message->subject = 'RE: ' . $conversation->subject;
		$message->company_id = auth()->user()->company_id;
		
		$attr = ['slug' => slugify($conversation->post->title), 'id' => $conversation->post->id];
		$message->message = $request->input('message')
			. '<br><br>'
			. t('Related to the ad')
			. ': <a href="' . lurl($conversation->post->uri, $attr) . '">' . t('Click here to see') . '</a>';
		
		// Save
		$message->save();
		
		// Save and Send user's resume
		if ($request->hasFile('filename')) {
			$message->filename = $request->file('filename');
			$message->save();
		}
		
		// Mark the Conversation as Unread
		if ($conversation->is_read != 0) {
			$conversation->is_read = 0;
			$conversation->save();
		}
		
		// Send Reply Email
		try {
			$message->notify(new ReplySent($message));
			flash(t("Your reply has been sent. Thank you!"))->success();
		} catch (\Exception $e) {
			flash($e->getMessage())->error();
		}
		
		return back();
	}
	
	/**
	 * Delete Conversation
	 *
	 * @param null $conversationId
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function destroy($conversationId = null)
	{
		// Get Entries ID
		$ids = [];
		if (request()->filled('entries')) {
			$ids = request()->input('entries');
		} else {
			if (!is_numeric($conversationId) && $conversationId <= 0) {
				$ids = [];
			} else {
				$ids[] = $conversationId;
			}
		}
		
		// Delete
		$nb = 0;
		foreach ($ids as $item) {
			// Get the conversation
			$message = Message::where('id', $item)
				->byUserId(auth()->user()->id)
				->first();
			if(empty($message) && !empty(auth()->user()->company_id))
			{
			    $message = Message::where('id', $item)
			        ->where('company_id',auth()->user()->company_id)
    			    ->first();
			}
			
			if (!empty($message)) {
				if (empty($message->deleted_by)) {
					// Delete the Entry for current user
					$message->deleted_by = auth()->user()->id;
					$message->save();
					$nb = 1;
				} else {
					// If the 2nd user delete the Entry,
					// Delete the Entry (definitely)
					if ($message->deleted_by != auth()->user()->id) {
						$nb = $message->delete();
					}
				}
			}
		}
		
		// Confirmation
		if ($nb == 0) {
			flash(t("No deletion is done. Please try again."))->error();
		} else {
			$count = count($ids);
			if ($count > 1) {
				flash(t("x :entities has been deleted successfully.", ['entities' => t('messages'), 'count' => $count]))->success();
			} else {
				flash(t("1 :entity has been deleted successfully.", ['entity' => t('message')]))->success();
			}
		}
		
		return back();
	}
	
	/**
	 * Delete Message
	 *
	 * @param $conversationId
	 * @param null $messageId
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function destroyMessages($conversationId, $messageId = null)
	{
		// Get Entries ID
		$ids = [];
		if (request()->filled('entries')) {
			$ids = request()->input('entries');
		} else {
			if (!is_numeric($messageId) && $messageId <= 0) {
				$ids = [];
			} else {
				$ids[] = $messageId;
			}
		}
		
		// Delete
		$nb = 0;
		foreach ($ids as $item) {
			// Don't delete the main conversation
			if ($item == $conversationId) {
				continue;
			}
			
			// Get the message
			$message = Message::where('parent_id', $conversationId)->where('id', $item)
				->byUserId(auth()->user()->id)
				->first();
			
			if(empty($message) && !empty(auth()->user()->company_id))
			{
			    $message = Message::where('id', $item)
			    ->where('company_id',auth()->user()->company_id)
			    ->first();
			}
			if (!empty($message)) {
				if (empty($message->deleted_by)) {
					// Delete the Entry for current user
					$message->deleted_by = auth()->user()->id;
					$message->save();
					$nb = 1;
				} else {
					// If the 2nd user delete the Entry,
					// Delete the Entry (definitely)
					if ($message->deleted_by != auth()->user()->id) {
						$nb = $message->delete();
					}
				}
			}
		}
		
		// Confirmation
		if ($nb == 0) {
			flash(t("No deletion is done. Please try again."))->error();
		} else {
			$count = count($ids);
			if ($count > 1) {
				flash(t("x :entities has been deleted successfully.", ['entities' => t('messages'), 'count' => $count]))->success();
			} else {
				flash(t("1 :entity has been deleted successfully.", ['entity' => t('message')]))->success();
			}
		}
		
		return back();
	}

	public function applicants($id = null)
	{
		$data = [];
		$isAjaxRequest = request()->ajax();
		$filterData = [];
		//if($isAjaxRequest)
		$filterData = array_filter(request()->query());
			//return response()->json(['data'=>$filterData], 200);
		// Set the Page Path
		view()->share('pagePath', 'conversations');
		
		$cities = City::where([['active', '=', 1], ['country_code', '=', 'IQ']])->get();
		// Get the Conversations
		$data['people'] = $this->getPostConversations($id, $filterData)->paginate($this->perPage);
		$data['people']->each(function ($item, $key) {
			$fromUserId = $item->from_user_id;
			$senderJobRole = '';
			$cvText = '';
			if($fromUserId != 0)
			{
				$senderData = User::where('id',$fromUserId)->first();
				if(!empty($senderData) && is_object($senderData))
				{
				    $senderJobRole = $senderData->job_role;
				    $cvText = $senderData->free_text;
				}
			}
			$item->setAttribute('job_role',$senderJobRole);
			$item->setAttribute('cvText',$cvText);
// 			$item->setAttribute('cvText',Resume::getCvText($item->filename));
		});
		
		$userId = auth()->user()->id;
		$stagesTotalCount = 0;

		if(!$isAjaxRequest)
		{
			$stagesCounts = DB::table('messages')
			->select(DB::raw('count(*) as stage_count, applicant_stage'))
			->where('messages.to_user_id', '=', $userId)
			->where('messages.deleted_by', '=', null)
			->where('messages.post_id', '=', $id)
			->groupBy('applicant_stage')
			->get();
			
			if((is_object($stagesCounts) && $stagesCounts->count() == 0) || (empty($stagesCounts) && !empty(auth()->user()->company_id)))
			{
			    $stagesCounts = DB::table('messages')
			    ->select(DB::raw('count(*) as stage_count, applicant_stage'))
			    ->where('messages.company_id', '=', auth()->user()->company_id)
			    ->where('messages.deleted_by', '=', null)
			    ->where('messages.post_id', '=', $id)
			    ->groupBy('applicant_stage')
			    ->get();
			}

			$stagesCountsArr = [];
			foreach($stagesCounts as $value)
			{
				$stagesCountsArr[$value->applicant_stage] = $value->stage_count;
				if($value->applicant_stage != null)
				   $stagesTotalCount += $value->stage_count;
			}
			$data['stagesCounts'] = $stagesCountsArr;

			$postData = Post::where('user_id', auth()->user()->id)->where('id', $id)->first();
			if((is_object($postData) && $postData->count() == 0) || (empty($postData) && !empty(auth()->user()->company_id)))
			{
			    $postData = Post::where('company_id', auth()->user()->company_id)->where('id', $id)->first();
			}
			if(empty($postData) && !empty(auth()->user()->company_id))
			    $postData = Post::where('company_id', auth()->user()->company_id)->where('id', $id)->first();
			$data['postData'] = $postData;
		}

		$posts = DB::table('posts')
		->select('id', 'title', 'company_name')
		->where('user_id', '=', $userId)->get();
		
		if((is_object($posts) && $posts->count() == 0) || (empty($posts) && !empty(auth()->user()->company_id)))
		{
		    $posts = DB::table('posts')
    		    ->select('id', 'title', 'company_name')
    		    ->where('company_id', '=', auth()->user()->company_id)->get();
		}
		
		$data['stagesTotalCount'] = $stagesTotalCount;
		$data['postId'] = $id;
		$data['cities'] = $cities;
		$data['filterAjaxUrl'] = lurl('account/conversations/'.$id.'/applicants');
		$data['filterData'] = $filterData;

		$ratings = [];
		$userRatings = UserRating::where('rated_by_user_id', '=', $userId)->get();
		if(!empty($userRatings))
		{
			foreach($userRatings as $rating)
				$ratings[$rating->user_id] = $rating->rating;
		}

		// Meta Tags
		MetaTag::set('title', t('Conversations Received'));
		MetaTag::set('description', t('Conversations Received on :app_name', ['app_name' => config('settings.app.app_name')]));

		$data['posts'] = $posts;
		$data['userRatings'] = $ratings;
		  
		// Meta Tags
		MetaTag::set('title', 'my people');
		MetaTag::set('description', 'people list on '.config('settings.app.app_name'));
		
		if($isAjaxRequest)
		{
		    $filterData = array_filter(request()->query());
			$data['people'] = $this->getPostConversations($id,$filterData, true);
// 			if(isset($filterData['is_search']) && $filterData['is_search'] == 1)
//             {
//                 $data['people'] = $data['people']->get();
// 				$searchKeywords = explode(' ',$filterData['search_keyword'])??[];
// 				$usersIds = [];
// 				foreach($data['people'] as $value)
// 				{
// 					$usersIds[] = $value->from_user_id;
// 				}

// 				$ajaxPeopleIds = DB::table('users')
// 					->where('deleted_at', '=', null)
// 					->where('user_type_id', '=', 2)
// 					->whereIn('id', $usersIds)
// 					->where(function($query) use ($searchKeywords) {
// 						foreach($searchKeywords as $keyword)
// 						{
// 							$query->orWhere('name', 'like', '%' . $keyword . '%')
// 							->orWhere('about', 'like', '%' . $keyword . '%')				
// 							->orWhere('phone', 'like', '%' . $keyword . '%')
// 							->orWhere('username', 'like', '%' . $keyword . '%')
// 							->orWhere('email', 'like', '%' . $keyword . '%')
// 							->orWhere('current_job_title', 'like', '%' . $keyword . '%')
// 							->orWhere('preferred_job_title', 'like', '%' . $keyword . '%')
// 							->orWhere('name_ar', 'like', '%' . $keyword . '%')
// 							->orWhere('martial_status', 'like', '%' . $keyword . '%')
// 							->orWhere('free_text', 'like', '%' . $keyword . '%');
// 						}
// 					})
// 					->orderBy('created_at', 'DESC')->get('id');

// 				$filterData['users_ids'] = $ajaxPeopleIds;
// 				$data['people'] = $this->getPostConversations($id,$filterData, true)->paginate($this->perPage);
//             }
//             else
//             {
            $data['people'] = $data['people']->paginate($this->perPage);
//             }
			$view=view('account.jobApplicantsFilterData', $data);
			$view=$view->render();
			$pagination=view('account.jobApplicantsFilterPagination', ['conversations'=>$data['people']]);
			$pagination=$pagination->render();
			return response()->json(['html'=>$view, 'pagination'=>$pagination], 200);
		}
		
		// if($isAjaxRequest)
		// {
		// 	$view=view('account.jobApplicantsFilterData', $data);
		// 	$view=$view->render();
		// 	$pagination=view('account.jobApplicantsFilterPagination', $data);
		// 	$pagination=$pagination->render();
		// 	return response()->json(['html'=>$view, 'pagination'=>$pagination], 200);
		// }
			
		return view('account.myPeople', $data);
	}

	public function addNote(Request $request)
	{
		$message = Message::findOrFail($request->input('message_id'));
		$message->applicant_note = $request->input('applicant_note');
		$message->save();

		return back();
	}

	public function changeApplicationStage($id)
	{
		$requestArr = request()->query();
		$message = Message::findOrFail($id);
		$message->applicant_stage = (int)$requestArr['applicant_stage'];
		$message->save();

		return response()->json(['result'=>$id], 200);
	}

	public function myPeople()
	{	
		$isAjaxRequest = request()->ajax();
		$data = [];
		$filterData = request()->query();
		//view()->share('pagePath', 'conversations');
		$userId = auth()->user()->id;
		if(!$isAjaxRequest)
		{
		    if(!empty($filterData) && is_array($filterData) && count($filterData) > 1)
		    {
		        $people = $this->getPostConversations(0,$filterData, true);
		    }
		    else 
		    {
    			$people = DB::table('users')
    				->join('messages', 'messages.from_user_id','=', 'users.id')
    				->select('messages.id as message_id','messages.*', 'users.id as user_id', 'users.*')
    				->where('messages.to_user_id', '=', $userId)
    				->where('messages.deleted_by', '=', null)
    				->orderBy('messages.created_at', 'DESC');
		    }
			if((is_object($people) && $people->count() == 0) || (empty($people) && !empty(auth()->user()->company_id)))
			{
			    $people = DB::table('users')
    			    ->join('messages', 'messages.from_user_id','=', 'users.id')
    			    ->select('messages.id as message_id','messages.*', 'users.id as user_id', 'users.*')
    			    ->where('messages.company_id', '=', auth()->user()->company_id)
    			    ->where('messages.deleted_by', '=', null)
    			    ->orderBy('messages.created_at', 'DESC');
			}

			$stagesCounts = DB::table('messages')
				->select(DB::raw('count(*) as stage_count, applicant_stage'))
				->where('messages.to_user_id', '=', $userId)
				->where('messages.deleted_by', '=', null)
				->groupBy('applicant_stage')
				->get();
			
			if((is_object($stagesCounts) && $stagesCounts->count() == 0) || (empty($stagesCounts) && !empty(auth()->user()->company_id)))
			{
			    $stagesCounts = DB::table('messages')
    			    ->select(DB::raw('count(*) as stage_count, applicant_stage'))
    			    ->where('messages.company_id', '=', auth()->user()->company_id)
    			    ->where('messages.deleted_by', '=', null)
    			    ->groupBy('applicant_stage')
    			    ->get();
			}

			$stagesCountsArr = [];
			foreach($stagesCounts as $value)
			{
				$stagesCountsArr[$value->applicant_stage] = $value->stage_count;
			}
			$data['stagesCounts'] = $stagesCountsArr;

			$data['totalCount'] = $people->count();
			$people = $people->paginate($this->perPage);
		}

		$posts = DB::table('posts')
			->select('id', 'title', 'company_name')
			->where('user_id', '=', $userId)->get();
		
		if((is_object($posts) && $posts->count() == 0) || (empty($posts) && !empty(auth()->user()->company_id)))
		{
		    $posts = DB::table('posts')
    		    ->select('id', 'title', 'company_name')
    		    ->where('company_id', '=', auth()->user()->company_id)
    		    ->paginate($this->perPage);
		}

		
		$ratings = [];
		$userRatings = UserRating::where('rated_by_user_id', '=', $userId)->get();
		if(!empty($userRatings))
		{
			foreach($userRatings as $rating)
				$ratings[$rating->user_id] = $rating->rating;
		}
		
		$peopleUsersIds = [];
		if(!$isAjaxRequest)
		{
			foreach($people as &$value)
			{
				$peopleUsersIds[] = $value->id;
				if(!empty(User::where('id',$value->from_user_id)->first('job_role')))
				{
					$value->job_role = User::where('id',$value->from_user_id)->first('job_role')->getAttribute('job_role');	
				}
// 				if (isset($value->free_text) && !empty($value->free_text))
// 				{
 				    $value->cvText = $value->free_text;
// 				}
// 				else
// 				{
//     				$value->cvText = Resume::getCvText($value->filename);
// 				}
			}
		}
		
		// Set the Page Path
		//view()->share('pagePath', 'conversations');
		
		$cities = City::where([['active', '=', 1], ['country_code', '=', 'IQ']])->get();
		$data['cities'] = $cities;
		if(!$isAjaxRequest)
		{
			$data['people'] = $people;
		}
		$data['usersIds'] = $peopleUsersIds;
		$data['posts'] = $posts;
		$data['userRatings'] = $ratings;
		$data['filterAjaxUrl'] = lurl('account/my-people');
		$data['filterData'] = $filterData;
		  
		// Meta Tags
		MetaTag::set('title', 'my people');
		MetaTag::set('description', 'people list on '.config('settings.app.app_name'));
		
		if($isAjaxRequest)
		{
			$filterData = request()->query();
			if (isset($filterData['note_keyword']) && empty($filterData['note_keyword']) && isset($filterData['is_search']) && $filterData['is_search'] == 1) {
			    unset($filterData['note_keyword']);
			}
			$data['people'] = $this->getPostConversations(0,$filterData, true);
// 			if(isset($filterData['is_search']) && $filterData['is_search'] == 1)
//             {
//                 $data['people'] = $data['people']->get();
// 				$searchKeywords = explode(' ',$filterData['search_keyword'])??[];
// 				$usersIds = [];
// 				foreach($data['people'] as $value)
// 				{
// 					$usersIds[] = $value->id;
// 				}
				
// 				$ajaxPeopleIds = DB::table('users')
// 					->where('deleted_at', '=', null)
// 					->where('user_type_id', '=', 2)
// 					->whereIn('id', $usersIds)
// 					->where(function($query) use ($searchKeywords) {
// 						foreach($searchKeywords as $keyword)
// 						{
// 							$query->orWhere('name', 'like', '%' . $keyword . '%')
// 							->orWhere('about', 'like', '%' . $keyword . '%')				
// 							->orWhere('phone', 'like', '%' . $keyword . '%')
// 							->orWhere('username', 'like', '%' . $keyword . '%')
// 							->orWhere('email', 'like', '%' . $keyword . '%')
// 							->orWhere('current_job_title', 'like', '%' . $keyword . '%')
// 							->orWhere('preferred_job_title', 'like', '%' . $keyword . '%')
// 							->orWhere('name_ar', 'like', '%' . $keyword . '%')
// 							->orWhere('martial_status', 'like', '%' . $keyword . '%')
// 							->orWhere('free_text', 'like', '%' . $keyword . '%');
// 						}
// 					})
// 					->orderBy('created_at', 'DESC')->get('id');

// 				$filterData['users_ids'] = $ajaxPeopleIds;
// 				$data['people'] = $this->getPostConversations(0,$filterData, true)->paginate($this->perPage);
//             }
//             else 
//             {
            $data['people'] = $data['people']->paginate($this->perPage);
//             }
            foreach($data['people'] as &$value)
            {
                if(!empty(User::where('id',$value->from_user_id)->first('job_role')))
                {
                    $value->job_role = User::where('id',$value->from_user_id)->first('job_role')->getAttribute('job_role');
                }
//                 if (isset($value->free_text) && !empty($value->free_text))
//                 {
                    $value->cvText = $value->free_text;
//                 }
//                 else
//                 {
//                     $value->cvText = Resume::getCvText($value->filename);
//                 }
            }
			$view=view('account.jobApplicantsFilterData', $data);
			$view=$view->render();

			$pagination=view('account.jobApplicantsFilterPagination', ['conversations'=>$data['people']]);
			$pagination=$pagination->render();
			return response()->json(['html'=>$view, 'pagination'=>$pagination], 200);
		}
			
		return view('account.myPeople', $data);
	}

	public function readCandidateResume(Request $request)
    {
		$data = array();

		$validator = Validator::make($request->all(), [
		   'resume' => 'required|mimes:txt,pdf,doc,docx'
		]);
  
		if ($validator->fails()) {
  
		   $data['success'] = 0;
		   $data['message'] = $validator->errors()->first('resume');// Error response
  
		}else{
		   if($request->file('resume')) {
  
			   $file = $request->file('resume');
			   $filename = time().'_'.$file->getClientOriginalName();
  
			   // File extension
			   $extension = $file->getClientOriginalExtension();
  
			   // File upload location
			   $location = public_path()."/storage/readResumes";
  
			   // Upload file
			   $file->move($location,$filename);
			   
			   // File path
			   $filepath = url("/storage/readResumes/".$filename);
                
			   $data['cvText'] = Resume::getCvText("readResumes/".$filename);
			   $data['resume'] = $request->file('resume');
  
			   // Response
			   if(!empty($data['cvText']))
			   {
			       $data['success'] = 1;
			       $data['message'] = 'Uploaded Successfully!';
			   }
			   else 
			   {
			       $data['success'] = 0;
			       $data['message'] = 'Unable to get the data from the CV file, Please fill the fields manually!';
			   }
			   $data['filepath'] = "readResumes/".$filename;
			   $data['extension'] = $extension;
		   }else{
			   // Response
			   $data['success'] = 2;
			   $data['message'] = 'File not uploaded.'; 
		   }
		}

		if(!empty($data['cvText']))
		{
			$pattern = '/[a-z0-9_\-\+\.]+@[a-z0-9\-]+\.([a-z]{2,4})(?:\.[a-z]{2})?/i';
			preg_match_all($pattern, $data['cvText'], $matches);
			if(count($matches) > 0 && isset($matches[0][0]))
			{
				$data["email"] = trim($matches[0][0]);
				$data["name"] = explode("@", $data["email"])[0];
			}
			
			$phonePattern = "/\b\s*[\+]?[\(]?[0-9]{3}[\)]?\s*[-]?\s*[0-9]{3}\s*[-]?\s*[0-9]{4,6}\b/";
			preg_match_all($phonePattern, $data['cvText'], $phoneMatches);
			if(count($phoneMatches) > 0 && isset($phoneMatches[0][0]))
				$data["phone"] = trim($phoneMatches[0][0]);
				
			$residenceCountry = include(base_path() . '/resources/lang/en/residenceCountry.php');
			$residenceCountry = array_map('strtolower', $residenceCountry);
			asort($residenceCountry);
			$residenceCountries = array_values($residenceCountry);
			$residenceCountries = implode("|",$residenceCountries);

			$residenceCountry = array_flip($residenceCountry);

			$pattern = '/('.$residenceCountries.')/i';
			preg_match_all($pattern, strtolower($data['cvText']), $matches);
			if(count($matches) > 0 && isset($matches[0][0]))
			{
				$matches[0][0] = trim($matches[0][0]);
				if(isset($residenceCountry[$matches[0][0]]))
					$data["country"] = $residenceCountry[$matches[0][0]];
			}
		}
  
		return response()->json($data);
    }

	public function addCandidate(Request $request)
    {
		$data = array();
		
		$validator = Validator::make($request->all(), [
		   'name' => 'required',
		   'email' => 'required',
		   'source_type' => 'required',
		   'residence_country' => 'required',
		   'regJobRole' => 'required',
		   'candidate_resume' => 'required',
		]);

		$userSource = UserSource::where('added_by_user_id', auth()->user()->id)
		->where('email', $request->input('email'))
		->where('source', 2) // 2 source type is referral
		->first();
		//return response()->json($request->file('resume')->getClientOriginalName());
		if(!empty($userSource))
		{
			$data['success'] = 1;
			$data['message'] = "The candidate is already added";
			return response()->json($data);
		}
  
		if ($validator->fails()) {
		   $data['formData'] = $request->all();
		   $data['success'] = 0;
		   $data['message'] = $validator->errors();// Error response
		} else {
			$user = new User();
			$user->name = $request->input('name');
			$user->email = $request->input('email');
			$user->user_type_id = 2;
			$user->password = Hash::make("AkojobsNewCandidatePassw0rd!");
			$user->residence_country = $request->input('residence_country');
			$user->phone = $request->input('phone');
			$user->country_code   = config('country.code');
			$user->user_source   = 2;
			$user->verified_phone = 1;
			$user->free_text = $request->input('free_text');
			$user->job_role = $request->input('regJobRole');
			$user->city_id = $request->input('city_id');
			$user->save();
			
			// Save user's resume
			$resume = new Resume();
			$resume->filename = $request->input('candidate_resume');
			$resume->country_code = config('country.code');
			$resume->user_id = $user->id;
			$resume->active = 1;
		    
		    $resume->save();
	
			$userSource = new UserSource();
			$userSource->added_by_user_id = auth()->user()->id;
			$userSource->email = $request->input('email');
			$userSource->source = '2';
			$userSource->save();
			
			$message = new Message();
			$message->post_id = $request->input('candidate_job');
			$message->from_user_id = $user->id;
			$message->from_name = $request->input('name');
			$message->from_email = $request->input('email');
			$message->from_phone = $request->input('phone');
			$message->to_user_id = auth()->user()->id;
			$message->to_name = auth()->user()->name;
			$message->to_email = auth()->user()->email;
			$message->to_phone = auth()->user()->phone;
			$message->applicant_stage = 8;
			$message->company_id = auth()->user()->company_id;
			$message->filename = $request->input('candidate_resume');
			$message->save();

			// Response
			$data['success'] = 1;
			$data['message'] = 'The candidate has been added successfully!';
		}
  
		return response()->json($data);
    }

	public function assignToJob(Request $request)
	{
		$postId = $request->input('job_id');
		$messageId = $request->input('conversation_id');
		if(!empty($postId))
		{
		    $postCompanyId = DB::table('posts')
    		    ->select("company_id")
    		    ->where('id', '=', $postId)
    		    ->first();
			$message = Message::findOrFail($messageId);
			$message->post_id = $postId;
			$message->company_id = $postCompanyId->company_id;
			$message->save();
		}
		return response()->json(['result'=>$messageId], 200);
	}

	public function addCandidateRating(Request $request)
	{
		$ratedUser = $request->input('rating_user');
		$rating = $request->input($ratedUser.'rating');
		if(!empty($rating))
		{
			UserRating::updateOrCreate(
				['rated_by_user_id' => auth()->user()->id, 'user_id' => $ratedUser],
				['rating' => $rating]
			);
		}
		return response()->json(['success'=>1, 'message'=>'Rating Saved'], 200);
	}

	public function allocateCandidatesToEmployers()
	{
	    if(!isAdminUser())
			return redirect()->back();

		$data = [];

		$jobseekers = DB::table('users')
			->where('deleted_at', '=', null)
			->where('user_type_id', '=', 2)
			->orderBy('created_at', 'DESC');

		$employers = DB::table('users')
			->select('users.id', 'companies.name')
			->join('companies', 'companies.user_id','=', 'users.id')
			->where('users.deleted_at', '=', null)
			->where('users.user_type_id', '=', 1)
			->orderBy('companies.created_at', 'DESC')
			->get();
		
		$cities = City::where([['active', '=', 1], ['country_code', '=', 'IQ']])->get();
		$data['cities'] = $cities;

		$jobseekers = $jobseekers->paginate($this->perPage);
		$data['jobseekers'] = $jobseekers;
		$data['employers'] = $employers;
		$data['filterAjaxUrl'] = lurl('account/allocate-candidates-filter');
		  
		// Meta Tags
		MetaTag::set('title', 'Allocate to employers');
		MetaTag::set('description', 'Allocate candidates to employers on '.config('settings.app.app_name'));
			
		return view('account.allocateCandidatesToEmployers', $data);
	}

	public function allocateCandidates(Request $request)
	{
		$validator = Validator::make($request->all(), [
		   'employersIds' => 'required',
		]);

		if ($validator->fails()) {
			flash("Please select jobseekers and the employers that you want to allocate")->error();
		} else {
			$employersIds = $request->input('employersIds');
			$jobseekersIds = $request->input('entries');
			$numberOfCandidates = $request->input('candidates_num');
			$jobseekers = [];
			
			if (!empty($numberOfCandidates) && empty($jobseekersIds)) {
			    $jobseekers = DB::table('users')
			        ->select('users.name AS user_name','resumes.*', 'users.*')
			        ->join('resumes', 'resumes.user_id', 'users.id')
    			    ->where('users.deleted_at', '=', null)
    			    ->where('users.user_type_id', '=', 2)
    			    ->orderBy('users.created_at', 'DESC')
			        ->get();
			}
			
			if(!empty($employersIds))
			{
				foreach($employersIds as $employerId)
				{
					$employerInfo = DB::table('users')
					               ->where('id',$employerId)->first();
					if(!empty($employerInfo))
					{
					    if (!empty($jobseekersIds))
					    {
    						foreach($jobseekersIds as $jobseekerId)
    						{
    							$userInfo = DB::table('users')
    							            ->select('users.name AS user_name','resumes.*', 'users.*')
    							            ->join('resumes', 'resumes.user_id', 'users.id')
    							            ->where('users.id',$jobseekerId)->first();
    							$existsCandidate = DB::table('messages')
    												->where('from_user_id',$jobseekerId)
    												->where('to_user_id',$employerId)
    												->first();
    							if(!empty($existsCandidate))
    								continue;
    							if(!empty($userInfo))
    							{
    								$message = new Message();
    								$message->post_id = 0;
    								$message->from_user_id = $jobseekerId;
    								$message->from_name = $userInfo->name;
    								$message->from_email = $userInfo->email;
    								$message->from_phone = $userInfo->phone;
    								$message->to_user_id = $employerId;
    								$message->to_name = $employerInfo->name;
    								$message->to_email = $employerInfo->email;
    								$message->to_phone = $employerInfo->phone;
    								$message->filename = $userInfo->filename;
    								$message->applicant_stage = 8;
    								$message->company_id = $employerInfo->company_id;
    								
    								$message->save();		
    							}
    						}
					    }
					    elseif(!empty($numberOfCandidates))
					    {
					        if (!empty($jobseekers)) {
					            $counter = 0;
					            foreach($jobseekers as $jobseeker)
					            {
					                $existsCandidate = DB::table('messages')
    					                ->where('from_user_id',$jobseeker->id)
    					                ->where('to_user_id',$employerId)
    					                ->first();
					                if(!empty($existsCandidate))
					                    continue;
				                    if(!empty($jobseeker))
				                    {
				                        if($counter == $numberOfCandidates)
				                            break;
				                        $counter++;
				                        $message = new Message();
				                        $message->post_id = 0;
				                        $message->from_user_id = $jobseeker->id;
				                        $message->from_name = $jobseeker->user_name;
				                        $message->from_email = $jobseeker->email;
				                        $message->from_phone = $jobseeker->phone;
				                        $message->to_user_id = $employerId;
				                        $message->to_name = $employerInfo->name;
				                        $message->to_email = $employerInfo->email;
				                        $message->to_phone = $employerInfo->phone;
				                        $message->filename = $jobseeker->filename;
				                        $message->applicant_stage = 8;
				                        $message->company_id = $employerInfo->company_id;
				                        $message->save();
				                    }
					            }
					        }
					    }
					}
				}
				flash("The candidates has been allocated successfully")->success();
			}
		}
		return redirect('/account/allocate-to-employers');
	}

	public function allocateCandidateFilter(Request $request)
	{
		$filterData = $request->all();
		$data = [];

		$jobseekers = DB::table('users')
			->where('deleted_at', '=', null)
			->where('user_type_id', '=', 2)
			->orderBy('created_at', 'DESC');

		if(!empty($filterData))
		{
			foreach($filterData as $key=>$data)
            	if(empty($filterData[$key]))
                	unset($filterData[$key]);

			if(isset($filterData['user_experience']))
				$jobseekers->wherein('user_experience', $filterData['user_experience']);

			if(isset($filterData['residence_country']))
				$jobseekers->wherein('residence_country', $filterData['residence_country']);

			if(isset($filterData['city_id']))
				$jobseekers->wherein('city_id', $filterData['city_id']);

			if(isset($filterData['job_role']))
				$jobseekers->wherein('job_role', $filterData['job_role']);

			if(isset($filterData['birthday']))
			{
				$birthdayStr = implode("_",$filterData['birthday']);
				$finalBirthdayArr = explode("_",$birthdayStr);
				sort($finalBirthdayArr);
				$minAge = min($finalBirthdayArr);
				$maxAge = max($finalBirthdayArr);
				$jobseekers->where(DB::raw('YEAR(birthday)'),'<=', date("Y", strtotime("-".$minAge." years")))
				->where(DB::raw('YEAR(birthday)'),'>=', date("Y", strtotime("-".$maxAge." years")));
			}

			if(isset($filterData['edu_degree']))
			{
				$jobseekers->join('users_educations', 'users_educations.user_id', 'users.id')
										->wherein('edu_degree', $filterData['edu_degree']);
			}
		}

		$jobseekers = $jobseekers->paginate($this->perPage);
		$data['jobseekers'] = $jobseekers;
		$view=view('account.allocateCandidatesFilterData', $data);
		$view=$view->render();
		$pagination=view('account.jobApplicantsFilterPagination', ['conversations'=>$jobseekers]);
		$pagination=$pagination->render();
		return response()->json(['html'=>$view, 'pagination'=>$pagination], 200);
	}

	public function mySentEmails()
	{
		$userTrackedEmails = DB::table('tracked_emails')
							->select('tracked_emails.id AS tracked_emails_id','tracked_emails.created_at AS sent_date', 'tracked_emails.subject','tracked_emails.message', 'users.*')
							->join('users', 'users.id','=', 'tracked_emails.to_user_id')
							->where('tracked_emails.from_user_id', auth()->user()->id)
							->orderBy('tracked_emails.created_at', 'DESC');

		$data['userTrackedEmails'] = $userTrackedEmails->paginate($this->perPage);
		return view('account.mySentEmails', $data);
	}

	public function candidateSearch(Request $request)
	{
		$requestData = $request->all();
		$searchKeywords = explode(' ',$requestData['search_keyword']);
		$candidates = [];
		
		if(!empty($searchKeywords))
		{
			$candidates = DB::table('users')
				 ->where('deleted_at', '=', null)
				 ->where('user_type_id', '=', 2);

			$candidates->where(function($query) use ($searchKeywords) {
				foreach($searchKeywords as $keyword)
				{
						$query->orWhere('name', 'like', '%' . $keyword . '%')
						->orWhere('about', 'like', '%' . $keyword . '%')				
						->orWhere('phone', 'like', '%' . $keyword . '%')
						->orWhere('username', 'like', '%' . $keyword . '%')
						->orWhere('email', 'like', '%' . $keyword . '%')
						->orWhere('current_job_title', 'like', '%' . $keyword . '%')
						->orWhere('preferred_job_title', 'like', '%' . $keyword . '%')
						->orWhere('name_ar', 'like', '%' . $keyword . '%')
						->orWhere('martial_status', 'like', '%' . $keyword . '%')
						->orWhere('free_text', 'like', '%' . $keyword . '%');
					
				}
			});
			$candidates->orderBy('created_at', 'DESC');
			$candidates = $candidates->paginate($this->perPage);
		}
		$data['jobseekers'] = $candidates;
		$view=view('account.allocateCandidatesFilterData', $data);
		$view=$view->render();
		$pagination=view('account.jobApplicantsFilterPagination', ['conversations'=>$candidates]);
		$pagination=$pagination->render();
		return response()->json(['html'=>$view, 'pagination'=>$pagination], 200);
	}
}
