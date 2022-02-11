<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/*
|--------------------------------------------------------------------------
| Upgrading
|--------------------------------------------------------------------------
|
| The upgrading process routes
|
*/
Route::group(['middleware' => ['web'], 'namespace' => 'App\Http\Controllers'], function () {
	Route::get('upgrade', 'UpgradeController@version');
});

Route::get('account/edit-profile-head','AccountBaseController@editProfileHead')->name('account.editProfileHead');

/*
|--------------------------------------------------------------------------
| Installation
|--------------------------------------------------------------------------
|
| The installation process routes
|
*/
Route::group([
	'middleware' => ['web', 'install.checker'],
	'namespace'  => 'App\Http\Controllers',
], function () {
	Route::get('install', 'InstallController@starting');
	Route::get('install/site_info', 'InstallController@siteInfo');
	Route::post('install/site_info', 'InstallController@siteInfo');
	Route::get('install/system_compatibility', 'InstallController@systemCompatibility');
	Route::get('install/database', 'InstallController@database');
	Route::post('install/database', 'InstallController@database');
	Route::get('install/database_import', 'InstallController@databaseImport');
	Route::get('install/cron_jobs', 'InstallController@cronJobs');
	Route::get('install/finish', 'InstallController@finish');
});


/*
|--------------------------------------------------------------------------
| Back-end
|--------------------------------------------------------------------------
|
| The admin panel routes
|
*/
Route::group([
	'namespace'  => 'App\Http\Controllers\Admin',
	'middleware' => ['web', 'install.checker'],
	'prefix'     => config('larapen.admin.route_prefix', 'admin'),
], function ($router) {
	// Auth
	Route::auth();
	Route::get('logout', 'Auth\LoginController@logout');
	
	// Admin Panel Area
	Route::group([
		'middleware' => ['admin', 'clearance', 'banned.user', 'prevent.back.history'],
	], function ($router) {
		// Dashboard
		Route::get('dashboard', 'DashboardController@dashboard');
		Route::get('/', 'DashboardController@redirect');
		
		// Extra (must be called before CRUD)
		Route::get('homepage/{action}', 'HomeSectionController@reset')->where('action', 'reset_(.*)');
		Route::get('languages/sync_files', 'LanguageController@syncFilesLines');
		Route::get('permissions/create_default_entries', 'PermissionController@createDefaultEntries');
		
		// CRUD
		CRUD::resource('advertisings', 'AdvertisingController');
		CRUD::resource('blacklists', 'BlacklistController');
		CRUD::resource('categories', 'CategoryController');
		CRUD::resource('categories/{catId}/subcategories', 'SubCategoryController');
		CRUD::resource('cities', 'CityController');
		CRUD::resource('companies', 'CompanyController');
		CRUD::resource('countries', 'CountryController');
		CRUD::resource('countries/{countryCode}/cities', 'CityController');
		CRUD::resource('countries/{countryCode}/admins1', 'SubAdmin1Controller');
		CRUD::resource('currencies', 'CurrencyController');
		CRUD::resource('genders', 'GenderController');
		CRUD::resource('homepage', 'HomeSectionController');
		CRUD::resource('admins1/{admin1Code}/cities', 'CityController');
		CRUD::resource('admins1/{admin1Code}/admins2', 'SubAdmin2Controller');
		CRUD::resource('admins2/{admin2Code}/cities', 'CityController');
		CRUD::resource('languages', 'LanguageController');
		CRUD::resource('meta_tags', 'MetaTagController');
		CRUD::resource('packages', 'PackageController');
		CRUD::resource('pages', 'PageController');
		CRUD::resource('payments', 'PaymentController');
		CRUD::resource('payment_methods', 'PaymentMethodController');
		CRUD::resource('permissions', 'PermissionController');
		CRUD::resource('pictures', 'PictureController');
		CRUD::resource('posts', 'PostController');
		CRUD::resource('p_types', 'PostTypeController');
		CRUD::resource('report_types', 'ReportTypeController');
		CRUD::resource('roles', 'RoleController');
		CRUD::resource('salary_types', 'SalaryTypeController');
		CRUD::resource('settings', 'SettingController');
		CRUD::resource('time_zones', 'TimeZoneController');
		CRUD::resource('users', 'UserController');
		CRUD::resource('employers', 'EmployerController');
		CRUD::resource('super-admins', 'SuperAdminsController');
		CRUD::resource('post_packages', 'PackagesController');
		CRUD::resource('packages-requests', 'PackageRequestController');
		
		// Others
		Route::get('account', 'UserController@account');
		Route::post('users/add-note', 'UserController@addNote');
		Route::post('ajax/{table}/{field}', 'InlineRequestController@make');
		
		// Backup
		Route::get('backups', 'BackupController@index');
		Route::put('backups/create', 'BackupController@create');
		Route::get('backups/download/{file_name?}', 'BackupController@download');
		Route::delete('backups/delete/{file_name?}', 'BackupController@delete')->where('file_name', '(.*)');
		
		// Actions
		Route::get('actions/clear_cache', 'ActionController@clearCache');
		Route::get('actions/call_ads_cleaner_command', 'ActionController@callAdsCleanerCommand');
		Route::post('actions/maintenance_down', 'ActionController@maintenanceDown');
		Route::get('actions/maintenance_up', 'ActionController@maintenanceUp');
		
		// Re-send Email or Phone verification message
		Route::get('verify/user/{id}/resend/email', 'UserController@reSendVerificationEmail');
		Route::get('verify/user/{id}/resend/sms', 'UserController@reSendVerificationSms');
		Route::get('verify/post/{id}/resend/email', 'PostController@reSendVerificationEmail');
		Route::get('verify/post/{id}/resend/sms', 'PostController@reSendVerificationSms');
		Route::post('verify/user/resend/email', 'UserController@ajaxReSendVerificationEmail');
		Route::post('complete-profile/user/resend/email', 'UserController@ajaxSendCompleteProfileEmail');
		Route::post('upload-resume/user/resend/email', 'UserController@ajaxSendUploadResumeEmail');
		Route::post('custom-email/user/resend/email', 'UserController@ajaxSendCustomEmail');
		Route::post('invoice-email/user/resend/email', 'UserController@ajaxSendInvoiceEmail');
		
		// Plugins
		Route::get('plugins', 'PluginController@index');
		Route::post('plugins/{plugin}/install', 'PluginController@install');
		Route::get('plugins/{plugin}/install', 'PluginController@install');
		Route::get('plugins/{plugin}/uninstall', 'PluginController@uninstall');
		Route::get('plugins/{plugin}/delete', 'PluginController@delete');
	});
});


/*
|--------------------------------------------------------------------------
| Front-end
|--------------------------------------------------------------------------
|
| The not translated front-end routes
|
*/
Route::group([
	'middleware' => ['web', 'install.checker'],
	'namespace'  => 'App\Http\Controllers',
], function ($router) {
	// SEO
	Route::get('sitemaps.xml', 'SitemapsController@index');
	
	// Impersonate (As admin user, login as an another user)
	Route::group(['middleware' => 'auth'], function ($router) {
		Route::impersonate();
	});
});


/*
|--------------------------------------------------------------------------
| Front-end
|--------------------------------------------------------------------------
|
| The translated front-end routes
|
*/
Route::group([
	'prefix'     => LaravelLocalization::setLocale(),
	'middleware' => ['local'],
	'namespace'  => 'App\Http\Controllers',
], function ($router) {
	Route::group(['middleware' => ['web', 'install.checker']], function ($router) {
		// HOMEPAGE
		Route::get('/', 'HomeController@index');
		Route::get('employer','HomeController@employers')->name('employer.landingPage');
		Route::get(LaravelLocalization::transRoute('routes.countries'), 'CountriesController@index');
		
		
		// AUTH
		Route::group(['middleware' => ['guest', 'prevent.back.history']], function ($router) {
		    //Home page
			// Registration Routes...
			Route::get(LaravelLocalization::transRoute('routes.register'), 'Auth\RegisterController@showRegistrationForm');
			Route::post(LaravelLocalization::transRoute('routes.register'), 'Auth\RegisterController@register');
			Route::get('register/finish', 'Auth\RegisterController@finish');
			Route::get('employers/register', 'Auth\RegisterController@showEmployerRegistrationForm')->name('employers.register');
			Route::get('employers-invitation/{companyId}/register', 'Auth\RegisterController@employerInviteRegister');
			Route::post('employers-invitation/{companyId}/register', 'Auth\RegisterController@employerInviteRegister');
			Route::post('employers/register', 'Auth\RegisterController@employerRegister')->name('employers.register.post');
			
			// Authentication Routes...
			Route::get('employers/login', 'Auth\LoginController@showLoginForm')->name('employers.login');
			Route::post('employers/login', 'Auth\LoginController@login');
			Route::get(LaravelLocalization::transRoute('routes.login'), 'Auth\LoginController@showLoginForm');
			Route::post(LaravelLocalization::transRoute('routes.login'), 'Auth\LoginController@login');
			
			// Forgot Password Routes...
			Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
			Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
			
			// Reset Password using Token
			Route::get('password/token', 'Auth\ForgotPasswordController@showTokenRequestForm');
			Route::post('password/token', 'Auth\ForgotPasswordController@sendResetToken');
			
			// Reset Password using Link (Core Routes...)
			Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
			Route::post('password/reset', 'Auth\ResetPasswordController@reset');
			
			// Social Authentication
			$router->pattern('provider', 'facebook|linkedin|twitter|google');
			Route::get('auth/{provider}', 'Auth\SocialController@redirectToProvider');
			Route::get('auth/{provider}/callback', 'Auth\SocialController@handleProviderCallback');
		});
		
		// Email Address or Phone Number verification
		$router->pattern('field', 'email|phone');
		Route::get('verify/user/{id}/resend/email', 'Auth\RegisterController@reSendVerificationEmail');
		Route::get('verify/user/{id}/resend/sms', 'Auth\RegisterController@reSendVerificationSms');
		Route::get('verify/user/{field}/{token?}', 'Auth\RegisterController@verification');
		Route::post('verify/user/{field}/{token?}', 'Auth\RegisterController@verification');
		
		// User Logout
		Route::get(LaravelLocalization::transRoute('routes.logout'), 'Auth\LoginController@logout');
		
		
		// POSTS
		Route::group(['namespace' => 'Post'], function ($router) {
			$router->pattern('id', '[0-9]+');
			// $router->pattern('slug', '.*');
			$router->pattern('slug', '^(?=.*)((?!\/).)*$');
			
			// SingleStep Post creation
			Route::group(['namespace' => 'CreateOrEdit\SingleStep'], function ($router) {
				Route::get('create', 'CreateController@getForm');
				Route::post('create', 'CreateController@postForm');
				Route::get('create/finish', 'CreateController@finish');
				
				// Payment Gateway Success & Cancel
				Route::get('create/payment/success', 'CreateController@paymentConfirmation');
				Route::get('create/payment/cancel', 'CreateController@paymentCancel');
				
				// Email Address or Phone Number verification
				$router->pattern('field', 'email|phone');
				Route::get('verify/post/{id}/resend/email', 'CreateController@reSendVerificationEmail');
				Route::get('verify/post/{id}/resend/sms', 'CreateController@reSendVerificationSms');
				Route::get('verify/post/{field}/{token?}', 'CreateController@verification');
				Route::post('verify/post/{field}/{token?}', 'CreateController@verification');
			});
			
			// MultiSteps Post creation
			Route::group(['namespace' => 'CreateOrEdit\MultiSteps'], function ($router) {
				Route::get('posts/create/{tmpToken?}', 'CreateController@getForm');
				Route::post('posts/create', 'CreateController@postForm');
				Route::put('posts/create/{tmpToken}', 'CreateController@postForm');
				Route::get('posts/create/{tmpToken}/payment', 'PaymentController@getForm');
				Route::post('posts/create/{tmpToken}/payment', 'PaymentController@postForm');
				Route::get('posts/create/{tmpToken}/finish', 'CreateController@finish');
				Route::get('posts/posting-plans', 'CreateController@jobPostingPlans');
				Route::post('posts/request-package', 'CreateController@requestPackage');
				
				// Payment Gateway Success & Cancel
				Route::get('posts/create/{tmpToken}/payment/success', 'PaymentController@paymentConfirmation');
				Route::get('posts/create/{tmpToken}/payment/cancel', 'PaymentController@paymentCancel');
				
				// Email Address or Phone Number verification
				$router->pattern('field', 'email|phone');
				Route::get('verify/post/{id}/resend/email', 'CreateController@reSendVerificationEmail');
				Route::get('verify/post/{id}/resend/sms', 'CreateController@reSendVerificationSms');
				Route::get('verify/post/{field}/{token?}', 'CreateController@verification');
				Route::post('verify/post/{field}/{token?}', 'CreateController@verification');
			});
			
			Route::group(['middleware' => 'auth'], function ($router) {
				$router->pattern('id', '[0-9]+');
				
				// SingleStep Post edition
				Route::group(['namespace' => 'CreateOrEdit\SingleStep'], function ($router) {
					Route::get('edit/{id}', 'EditController@getForm');
					Route::put('edit/{id}', 'EditController@postForm');
					
					// Payment Gateway Success & Cancel
					Route::get('edit/{id}/payment/success', 'EditController@paymentConfirmation');
					Route::get('edit/{id}/payment/cancel', 'EditController@paymentCancel');
				});
				
				// MultiSteps Post edition
				Route::group(['namespace' => 'CreateOrEdit\MultiSteps'], function ($router) {
					Route::get('posts/{id}/edit', 'EditController@getForm');
					Route::put('posts/{id}/edit', 'EditController@postForm');
					Route::get('posts/{id}/payment', 'PaymentController@getForm');
					Route::post('posts/{id}/payment', 'PaymentController@postForm');
					
					// Payment Gateway Success & Cancel
					Route::get('posts/{id}/payment/success', 'PaymentController@paymentConfirmation');
					Route::get('posts/{id}/payment/cancel', 'PaymentController@paymentCancel');
				});
			});
			
			// Post's Details
			Route::get(LaravelLocalization::transRoute('routes.post'), 'DetailsController@index');
			
			// Contact Job's Author
			Route::post('posts/{id}/contact', 'DetailsController@sendMessage');
			
			Route::post('posts/ajax-check-if-registered', 'DetailsController@checkIfRegistered');
			
			// Send report abuse
			Route::get('posts/{id}/report', 'ReportController@showReportForm');
			Route::post('posts/{id}/report', 'ReportController@sendReport');
		});
		Route::post('send-by-email', 'Search\SearchController@sendByEmail');
		
		
		// ACCOUNT
		Route::group(['middleware' => ['auth', 'banned.user', 'prevent.back.history'], 'namespace' => 'Account'], function ($router) {
			$router->pattern('id', '[0-9]+');
			
			// Users
			Route::get('account', 'EditController@index');
			Route::get('account/company-invitation', 'EditController@companyEmployersInvitation');
			Route::get('account/generate-free-text', 'EditController@generateFreeText');
			Route::post('invite-company-members/send/email', 'EditController@sendInviteCompanyMemberEmail');
			Route::group(['middleware' => 'impersonate.protect'], function () {
				Route::put('account', 'EditController@updateDetails');
				Route::put('account/settings', 'EditController@updateSettings');
				Route::put('account/preferences', 'EditController@updatePreferences');
			});
			Route::get('account/close', 'CloseController@index');
			Route::group(['middleware' => 'impersonate.protect'], function () {
				Route::post('account/close', 'CloseController@submit');
			});

			
			Route::get('account/edit-profile-head','ProfileController@editProfileHead')
			->name('account.editProfileHead');

			Route::put('account/update-profile-head','ProfileController@updateProfileHead')
			->name('account.updateProfileHead');

			Route::get('account/edit-personal-info','ProfileController@editPersonalInfo')
			->name('account.editPersonalInfo');

			Route::put('account/update-personal-info','ProfileController@updatePersonalInfo')
			->name('account.updatePersonalInfo');

			Route::get('account/edit-contact-info','ProfileController@editContactInfo')
			->name('account.editContactInfo');

			Route::put('account/update-contact-info','ProfileController@updateContactInfo')
			->name('account.updateContactInfo');

			Route::get('account/edit-preferred-job','ProfileController@editPreferredJob')
			->name('account.editPreferredJob');

			Route::put('account/update-preferred-job','ProfileController@updatePreferredJob')
			->name('account.updatePreferredJob');

			Route::get('account/edit-user-experience/{id}','ProfileController@editUserExperience')
			->name('account.editUserExperience');

			Route::put('account/update-user-experience/{id}','ProfileController@updateUserExperience')
			->name('account.updateUserExperience');

			Route::get('account/create-user-experience','ProfileController@createUserExperience')
			->name('account.createUserExperience');

			Route::post('account/store-user-experience','ProfileController@storeUserExperience')
			->name('account.storeUserExperience');

			Route::get('account/delete-user-experience/{id}','ProfileController@deleteUserExperience')
			->name('account.deleteUserExperience');

			Route::get('account/edit-total-experience/','ProfileController@editTotalExperience')
			->name('account.editTotalExperience');

			Route::put('account/update-total-experience/','ProfileController@updateTotalExperience')
			->name('account.updateTotalExperience');

			Route::get('account/edit-user-education/{id}','ProfileController@editUserEducation')
			->name('account.editUserEducation');

			Route::put('account/update-user-education/{id}','ProfileController@updateUserEducation')
			->name('account.updateUserEducation');

			Route::get('account/create-user-education','ProfileController@createUserEducation')
			->name('account.createUserEducation');

			Route::post('account/store-user-education','ProfileController@storeUserEducation')
			->name('account.storeUserEducation');

			Route::get('account/delete-user-education/{id}','ProfileController@deleteUserEducation')
			->name('account.deleteUserEducation');

			Route::get('account/edit-user-skill/{id}','ProfileController@editUserSkill')
			->name('account.editUserSkill');

			Route::put('account/update-user-skill/{id}','ProfileController@updateUserSkill')
			->name('account.updateUserSkill');

			Route::get('account/create-user-skill','ProfileController@createUserSkill')
			->name('account.createUserSkill');

			Route::post('account/store-user-skill','ProfileController@storeUserSkill')
			->name('account.storeUserSkill');

			Route::get('account/delete-user-skill/{id}','ProfileController@deleteUserSkill')
			->name('account.deleteUserSkill');

			Route::get('account/edit-user-lang/{id}','ProfileController@editUserLang')
			->name('account.editUserLang');

			Route::put('account/update-user-lang/{id}','ProfileController@updateUserLang')
			->name('account.updateUserLang');

			Route::get('account/create-user-lang','ProfileController@createUserLang')
			->name('account.createUserLang');

			Route::post('account/store-user-lang','ProfileController@storeUserLang')
			->name('account.storeUserLang');

			Route::get('account/delete-user-lang/{id}','ProfileController@deleteUserLang')
			->name('account.deleteUserLang');

			Route::get('account/edit-user-training/{id}','ProfileController@editUserTraining')
			->name('account.editUserTraining');

			Route::put('account/update-user-training/{id}','ProfileController@updateUserTraining')
			->name('account.updateUserTraining');

			Route::get('account/create-user-training','ProfileController@createUserTraining')
			->name('account.createUserTraining');

			Route::post('account/store-user-training','ProfileController@storeUserTraining')
			->name('account.storeUserTraining');

			Route::get('account/delete-user-training/{id}','ProfileController@deleteUserTraining')
			->name('account.deleteUserTraining');

			Route::get('account/edit-user-reference/{id}','ProfileController@editUserReference')
			->name('account.editUserReference');

			Route::put('account/update-user-reference/{id}','ProfileController@updateUserReference')
			->name('account.updateUserReference');

			Route::get('account/create-user-reference','ProfileController@createUserReference')
			->name('account.createUserReference');

			Route::post('account/store-user-reference','ProfileController@storeUserReference')
			->name('account.storeUserReference');

			Route::get('account/delete-user-reference/{id}','ProfileController@deleteUserReference')
			->name('account.deleteUserReference');

			Route::get('account/edit-video/{id}','ProfileController@editVideo')
			->name('account.editVideo');

			Route::put('account/update-video/{id}','ProfileController@updateVideo')
			->name('account.updateVideo');

			Route::get('account/create-video','ProfileController@createVideo')
			->name('account.createVideo');

			Route::post('account/store-video','ProfileController@storeVideo')
			->name('account.storeVideo');

			Route::get('account/delete-video/{id}','ProfileController@deleteVideo')
			->name('account.deleteVideo');

			Route::get('profile/employer-view/{id}','ProfileController@userProfileEmployerView')
			->name('profile.userProfileEmployerView');

			
			// Companies
			Route::get('account/companies', 'CompanyController@index');
			Route::get('account/companies/create', 'CompanyController@create');
			Route::post('account/companies', 'CompanyController@store');
			Route::get('account/companies/{id}', 'CompanyController@show');
			Route::get('account/companies/{id}/edit', 'CompanyController@edit');
			Route::put('account/companies/{id}', 'CompanyController@update');
			Route::get('account/companies/{id}/delete', 'CompanyController@destroy');
			Route::post('account/companies/delete', 'CompanyController@destroy');
			
			// Resumes
			Route::get('account/resumes', 'ResumeController@index');
			Route::get('account/resumes/create', 'ResumeController@create');
			Route::post('account/resumes', 'ResumeController@store');
			Route::get('account/resumes/{id}', 'ResumeController@show');
			Route::get('account/resumes/{id}/edit', 'ResumeController@edit');
			Route::put('account/resumes/{id}', 'ResumeController@update');
			Route::get('account/resumes/{id}/delete', 'ResumeController@destroy');
			Route::post('account/resumes/delete', 'ResumeController@destroy');
			
			// Posts
			Route::get('account/saved-search', 'PostsController@getSavedSearch');
			$router->pattern('pagePath', '(my-posts|archived|favourite|pending-approval|saved-search)+');
			Route::get('account/{pagePath}', 'PostsController@getPage');
			Route::get('account/my-posts/{id}/offline', 'PostsController@getMyPosts');
			Route::get('account/archived/{id}/repost', 'PostsController@getArchivedPosts');
			Route::get('account/{pagePath}/{id}/delete', 'PostsController@destroy');
			Route::post('account/{pagePath}/delete', 'PostsController@destroy');
			
			// Conversations
			Route::get('account/conversations', 'ConversationsController@index');
			Route::get('account/conversations/{id}/delete', 'ConversationsController@destroy');
			Route::post('account/conversations/delete', 'ConversationsController@destroy');
			Route::post('account/conversations/{id}/reply', 'ConversationsController@reply');
			$router->pattern('msgId', '[0-9]+');
			Route::get('account/conversations/{id}/messages', 'ConversationsController@messages');
			Route::get('account/conversations/{id}/messages/{msgId}/delete', 'ConversationsController@destroyMessages');
			Route::get('account/conversations/{id}/applicants', 'ConversationsController@applicants')->name('job.applicants');
			Route::get('account/my-people', 'ConversationsController@myPeople')->name('job.people');
			Route::get('account/allocate-to-employers', 'ConversationsController@allocateCandidatesToEmployers')->name('job.people');
			Route::get('account/my-sent-emails', 'ConversationsController@mySentEmails');
			Route::post('account/allocate-candidates', 'ConversationsController@allocateCandidates');
			Route::post('account/allocate-candidates-filter', 'ConversationsController@allocateCandidateFilter');
			Route::post('account/candidates-search', 'ConversationsController@candidateSearch');
			Route::post('account/conversations/{id}/messages/delete', 'ConversationsController@destroyMessages');
			Route::post('account/conversations/add-note', 'ConversationsController@addNote')->name("conversation.addNote");
			Route::get('account/conversations/{id}/stage', 'ConversationsController@changeApplicationStage')->name('job.applicationStage');
			Route::post('read-resume', 'ConversationsController@readCandidateResume');
			Route::post('add-candidate', 'ConversationsController@addCandidate');
			Route::post('assign-to-job', 'ConversationsController@assignToJob');
			Route::post('add-candidate-rating', 'ConversationsController@addCandidateRating');

			
			// Transactions
			Route::get('account/transactions', 'TransactionsController@index');
		});
		
		
		// AJAX
		Route::group(['prefix' => 'ajax'], function ($router) {
			Route::get('countries/{countryCode}/admins/{adminType}', 'Ajax\LocationController@getAdmins');
			Route::get('countries/{countryCode}/admins/{adminType}/{adminCode}/cities', 'Ajax\LocationController@getCities');
			Route::get('countries/{countryCode}/cities/{id}', 'Ajax\LocationController@getSelectedCity');
			Route::post('countries/{countryCode}/cities/autocomplete', 'Ajax\LocationController@searchedCities');
			Route::post('countries/{countryCode}/admin1/cities', 'Ajax\LocationController@getAdmin1WithCities');
			Route::post('category/sub-categories', 'Ajax\CategoryController@getSubCategories');
			Route::post('save/post', 'Ajax\PostController@savePost');
			Route::post('save/search', 'Ajax\PostController@saveSearch');
			Route::post('post/phone', 'Ajax\PostController@getPhone');
			Route::post('messages/check', 'Ajax\ConversationController@checkNewMessages');
		});
		
		
		// FEEDS
		Route::feeds();
		
		
		// Country Code Pattern
		$countryCodePattern = implode('|', array_map('strtolower', array_keys(getCountries())));
		$router->pattern('countryCode', $countryCodePattern);
		
		
		// XML SITEMAPS
		Route::get('{countryCode}/sitemaps.xml', 'SitemapsController@site');
		Route::get('{countryCode}/sitemaps/pages.xml', 'SitemapsController@pages');
		Route::get('{countryCode}/sitemaps/categories.xml', 'SitemapsController@categories');
		Route::get('{countryCode}/sitemaps/cities.xml', 'SitemapsController@cities');
		Route::get('{countryCode}/sitemaps/posts.xml', 'SitemapsController@posts');
		
		
		// STATICS PAGES
		Route::get(LaravelLocalization::transRoute('routes.page'), 'PageController@index');
		Route::get(LaravelLocalization::transRoute('routes.contact'), 'PageController@contact');
		Route::post(LaravelLocalization::transRoute('routes.contact'), 'PageController@contactPost');
		Route::get(LaravelLocalization::transRoute('routes.sitemap'), 'SitemapController@index');
		Route::get(LaravelLocalization::transRoute('routes.companies-list'), 'Search\CompanyController@index');
		
		
		// DYNAMIC URL PAGES
		$router->pattern('id', '[0-9]+');
		$router->pattern('username', '[a-zA-Z0-9]+');
		Route::get(LaravelLocalization::transRoute('routes.search'), 'Search\SearchController@index');
		Route::get(LaravelLocalization::transRoute('routes.search-user'), 'Search\UserController@index');
		Route::get(LaravelLocalization::transRoute('routes.search-username'), 'Search\UserController@profile');
		Route::get(LaravelLocalization::transRoute('routes.search-company'), 'Search\CompanyController@profile');
		Route::get(LaravelLocalization::transRoute('routes.search-tag'), 'Search\TagController@index');
		Route::get(LaravelLocalization::transRoute('routes.search-city'), 'Search\CityController@index');
		Route::get(LaravelLocalization::transRoute('routes.search-subCat'), 'Search\CategoryController@index');
		Route::get(LaravelLocalization::transRoute('routes.search-cat'), 'Search\CategoryController@index');
	});
});
