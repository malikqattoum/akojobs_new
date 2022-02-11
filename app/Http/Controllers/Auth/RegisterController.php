<?php


namespace App\Http\Controllers\Auth;

use App\Helpers\Ip;
use App\Helpers\Localization\Country as CountryLocalization;
use App\Helpers\Localization\Helpers\Country as CountryLocalizationHelper;
use App\Http\Controllers\Auth\Traits\VerificationTrait;
use App\Http\Controllers\FrontController;
use App\Http\Requests\UserRequest;
use App\Models\Gender;
use App\Models\Permission;
use App\Models\Resume;
use App\Models\User;
use App\Models\Company;
use App\Models\UserType;
use App\Notifications\UserActivated;
use App\Notifications\UserNotification;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\View;
use Torann\LaravelMetaTags\Facades\MetaTag;
use App\Models\City;
use App\UserSource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class RegisterController extends FrontController
{
	use RegistersUsers, VerificationTrait;
	
	/**
	 * Where to redirect users after login / registration.
	 *
	 * @var string
	 */
	protected $redirectTo = '/account';
	
	/**
	 * @var array
	 */
	public $msg = [];
	
	/**
	 * RegisterController constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		
		// From Laravel 5.3.4 or above
		$this->middleware(function ($request, $next) {
			$this->commonQueries();
			
			return $next($request);
		});
	}
	
	/**
	 * Common Queries
	 */
	public function commonQueries()
	{
		$this->redirectTo = config('app.locale') . '/account';
	}
	
	/**
	 * Show the form the create a new user account.
	 *
	 * @return View
	 */
	public function showRegistrationForm()
	{
		$data = [];
		$cities = City::where([['active', '=', 1], ['country_code', '=', 'IQ']])->get();
		// References
		$data['countries'] = CountryLocalizationHelper::transAll(CountryLocalization::getCountries());
		$data['genders'] = Gender::trans()->get();
		$data['userTypes'] = UserType::all();
		$data['cities'] = $cities;
		
		// Meta Tags
		MetaTag::set('title', getMetaTag('title', 'register'));
		MetaTag::set('description', strip_tags(getMetaTag('description', 'register')));
		MetaTag::set('keywords', getMetaTag('keywords', 'register'));
		
		return view('auth.register.index', $data);
	}
	
	/**
	 * Register a new user account.
	 *
	 * @param UserRequest $request
	 * @return $this|\Illuminate\Http\RedirectResponse
	 */
	public function register(UserRequest $request)
	{
		// Conditions to Verify User's Email or Phone
		//$emailVerificationRequired = config('settings.mail.email_verification') == 1 && $request->filled('email');
		$emailVerificationRequired = true && $request->filled('email');
        $phoneVerificationRequired = config('settings.sms.phone_verification') == 1 && $request->filled('phone');
		//die(var_dump(config('settings.mail.email_verification')));
		// $userSource = UserSource::where('email', $request->input('email'))
		// 	->where('source', '1') // 1 source type is organic source
		// 	->first();

		// if(!empty($userSource))
		// {
		// 	$request->session()->flash('message', t("This user is already exist"));
		// 	return redirect(config('app.locale') . '/register');
		// }
		// New User
		$user = new User();
		$input = $request->only($user->getFillable());
		foreach ($input as $key => $value) {
			$user->{$key} = $value;
		}
		
		$user->country_code   = config('country.code');
		$user->language_code  = config('app.locale');
		$user->password       = Hash::make($request->input('password'));
		$user->phone_hidden   = $request->input('phone_hidden');
		$user->ip_addr        = Ip::get();
		$user->verified_email = 0;
		$user->verified_phone = 1;
		$user->job_role = $request->input('regJobRole');
		$user->sec_job_role = $request->input('secondaryRegJobRole');
		$user->industry = $request->input('industry');
		$user->residence_country = $request->input('residence_country');
		$user->current_job_title = $request->input('curJobTitle');
		$user->user_experience = $request->input('userExperience');
		$user->city_id = $request->input('city_id');
		$user->pref_lang = $request->input('prefLang');
		$user->user_source = 1;

		
		// Email verification key generation
		if ($emailVerificationRequired) {
			$user->email_token = md5(microtime() . mt_rand());
			$user->verified_email = 0;
		}
		
		// Mobile activation key generation
		if ($phoneVerificationRequired) {
			$user->phone_token = mt_rand(100000, 999999);
			$user->verified_phone = 0;
		}
		
		// Save
		$user->save();

		$userSource = new UserSource();
		$userSource->email = $request->input('email');
		$userSource->source = '1';
		$userSource->save();
		
		// Add Job seekers resume
		if ($request->input('user_type_id') == 2) {
			if ($request->hasFile('resume.filename')) {
			    // Save user's resume
			    $resumeInfo = [
			        'country_code' => config('country.code'),
			        'user_id'      => $user->id,
			        'active'       => 1,
			    ];
			    $resume = new Resume($resumeInfo);
			    $resume->save();
			    
			    // Upload user's resume
			    $resume->filename = $request->file('resume')['filename'];
			    $resume->save();
			    
			    $file = $request->file('resume.filename');
			    $filename = $file->getClientOriginalName();
			    
			    // File upload location
			    $location = public_path()."/storage/readResumes";
			    
			    // Upload file
			    $file->move($location,$filename);
			    
			    $cvText = Resume::getCvText($filename);
			    if (!empty($cvText)) {
			        $user->free_text = $cvText;
			        $user->save();
			    }
			}
		}
		
		// Message Notification & Redirection
		$request->session()->flash('message', t("Your account has been created."));
		$nextUrl = config('app.locale') . '/register/finish';
		
		// Send Admin Notification Email
		if (config('settings.mail.admin_notification') == 1) {
			try {
				// Get all admin users
				$admins = User::permission(Permission::getStaffPermissions())->get();
				if ($admins->count() > 0) {
					Notification::send($admins, new UserNotification($user));
					/*
                    foreach ($admins as $admin) {
						Notification::route('mail', $admin->email)->notify(new UserNotification($user));
                    }
					*/
				}
			} catch (\Exception $e) {
				flash($e->getMessage())->error();
			}
		}
		
		// Send Verification Link or Code
		if ($emailVerificationRequired || $phoneVerificationRequired) {
			
			// Save the Next URL before verification
			session(['userNextUrl' => $nextUrl]);
			
			// Email
			if ($emailVerificationRequired) {
				// Send Verification Link by Email
				$this->sendVerificationEmail($user);
				
				// Show the Re-send link
				$this->showReSendVerificationEmailLink($user, 'user');
			}
			
			// Phone
			if ($phoneVerificationRequired) {
				// Send Verification Code by SMS
				$this->sendVerificationSms($user);
				
				// Show the Re-send link
				$this->showReSendVerificationSmsLink($user, 'user');
				
				// Go to Phone Number verification
				$nextUrl = config('app.locale') . '/verify/user/phone/';
			}
			
			// Send Confirmation Email or SMS,
			// When User clicks on the Verification Link or enters the Verification Code.
			// Done in the "app/Observers/UserObserver.php" file.
			
		} else {
			
			// Send Confirmation Email or SMS
			if (config('settings.mail.confirmation') == 1) {
				try {
					$user->notify(new UserActivated($user));
				} catch (\Exception $e) {
					flash($e->getMessage())->error();
				}
			}
			
			// Redirect to the user area If Email or Phone verification is not required
			if (Auth::loginUsingId($user->id)) {
				return redirect()->intended(config('app.locale') . '/account');
			}
			
		}
		
		// Redirection
		return redirect($nextUrl);
	}
	
	/**
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|View
	 */
	public function finish()
	{
		// Keep Success Message for the page refreshing
		session()->keep(['message']);
		if (!session()->has('message')) {
			return redirect(config('app.locale') . '/');
		}
		
		// Meta Tags
		MetaTag::set('title', session('message'));
		MetaTag::set('description', session('message'));
		
		return view('auth.register.finish');
	}

	public function showEmployerRegistrationForm()
	{
		$data = [];
		
		// References
		$data['countries'] = CountryLocalizationHelper::transAll(CountryLocalization::getAllCountries());
		
		// Meta Tags
		MetaTag::set('title', 'AkoJobs - '.t('Iraq Recruitment Solutions'));
		MetaTag::set('description', strip_tags(getMetaTag('description', 'register')));
		MetaTag::set('keywords', getMetaTag('keywords', 'register'));
		
		return view('auth.register.employerRegister', $data);
	}

	/**
	 * Register a new user account.
	 *
	 * @param UserRequest $request
	 * @return $this|\Illuminate\Http\RedirectResponse
	 */
	public function employerRegister(UserRequest $request)
	{
		// Conditions to Verify User's Email or Phone
		//$emailVerificationRequired = config('settings.mail.email_verification') == 1 && $request->filled('email');
		$emailVerificationRequired = true && $request->filled('email');
        $phoneVerificationRequired = config('settings.sms.phone_verification') == 1 && $request->filled('phone');
		//die(var_dump(config('settings.mail.email_verification')));
		// New User
		$user = new User();
		$input = $request->only($user->getFillable());
		foreach ($input as $key => $value) {
			$user->{$key} = $value;
		}
		
		$user->country_code   = $request->input('country_code');
		$user->language_code  = config('app.locale');
		$user->password       = Hash::make($request->input('password'));
		$user->phone_hidden   = $request->input('phone_hidden');
		$user->ip_addr        = Ip::get();
		$user->verified_email = 0;
		$user->verified_phone = 1;
		$user->current_job_title = $request->input('curJobTitle');

		
		// Email verification key generation
		if ($emailVerificationRequired) {
			$user->email_token = md5(microtime() . mt_rand());
			$user->verified_email = 0;
		}
		
		// Mobile activation key generation
		if ($phoneVerificationRequired) {
			$user->phone_token = mt_rand(100000, 999999);
			$user->verified_phone = 0;
		}
		
		
		// Save
		$user->save();

		$company = new Company();
		$company->user_id = $user->id;
		$company->name = $request->input('company.name');
		$company->company_size = $request->input('company.size');
		$company->company_type = $request->input('company.type');
		$company->company_location   = $request->input('company.location');
		$company->email = $request->email;
		$company->country_code   = $request->input('country_code');
		$company->phone   = $request->input('phone');
		$company->save();

		$user->company_id = $company->id;
		$user->save();
		
		// Message Notification & Redirection
		$request->session()->flash('message', t("Your account has been created."));
		$nextUrl = config('app.locale') . '/register/finish';
		
		// Send Admin Notification Email
		if (config('settings.mail.admin_notification') == 1) {
			try {
				// Get all admin users
				$admins = User::permission(Permission::getStaffPermissions())->get();
				if ($admins->count() > 0) {
					Notification::send($admins, new UserNotification($user));
					/*
                    foreach ($admins as $admin) {
						Notification::route('mail', $admin->email)->notify(new UserNotification($user));
                    }
					*/
				}
			} catch (\Exception $e) {
				flash($e->getMessage())->error();
			}
		}
		
		// Send Verification Link or Code
		if ($emailVerificationRequired || $phoneVerificationRequired) {
			
			// Save the Next URL before verification
			session(['userNextUrl' => $nextUrl]);
			
			// Email
			if ($emailVerificationRequired) {
				// Send Verification Link by Email
				$this->sendVerificationEmail($user);
				
				// Show the Re-send link
				$this->showReSendVerificationEmailLink($user, 'user');
			}
			
			// Phone
			if ($phoneVerificationRequired) {
				// Send Verification Code by SMS
				$this->sendVerificationSms($user);
				
				// Show the Re-send link
				$this->showReSendVerificationSmsLink($user, 'user');
				
				// Go to Phone Number verification
				$nextUrl = config('app.locale') . '/verify/user/phone/';
			}
			
			// Send Confirmation Email or SMS,
			// When User clicks on the Verification Link or enters the Verification Code.
			// Done in the "app/Observers/UserObserver.php" file.
			
		} else {
			
			// Send Confirmation Email or SMS
			if (config('settings.mail.confirmation') == 1) {
				try {
					$user->notify(new UserActivated($user));
				} catch (\Exception $e) {
					flash($e->getMessage())->error();
				}
			}
			
			// Redirect to the user area If Email or Phone verification is not required
			if (Auth::loginUsingId($user->id)) {
				return redirect()->intended(config('app.locale') . '/account');
			}
			
		}
		
		// Redirection
		return redirect($nextUrl);
	}

	public function employerInviteRegister($companyId, Request $request)
	{
		if($request->method() == "POST")
		{
			$validator = Validator::make($request->all(), [
				'name' => 'required',
				'email' => 'required',
			 ]);
	   
			 if ($validator->fails()) {
				flash("Please fill the fields")->error();
			 }else{
				// New User
				$user = new User();
				$input = $request->only($user->getFillable());
				foreach ($input as $key => $value) {
					$user->{$key} = $value;
				}
				
				$user->country_code   = config('country.code');
				$user->language_code  = config('app.locale');
				$user->password       = Hash::make($request->input('password'));
				$user->ip_addr        = Ip::get();
				$user->verified_email = 0;
				$user->verified_phone = 1;
				$user->verified_email = 1;
				$user->company_id = $request->input('company_id');
				$user->user_type_id = 1;
				$user->user_source = 4;
				
				// Save
				$user->save();
				
				// Message Notification & Redirection
				$request->session()->flash('message', t("Your account has been created."));
				
				// Send Admin Notification Email
				if (config('settings.mail.admin_notification') == 1) {
					try {
						// Get all admin users
						$admins = User::permission(Permission::getStaffPermissions())->get();
						if ($admins->count() > 0) {
							Notification::send($admins, new UserNotification($user));
							/*
							foreach ($admins as $admin) {
								Notification::route('mail', $admin->email)->notify(new UserNotification($user));
							}
							*/
						}
					} catch (\Exception $e) {
						flash($e->getMessage())->error();
					}
				}
				
					
				// Redirect to the user area If Email or Phone verification is not required
				if (Auth::loginUsingId($user->id)) {
					return redirect()->intended(config('app.locale') . '/account');
				}

				return redirect(lurl('employers/login'));
			}
		}
		else
		{
			$company = DB::table('companies')
				->select('name')
				->where('id', '=', $companyId)
				->first();

			return view('auth.register.companyInviteRegister', ['companyId'=>$companyId, 'companyName'=>$company->name]);
		}
	}
	
	private function get_http_response_code($url) {
	    // Remove this stream_context_set_default on production
	    stream_context_set_default( [
	        'ssl' => [
	            'verify_peer' => false,
	            'verify_peer_name' => false,
	        ],
	    ]);
	    $headers = get_headers($url);
	    return substr($headers[0], 9, 3);
	}
	
}
