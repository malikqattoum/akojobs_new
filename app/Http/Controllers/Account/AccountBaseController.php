<?php


namespace App\Http\Controllers\Account;

use App\Http\Controllers\FrontController;
use App\Models\Company;
use App\Models\Post;
use App\Models\Message;
use App\Models\Payment;
use App\Models\Resume;
use App\Models\SavedPost;
use App\Models\SavedSearch;
use App\Models\Scopes\VerifiedScope;
use App\Models\Scopes\ReviewedScope;
use App\Helpers\Localization\Helpers\Country as CountryLocalizationHelper;
use App\Helpers\Localization\Country as CountryLocalization;
use Illuminate\Support\Facades\DB;
use App\TrackedEmail;
use App\Models\Permission;

use Illuminate\Database\Eloquent\Builder;
use Debugbar;

abstract class AccountBaseController extends FrontController
{
    public $countries;
    public $myPosts;
    public $archivedPosts;
    public $favouritePosts;
    public $pendingPosts;
	public $conversations;
	public $transactions;
	public $companies;
    public $resumes;
    public $jobConversations;

    /**
     * AccountBaseController constructor.
     */
    public function __construct()
    {
        parent::__construct();
		
        $this->middleware(function ($request, $next) {
            $this->leftMenuInfo();
            return $next($request);
        });
        
	
		view()->share('pagePath', '');
    }

    public function leftMenuInfo()
    {
        $isAdminPermission = isAdminUser();
        view()->share('isAdminPermission', $isAdminPermission);
		// Get & Share Countries
        $this->countries = CountryLocalizationHelper::transAll(CountryLocalization::getAllCountries());
        view()->share('countries', $this->countries);
	
		// Share User Info
		view()->share('user', auth()->user());

        // My Posts
        $this->myPosts = Post::currentCountry()
            ->where('user_id', auth()->user()->id)
            ->verified()
			->unarchived()
            ->reviewed()
            ->with(['city', 'latestPayment' => function ($builder) { $builder->with(['package']); }])
            ->orderByDesc('id');
        if(is_object($this->myPosts) && $this->myPosts->count() == 0 && !empty(auth()->user()->company_id))
        {
            $this->myPosts = Post::currentCountry()
                ->where('company_id', auth()->user()->company_id)
                ->verified()
                ->unarchived()
                ->reviewed()
                ->with(['city', 'latestPayment' => function ($builder) { $builder->with(['package']); }])
                ->orderByDesc('id');
        }
        view()->share('countMyPosts', $this->myPosts->count());

        $myTrackedEmails = TrackedEmail::where('from_user_id', auth()->user()->id);
        view()->share('myTrackedEmailsCount', $myTrackedEmails->count());

        // Archived Posts
        $this->archivedPosts = Post::currentCountry()
            ->where('user_id', auth()->user()->id)
            ->archived()
            ->with(['city', 'latestPayment' => function ($builder) { $builder->with(['package']); }])
            ->orderByDesc('id');
        view()->share('countArchivedPosts', $this->archivedPosts->count());

        // Favourite Posts
        $this->favouritePosts = SavedPost::whereHas('post', function($query) {
                $query->currentCountry();
            })
            ->where('user_id', auth()->user()->id)
            ->with(['post.city'])
            ->orderByDesc('id');
        view()->share('countFavouritePosts', $this->favouritePosts->count());

        // Pending Approval Posts
        $this->pendingPosts = Post::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
            ->currentCountry()
            ->where('user_id', auth()->user()->id)
            ->unverified()
            ->with(['city', 'latestPayment' => function ($builder) { $builder->with(['package']); }])
            ->orderByDesc('id');
        view()->share('countPendingPosts', $this->pendingPosts->count());

        // Save Search
        $savedSearch = SavedSearch::currentCountry()
            ->where('user_id', auth()->user()->id)
            ->orderByDesc('id');
        view()->share('countSavedSearch', $savedSearch->count());
	
		// Conversations
		$this->conversations = Message::with('latestReply')
			->whereHas('post', function($query) {
				$query->currentCountry();
			})
			->byUserId(auth()->user()->id)
			->where('parent_id', 0)
			->orderByDesc('id');
        view()->share('countConversations', $this->conversations->count());
	
		// Payments
		$this->transactions = Payment::whereHas('post', function($query) {
				$query->currentCountry()->whereHas('user', function($query) {
					$query->where('user_id', auth()->user()->id);
            	});
			})
			->with(['post', 'paymentMethod', 'package' => function ($builder) { $builder->with(['currency']); }])
			->orderByDesc('id');
		view()->share('countTransactions', $this->transactions->count());
	
		// Companies
		$this->companies = Company::where('user_id', auth()->user()->id)->orderByDesc('id');
		if ($this->companies->count() == 0 && !empty(auth()->user()->company_id))
		{
		    $this->companies = Company::where('id', auth()->user()->company_id)->orderByDesc('id');
		}
		view()->share('countCompanies', $this->companies->count());
	
		// Resumes
		$this->resumes = Resume::where('user_id', auth()->user()->id)->orderByDesc('id');
		view()->share('countResumes', $this->resumes->count());
    }

    public function getPostConversations($postId=0, $filterData, $isPeople=false)
    {
        foreach($filterData as $key=>$data)
            if(empty($filterData[$key]))
                unset($filterData[$key]);
        //job conversations
        if(!empty($filterData))
        {
            if($postId != 0)
            {
                $this->jobConversations = Message::with(['latestReply', 'user'])
                ->whereHas('post', function($query) {
                    $query->currentCountry();
                })
                    ->byUserId(auth()->user()->id)
                    ->select('messages.*', 'users.*', 'messages.id AS id')
                    ->where('parent_id', 0)
                    ->where('post_id', $postId)
                    ->orderByDesc('messages.id')
                    ->distinct()
                    ->join('users', 'users.id', '=', 'messages.from_user_id');
                
                if((is_object($this->jobConversations) && $this->jobConversations->count() == 0) || (empty($this->jobConversations) && !empty(auth()->user()->company_id)))
                {
                    $this->jobConversations = Message::with(['latestReply', 'user'])
                    ->whereHas('post', function($query) {
                        $query->currentCountry();
                    })
                        ->where('messages.company_id', auth()->user()->company_id)
                        ->select('messages.*', 'users.*', 'messages.id AS id')
                        ->where('parent_id', 0)
                        ->where('post_id', $postId)
                        ->orderByDesc('messages.id')
                        ->distinct()
                        ->join('users', 'users.id', '=', 'messages.from_user_id');
                }
            }
            else
            {
                if($isPeople)
                {
                    $this->jobConversations = DB::table('users')
                    ->join('messages', 'messages.from_user_id','=', 'users.id')
                    ->select('messages.id as message_id','messages.*', 'users.id as user_id', 'users.*')
                    ->where('messages.to_user_id', '=', auth()->user()->id)
                    ->where('messages.deleted_by', '=', null)
                    ->orderBy('messages.created_at', 'DESC');
                    
                    if((is_object($this->jobConversations) && $this->jobConversations->count() == 0) || (empty($this->jobConversations) && !empty(auth()->user()->company_id)))
                    {
                        $this->jobConversations = DB::table('users')
                        ->join('messages', 'messages.from_user_id','=', 'users.id')
                        ->select('messages.id as message_id','messages.*', 'users.id as user_id', 'users.*')
                        ->where('messages.company_id', '=', auth()->user()->company_id)
                        ->where('messages.deleted_by', '=', null)
                        ->orderBy('messages.created_at', 'DESC');
                    }
                }
                else
                {
                    $this->jobConversations = Message::with(['latestReply', 'user'])
                    ->whereHas('post', function($query) {
                        $query->currentCountry();
                    })
                        ->select('messages.*', 'users.*', 'messages.id AS id')
                        ->where('parent_id', 0)
                        ->where('to_user_id', auth()->user()->id)
                        ->orderByDesc('messages.id')
                        ->distinct()
                        ->join('users', 'users.id', '=', 'messages.from_user_id');
                    
                    if((is_object($this->jobConversations) && $this->jobConversations->count() == 0) || (empty($this->jobConversations) && !empty(auth()->user()->company_id)))
                    {
                        $this->jobConversations = Message::with(['latestReply', 'user'])
                        ->whereHas('post', function($query) {
                            $query->currentCountry();
                        })
                        ->select('messages.*', 'users.*', 'messages.id AS id')
                        ->where('parent_id', 0)
                        ->where('messages.company_id', auth()->user()->company_id)
                        ->orderByDesc('messages.id')
                        ->distinct()
                        ->join('users', 'users.id', '=', 'messages.from_user_id');
                    }
                }

            }
            
            if(isset($filterData['search_keyword']))
            {
                $searchKeywords = explode(' ',$filterData['search_keyword'])??[];
                
                $this->jobConversations->where('users.deleted_at', '=', null)
                ->where('users.user_type_id', '=', 2)
                ->where(function($query) use ($searchKeywords) {
                    foreach($searchKeywords as $keyword)
                    {
                        $query->orWhere('users.name', 'like', '%' . $keyword . '%')
                        ->orWhere('users.about', 'like', '%' . $keyword . '%')
                        ->orWhere('users.phone', 'like', '%' . $keyword . '%')
                        ->orWhere('users.username', 'like', '%' . $keyword . '%')
                        ->orWhere('users.email', 'like', '%' . $keyword . '%')
                        ->orWhere('users.current_job_title', 'like', '%' . $keyword . '%')
                        ->orWhere('users.preferred_job_title', 'like', '%' . $keyword . '%')
                        ->orWhere('users.name_ar', 'like', '%' . $keyword . '%')
                        ->orWhere('users.martial_status', 'like', '%' . $keyword . '%')
                        ->orWhere('users.free_text', 'like', '%' . $keyword . '%');
                    }
                });
            }

            if(isset($filterData['user_experience']))
                $this->jobConversations->wherein('users.user_experience', $filterData['user_experience']);

            if(isset($filterData['residence_country']))
                $this->jobConversations->wherein('users.residence_country', $filterData['residence_country']);

            if(isset($filterData['city_id']))
                $this->jobConversations->wherein('users.city_id', $filterData['city_id']);

            if(isset($filterData['job_role']))
                $this->jobConversations->wherein('users.job_role', $filterData['job_role']);

            if(isset($filterData['birthday']))
            {
                $birthdayStr = implode("_",$filterData['birthday']);
                $finalBirthdayArr = explode("_",$birthdayStr);
                sort($finalBirthdayArr);
                $minAge = min($finalBirthdayArr);
                $maxAge = max($finalBirthdayArr);
                $this->jobConversations->where(DB::raw('YEAR(users.birthday)'),'<=', date("Y", strtotime("-".$minAge." years")))
                ->where(DB::raw('YEAR(users.birthday)'),'>=', date("Y", strtotime("-".$maxAge." years")));
            }

            if(isset($filterData['rating']))
            {
                $this->jobConversations->join('users_ratings', 'users_ratings.user_id', 'users.id')
                                        ->wherein('rating', $filterData['rating'])
                                        ->where('rated_by_user_id',auth()->user()->id);
            }

            if(isset($filterData['edu_degree']))
            {
                $this->jobConversations->join('users_educations', 'users_educations.user_id', 'users.id')
                                        ->wherein('edu_degree', $filterData['edu_degree']);
            }

            if(isset($filterData['stage']))
            {
                $this->jobConversations->where('messages.applicant_stage', $filterData['stage']);
            }

            if(isset($filterData['note_keyword']))
            {
                $this->jobConversations->where('messages.applicant_note','like', "%".$filterData['note_keyword']."%");
            }

            if(isset($filterData['users_ids']))
            {
                $usersIds = [];
                foreach($filterData['users_ids'] as $value)
				{
                    $usersIds[] = $value->id;
				}
                $this->jobConversations->wherein('users.id',$usersIds);
            }

            view()->share('countJobConversations', $this->jobConversations->count());
        } else {
            if($postId != 0)
            {
                $this->jobConversations = Message::with(['latestReply', 'user'])
                ->whereHas('post', function($query) {
                    $query->currentCountry();
                })
                    ->byUserId(auth()->user()->id)
                    ->select('messages.*', 'users.*', 'messages.id AS id')
                    ->where('parent_id', 0)
                    ->where('post_id', $postId)
                    ->orderByDesc('messages.id')
                    ->join('users', 'users.id', '=', 'messages.from_user_id');
                view()->share('countJobConversations', $this->jobConversations->count());
                
                if((is_object($this->jobConversations) && $this->jobConversations->count() == 0) || (empty($this->jobConversations) && !empty(auth()->user()->company_id)))
                {
                    $this->jobConversations = Message::with(['latestReply', 'user'])
                    ->whereHas('post', function($query) {
                        $query->currentCountry();
                    })
                    ->where('messages.company_id', auth()->user()->company_id)
                    ->select('messages.*', 'users.*', 'messages.id AS id')
                    ->where('parent_id', 0)
                    ->where('post_id', $postId)
                    ->orderByDesc('messages.id')
                    ->join('users', 'users.id', '=', 'messages.from_user_id');
                    view()->share('countJobConversations', $this->jobConversations->count());
                }
            }
            else
            {
                if($isPeople)
                {
                    $this->jobConversations = DB::table('users')
                    ->join('messages', 'messages.from_user_id','=', 'users.id')
                    ->select('messages.id as message_id','messages.*', 'users.id as user_id', 'users.*')
                    ->where('messages.to_user_id', '=', auth()->user()->id)
                    ->where('messages.deleted_by', '=', null)
                    ->orderBy('messages.created_at', 'DESC');
                    
                    if((is_object($this->jobConversations) && $this->jobConversations->count() == 0) || (empty($this->jobConversations) && !empty(auth()->user()->company_id)))
                    {
                        $this->jobConversations = DB::table('users')
                        ->join('messages', 'messages.from_user_id','=', 'users.id')
                        ->select('messages.id as message_id','messages.*', 'users.id as user_id', 'users.*')
                        ->where('messages.company_id', '=', auth()->user()->company_id)
                        ->where('messages.deleted_by', '=', null)
                        ->orderBy('messages.created_at', 'DESC');
                    }
                }
                else
                {
                    $this->jobConversations = Message::with(['latestReply', 'user'])
                    ->whereHas('post', function($query) {
                        $query->currentCountry();
                    })
                        ->byUserId(auth()->user()->id)
                        ->where('parent_id', 0)
                        ->orderByDesc('id');
                    
                    if((is_object($this->jobConversations) && $this->jobConversations->count() == 0) || (empty($this->jobConversations) && !empty(auth()->user()->company_id)))
                    {
                        $this->jobConversations = Message::with(['latestReply', 'user'])
                        ->whereHas('post', function($query) {
                            $query->currentCountry();
                        })
                        ->where('messages.company_id', auth()->user()->company_id)
                        ->where('parent_id', 0)
                        ->orderByDesc('id');
                    }
                    view()->share('countJobConversations', $this->jobConversations->count());
                }
            }
        }

        return $this->jobConversations;
    }
}
