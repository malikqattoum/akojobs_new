
@extends('layouts.master')

@section('content')
	@include('common.spacer')
	<div class="main-container">
		<div class="container">
			<div class="row">

				@if (isset($errors) and $errors->any())
					<div class="col-xl-12">
						<div class="alert alert-danger">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<h5><strong>{{ t('Oops ! An error has occurred. Please correct the red fields in the form') }}</strong></h5>
							<ul class="list list-check">
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					</div>
				@endif

				@if (Session::has('flash_notification'))
					<div class="col-xl-12">
						<div class="row">
							<div class="col-lg-12">
								@include('flash::message')
							</div>
						</div>
					</div>
				@endif

				<div class="col-md-12 page-content">
					<div class="inner-box">
						<h2 class="title-2">
							<strong><i class="icon-user-add"></i> {{ t('Create your free account') }}</strong>
						</h2>
						
						@if (
							config('settings.social_auth.social_login_activation')
							and (
								(config('settings.social_auth.facebook_client_id') and config('settings.social_auth.facebook_client_secret'))
								or (config('settings.social_auth.linkedin_client_id') and config('settings.social_auth.linkedin_client_secret'))
								or (config('settings.social_auth.twitter_client_id') and config('settings.social_auth.twitter_client_secret'))
								or (config('settings.social_auth.google_client_id') and config('settings.social_auth.google_client_secret'))
								)
							)
							<div class="row mb-3 d-flex justify-content-center pl-3 pr-3">
								@if (config('settings.social_auth.facebook_client_id') and config('settings.social_auth.facebook_client_secret'))
								<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-1 pl-1 pr-1">
									<div class="col-xl-12 col-md-12 col-sm-12 col-xs-12 btn btn-lg btn-fb">
										<a href="{{ lurl('auth/facebook') }}" class="btn-fb"><i class="icon-facebook-rect"></i> {!! t('Login with Facebook') !!}</a>
									</div>
								</div>
								@endif
								@if (config('settings.social_auth.linkedin_client_id') and config('settings.social_auth.linkedin_client_secret'))
								<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-1 pl-1 pr-1">
									<div class="col-xl-12 col-md-12 col-sm-12 col-xs-12 btn btn-lg btn-lkin">
										<a href="{{ lurl('auth/linkedin') }}" class="btn-lkin"><i class="icon-linkedin"></i> {!! t('Login with LinkedIn') !!}</a>
									</div>
								</div>
								@endif
								@if (config('settings.social_auth.twitter_client_id') and config('settings.social_auth.twitter_client_secret'))
								<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-1 pl-1 pr-1">
									<div class="col-xl-12 col-md-12 col-sm-12 col-xs-12 btn btn-lg btn-tw">
										<a href="{{ lurl('auth/twitter') }}" class="btn-tw"><i class="icon-twitter-bird"></i> {!! t('Login with Twitter') !!}</a>
									</div>
								</div>
								@endif
								@if (config('settings.social_auth.google_client_id') and config('settings.social_auth.google_client_secret'))
								<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-1 pl-1 pr-1">
									<div class="col-xl-12 col-md-12 col-sm-12 col-xs-12 btn btn-lg btn-danger">
										<a href="{{ lurl('auth/google') }}" class="btn-danger"><i class="icon-googleplus-rect"></i> {!! t('Login with Google') !!}</a>
									</div>
								</div>
								@endif
							</div>
							
							<div class="row d-flex justify-content-center loginOr">
								<div class="col-xl-12 mb-1">
									<hr class="hrOr">
									<span class="spanOr rounded">{{ t('or') }}</span>
								</div>
							</div>
						@endif
						
						<div class="row mt-5">
							<div class="col-xl-12">
								<form id="signupForm" class="form-horizontal" method="POST" action="{{ url()->current() }}" enctype="multipart/form-data">
									{!! csrf_field() !!}
									<fieldset>
										<?php
										/*
										<!-- gender_id -->
										<?php $genderIdError = (isset($errors) and $errors->has('gender_id')) ? ' is-invalid' : ''; ?>
										<div class="form-group row required">
											<label class="col-md-3 col-form-label{{ $genderIdError }}">{{ t('Gender') }} <sup>*</sup></label>
											<div class="col-md-7">
												<select name="gender_id" id="genderId" class="form-control selecter{{ $genderIdError }}">
													<option value="0"
															@if (old('gender_id')=='' or old('gender_id')==0)
																selected="selected"
															@endif
													> {{ t('Select') }} </option>
													@foreach ($genders as $gender)
														<option value="{{ $gender->tid }}"
																@if (old('gender_id')==$gender->tid)
																	selected="selected"
																@endif
														>
															{{ $gender->name }}
														</option>
													@endforeach
												</select>
											</div>
										</div>
										*/
										?>

										<!-- name -->
										<?php $nameError = (isset($errors) and $errors->has('name')) ? ' is-invalid' : ''; ?>
										<div class="form-group row required">
											<label class="col-md-4 col-form-label">{{ t('Name') }} <sup>*</sup></label>
											<div class="col-md-6">
												<input name="name" placeholder="{{ t('Name') }}" class="form-control input-md{{ $nameError }}" type="text" value="{{ old('name') }}">
											</div>
										</div>

										<!-- user_type_id -->
										<?php $userTypeIdError = (isset($errors) and $errors->has('user_type_id')) ? ' is-invalid' : ''; ?>
										{{-- <div class="form-group row required">
											<label class="col-md-4 col-form-label">{{ t('You are a') }} <sup>*</sup></label>
											<div class="col-md-6">
												@foreach ($userTypes as $type)
													<div class="form-check form-check-inline pt-2">
														<input type="radio"
															   name="user_type_id"
															   id="userTypeId-{{ $type->id }}"
															   class="form-check-input user-type{{ $userTypeIdError }}"
															   value="{{ $type->id }}" {{ (old('user_type_id', request()->get('type'))==$type->id) ? 'checked="checked"' : '' }}
														>
														<label class="form-check-label" for="user_type_id-{{ $type->id }}">
															{{ t('' . $type->name) }}
														</label>
													</div>
												@endforeach
											</div>
										</div> --}}
										<input type="hidden" value="2" name="user_type_id" />


										<!-- country_code -->
										@if (empty(config('country.code')))
											<?php $countryCodeError = (isset($errors) and $errors->has('country_code')) ? ' is-invalid' : ''; ?>
											<div class="form-group row required">
												<label class="col-md-4 col-form-label{{ $countryCodeError }}" for="country_code">{{ t('Your Country') }} <sup>*</sup></label>
												<div class="col-md-6">
													<select id="countryCode" name="country_code" class="form-control sselecter{{ $countryCodeError }}">
														<option value="0" {{ (!old('country_code') or old('country_code')==0) ? 'selected="selected"' : '' }}>{{ t('Select') }}</option>
														@foreach ($countries as $code => $item)
															<option value="{{ $code }}" {{ (old('country_code', (!empty(config('ipCountry.code'))) ? config('ipCountry.code') : 0)==$code) ? 'selected="selected"' : '' }}>
																{{ $item->get('name') }}
															</option>
														@endforeach
													</select>
												</div>
											</div>
										@else
											<input id="countryCode" name="country_code" type="hidden" value="{{ config('country.code') }}">
										@endif
										
										@if (isEnabledField('phone'))
											<!-- phone -->
											<?php $phoneError = (isset($errors) and $errors->has('phone')) ? ' is-invalid' : ''; ?>
											<div class="form-group row required">
												<label class="col-md-4 col-form-label">{{ t('Phone') }}
													@if (!isEnabledField('email'))
														<sup>*</sup>
													@endif
													<sup>*</sup>
												</label>
												<div class="col-md-6">
													<div class="input-group">
														{{-- <div class="input-group-prepend">
															<span id="phoneCountry" class="input-group-text">{!! getPhoneIcon(old('country', config('country.code'))) !!}</span>
														</div> --}}
														
														<input name="phone"
															   placeholder="{{ (!isEnabledField('email')) ? t('Mobile Phone Number') : t('Phone Number') }}"
															   class="form-control input-md{{ $phoneError }}"
															   type="text"
															   value="{{ phoneFormat(old('phone'), old('country', config('country.code'))) }}"
														>
														
														<div class="input-group-append tooltipHere" data-placement="top"
															 data-toggle="tooltip"
															 data-original-title="{{ t('Hide the phone number on the ads.') }}">
															<span class="input-group-text">
																<input name="phone_hidden" id="phoneHidden" type="checkbox"
																	   value="1" {{ (old('phone_hidden')=='1') ? 'checked="checked"' : '' }}>&nbsp;<small>{{ t('Hide') }}</small>
															</span>
														</div>
													</div>
												</div>
											</div>
										@endif
										
										@if (isEnabledField('email'))
											<!-- email -->
											<?php $emailError = (isset($errors) and $errors->has('email')) ? ' is-invalid' : ''; ?>
											<div class="form-group row required">
												<label class="col-md-4 col-form-label" for="email">{{ t('Email') }}
													@if (!isEnabledField('phone'))
														<sup>*</sup>
													@endif
													<sup>*</sup>
												</label>
												<div class="col-md-6">
													<div class="input-group">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="icon-mail"></i></span>
														</div>
														<input id="email"
															   name="email"
															   type="email"
															   class="form-control{{ $emailError }}"
															   placeholder="{{ t('Email') }}"
															   value="{{ old('email') }}"
														>
													</div>
												</div>
											</div>
										@endif
										
										@if (0)
											<!-- username -->
											<?php $usernameError = (isset($errors) and $errors->has('username')) ? ' is-invalid' : ''; ?>
											<div class="form-group row required">
												<label class="col-md-4 col-form-label" for="email">{{ t('Username') }}</label>
												<div class="col-md-6">
													<div class="input-group">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="icon-user"></i></span>
														</div>
														<input id="username"
															   name="username"
															   type="text"
															   class="form-control{{ $usernameError }}"
															   placeholder="{{ t('Username') }}"
															   value="{{ old('username') }}"
														>
													</div>
												</div>
											</div>
										@endif
										
										{{-- @if (isEnabledField('username')) --}}
											<!-- job role -->
											<?php $roles = include(base_path() . '/resources/lang/en/roles.php'); 
												asort($roles);
												$rolesKeys = array_keys($roles);
											?>
											<?php //die(var_dump(array_keys($roles))); ?> 
											<?php $regJobRoleError = (isset($errors) and $errors->has('regJobRole')) ? ' is-invalid' : ''; ?>
											<div class="form-group row required" id="jobRoleField">
												<label class="col-md-4 col-form-label" for="email">{{ t('Main Job Role') }} <sup>*</sup></label>
												<div class="col-md-6">
													<div class="form-group">
														<select class="form-control {{$regJobRoleError}}" name="regJobRole" id="regJobRole" placeholder="{{ t('Which field do you want to work in?') }}"
														value="{{ old('regJobRole') }}">
															<option value="" selected="">{{ t('Which field do you want to work in?') }}</option>
															@foreach ($rolesKeys as $item)
																<option value="{{$item}}" {{ (old("regJobRole") == $item ? "selected":"") }}>{{t($item,[],'roles')}}</option>
															@endforeach
														</select>
													</div>
												</div>
											</div>
										{{-- @endif --}}

										<?php $secondaryRegJobRoleError = (isset($errors) and $errors->has('secondaryRegJobRole')) ? ' is-invalid' : ''; ?>
											<div class="form-group row required" id="secondaryJobRoleField">
												<label class="col-md-4 col-form-label">{{ t('Secondary Job Role') }}</label>
												<div class="col-md-6">
													<div class="form-group">
														<select class="form-control {{$secondaryRegJobRoleError}}" name="secondaryRegJobRole" id="secondaryRegJobRole" placeholder="{{ t('Which field do you want to work in?') }}"
														value="{{ old('secondaryRegJobRole') }}">
															<option value="" selected="">{{ t('Which field do you want to work in?') }}</option>
															@foreach ($rolesKeys as $item)
																<option value="{{$item}}" {{ (old("secondaryRegJobRole") == $item ? "selected":"") }}>{{t($item,[],'roles')}}</option>
															@endforeach

														</select>
													</div>
												</div>
											</div>


											<?php $industry = include(base_path() . '/resources/lang/en/industry.php');
												asort($industry); 
												$industryKeys = array_keys($industry);
											?>
											<?php $industryError = (isset($errors) and $errors->has('industry')) ? ' is-invalid' : ''; ?>
											<div class="form-group row required" id="industry">
												<label class="col-md-4 col-form-label">{{ t('Industry') }}</label>
												<div class="col-md-6">
													<div class="form-group">
														<select class="form-control {{$industryError}}" name="industry" id="industry" placeholder="{{ t('Which field do you want to work in?') }}"
														value="{{ old('industry') }}">
															<option value="" selected="">{{ t('What is your industry?') }}</option>
															@foreach ($industryKeys as $item)
																<option value="{{$item}}" {{ (old("industry") == $item ? "selected":"") }} >{{t($item,[],'industry')}}</option>
															@endforeach
														</select>
													</div>
												</div>
											</div>


										<?php $residenceCountry = include(base_path() . '/resources/lang/en/residenceCountry.php');
											asort($residenceCountry); 
											$residenceCountryKeys = array_keys($residenceCountry);
										?>
										<?php $residenceCountryError = (isset($errors) and $errors->has('residence_country')) ? ' is-invalid' : ''; ?>
										<div class="form-group row required" id="residenceCountry">
											<label class="col-md-4 col-form-label">{{ t('residence country') }} <sup>*</sup></label>
											<div class="col-md-6">
												<div class="form-group">
													<select class="form-control {{$residenceCountryError}}" name="residence_country" id="residence_country" placeholder="{{ t('What is your residence country?') }}"
													value="{{ old('residence_country') }}" onchange="showDiv('city', this)">
														<option value="" selected="">{{ t('What is your residence country?') }}</option>
														@foreach ($residenceCountryKeys as $item)
															<option value="{{$item}}" {{ (old("residence_country") == $item ? "selected":"") }} >{{t($item,[],'residenceCountry')}}</option>
														@endforeach
													</select>
												</div>
											</div>
										</div>

										<?php $cityIdError = (isset($errors) and $errors->has('city_id')) ? ' is-invalid' : ''; ?>
										<div id="cityBox" class="form-group row required">
											<label class="col-md-4 col-form-label" for="city_id">
												{{ t('City') }} <sup>*</sup>
											</label>
											<div class="col-md-6">
												<select id="city" name="city_id" class="form-control {{ $cityIdError }}" disabled>
													<option value="" {{ (!old('city_id') or old('city_id')==0) ? 'selected="selected"' : '' }}>
														{{ t('Select a city') }}
													</option>
													@foreach ($cities as $city)
														<option value="{{ $city->id }}" {{ (old('city_id')) ? 'selected="selected"' : '' }}>
															{{(strpos(url()->current(), '/ar') !== false)?$city->ar_name:$city->name}}
														</option>
													@endforeach
												</select>
											</div>
										</div>

										<?php $curJobTitleError = (isset($errors) and $errors->has('curJobTitle')) ? ' is-invalid' : ''; ?>
											<div class="form-group row required" id="jobTitleField">
												<label class="col-md-4 col-form-label" for="email">{{ t('Current Job Title') }}</label>
												<div class="col-md-6">
													<div class="form-group">
														<input id="curJobTitle"
															   name="curJobTitle"
															   type="text"
															   class="form-control{{ $curJobTitleError }}"
															   placeholder="{{ t('Current Job Title') }}"
															   value="{{ old('curJobTitle') }}"
														>
													</div>
												</div>
											</div>

										<?php $experience = include(base_path() . '/resources/lang/en/experience.php'); 
											$experienceKeys = array_keys($experience);
										?>
										<?php $userExperienceError = (isset($errors) and $errors->has('userExperience')) ? ' is-invalid' : ''; ?>
											<div class="form-group row required" id="expField">
												<label class="col-md-4 col-form-label" for="email">{{ t('Experience Years') }}</label>
												<div class="col-md-6">
													<div class="form-group">
														<select class="form-control {{$userExperienceError}}" name="userExperience" id="userExperience" placeholder="{{ t('Which field do you want to work in?') }}"
														value="{{ old('userExperience') }}">
															<option selected value="">{{ t('Experience Years') }}</option>
															@foreach ($experienceKeys as $item)
																<option value="{{$item}}" {{ (old("userExperience") == $item ? "selected":"") }}>{{t($item,[],'experience')}}</option>
															@endforeach
														</select>
													</div>
												</div>
											</div>
											

										<?php $preferredLangError = (isset($errors) and $errors->has('prefLang')) ? ' is-invalid' : ''; ?>
											<div class="form-group row required" id="prefLangField">
												<label class="col-md-4 col-form-label" for="email">{{ t('Preferred Languange') }}</label>
												<div class="col-md-6">
													<div class="form-group">
														<select class="form-control {{$preferredLangError}}" name="prefLang" id="prefLang" placeholder="{{ t('selectLanguage') }}"
														value="{{ old('prefLang') }}">
															<option selected value="">{{ t('simditor.selectLanguage') }}</option>
																<option value="en" {{ (old("prefLang") == "en" ? "selected":"") }}>English</option>
																<option value="ar" {{ (old("prefLang") == "ar" ? "selected":"") }}>العربية</option>
														</select>
													</div>
												</div>
											</div>
							
										<!-- password -->
										<?php $passwordError = (isset($errors) and $errors->has('password')) ? ' is-invalid' : ''; ?>
										<div class="form-group row required">
											<label class="col-md-4 col-form-label" for="password">{{ t('Password') }} <sup>*</sup></label>
											<div class="col-md-6">
												<input id="password" name="password" type="password" class="form-control{{ $passwordError }}" placeholder="{{ t('Password') }}">
												<br>
												<input id="password_confirmation" name="password_confirmation" type="password" class="form-control{{ $passwordError }}"
													   placeholder="{{ t('Password Confirmation') }}">
												<small id="" class="form-text text-muted">
													{{ t('At least :num characters', ['num' => config('larapen.core.passwordLength.min', 6)]) }}
												</small>
											</div>
										</div>
										
										@if (config('larapen.core.register.showCompanyFields'))
											<div id="companyBloc">
												<div class="content-subheading">
													<i class="icon-town-hall fa"></i>
													<strong>{{ t('Company Information') }}</strong>
												</div>
												
												@include('account.company._form', ['originForm' => 'user'])
											</div>
										@endif
										<?php //die(config('larapen.core.register.showResumeFields')); ?>
										@if (config('larapen.core.register.showResumeFields'))
											<div id="resumeBloc">
												<div class="content-subheading">
													<i class="icon-attach fa"></i>
													<strong>{{ t('Resume') }}</strong>
												</div>
												
												@include('account.resume._form', ['originForm' => 'user'])
											</div>
										@endif
										
										@include('layouts.inc.tools.recaptcha', ['colLeft' => 'col-md-4', 'colRight' => 'col-md-6'])
										
										<!-- term -->
										<?php $termError = (isset($errors) and $errors->has('term')) ? ' is-invalid' : ''; ?>
										<div class="form-group row required"
											 style="margin-top: -10px;">
											<label class="col-md-4 col-form-label"></label>
											<div class="col-md-6">
												<div class="form-check">
													<input name="term" id="term"
														   class="form-check-input{{ $termError }}"
														   value="1"
														   type="checkbox" {{ (old('term')=='1') ? 'checked="checked"' : '' }}
													>
													
													<label class="form-check-label" for="term">
														{!! t('I have read and agree to the <a :attributes>Terms & Conditions</a>', ['attributes' => getUrlPageByType('terms')]) !!}
													</label>
												</div>
												<div style="clear:both"></div>
											</div>
										</div>

										<!-- Button  -->
										<div class="form-group row">
											<label class="col-md-4 col-form-label"></label>
											<div class="col-md-8">
												<button id="signupBtn" class="btn btn-success btn-lg"> {{ t('Register') }} </button>
											</div>
										</div>

										<div style="margin-bottom: 30px;"></div>

									</fieldset>
								</form>
							</div>
						</div>
					</div>
				</div>

				{{-- <div class="col-md-4 reg-sidebar">
					<div class="reg-sidebar-inner text-center">
						<div class="promo-text-box"><i class=" icon-picture fa fa-4x icon-color-1"></i>
							<h3><strong>{{ t('Post a Job') }}</strong></h3>
							<p>
								{{ t('Do you have a post to be filled within your company? Find the right candidate in a few clicks at :app_name',
								['app_name' => config('app.name')]) }}
							</p>
						</div>
						<div class="promo-text-box"><i class="icon-pencil-circled fa fa-4x icon-color-2"></i>
							<h3><strong>{{ t('Create and Manage Jobs') }}</strong></h3>
							<p>{{ t('Become a best company. Create and Manage your jobs. Repost your old jobs, etc.') }}</p>
						</div>
						<div class="promo-text-box"><i class="icon-heart-2 fa fa-4x icon-color-3"></i>
							<h3><strong>{{ t('Create your Favorite jobs list.') }}</strong></h3>
							<p>{{ t('Create your Favorite jobs list, and save your searches. Don\'t forget any opportunity!') }}</p>
						</div>
					</div>
				</div> --}}
			</div>
		</div>
	</div>
@endsection

@section('after_styles')
	<link href="{{ url('assets/plugins/bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet">
	@if (config('lang.direction') == 'rtl')
		<link href="{{ url('assets/plugins/bootstrap-fileinput/css/fileinput-rtl.min.css') }}" rel="stylesheet">
	@endif
	<style>
		.krajee-default.file-preview-frame:hover:not(.file-preview-error) {
			box-shadow: 0 0 5px 0 #666666;
		}
	</style>
@endsection

@section('after_scripts')
	<script src="{{ url('assets/plugins/bootstrap-fileinput/js/plugins/sortable.min.js') }}" type="text/javascript"></script>
	<script src="{{ url('assets/plugins/bootstrap-fileinput/js/fileinput.min.js') }}" type="text/javascript"></script>
	<script src="{{ url('assets/plugins/bootstrap-fileinput/themes/fa/theme.js') }}" type="text/javascript"></script>
	@if (file_exists(public_path() . '/assets/plugins/bootstrap-fileinput/js/locales/'.ietfLangTag(config('app.locale')).'.js'))
		<script src="{{ url('assets/plugins/bootstrap-fileinput/js/locales/'.ietfLangTag(config('app.locale')).'.js') }}" type="text/javascript"></script>
	@endif
	
	<script>
		var userTypeId = '<?php echo old('user_type_id', request()->get('type')); ?>';

		$(document).ready(function ()
		{
			if($("#residence_country").val() == 'iq')
			{
				$("#city").removeAttr("disabled");
			}
			/* Set user type */
			//setUserType(userTypeId);
			// temporary set to 2 to show resume field ( I have hide the user type field and the companies in general )
			setUserType(2);
			$('.user-type').click(function () {
				userTypeId = $(this).val();
				setUserType(userTypeId);
			});

			/* Submit Form */
			$("#signupBtn").click(function () {
				$("#signupForm").submit();
				return false;
			});
		});

		function showDiv(divId, element)
		{
			var attr = $("#"+divId).attr('disabled');

			// For some browsers, `attr` is undefined; for others,
			// `attr` is false.  Check for both.
			if (typeof attr !== typeof undefined && attr !== false && element.value == 'iq') {
				$("#"+divId).removeAttr("disabled");
			} else if ((typeof attr === typeof undefined || attr === false) && element.value != 'iq') {
				$("#"+divId).attr("disabled", "disabled");
				$("#"+divId).val("");
			}
		}
	</script>
@endsection
