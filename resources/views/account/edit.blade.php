
@extends('layouts.master')

@section('content')
	@include('common.spacer')
	<div class="main-container">
		<div class="container">
			<div class="row">
				<div class="col-md-3 page-sidebar">
					@include('account.inc.sidebar')
				</div>
				<!--/.page-sidebar-->

				<div class="col-md-9 page-content">
					@if (\Session::has('success'))
						<div class="alert alert-success ml-3">
							<ul>
								<li>{!! \Session::get('success') !!}</li>
							</ul>
						</div>
					@endif
					@include('flash::message')

					@if (isset($errors) and $errors->any())
						<div class="alert alert-danger">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<h5><strong>{{ t('Oops ! An error has occurred. Please correct the red fields in the form') }}</strong></h5>
							<ul class="list list-check">
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					<div class="inner-box default-inner-box">
						<div class="row">
							<div class="col-md-5 col-xs-4 col-xxs-12">
								<h3 class="no-padding text-center-480 useradmin">
									<a href="">
										@if (!empty($userPhoto))
											<img class="userImg" src="{{ $userPhoto }}" alt="user">&nbsp;
										@else
											<img class="userImg" src="{{ url('images/user.jpg') }}" alt="user">
										@endif
										{{ $user->name }}
									</a>
								</h3>
							</div>
							<div class="col-md-7 col-xs-8 col-xxs-12">
								<div class="header-data text-center-xs">
									<!-- Conversations Stats -->
									<div class="hdata">
										<div class="mcol-left">
											<i class="fas fa-envelope ln-shadow"></i></div>
										<div class="mcol-right">
											<!-- Number of messages -->
											<p>
												<a href="{{ lurl('account/conversations') }}">
													{{ isset($countConversations) ? \App\Helpers\Number::short($countConversations) : 0 }}
													<em>{{ trans_choice('global.count_mails', getPlural($countConversations)) }}</em>
												</a>
											</p>
										</div>
										<div class="clearfix"></div>
									</div>
									
									@if (isset($user) and in_array($user->user_type_id, [1]))
									<!-- Traffic Stats -->
									<div class="hdata">
										<div class="mcol-left">
											<i class="fa fa-eye ln-shadow"></i>
										</div>
										<div class="mcol-right">
											<!-- Number of visitors -->
											<p>
												<a href="{{ lurl('account/my-posts') }}">
													<?php $totalPostsVisits = (isset($countPostsVisits) and $countPostsVisits->total_visits) ? $countPostsVisits->total_visits : 0 ?>
                                                    {{ \App\Helpers\Number::short($totalPostsVisits) }}
												    <em>{{ trans_choice('global.count_visits', getPlural($totalPostsVisits)) }}</em>
                                                </a>
											</p>
										</div>
										<div class="clearfix"></div>
									</div>
									
									<!-- Ads Stats -->
									<div class="hdata">
										<div class="mcol-left">
											<i class="icon-th-thumb ln-shadow"></i>
										</div>
										<div class="mcol-right">
											<!-- Number of ads -->
											<p>
												<a href="{{ lurl('account/my-posts') }}">
                                                    {{ \App\Helpers\Number::short($countPosts) }}
												    <em>{{ trans_choice('global.count_posts', getPlural($countPosts)) }}</em>
                                                </a>
											</p>
										</div>
										<div class="clearfix"></div>
									</div>
									@endif
                                    
                                    @if (isset($user) and in_array($user->user_type_id, [2]))
									<!-- Favorites Stats -->
									<div class="hdata">
										<div class="mcol-left">
											<i class="fa fa-user ln-shadow"></i>
										</div>
										<div class="mcol-right">
											<!-- Number of favorites -->
											<p>
												<a href="{{ lurl('account/favourite') }}">
                                                    {{ \App\Helpers\Number::short($countFavoritePosts) }}
												    <em>{{ trans_choice('global.count_favorites', getPlural($countFavoritePosts)) }} </em>
                                                </a>
											</p>
										</div>
										<div class="clearfix"></div>
									</div>
                                    @endif
								</div>
							</div>
						</div>
					</div>

					@if (isset($user) and in_array($user->user_type_id, [1]))

						<div class="row">
							<div class="col-sm-12">
								<div class="card-group mb-3">
									<div class="card stages_filter" style="width: 18rem;">
										<div class="card-body">
											<p class="text-center mb-0 text-primary mt-2">{{$countMyPosts}}</p>
											<p class="font-weight-bold text-center mt-1 mb-0">{{t('Active jobs count')}}</p>
										</div>
									</div>
									<div class="card stages_filter" style="width: 18rem;">
										<div class="card-body">
											<p class="text-center mb-0 text-primary mt-2">{{$peopleCount}}</p>
											<p class="font-weight-bold text-center mt-1 mb-0">{{t('my people count')}}</p>
										</div>
									</div>
								</div>
							</div>
						</div>

						<?php $applicantsStages = include(base_path() . '/resources/lang/en/applicantsStages.php');
							$applicantsStagesKeys = array_keys($applicantsStages);
						?>
						<div class="row">
							<div class="col-sm-12">
								<div class="card-group mb-3">
									@foreach ($applicantsStagesKeys as $item)
										<div class="card stages_filter" style="width: 18rem;">
											<div class="card-body">
												<p class="text-center mb-0 text-primary mt-2">{{(empty($item) || !isset($stagesCounts[$item]))?0:$stagesCounts[$item]}}</p>
												<p class="stage_{{$item}} font-weight-bold text-center mt-1 mb-0">{{t($item,[],'applicantsStages')}}</p>
											</div>
										</div>
									@endforeach
								</div>
							</div>
						</div>
					@endif
					<div class="inner-box default-inner-box">
						<div class="welcome-msg">
							<h3 class="page-sub-header2 clearfix no-padding">{{ t('Hello') }} {{ $user->name }} ! </h3>
							<span class="page-sub-header-sub small">
                                {{ t('You last logged in at') }}: {{ $user->last_login_at->formatLocalized(config('settings.app.default_datetime_format')) }}
                            </span>

<!-- 							@if ($user->user_type_id == 1 && !empty($company)) -->
<!-- 								<form method="POST" class="mt-4" action="{{ lurl('invite-company-members/send/email') }}"> -->
<!-- 									{!! csrf_field() !!} -->
<!-- 									<div class="form-group"> -->
<!-- 										<label class="form-label">{{t('Invite your company members')}}</label> -->
<!-- 										<input type="text" name="emails" class="form-control" placeholder="Enter emails saparated by comma ex: example@company.com,example2@company.com" /> -->
<!-- 										<input type="hidden" name="companyId" value="{{$company->id}}" /> -->
<!-- 									</div> -->
<!-- 									<input type="submit" class="btn btn-primary" value="{{t('Send')}}" name="submit" /> -->
<!-- 								</form> -->
<!-- 							@endif -->
						</div>
						@if ($user->user_type_id != 1)							
							@include('account.profileHead') 
							@include('account.personalInformationSection') 
							@include('account.contactSection') 
							@include('account.preferredJobSection') 
							@include('account.experienceSection') 
							@include('account.educationSection') 
							@include('account.skillsSection') 
							@include('account.languagesSection') 
							@include('account.trainingSection') 
							@include('account.referencesSection') 
							@include('account.videoSection') 
						@endif

						@if ($user->user_type_id == 1 && !empty($company))	
							<div class="inner-box mb-2">
								<div class="row">
									<?php
										$companyInfoExists = false;
										$companyGeneralInfo = false;
										$infoCol = 'col-sm-12';
										if (
											(isset($company->address) and !empty($company->address)) or
											(isset($company->phone) and !empty($company->phone)) or
											(isset($company->mobile) and !empty($company->mobile)) or
											(isset($company->fax) and !empty($company->fax))
										) {
											$companyInfoExists = true;
											//$infoCol = 'col-sm-6';
										}
			
										if (
											(isset($company->company_size) and !empty($company->company_size)) or
											(isset($company->company_type) and !empty($company->company_type)) or
											(isset($company->company_location) and !empty($company->company_location))
										) {
											$companyGeneralInfo = true;
										}
									?>
									<div class="{{ $infoCol }}">
										<div class="seller-info seller-profile">
											<div class="seller-profile-img">
												<a><img src="{{ resize(\App\Models\Company::getLogo($company->logo), 'medium') }}" class="img-fluid img-thumbnail" alt="img"> </a>
											</div>
											<h3 class="no-margin no-padding link-color uppercase">
												@if (auth()->check())
													@if (auth()->user()->id == $company->user_id)
														<a href="{{ lurl('account/companies/' . $company->id . '/edit') }}" class="btn btn-default">
															<i class="fa fa-pencil-square-o"></i> {{ t('Edit') }}
														</a>
													@endif
												@endif
												{{ $company->name }}
											</h3>
											
											<div class="text-muted">
												{!! $company->description !!}
											</div>
											
											<div class="seller-social-list">
												<ul class="share-this-post">
													@if (isset($company->googleplus) and !empty($company->googleplus))
														<li><a class="google-plus" href="{{ $company->googleplus }}" target="_blank"><i class="fab fa-google-plus-g"></i></a></li>
													@endif
													@if (isset($company->linkedin) and !empty($company->linkedin))
														<li><a href="{{ $company->linkedin }}" target="_blank"><i class="fa icon-linkedin-rect"></i></a></li>
													@endif
													@if (isset($company->facebook) and !empty($company->facebook))
														<li><a class="facebook" href="{{ $company->facebook }}" target="_blank"><i class="fab fa-facebook"></i></a></li>
													@endif
													@if (isset($company->twitter) and !empty($company->twitter))
														<li><a href="{{ $company->twitter }}" target="_blank"><i class="fab fa-twitter"></i></a></li>
													@endif
													@if (isset($company->pinterest) and !empty($company->pinterest))
														<li><a class="pinterest" href="{{ $company->pinterest }}" target="_blank"><i class="fab fa-pinterest"></i></a></li>
													@endif
												</ul>
											</div>
										</div>
									</div>
									
									@if ($companyInfoExists)
										<div class="{{ $infoCol }}">
											<div class="seller-contact-info mt5">
												<h3 class="no-margin"> {{ t('Contact Information') }} </h3>
												<dl class="dl-horizontal">
													@if (isset($company->address) and !empty($company->address))
														<dt>{{ t('Address') }}:</dt>
														<dd class="contact-sensitive">{!! $company->address !!}</dd>
													@endif
													
													@if (isset($company->phone) and !empty($company->phone))
														<dt>{{ t('Phone') }}:</dt>
														<dd class="contact-sensitive">{{ $company->phone }}</dd>
													@endif
													
													@if (isset($company->mobile) and !empty($company->mobile))
														<dt>{{ t('Mobile Phone') }}:</dt>
														<dd class="contact-sensitive">{{ $company->mobile }}</dd>
													@endif
													
													@if (isset($company->fax) and !empty($company->fax))
														<dt>{{ t('Fax') }}:</dt>
														<dd class="contact-sensitive">{{ $company->fax }}</dd>
													@endif
													
													@if (isset($company->website) and !empty($company->website))
														<dt>{{ t('Website') }}:</dt>
														<dd class="contact-sensitive">
															<a href="{!! $company->website !!}" target="_blank">
																{!! $company->website !!}
															</a>
														</dd>
													@endif
												</dl>
											</div>
										</div>
									@endif
			
									<!-- Company general info -->
								@if ($companyInfoExists)
									<div class="{{ $infoCol }}">
										<div class="seller-contact-info mt5">
											<h3 class="no-margin"> {{t('Company information')}} </h3>
											<dl class="dl-horizontal">
												@if (isset($company->company_size) and !empty($company->company_size))
													<dt>{{t('Company size')}}:</dt>
													<dd class="contact-sensitive">{{ t($company->company_size, [], 'companySize') }}</dd>
												@endif
												
												@if (isset($company->company_location) and !empty($company->company_location))
													<dt>{{t('Company Location')}}:</dt>
													<dd class="contact-sensitive">{{ t($company->company_location, [], 'residenceCountry') }}</dd>
												@endif
												
												@if (isset($company->company_type) and !empty($company->company_type))
													<dt>{{t('Company type')}}:</dt>
													<dd class="contact-sensitive">{{ t($company->company_type, [], 'companyType') }}</dd>
												@endif
											</dl>
										</div>
									</div>
								@endif
								@if(isset($company->description) and !empty($company->description))
									<div class="{{ $infoCol }}">
										<div class="seller-contact-info mt5">
											<h3>{{t('Company Summary')}}</h3>
											<p>{!! $company->description !!}</p>
										</div>
									</div>
								@endif
								</div>
							</div>

							<div id="accordion" class="panel-group mb-2">
								
								<!-- COMPANY -->
								<div class="card card-default">
									<div class="card-header">
										<h4 class="card-title"><a href="#companyPanel" data-toggle="collapse" data-parent="#accordion"> {{ t('Company Information') }} </a></h4>
									</div>
									<div class="panel-collapse collapse show" id="companyPanel">
										<div class="card-body">
											<form name="company" class="form-horizontal" role="form" method="POST" action="{{ lurl('account/companies/' . $company->id) }}" enctype="multipart/form-data">
												{!! csrf_field() !!}
												<input name="_method" type="hidden" value="PUT">
												<input name="panel" type="hidden" value="companyPanel">
												<input name="company_id" type="hidden" value="{{ $company->id }}">
												
												@include('account.company._form')
												
												<div class="form-group row">
													<div class="offset-md-3 col-md-9"></div>
												</div>
												
												<!-- Button -->
												<div class="form-group row">
													<div class="offset-md-3 col-md-9">
														<button type="submit" class="btn btn-primary">{{ t('Update') }}</button>
													</div>
												</div>
											</form>
										</div>
									</div>
								</div>
							
							</div>
						@endif

						<div id="accordion" class="panel-group">
							<!-- USER -->
							{{-- <div class="card card-default">
								<div class="card-header">
									<h4 class="card-title"><a href="#profileSummary" data-toggle="collapse" data-parent="#accordion" aria-expanded="false" class="collapsed">Profile Summary</a></h4>
								</div>
								<div class="panel-collapse collapse" id="profileSummary" style="">
									<div class="card-body">
										<form>
											<div class="form-group row required">
												<label class="col-md-3 col-form-label{{ $userTypeIdError }}">{{ t('You are a') }} <sup>*</sup></label>
												<div class="col-md-9">
													
												</div>
											</div>
										</form>
									</div>
								</div>
							</div> --}}

							<div class="card card-default">
								<div class="card-header">
									<h4 class="card-title"><a href="#userPanel" data-toggle="collapse" data-parent="#accordion">{{ t('Account Details') }}</a></h4>
								</div>
								<div class="panel-collapse collapse {{ (old('panel')=='' or old('panel')=='userPanel') ? 'show' : '' }}" id="userPanel">
									<div class="card-body">
										<form name="details" class="form-horizontal" role="form" method="POST" action="{{ url()->current() }}" enctype="multipart/form-data">
											{!! csrf_field() !!}
											<input name="_method" type="hidden" value="PUT">
											<input name="panel" type="hidden" value="userPanel">
                                            
                                            @if (empty($user->user_type_id) or $user->user_type_id == 0)
                                                
                                                <!-- user_type_id -->
												<?php $userTypeIdError = (isset($errors) and $errors->has('user_type_id')) ? ' is-invalid' : ''; ?>
                                                <div class="form-group row required">
                                                    <label class="col-md-3 col-form-label{{ $userTypeIdError }}">{{ t('You are a') }} <sup>*</sup></label>
                                                    <div class="col-md-9">
                                                        <select name="user_type_id" id="userTypeId" class="form-control selecter{{ $userTypeIdError }}">
                                                            <option value="0"
																	@if (old('user_type_id')=='' or old('user_type_id')==0)
																		selected="selected"
																	@endif
															>
                                                                {{ t('Select') }}
                                                            </option>
                                                            @foreach ($userTypes as $type)
                                                                <option value="{{ $type->id }}"
																		@if (old('user_type_id', $user->user_type_id)==$type->id)
																			selected="selected"
																		@endif
																>
                                                                    {{ t($type->name) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                    
                                            @else
											@if (auth()->user()->user_type_id == 2)
												<!-- gender_id -->
												<?php $genderIdError = (isset($errors) and $errors->has('gender_id')) ? ' is-invalid' : ''; ?>
												<div class="form-group row required">
													<label class="col-md-3 col-form-label">{{ t('Gender') }} <sup>*</sup></label>
													<div class="col-md-9">
														@if ($genders->count() > 0)
															@foreach ($genders as $gender)
																<div class="form-check form-check-inline pt-2">
																	<input name="gender_id"
																		id="gender_id-{{ $gender->tid }}"
																		value="{{ $gender->tid }}"
																		class="form-check-input{{ $genderIdError }}"
																		type="radio" {{ (old('gender_id', $user->gender_id)==$gender->tid) ? 'checked="checked"' : '' }}
																	>
																	<label class="form-check-label" for="gender_id">
																		{{ $gender->name }}
																	</label>
																</div>
															@endforeach
														@endif
													</div>
												</div>
											@endif
    
                                                <!-- name -->
												<?php $nameError = (isset($errors) and $errors->has('name')) ? ' is-invalid' : ''; ?>
                                                <div class="form-group row required">
                                                    <label class="col-md-3 col-form-label">{{ t('Name') }} <sup>*</sup></label>
                                                    <div class="col-md-9">
                                                        <input name="name" type="text" class="form-control{{ $nameError }}" placeholder="" value="{{ old('name', $user->name) }}">
                                                    </div>
                                                </div>
	
												<!-- username -->
												<?php //$usernameError = (isset($errors) and $errors->has('username')) ? ' is-invalid' : ''; ?>
												{{-- <div class="form-group row required">
													<label class="col-md-3 col-form-label" for="email">{{ t('Username') }} <sup>*</sup></label>
													<div class="input-group col-md-9">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="icon-user"></i></span>
														</div>
														
														<input id="username"
															   name="username"
															   type="text"
															   class="form-control{{ $usernameError }}"
															   placeholder="{{ t('Username') }}"
															   value="{{ old('username', $user->username) }}"
														>
													</div>
												</div> --}}
    
                                                <!-- email -->
												<?php $emailError = (isset($errors) and $errors->has('email')) ? ' is-invalid' : ''; ?>
                                                <div class="form-group row required">
                                                    <label class="col-md-3 col-form-label">{{ t('Email')}} <sup>*</sup></label>
													<div class="input-group col-md-9">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="icon-mail"></i></span>
														</div>
		
														<input id="email"
															   name="email"
															   type="email"
															   class="form-control{{ $emailError }}"
															   placeholder="{{ t('Email') }}"
															   value="{{ old('email', $user->email) }}"
														>
													</div>
                                                </div>
												<?php $preferredLangError = (isset($errors) and $errors->has('prefLang')) ? ' is-invalid' : ''; ?>
												<div class="form-group row required" id="prefLangField">
													<label class="col-md-3 col-form-label" for="prefLang">{{ t('Preferred Languange') }}</label>
													<div class="col-md-9">
														<div class="form-group">
															<select class="form-control {{$preferredLangError}}" name="prefLang" id="prefLang" placeholder="{{ t('selectLanguage') }}"
															value="{{ old('prefLang') }}">
																<option selected value="">{{ t('simditor.selectLanguage') }}</option>
																	<option value="en" @if($user->pref_lang == "en"){{"selected"}}@endif>English</option>
																	<option value="ar" @if($user->pref_lang == "ar"){{"selected"}}@endif>العربية</option>
															</select>
														</div>
													</div>
												</div>
											@if(auth()->user()->user_type_id == 2)
												<?php $roles = include(base_path() . '/resources/lang/en/roles.php'); 
													asort($roles);
													$rolesKeys = array_keys($roles);
												?>
												<?php $regJobRoleError = (isset($errors) and $errors->has('regJobRole')) ? ' is-invalid' : ''; ?>
												<div class="form-group row required">
													<label class="col-md-3 col-form-label" for="email">{{ t('Job Role') }}</label>
													<div class="col-md-9">
														<div class="form-group">
															<select class="form-control {{$regJobRoleError}}" name="regJobRole" id="regJobRole" placeholder="{{ t('Which field do you want to work in?') }}"
															value="{{ old('job_role',$user->job_role) }}">
																<option value="" selected="">{{ t('Which field do you want to work in?') }}</option>
																@foreach ($rolesKeys as $item)
																	<option value="{{$item}}" @if($user->job_role == $item){{"selected"}}@endif>{{t($item,[],'roles')}}</option>
																@endforeach
															</select>
														</div>
													</div>
												</div>
												
												
												<?php $secondaryRegJobRoleError = (isset($errors) and $errors->has('secondaryRegJobRole')) ? ' is-invalid' : ''; ?>
												<div class="form-group row required">
													<label class="col-md-3 col-form-label" for="email">{{ t('Secondary Job Role') }}</label>
													<div class="col-md-9">
														<div class="form-group">
															<select class="form-control {{$secondaryRegJobRoleError}}" name="secondaryRegJobRole" id="secondaryRegJobRole" placeholder="{{ t('Which field do you want to work in?') }}"
															value="{{ old('sec_job_role',$user->sec_job_role) }}">
																<option value="" selected="">{{ t('Which field do you want to work in?') }}</option>
																@foreach ($rolesKeys as $item)
																	<option value="{{$item}}" @if($user->sec_job_role == $item){{"selected"}}@endif>{{t($item,[],'roles')}}</option>
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
												<div class="form-group row required">
													<label class="col-md-3 col-form-label" for="email">{{ t('Industry') }}</label>
													<div class="col-md-9">
														<div class="form-group">
															<select class="form-control {{$industryError}}" name="industry" id="industry" placeholder="{{ t('Which field do you want to work in?') }}"
															value="{{ old('industry',$user->industry) }}">
																<option value="" selected="">{{ t('What is your industry?') }}</option>
																@foreach ($industryKeys as $item)
																	<option value="{{$item}}" @if($user->industry == $item){{"selected"}}@endif>{{t($item,[],'industry')}}</option>
																@endforeach
															</select>
														</div>
													</div>
												</div>
											{{-- @endif --}}

										<?php $residenceCountry = include(base_path() . '/resources/lang/en/residenceCountry.php');
											asort($residenceCountry); 
											$residenceCountryKeys = array_keys($residenceCountry);
										?>
										<?php $residenceCountryError = (isset($errors) and $errors->has('residence_country')) ? ' is-invalid' : ''; ?>
										<div class="form-group row required" id="residenceCountry">
											<label class="col-md-3 col-form-label">{{ t('residence country') }}</label>
											<div class="col-md-9">
												<div class="form-group">
													<select class="form-control {{$residenceCountryError}}" name="residence_country" id="residence_country" placeholder="{{ t('What is your residence country?') }}"
													value="{{ old('residence_country',$user->residence_country) }}">
														<option value="" selected="">{{ t('What is your residence country?') }}</option>
														@foreach ($residenceCountryKeys as $item)
															<option value="{{$item}}" {{ (old("residence_country",$user->residence_country) == $item ? "selected":"") }} >{{t($item,[],'residenceCountry')}}</option>
														@endforeach
													</select>
												</div>
											</div>
										</div>

											<?php $experience = include(base_path() . '/resources/lang/en/experience.php'); 
												$experienceKeys = array_keys($experience);
											?>
											<?php $userExperienceError = (isset($errors) and $errors->has('userExperience')) ? ' is-invalid' : ''; ?>
												<div class="form-group row required">
													<label class="col-md-3 col-form-label" for="email">{{ t('Experience Years') }}</label>
													<div class="col-md-9">
														<div class="form-group">
															<select class="form-control {{$userExperienceError}}" name="userExperience" id="userExperience"
																value="{{ old('user_experience') }}">
																<option selected="" value="">{{ t('Experience Years') }}</option>
																@foreach ($experienceKeys as $item)
																	<option value="{{$item}}" @if($user->user_experience == $item){{"selected"}}@endif>{{t($item,[],'experience')}}</option>
																@endforeach
															</select>
														</div>
													</div>
												</div>
											@endif

											<?php $curJobTitleError = (isset($errors) and $errors->has('curJobTitle')) ? ' is-invalid' : ''; ?>
											<div class="form-group row required">
												<label class="col-md-3 col-form-label">{{ t('Current Job Title') }} <sup>*</sup></label>
												<div class="col-md-9">
													<div class="form-group">
														<input id="curJobTitle"
															name="curJobTitle"
															type="text"
															class="form-control{{ $curJobTitleError }}"
															placeholder="{{ t('Current Job Title')}}"
															value="{{ old('curJobTitle',$user->current_job_title) }}"
														>
													</div>
												</div>
											</div>
                                                <!-- country_code -->
                                                
                                                <?php $countryCodeError = (isset($errors) and $errors->has('country_code')) ? ' is-invalid' : ''; ?>
                                                <div class="form-group row required">
                                                    <label class="col-md-3 col-form-label{{ $countryCodeError }}" for="country_code">{{t('Your phone country')}} <sup>*</sup></label>
                                                    <div class="col-md-9">
														<select name="country_code" class="form-control sselecter{{ $countryCodeError }}">
															<option value="" disabled {{ (!old('country_code') or old('country_code')==0) ? 'selected="selected"' : '' }}>
																{{ t('Select a country') }}
															</option>
															@foreach ($countries as $code => $item)
																<option value="{{ $code }}" {{ ((old('country_code', $user->country_code) ? $user->country_code : "")==$code) ? 'selected="selected"' : '' }}>
																	{{ $item->get('name') }}
																</option>
															@endforeach
														</select>
                                                    </div>
                                                </div>

                                                {{-- <input name="country_code" type="hidden" value="{{ $user->country_code }}"> --}}
												
                                                <!-- phone -->
												<?php $phoneError = (isset($errors) and $errors->has('phone')) ? ' is-invalid' : ''; ?>
                                                <div class="form-group row required">
                                                    <label for="phone" class="col-md-3 col-form-label">{{ t('Phone') }} <sup>*</sup></label>
													<div class="input-group col-md-9">
														{{-- <div class="input-group-prepend">
															<span id="phoneCountry" class="input-group-text">{!! getPhoneIcon(old('country_code', $user->country_code)) !!}</span>
														</div> --}}
		
														<input id="phone" name="phone" type="text" class="form-control{{ $phoneError }}"
															   placeholder="{{ (!isEnabledField('email')) ? t('Mobile Phone Number') : t('Phone Number') }} Ex:786666666"
															   value="{{ phoneFormat(old('phone', $user->phone), old('country_code', $user->country_code)) }}">
		
														<div class="input-group-append">
														<span class="input-group-text">
															<input name="phone_hidden" id="phoneHidden" type="checkbox"
																   value="1" {{ (old('phone_hidden', $user->phone_hidden)=='1') ? 'checked="checked"' : '' }}>&nbsp;
															<small>{{ t('Hide') }}</small>
														</span>
														</div>
													</div>
                                                </div>
                                                
                                            @endif

											<div class="form-group row">
												<div class="offset-md-3 col-md-9"></div>
											</div>
											
											<!-- Button -->
											<div class="form-group row">
												<div class="offset-md-3 col-md-9">
													<button type="submit" class="btn btn-primary">{{ t('Update') }}</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						
							<!-- SETTINGS -->
							<div class="card card-default">
								<div class="card-header">
									<h4 class="card-title"><a href="#settingsPanel" data-toggle="collapse" data-parent="#accordion">{{ t('Settings') }}</a></h4>
								</div>
								<div class="panel-collapse collapse {{ (old('panel')=='settingsPanel') ? 'in' : '' }}" id="settingsPanel">
									<div class="card-body">
										<form name="settings" class="form-horizontal" role="form" method="POST" action="{{ lurl('account/settings') }}">
											{!! csrf_field() !!}
											<input name="_method" type="hidden" value="PUT">
											<input name="panel" type="hidden" value="settingsPanel">
										
											@if (config('settings.single.activation_facebook_comments') and config('services.facebook.client_id'))
												<!-- disable_comments -->
												<div class="form-group row">
													<label class="col-md-3 col-form-label"></label>
													<div class="col-md-9">
														<div class="form-check form-check-inline pt-2">
															<label>
																<input id="disable_comments"
																	   name="disable_comments"
																	   value="1"
																	   type="checkbox" {{ ($user->disable_comments==1) ? 'checked' : '' }}
																>
																{{ t('Disable comments on my ads') }}
															</label>
														</div>
													</div>
												</div>
											@endif
											
											<!-- password -->
											<?php $passwordError = (isset($errors) and $errors->has('password')) ? ' is-invalid' : ''; ?>
											<div class="form-group row">
												<label class="col-md-3 col-form-label">{{ t('New Password') }}</label>
												<div class="col-md-9">
													<input id="password" name="password" type="password" class="form-control{{ $passwordError }}" placeholder="{{ t('Password') }}">
												</div>
											</div>
											
											<!-- password_confirmation -->
											<?php $passwordError = (isset($errors) and $errors->has('password')) ? ' is-invalid' : ''; ?>
											<div class="form-group row <?php echo (isset($errors) and $errors->has('password')) ? ' is-invalid' : ''; ?>">
												<label class="col-md-3 col-form-label">{{ t('Confirm Password') }}</label>
												<div class="col-md-9">
													<input id="password_confirmation" name="password_confirmation" type="password"
														   class="form-control{{ $passwordError }}" placeholder="{{ t('Confirm Password') }}">
												</div>
											</div>
											
											<!-- Button -->
											<div class="form-group row">
												<div class="offset-md-3 col-md-9">
													<button type="submit" class="btn btn-primary">{{ t('Update') }}</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>

						</div>
						<!--/.row-box End-->

					</div>
				</div>
				<!--/.page-content-->
			</div>
			<!--/.row-->
		</div>
		<!--/.container-->
	</div>
	<!-- /.main-container -->
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
@endsection
