<?php


namespace App\Http\Controllers\Post\CreateOrEdit\MultiSteps;

use App\Helpers\ArrayHelper;
use App\Helpers\Ip;
use App\Http\Controllers\Post\CreateOrEdit\Traits\AutoRegistrationTrait;
use App\Http\Controllers\Post\CreateOrEdit\MultiSteps\Traits\EditTrait;
use App\Http\Controllers\Auth\Traits\VerificationTrait;
use App\Http\Requests\PostRequest;
use App\Models\Company;
use App\Models\Permission;
use App\Models\Post;
use App\Models\PostType;
use App\Models\Category;
use App\Models\Package;
use App\Models\City;
use App\Models\SalaryType;
use App\Models\User;
use App\Models\PostPackage;
use App\Models\PackageRequest;
use App\Http\Controllers\FrontController;
use App\Models\Scopes\VerifiedScope;
use App\Models\Scopes\ReviewedScope;
use App\Notifications\PostActivated;
use App\Notifications\PostNotification;
use App\Notifications\PostReviewed;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Torann\LaravelMetaTags\Facades\MetaTag;
use App\Helpers\Localization\Helpers\Country as CountryLocalizationHelper;
use App\Helpers\Localization\Country as CountryLocalization;
use App\Http\Requests\Admin\PackageRequest as AdminPackageRequest;
use App\Http\Requests\PackageRequest as RequestsPackageRequest;
use Illuminate\Http\Request;

class CreateController extends FrontController
{
	use EditTrait, VerificationTrait, AutoRegistrationTrait;
	
	public $data;
	
	/**
	 * CreateController constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Check if guests can post Ads
		if (config('settings.single.guests_can_post_ads') != '1') {
			$this->middleware('auth')->only(['getForm', 'postForm']);
		}
		
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
		// References
		$data = [];
		
		// Get Countries
		$data['countries'] = CountryLocalizationHelper::transAll(CountryLocalization::getCountries());
		view()->share('countries', $data['countries']);
		
		// Get Categories
		$cacheId = 'categories.parentId.0.with.children' . config('app.locale');
		$data['categories'] = Cache::remember($cacheId, $this->cacheExpiration, function () {
			$categories = Category::trans()->where('parent_id', 0)->with([
				'children' => function ($query) {
					$query->trans();
				},
			])->orderBy('lft')->orderBy('name')->get();
			
			return $categories;
		});
		view()->share('categories', $data['categories']);
		
		// Get Post Types
		$cacheId = 'postTypes.all.' . config('app.locale');
		$data['postTypes'] = Cache::remember($cacheId, $this->cacheExpiration, function () {
			$postTypes = PostType::trans()->orderBy('lft')->get();
			
			return $postTypes;
		});
		view()->share('postTypes', $data['postTypes']);
		
		// Get Salary Types
		$cacheId = 'salaryTypes.all.' . config('app.locale');
		$data['salaryTypes'] = Cache::remember($cacheId, $this->cacheExpiration, function () {
			$salaryTypes = SalaryType::trans()->orderBy('lft')->get();
			
			return $salaryTypes;
		});
		view()->share('salaryTypes', $data['salaryTypes']);
		
		if (auth()->check()) {
			// Get all the User's Companies
		    $isAdmin = isAdminUser();

			if($isAdmin)
			{
				$data['companies'] = Company::orderByDesc('id')->take(100)->get();
			}
			else
			{
				$data['companies'] = Company::where('user_id', auth()->user()->id)->take(100)->orderByDesc('id')->get();
				if(empty($data['company']) && !empty(auth()->user()->company_id))
				{
				    $data['companies'] = Company::where('id', auth()->user()->company_id)->get();
				}
			}
			view()->share('companies', $data['companies']);
			
			// Get the User's latest Company
			if ($data['companies']->has(0)) {
				$data['postCompany'] = $data['companies']->get(0);
				view()->share('postCompany', $data['postCompany']);
			}
		}
		
		// Count Packages
		$data['countPackages'] = Package::trans()->applyCurrency()->count();
		view()->share('countPackages', $data['countPackages']);
		
		// Count Payment Methods
		$data['countPaymentMethods'] = $this->countPaymentMethods;
		
		// Save common's data
		$this->data = $data;
	}
	
	/**
	 * New Post's Form.
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function getForm()
	{
		$cities = City::where([['active', '=', 1], ['country_code', '=', 'IQ']])->get();
		// $userPackages = PackageRequest::where([
		// 										['user_id', '=', auth()->user()->id],
		// 										['active', '=', 1],
		// 										['valid_jobs_num', '>', '0'],
		// 										['end_date', '>', date('Y-m-d', time())],
		// 									])->get();
		// if($userPackages->isEmpty() && auth()->user()->is_admin != 1)
		// 	return redirect(lurl('posts/posting-plans'));
		
		// Check if the form type is 'Single Step Form', and make redirection to it (permanently).
		if (config('settings.single.publication_form_type') == '2') {
			return redirect(lurl('create'), 301)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
		}
		
		// Only Admin users and Employers/Companies can post ads
		if (auth()->check()) {
			if (!in_array(auth()->user()->user_type_id, [1])) {
				return redirect()->intended(config('app.locale') . '/account');
			}
		}
		
		// Check possible Update
		if (!empty($tmpToken)) {
			session()->keep(['message']);
			
			return $this->getUpdateForm($tmpToken);
		}
		
		// Meta Tags
		MetaTag::set('title', getMetaTag('title', 'create'));
		MetaTag::set('description', strip_tags(getMetaTag('description', 'create')));
		MetaTag::set('keywords', getMetaTag('keywords', 'create'));
		
		// Create
		return view('post.createOrEdit.multiSteps.create')->with('cities', $cities);
	}
	
	/**
	 * Store a new Post.
	 *
	 * @param null $tmpToken
	 * @param PostRequest $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function postForm($tmpToken = null, PostRequest $request)
	{
		// Check possible update
		if (!empty($tmpToken)) {
			session()->keep(['message']);
			
			return $this->postUpdateForm($tmpToken, $request);
		}
		
		// Get the Post's City
		$city = City::find($request->input('city_id', 0));
		if (empty($city)) {
			flash(t("Posting Ads was disabled for this time. Please try later. Thank you."))->error();
			
			return back()->withInput($request->except('company.logo'));
		}
		
		// Conditions to Verify User's Email or Phone
		if (auth()->check()) {
			$emailVerificationRequired = config('settings.mail.email_verification') == 1 && $request->filled('email') && $request->input('email') != auth()->user()->email;
			$phoneVerificationRequired = config('settings.sms.phone_verification') == 1 && $request->filled('phone') && $request->input('phone') != auth()->user()->phone;
		} else {
			$emailVerificationRequired = config('settings.mail.email_verification') == 1 && $request->filled('email');
			$phoneVerificationRequired = config('settings.sms.phone_verification') == 1 && $request->filled('phone');
		}
		
		// Get or Create Company
		if ($request->filled('company_id') && !empty($request->input('company_id'))) {
			// Get the User's Company
			//$company = Company::where('id', $request->input('company_id'))->where('user_id', auth()->user()->id)->first();
			$company = Company::where('id', $request->input('company_id'))->first();
		} else {
			$companyInfo = $request->input('company');
			if (!isset($companyInfo['country_code']) || empty($companyInfo['country_code'])) {
				$companyInfo += ['country_code' => config('country.code')];
			}
			
			// Logged Users
			if (auth()->check()) {
				if (!isset($companyInfo['user_id']) || empty($companyInfo['user_id'])) {
					$companyInfo += ['user_id' => auth()->user()->id];
				}
				
				// Store the User's Company
				$company = new Company($companyInfo);
				$company->save();
				
				// Save the Company's Logo
				if ($request->hasFile('company.logo')) {
					$company->logo = $request->file('company.logo');
					$company->save();
				}
			} else {
				// Guest Users
				$company = ArrayHelper::toObject($companyInfo);
			}
		}
		
		// Return error if company is not set
		if (empty($company)) {
			flash(t("Please select a company or 'New Company' to create one."))->error();
			
			return back()->withInput($request->except('company.logo'));
		}
		
		// New Post
		$post = new Post();
		$input = $request->only($post->getFillable());
		foreach ($input as $key => $value) {
			$post->{$key} = $value;
		}
		
		$post->country_code = config('country.code');
		$post->user_id = (auth()->check()) ? auth()->user()->id : 0;
		$post->company_id = (isset($company->id)) ? $company->id : 0;
		$post->company_name = (isset($company->name)) ? $company->name : null;
		$post->logo = (isset($company->logo)) ? $company->logo : null;
		$post->company_description = (isset($company->description)) ? $company->description : null;
		$post->negotiable = $request->input('negotiable');
		$post->phone_hidden = $request->input('phone_hidden');
		$post->lat = $city->latitude;
		$post->lon = $city->longitude;
		$post->ip_addr = Ip::get();
		$post->tmp_token = md5(microtime() . mt_rand(100000, 999999));
		$post->verified_email = 1;
		$post->verified_phone = 1;
		$post->reviewed = 0;
		$post->is_confidential = $request->input('is_confidential');

		$minSalary = (double)$request->input('salary_min');
		$maxSalary = (double)$request->input('salary_max');
		

		if ($minSalary < 1000000) {
			
			// Anything less than a million
			$post->salary_min = number_format($minSalary);
		} elseif ($minSalary >= 1000000) {
			// Anything more than a million
			$post->salary_min = number_format($minSalary / 1000000, 3) . 'M';
		}

		if ($maxSalary < 1000000) {
			// Anything less than a million
			$post->salary_max = number_format($maxSalary);
		} elseif ($minSalary >= 1000000) {
			// Anything more than a million
			$post->salary_max = number_format($maxSalary / 1000000, 3) . 'M';
		}

		if(empty($request->input('currency')))
			$post->salary_currency = 'IQD';
		else
			$post->salary_currency = $request->input('currency');
		//die($post->salary_currency);
		// Email verification key generation
		if ($emailVerificationRequired) {
			$post->email_token = md5(microtime() . mt_rand());
			$post->verified_email = 0;
		}
		
		// Mobile activation key generation
		if ($phoneVerificationRequired) {
			$post->phone_token = mt_rand(100000, 999999);
			$post->verified_phone = 0;
		}
		
		// Save
		$post->save();

		// if(auth()->user()->is_admin != 1)
		// {
		// 	$userPackages = PackageRequest::where([
		// 		['user_id', '=', auth()->user()->id],
		// 		['active', '=', 1],
		// 		['valid_jobs_num', '>', '0'],
		// 		['end_date', '>', date('Y-m-d', time())],
		// 	])->first();

		// 	$userPackages->valid_jobs_num = (int)$userPackages->valid_jobs_num - 1;
		// 	$userPackages->save();
		// }


		// Save ad Id in session (for next steps)
		session(['tmpPostId' => $post->id]);
		
		// Auto-Register the Author
		$user = $this->register($post);
		
		// Save Logo (for Guest Users)
		if (!auth()->check()) {
			if ($request->hasFile('company.logo')) {
				$post->logo = $request->file('company.logo');
				$post->save();
			}
		}
		
		// The Post's creation message
		if (getSegment(2) == 'create') {
			session()->flash('message', t('Your ad has been created.'));
		}
		
		// Get Next URL
		if (
			isset($this->data['countPackages']) &&
			isset($this->data['countPaymentMethods']) &&
			$this->data['countPackages'] > 0 &&
			$this->data['countPaymentMethods'] > 0
		) {
			$nextStepUrl = config('app.locale') . '/posts/create/' . $post->tmp_token . '/payment';
		} else {
			$request->session()->flash('message', t('Your ad has been created.'));
			$nextStepUrl = config('app.locale') . '/posts/create/' . $post->tmp_token . '/finish';
		}
		
		// Send Admin Notification Email
		if (config('settings.mail.admin_notification') == 1) {
			try {
				// Get all admin users
				$admins = User::permission(Permission::getStaffPermissions())->get();
				if ($admins->count() > 0) {
					Notification::send($admins, new PostNotification($post));
					/*
					foreach ($admins as $admin) {
						Notification::route('mail', $admin->email)->notify(new PostNotification($post));
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
			session(['itemNextUrl' => $nextStepUrl]);
			
			// Email
			if ($emailVerificationRequired) {
				// Send Verification Link by Email
				$this->sendVerificationEmail($post);
				
				// Show the Re-send link
				$this->showReSendVerificationEmailLink($post, 'post');
			}
			
			// Phone
			if ($phoneVerificationRequired) {
				// Send Verification Code by SMS
				$this->sendVerificationSms($post);
				
				// Show the Re-send link
				$this->showReSendVerificationSmsLink($post, 'post');
				
				// Go to Phone Number verification
				$nextStepUrl = config('app.locale') . '/verify/post/phone/';
			}
			
			// Send Confirmation Email or SMS,
			// When User clicks on the Verification Link or enters the Verification Code.
			// Done in the "app/Observers/PostObserver.php" file.
			
		} else {
			
			// Send Confirmation Email or SMS
			if (config('settings.mail.confirmation') == 1) {
				try {
					if (config('settings.single.posts_review_activation') == 1) {
						$post->notify(new PostActivated($post));
					} else {
						$post->notify(new PostReviewed($post));
					}
				} catch (\Exception $e) {
					flash($e->getMessage())->error();
				}
			}
			
		}
		
		// Redirection
		return redirect($nextStepUrl);
	}
	
	/**
	 * Confirmation
	 *
	 * @param $tmpToken
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
	 */
	public function finish($tmpToken)
	{
		// Keep Success Message for the page refreshing
		session()->keep(['message']);
		if (!session()->has('message')) {
			return redirect(config('app.locale') . '/');
		}
		
		// Clear the steps wizard
		if (session()->has('tmpPostId')) {
			// Get the Post
			$post = Post::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])->where('id', session('tmpPostId'))->where('tmp_token', $tmpToken)->first();
			if (empty($post)) {
				abort(404);
			}
			
			// Apply finish actions
			$post->tmp_token = null;
			$post->save();
			session()->forget('tmpPostId');
		}
		
		// Redirect to the Post,
		// - If User is logged
		// - Or if Email and Phone verification option is not activated
		if (auth()->check() || (config('settings.mail.email_verification') != 1 && config('settings.sms.phone_verification') != 1)) {
			if (!empty($post)) {
				flash(session('message'))->success();
				
				return redirect(config('app.locale') . '/' . $post->uri . '?preview=1');
			}
		}
		
		// Meta Tags
		MetaTag::set('title', session('message'));
		MetaTag::set('description', session('message'));
		
		return view('post.createOrEdit.multiSteps.finish');
	}

	public function jobPostingPlans()
	{
		$data = [];
		$postPackages = PostPackage::where('active', 1)->get();
		$userPackages = PackageRequest::where('user_id', auth()->user()->id)->get();
		$hasFreePackage = 0;
		if(!empty($userPackages))
		{
			foreach($userPackages as $userPackage)
			{
				if($userPackage->postPackage->price == 0)
				{
					$hasFreePackage = 1;
					break;
				}
			}
		}

		$data['postPackages'] = $postPackages;
		$data['hasFreePackage'] = $hasFreePackage;
		return view('post.createOrEdit.multiSteps.postingPlans', $data);
	}

	public function requestPackage(Request $request)
	{
		$packageId = $request->input('package_id');
		$requestedPackage = PostPackage::findOrFail($packageId);
		$userPackageRequest = new PackageRequest();
		$userPackageRequest->user_id = auth()->user()->id;
		$userPackageRequest->package_id = $packageId;
		$userPackageRequest->date = date('Y-m-d h:i:s', time());

		if($requestedPackage->price == 0)
		{
			$userPackageRequest->paid_status = 1;
			//$userPackageRequest->approve_date = date('Y-m-d', time());
			//$period = $requestedPackage->period;
			// $period = (string) $period;
			// $userPackageRequest->end_date = date('Y-m-d',strtotime("+".$period." day", time()));
			$userPackageRequest->active = 0;
			$userPackageRequest->valid_jobs_num = $requestedPackage->post_num;
			$userPackageRequest->save();

			return redirect(lurl('account'))->with('success', 'The '.$requestedPackage->name.' package has been requested successfully');

			// change approved date and end date on active status changed for the non free packages
		}
		else
		{
			$userPackageRequest->paid_status = 0;
			$userPackageRequest->active = 0;
			$userPackageRequest->valid_jobs_num = $requestedPackage->post_num;
			$userPackageRequest->save();

			return redirect(lurl('account'))->with('success', 'The '.$requestedPackage->name.' package has been requested successfully');
		}
		
	}
}
