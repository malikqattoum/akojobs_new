<?php
// Search parameters
$queryString = (request()->getQueryString() ? ('?' . request()->getQueryString()) : '');

// Get the Default Language
$cacheExpiration = (isset($cacheExpiration)) ? $cacheExpiration : config('settings.optimization.cache_expiration', 86400);
$defaultLang = Cache::remember('language.default', $cacheExpiration, function () {
    $defaultLang = \App\Models\Language::where('default', 1)->first();
    return $defaultLang;
});

// Check if the Multi-Countries selection is enabled
$multiCountriesIsEnabled = false;
$multiCountriesLabel = '';
if (config('settings.geo_location.country_flag_activation')) {
	if (!empty(config('country.code'))) {
		if (\App\Models\Country::where('active', 1)->count() > 1) {
			$multiCountriesIsEnabled = true;
			$multiCountriesLabel = 'title="' . t('Select a Country') . '"';
		}
	}
}

// Logo Label
$logoLabel = '';
if (getSegment(1) != trans('routes.countries')) {
	$logoLabel = config('settings.app.app_name') . ((!empty(config('country.name'))) ? ' ' . config('country.name') : '');
}
?>
<style>
    .mainLogo{
    width: auto;
    height: 62px;
    float: right;
    margin: -13px 0 0 5px;
    }
    .registerButton12 {
    background: #0841b0 !important;
    border-color: #0841b0 !important;
    color: #fff !important;
    padding: 8px !important;
    font-size: 13px;
    border-radius: 4px;
    box-shadow: none;
    border: none;
    justify-self: center;
    margin-top: 2px;
    }
    .registerButton12:hover{
         background: #808080 !important;
        color:#fff;
    }
    .registerButton:hover {
        background: #808080 !important;
        color:#fff;
    }
    .changeFont{
       font-family: sans-serif;
        font-weight: 400;
    }
    .mobileChange{
        font-size:1rem;
    }
    .registerButtonHeader{
       background: #0841b0 !important;
        border-color: #0841b0 !important;
        color: #fff !important;
        padding: 6px !important;
        border-radius: 4px!important;
    }
</style>
<div class="header">
	<nav class="navbar fixed-top navbar-site navbar-light bg-light navbar-expand-md" role="navigation">
		<div class="container">
			
			<div class="navbar-identity">
				{{-- Logo --}}
				@if (auth()->check() && in_array(auth()->user()->user_type_id, [1]))
					<a href="{{ lurl('/account') }}" class="navbar-brand logo logo-title">
				@elseif(!auth()->check() && getSegment(1) == "employers")
					<a href="{{ route('employers.login') }}" class="navbar-brand logo logo-title">
				@else
					<a href="{{ lurl('/') }}" class="navbar-brand logo logo-title">
				@endif
				
				@php
                $route = Route::current();
                $name = $route->getName();
				@endphp

					@if((!auth()->check() && strpos(url()->current(), 'employers')) !== FALSE || (auth()->check() && in_array(auth()->user()->user_type_id, [1])))
						<!--<img src="{{ \Storage::url('app/logo/akojobs_emp.png') }}"-->
						<!--	alt="{{ strtolower(config('settings.app.app_name')) }}" class="tooltipHere main-logo" title="" data-placement="bottom"-->
						<!--	data-toggle="tooltip"-->
						<!--	data-original-title="{!! isset($logoLabel) ? $logoLabel : '' !!}"/>-->
								<img src="{{ asset('employers/logo.png') }}"
							alt="{{ strtolower(config('settings.app.app_name')) }}" class="tooltipHere main-logo mainLogo  " title="" data-placement="bottom"
							data-toggle="tooltip"
							data-original-title="{!! isset($logoLabel) ? $logoLabel : '' !!}"/>
					@elseif($name == 'employer.landingPage')
					<!--<img src="{{ \Storage::url('app/logo/akojobs_emp.png') }}"-->
					<!--		alt="{{ strtolower(config('settings.app.app_name')) }}" class="tooltipHere main-logo" title="" data-placement="bottom"-->
					<!--		data-toggle="tooltip"-->
					<!--		data-original-title="{!! isset($logoLabel) ? $logoLabel : '' !!}"/>-->
					<img src="{{ asset('employers/logo.png') }}"
							alt="{{ strtolower(config('settings.app.app_name')) }}" class="tooltipHere mainLogo" title="" data-placement="bottom"
							data-toggle="tooltip"
							data-original-title="{!! isset($logoLabel) ? $logoLabel : '' !!}"/>
					@elseif(auth()->check() || !auth()->check() || (auth()->check() && in_array(auth()->user()->user_type_id, [2])))
						<img src="{{ \Storage::url('app/logo/akojobs_js.jpeg') }}"
							alt="{{ strtolower(config('settings.app.app_name')) }}" class="tooltipHere main-logo" title="" data-placement="bottom"
							data-toggle="tooltip"
							data-original-title="{!! isset($logoLabel) ? $logoLabel : '' !!}"/>
						<!--<img src="{{ \Storage::url('app/logo/akojobs_emp.jpeg') }}"-->
						<!--	alt="{{ strtolower(config('settings.app.app_name')) }}" class="tooltipHere main-logo" title="" data-placement="bottom"-->
						<!--	data-toggle="tooltip"-->
						<!--	data-original-title="{!! isset($logoLabel) ? $logoLabel : '' !!}"/>-->
					@else
						<img src="{{ \Storage::url('app/logo/akojobs_js.jpeg') }}"
							alt="{{ strtolower(config('settings.app.app_name')) }}" class="tooltipHere main-logo" title="" data-placement="bottom"
							data-toggle="tooltip"
							data-original-title="{!! isset($logoLabel) ? $logoLabel : '' !!}"/>
					@endif
				</a>
				{{-- Toggle Nav (Mobile) --}}
				<button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggler pull-right" type="button">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30" width="30" height="30" focusable="false">
						<title>{{ t('Menu') }}</title>
						<path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-miterlimit="10" d="M4 7h22M4 15h22M4 23h22"></path>
					</svg>
				</button>
				{{-- Country Flag (Mobile) --}}
				@if (getSegment(1) != trans('routes.countries'))
					@if (isset($multiCountriesIsEnabled) and $multiCountriesIsEnabled)
						@if (!empty(config('country.icode')))
							@if (file_exists(public_path().'/images/flags/24/'.config('country.icode').'.png'))
								<button class="flag-menu country-flag d-block d-md-none btn btn-secondary hidden pull-right" href="#selectCountry" data-toggle="modal">
									<img src="{{ url('images/flags/24/'.config('country.icode').'.png') . getPictureVersion() }}" style="float: left;">
									<span class="caret hidden-xs"></span>
								</button>
							@endif
						@endif
					@endif
				@endif
			</div>
			
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav navbar-left">
					{{-- Country Flag --}}
					@if (getSegment(1) != trans('routes.countries'))
						@if (config('settings.geo_location.country_flag_activation'))
							@if (!empty(config('country.icode')))
								@if (file_exists(public_path().'/images/flags/32/'.config('country.icode').'.png'))
									<li class="flag-menu country-flag tooltipHere hidden-xs nav-item" data-toggle="tooltip" data-placement="{{ (config('lang.direction') == 'rtl') ? 'bottom' : 'right' }}" {!! $multiCountriesLabel !!}>
										@if (isset($multiCountriesIsEnabled) and $multiCountriesIsEnabled)
											<a href="#selectCountry" data-toggle="modal" class="nav-link">
												<img class="flag-icon" src="{{ url('images/flags/32/'.config('country.icode').'.png') . getPictureVersion() }}">
												<span class="caret hidden-sm"></span>
											</a>
										@else
											<a style="cursor: default;">
												<img class="flag-icon no-caret" src="{{ url('images/flags/32/'.config('country.icode').'.png') . getPictureVersion() }}">
											</a>
										@endif
									</li>
								@endif
							@endif
						@endif
					@endif
					<li class="nav-item {{ $name=='employer.landingPage'?'changeFont':'' }}">
						<?php $attr = ['countryCode' => config('country.icode')]; ?>
						<a href="{{ lurl(trans('routes.v-search', $attr), $attr) }}" class="nav-link d-none d-sm-block">
							<i class="icon-th-list-2 hidden-sm"></i> {{ t('Browse Jobs') }}
							
						</a>
						<a href="{{ lurl(trans('routes.v-search', $attr), $attr) }}" class="nav-link d-block d-sm-none mobileChange">
							<i class="icon-th-list-2 hidden-sm"></i> {{ t('Browse Jobs') }}
							
						</a>
					</li>
				</ul>
				
				<ul class="nav navbar-nav ml-auto navbar-right">
					@if (!auth()->check())
						<li class="nav-item">
							@if (config('settings.security.login_open_in_modal'))
								<a href="#quickLogin" class="nav-link" data-toggle="modal"><i class="icon-user fa"></i> {{ t('Log In') }}</a>
							@else
							 @if($name == 'employer.landingPage')
							    <!--<a href="{{ lurl(trans('routes.register')) }}" class='nav-link btn btn-block btn-post btn-add-listing registerButton'><i class="icon-user-add fa"></i>{{ t('Register') }}</a>-->
							    <!--<a href="{{ lurl(trans('routes.register')) }}" class='registerButton'><i class="icon-user-add fa"></i> {{ t('Register') }}</a>-->
							    <a href="{{ route('employers.login') }}" class="nav-link {{ $name=='employer.landingPage'?'changeFont':'' }} d-none d-sm-block"><i class="icon-user fa"></i> {{ t('Log In') }}</a>
							    <a href="{{ route('employers.login') }}" class="nav-link {{ $name=='employer.landingPage'?'changeFont':'' }} d-block d-sm-none mobileChange"><i class="icon-user fa"></i> {{ t('Log In') }}</a>
							    @else
							     <a href="{{ lurl(trans('routes.login')) }}" class="nav-link {{ $name=='employer.landingPage'?'changeFont':'' }}"><i class="icon-user fa"></i> {{ t('Log In') }}</a>
							 @endif   
							@endif
						</li>
						@if (getSegment(1) == "employers")
							<li class="nav-item">
								<a href="{{ route('employers.register') }}" class="nav-link btn btn-block btn-post text-white btn-primary {{ $name=='employer.landingPage'?'changeFont':'' }}"><i class="icon-user-add fa"></i> {{ t('Register') }}</a>
							</li>
							<li class="nav-item">
								<a href="{{ lurl(trans('routes.login')) }}" class="nav-link {{ $name=='employer.landingPage'?'changeFont':'' }}">{{t('jobseeker?')}}</a>
							</li>
						@else
						
							<li class="nav-item">
							    @if($name == 'employer.landingPage')
							    
							    <!--<a href="{{ lurl(trans('routes.register')) }}" class='nav-link btn btn-block btn-post btn-add-listing registerButton'><i class="icon-user-add fa"></i>{{ t('Register') }}</a>-->
							    <!-- Desktop -->
							    <!--<button href="{{ route('employers.register') }}" class="registerButton12 {{ $name=='employer.landingPage'?'changeFont':'' }} d-none d-sm-block text-center align-middle">-->
							    <!--    <i class="icon-user-add fa"></i> {{ t('Register') }}-->
							    <!--</button>-->
								<a href="{{ route('employers.register') }}" class="nav-link btn btn-block btn-post text-white btn-primary"><i class="icon-user-add fa"></i> {{ t('Register') }}</a>
							    <!-- Mobile -->
							    <!--<a href="{{ route('employers.register') }}" class="{{ $name=='employer.landingPage'?'changeFont':'' }} d-block d-sm-none mobileChange registerButtonHeader text-center"><i class="icon-user-add fa"></i>{{ t('Register') }}</a>-->
							    @else
							     <a href="{{ lurl(trans('routes.register')) }}" class="nav-link btn btn-block btn-post btn-add-listing "><i class="icon-user-add fa"></i> {{ t('Register') }}</a>
							    @endif
							</li>
						@endif
						@if (getSegment(1) != "employers")
							<li class="nav-item">
							     @if($name == 'employer.landingPage')
							    <!--<a href="{{ lurl(trans('routes.register')) }}" class='nav-link btn btn-block btn-post btn-add-listing registerButton'><i class="icon-user-add fa"></i>{{ t('Register') }}</a>-->
								<a href="https://www.akojobs.com/login" class="nav-link {{ $name=='employer.landingPage'?'changeFont':'' }} d-none d-sm-block">{{t('jobseeker?')}}</a>
								<a href="https://www.akojobs.com/login" class="nav-link {{ $name=='employer.landingPage'?'changeFont':'' }} d-block d-sm-none mobileChange">{{t('jobseeker?')}}</a>
							    @else
								<a href="{{ route('employer.landingPage') }}" class="nav-link {{ $name=='employer.landingPage'?'changeFont':'' }}">{{t('Employer?')}}</a>
							    @endif
							</li>
						@endif
					@else
						<li class="nav-item">
							@if (app('impersonate')->isImpersonating())
								<a href="{{ route('impersonate.leave') }}" class="nav-link">
									<i class="icon-logout hidden-sm"></i> {{ t('Leave') }}
								</a>
							@else
								<a href="{{ lurl(trans('routes.logout')) }}" class="nav-link">
									<i class="icon-logout hidden-sm"></i> {{ t('Log Out') }}
								</a>
							@endif
						</li>
						<li class="nav-item dropdown no-arrow">
							<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
								<i class="icon-user fa hidden-sm"></i>
								<span>{{ auth()->user()->name }}</span>
								<span class="badge badge-pill badge-important count-conversations-with-new-messages hidden-sm">0</span>
								<i class="icon-down-open-big fa hidden-sm"></i>
							</a>
							<ul id="userMenuDropdown" class="dropdown-menu user-menu dropdown-menu-right shadow-sm">
								<li class="dropdown-item active"><a href="{{ lurl('account') }}"><i class="icon-home"></i> {{ t('Personal Home') }}</a></li>
								@if (in_array(auth()->user()->user_type_id, [1]))
									{{-- <li class="dropdown-item"><a href="{{ lurl('account/companies') }}"><i class="icon-town-hall"></i> {{ t('My companies') }} </a></li> --}}
									<li class="dropdown-item"><a href="{{ lurl('account/my-posts') }}"><i class="icon-th-thumb"></i> {{ t('My ads') }} </a></li>
									<li class="dropdown-item"><a href="{{ lurl('account/pending-approval') }}"><i class="icon-hourglass"></i> {{ t('Pending approval') }} </a></li>
									<li class="dropdown-item"><a href="{{ lurl('account/archived') }}"><i class="icon-folder-close"></i> {{ t('Archived ads') }}</a></li>
									<li class="dropdown-item">
										<a href="{{ url(config('app.locale') . '/posts/create') }}">
											<i class="icon-docs"></i> {{ trans('admin::messages.Post New Ad') }}&nbsp;
										</a>
									</li>
									{{-- <li class="dropdown-item">
										<a href="{{ lurl('account/conversations') }}">
											<i class="icon-mail-1"></i> {{ t('Conversations') }}
											<span class="badge badge-pill badge-important count-conversations-with-new-messages">0</span>
										</a>
									</li> --}}
									{{-- <li class="dropdown-item"><a href="{{ lurl('account/transactions') }}"><i class="icon-money"></i> {{ t('Transactions') }}</a></li> --}}
								@endif
								@if (in_array(auth()->user()->user_type_id, [2]))
									<li class="dropdown-item"><a href="{{ lurl('account/resumes') }}"><i class="icon-attach"></i> {{ t('My resumes') }} </a></li>
									<li class="dropdown-item"><a href="{{ lurl('account/favourite') }}"><i class="icon-heart"></i> {{ t('Favourite jobs') }} </a></li>
									{{-- <li class="dropdown-item"><a href="{{ lurl('account/saved-search') }}"><i class="icon-star-circled"></i> {{ t('Saved searches') }} </a></li> --}}
									<li class="dropdown-item">
										<a href="{{ lurl('account/conversations') }}">
											<i class="icon-mail-1"></i> {{ t('Conversations') }}
											<span class="badge badge-pill badge-important count-conversations-with-new-messages">0</span>
										</a>
									</li>
								@endif
							</ul>
						</li>
					@endif
					
					{{-- @if (!auth()->check())
						<li class="nav-item postadd">
							@if (config('settings.single.guests_can_post_ads') != '1')
								<a class="btn btn-block btn-post btn-add-listing" href="#quickLogin" data-toggle="modal">
									<i class="fa fa-plus-circle"></i> {{ t('Create a Job ad') }}
								</a>
							@else
								<a class="btn btn-block btn-post btn-add-listing" href="{{ addPostURL() }}">
									<i class="fa fa-plus-circle"></i> {{ t('Create a Job ad') }}
								</a>
							@endif
						</li>
					@else
						@if (in_array(auth()->user()->user_type_id, [1]))
							<li class="nav-item postadd">
								<a class="btn btn-block btn-post btn-add-listing" href="{{ addPostURL() }}">
									<i class="fa fa-plus-circle"></i> {{ t('Create a Job ad') }}
								</a>
							</li>
						@endif
					@endif --}}
					
					@include('layouts.inc.menu.select-language')
					
				</ul>
			</div>
		</div>
	</nav>
</div>
