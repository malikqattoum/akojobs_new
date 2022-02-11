
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
							<strong><i class="icon-user-add"></i> {{t('Create your free employer account')}}</strong>
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
											<!-- name -->
										<?php $companyNameError = (isset($errors) and $errors->has('company.name')) ? ' is-invalid' : ''; ?>
										<div class="form-group row required">
											<label class="col-md-4 col-form-label" for="company_name">{{ t('Company Name') }} <sup>*</sup></label>
											<div class="col-md-6">
												<input name="company[name]"
													placeholder="{{ t('Company Name') }}"
													class="form-control input-md{{ $companyNameError }}"
													type="text"
													value="{{ old('company.name','') }}">
											</div>
										</div>
                                        <input type="hidden" value="1" name="user_type_id" />
                                        
                                        <?php $companyLocation = include(base_path() . '/resources/lang/en/residenceCountry.php');
											asort($companyLocation); 
											$companyLocationKeys = array_keys($companyLocation);
										?>
										<?php $companyLocationError = (isset($errors) and $errors->has('company.location')) ? ' is-invalid' : ''; ?>
										<div class="form-group row required" id="companyLocation">
											<label class="col-md-4 col-form-label">{{t('Company Location')}} <sup>*</sup></label>
											<div class="col-md-6">
												<div class="form-group">
													<select class="form-control {{$companyLocationError}}" name="company[location]" id="company_location" placeholder="{{ t('Select') }}"
													value="{{ old('company.location') }}">
														<option value="" selected="">{{ t('Select') }}</option>
														@foreach ($companyLocationKeys as $item)
															<option value="{{$item}}" {{ (old("company.location") == $item ? "selected":"") }} >{{t($item,[],'residenceCountry')}}</option>
														@endforeach
													</select>
												</div>
											</div>
                                        </div>
                                        
                                    <?php $companySize = include(base_path() . '/resources/lang/en/companySize.php');
										ksort($companySize); 
                                        $companySizeKeys = array_keys($companySize);
                                    ?>
                                    <?php $companySizeError = (isset($errors) and $errors->has('company.size')) ? ' is-invalid' : ''; ?>
                                    <div class="form-group row required" id="companySize">
                                        <label class="col-md-4 col-form-label">{{t('Company size')}} <sup>*</sup></label>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <select class="form-control {{$companySizeError}}" name="company[size]" id="company_size" placeholder="{{ t('Select') }}"
                                                value="{{ old('company.size') }}">
                                                    <option value="" selected="">{{ t('Select') }}</option>
                                                    @foreach ($companySizeKeys as $item)
                                                        <option value="{{$item}}" {{ (old("company.size") == $item ? "selected":"") }} >{{t($item,[],'companySize')}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
									</div>
									
									<?php $companyType = include(base_path() . '/resources/lang/en/companyType.php');
										asort($companyType); 
										$companyTypeKeys = array_keys($companyType);
									?>
									<?php $companyTypeError = (isset($errors) and $errors->has('company.type')) ? ' is-invalid' : ''; ?>
									<div class="form-group row required" id="companyType">
										<label class="col-md-4 col-form-label">{{t('Company type')}} <sup>*</sup></label>
										<div class="col-md-6">
											<div class="form-group">
												<select class="form-control {{$companyTypeError}}" name="company[type]" id="company_type" placeholder="{{ t('Select') }}"
												value="{{ old('company.type') }}">
													<option value="" selected="">{{ t('Select') }}</option>
													@foreach ($companyTypeKeys as $item)
														<option value="{{$item}}" {{ (old("company.type") == $item ? "selected":"") }} >{{t($item,[],'companyType')}}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>

									<?php $contactPersonError = (isset($errors) and $errors->has('name')) ? ' is-invalid' : ''; ?>
									<div class="form-group row required">
										<label class="col-md-4 col-form-label">{{t('Contact person name')}} <sup>*</sup></label>
										<div class="col-md-6">
											<input name="name" placeholder="{{t('Contact person name')}}" class="form-control input-md{{ $contactPersonError }}" type="text" value="{{ old('name') }}">
										</div>
									</div>
										<!-- country_code -->
										@if (empty(config('country.code')) || 1)
											<?php $countryCodeError = (isset($errors) and $errors->has('country_code')) ? ' is-invalid' : ''; ?>
											<div class="form-group row required">
												<label class="col-md-4 col-form-label{{ $countryCodeError }}" for="country_code">{{t('Contact person phone country')}} <sup>*</sup></label>
												<div class="col-md-6">
													<select id="countryCode" name="country_code" class="form-control sselecter{{ $countryCodeError }}">
														<option value="" selected="selected" disabled>{{ t('Select') }}</option>
														@foreach ($countries as $code => $item)
															<option value="{{ $code }}" {{ (old('country_code', (!empty(config('ipCountry.code'))) ? config('ipCountry.code') : "")==$code) ? 'selected="selected"' : '' }}>
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
												<label class="col-md-4 col-form-label">{{t('Contact person phone number')}}
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
												<label class="col-md-4 col-form-label" for="email">{{t('Contact person email address')}}
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
										
										<?php $curJobTitleError = (isset($errors) and $errors->has('current_job_title')) ? ' is-invalid' : ''; ?>
											<div class="form-group row required" id="jobTitleField">
												<label class="col-md-4 col-form-label" for="email">{{t('Contact person job title')}} <sup>*</sup></label>
												<div class="col-md-6">
													<div class="form-group">
														<input id="curJobTitle"
															   name="current_job_title"
															   type="text"
															   class="form-control{{ $curJobTitleError }}"
															   placeholder="{{ t('Current Job Title') }}"
															   value="{{ old('current_job_title') }}"
														>
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
									
										<div id="companyBloc">
                                            <div class="content-subheading">
                                                <i class="icon-town-hall fa"></i>
                                                <strong>{{ t('Company Information') }}</strong>
                                            </div>
                                            
                                            {{-- @include('account.company._form', ['originForm' => 'user']) --}}
                                        </div>
										
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
	</script>
@endsection
