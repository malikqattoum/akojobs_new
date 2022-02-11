<?php


namespace App\Http\Controllers\Account;

use App\Http\Controllers\Auth\Traits\VerificationTrait;
use App\Http\Requests\UserRequest;
use App\Models\Scopes\VerifiedScope;
use App\Models\UserType;
use Creativeorange\Gravatar\Facades\Gravatar;
use App\Models\Post;
use App\Models\SavedPost;
use App\Models\Gender;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Torann\LaravelMetaTags\Facades\MetaTag;
use App\Helpers\Localization\Helpers\Country as CountryLocalizationHelper;
use App\Helpers\Localization\Country as CountryLocalization;
use App\Models\User;
use App\Models\Company;
use App\UserExperience;
use App\UserEducation;
use App\UserSkill;
use App\UserVideo;
use App\UserLanguage;
use App\UserTraining;
use App\UserReference;
use App\Models\Resume;
use Barryvdh\Debugbar\Facade as Debugbar;
use Illuminate\Support\Facades\Auth;

class EditController extends AccountBaseController
{
	use VerificationTrait;
	
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		$data = [];
		
		// $data['countries'] = CountryLocalizationHelper::transAll(CountryLocalization::getCountries());
		$data['countries'] = CountryLocalizationHelper::transAll(CountryLocalization::getAllCountries());
		$data['genders'] = Gender::trans()->get();
		$data['userTypes'] = UserType::all();
		$data['userPhoto'] = (!empty(auth()->user()->email)) ? auth()->user()->photo : null;
		
		// Mini Stats
		$data['countPostsVisits'] = DB::table('posts')
			->select('user_id', DB::raw('SUM(visits) as total_visits'))
			->where('country_code', config('country.code'))
			->where('user_id', auth()->user()->id)
			->groupBy('user_id')
			->first();
		$data['countPosts'] = Post::currentCountry()
			->where('user_id', auth()->user()->id)
			->count();
		$data['countFavoritePosts'] = SavedPost::whereHas('post', function ($query) {
			$query->currentCountry();
		})->where('user_id', auth()->user()->id)
			->count();
		
		$data['userExperiences'] = UserExperience::where('user_id', auth()->user()->id)->get();
		$data['userEducations'] = UserEducation::where('user_id', auth()->user()->id)->get();
		$data['userSkills'] = UserSkill::where('user_id', auth()->user()->id)->get();
		$data['userLanguages'] = UserLanguage::where('user_id', auth()->user()->id)->get();
		$data['userTrainings'] = UserTraining::where('user_id', auth()->user()->id)->get();
		$data['userReferences'] = UserReference::where('user_id', auth()->user()->id)->get();
		$data['userVideo'] = UserVideo::where('user_id', auth()->user()->id)->get();

		$data['company'] = [];
		// Get the Company
		if(auth()->user()->user_type_id == 1)
		{
			$people = DB::table('users')
				->join('messages', 'messages.from_user_id','=', 'users.id')
				->select('messages.id as message_id','messages.*', 'users.id as user_id', 'users.*')
				->where('messages.to_user_id', '=', auth()->user()->id)
				->where('messages.deleted_by', '=', null)
				->orderBy('messages.created_at', 'DESC');

			$stagesCounts = DB::table('messages')
				->select(DB::raw('count(*) as stage_count, applicant_stage'))
				->where('messages.to_user_id', '=', auth()->user()->id)
				->where('messages.deleted_by', '=', null)
				->groupBy('applicant_stage')
				->get();

			$stagesCountsArr = [];
			foreach($stagesCounts as $value)
			{
				$stagesCountsArr[$value->applicant_stage] = $value->stage_count;
			}
			$data['stagesCounts'] = $stagesCountsArr;
			$data['peopleCount'] = $people->count();
			
			$data['company'] = Company::where('user_id', auth()->user()->id)->first();
			if(isset($data['company']) && !empty($data['company']) && $data['company']->count() != 0)
			{
				$data['company']->phone = ($data['company']->phone)?CountryLocalization::getCountryCodeFromIso($data['company']->country_code).$data['company']->phone:$data['company']->phone;
			}
			elseif (empty($data['company']) && !empty(auth()->user()->company_id))
			{
			    $data['company'] = Company::where('id', auth()->user()->company_id)->first();
			    if (isset($data['company']) && !empty($data['company']) && $data['company']->count() != 0) {
			        $data['company']->phone = ($data['company']->phone)?CountryLocalization::getCountryCodeFromIso($data['company']->country_code).$data['company']->phone:$data['company']->phone;
			    }
			}
			else
			{
				$data['company'] = [];
			}

			// Meta Tags
			MetaTag::set('title', t('Edit the Company'));
			MetaTag::set('description', t('Edit the Company - :app_name', ['app_name' => config('settings.app.app_name')]));
			
		}
		// Meta Tags
		MetaTag::set('title', t('My account'));
		MetaTag::set('description', t('My account on :app_name', ['app_name' => config('settings.app.app_name')]));
		
		return view('account.edit', $data);
	}
	
	/**
	 * @param UserRequest $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function updateDetails(UserRequest $request)
	{
		// Check if these fields has changed
		$emailChanged = $request->filled('email') && $request->input('email') != auth()->user()->email;
		$phoneChanged = $request->filled('phone') && $request->input('phone') != auth()->user()->phone;
		$usernameChanged = $request->filled('username') && $request->input('username') != auth()->user()->username;
		
		// Conditions to Verify User's Email or Phone
		$emailVerificationRequired = config('settings.mail.email_verification') == 1 && $emailChanged;
		$phoneVerificationRequired = config('settings.sms.phone_verification') == 1 && $phoneChanged;
		
		// Get User
		$user = User::withoutGlobalScopes([VerifiedScope::class])->find(auth()->user()->id);
		
		// Update User
		$input = $request->only($user->getFillable());
		foreach ($input as $key => $value) {
			if (in_array($key, ['email', 'phone', 'username', 'phone_hidden', 'country_code']) && empty($value)) {
				continue;
			}
			$user->{$key} = $value;
		}

		if(!empty($request->input('prefLang')))
			$user->pref_lang = $request->input('prefLang');

		if(!empty($request->input('regJobRole')))
			$user->job_role = $request->input('regJobRole');

		if(!empty($request->input('secondaryRegJobRole')))
			$user->sec_job_role = $request->input('secondaryRegJobRole');

		if(!empty($request->input('industry')))
			$user->industry = $request->input('industry');

		if(!empty($request->input('residence_country')))
			$user->residence_country = $request->input('residence_country');

		if(!empty($request->input('curJobTitle')))
			$user->current_job_title = $request->input('curJobTitle');

		if(!empty($request->input('userExperience')))
			$user->user_experience = $request->input('userExperience');
		
		// Email verification key generation
		if ($emailVerificationRequired) {
			$user->email_token = md5(microtime() . mt_rand());
			$user->verified_email = 0;
		}
		
		// Phone verification key generation
		if ($phoneVerificationRequired) {
			$user->phone_token = mt_rand(100000, 999999);
			$user->verified_phone = 0;
		}
		
		// Don't logout the User (See User model)
		if ($emailVerificationRequired || $phoneVerificationRequired) {
			session(['emailOrPhoneChanged' => true]);
		}
		
		// Save
		$user->save();
		
		// Message Notification & Redirection
		flash(t("Your details account has updated successfully."))->success();
		$nextUrl = config('app.locale') . '/account';
		
		// Send Email Verification message
		if ($emailVerificationRequired) {
			$this->sendVerificationEmail($user);
			$this->showReSendVerificationEmailLink($user, 'user');
		}
		
		// Send Phone Verification message
		if ($phoneVerificationRequired) {
			// Save the Next URL before verification
			session(['itemNextUrl' => $nextUrl]);
			
			$this->sendVerificationSms($user);
			$this->showReSendVerificationSmsLink($user, 'user');
			
			// Go to Phone Number verification
			$nextUrl = config('app.locale') . '/verify/user/phone/';
		}
		
		// Redirection
		return redirect($nextUrl);
	}
	
	/**
	 * @param UserRequest $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function updateSettings(UserRequest $request)
	{
		// Get User
		$user = User::find(auth()->user()->id);
		
		// Update
		$user->disable_comments = (int)$request->input('disable_comments');
		if ($request->filled('password')) {
			$user->password = Hash::make($request->input('password'));
		}
		$user->save();
		
		flash(t("Your settings account has updated successfully."))->success();
		
		return redirect(config('app.locale') . '/account');
	}
	
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function updatePreferences()
	{
		$data = [];
		
		return view('account.edit', $data);
	}
	
	public function companyEmployersInvitation()
	{
	    $data = [];
	    $data['companyData'] = Company::where('user_id', auth()->user()->id)->orderBy('created_at', 'ASC')->first();
	    $data['invitedEmployers'] = User::where('user_type_id', 1)->where('user_source', 4)->where('company_id',$data['companyData']->id)->orderBy('created_at', 'DESC')->get();
	    
	    return view('account.companyInvitation', $data);
	}
	
	public function generateFreeText()
	{
	    if(!isAdminUser())
	        return redirect(config('app.locale') . '/account');
	    
	    $queryData = request()->query();
	    //if(isset($queryData['limit']) && isset($queryData['offset']))
	    $limit=50;
	    $offset=1400;
	    if($limit)
	    {
	        for($i = 0; $i < 500; $i++)
	        {
        	    $usersResumes = DB::table('resumes')
                                        ->select("user_id", "filename")
                                        ->whereNotNull("filename")
                                        ->where("filename", "!=", '')
                                        ->limit($limit)
                                        ->offset($offset)
                                        ->orderBy('id', 'ASC')
                                        ->get();
                $offset += 50;
                $updatedUsersCount = 0;
                $emptyUserCount = 0;
                $emptyResumeText = 0;
                foreach($usersResumes as $userResume)
        	    {
        	        $user = User::where("id",$userResume->user_id)->first();
        	        if(!empty($user) && $user->id != 0 && empty($user->free_text))
        	        {
    //     	            print "user id debug --> $user->id";
    //                     print "filename debug ----> $userResume->filename";
    //                     return;
        	            $resumeText = Resume::getCvText($userResume->filename);
        	            if(!empty($resumeText))
        	            {
            	           $user->free_text = $resumeText;
            	           $user->save();
            	           $updatedUsersCount++;
        	            }
        	            else 
        	            {
        	                $emptyResumeText++;
        	            }
        	        }
        	        else 
        	        {
        	            $emptyUserCount++;
        	        }
        	    }
        	    print "<h1>updatedUsersCount: $updatedUsersCount, emptyUserCount: $emptyUserCount, emptyResumeText: $emptyResumeText</h1>";
	        }
	    }
	}
}
