
@extends('layouts.master')

@section('content')
	@include('common.spacer')
	<div class="main-container">
		<div class="container">
			@if (isset($postData) && !empty($postData))
				<div class="card mb-2 text-white" style="background-color:#0B2271">
					<div class="card-body">
						<h3>{{$postData->title}}</h3>
						<p>
							@if ($postData->archived != 0)
								<i class="fa fa-dot-circle text-danger"></i> {{t('deactivated')." * ".date('d M Y H:i',strtotime($postData->archived_at))." *"}}
							@else
								<i class="fa fa-dot-circle text-success"></i> {{t('published')." * ".date('d M Y H:i',strtotime($postData->created_at))." *"}}
							@endif
							<span class="float-right">{{t('stages total count')}}: {{$stagesTotalCount}}</span>
						</p>
					</div>
				</div>
			@endif
			<?php $applicantsStages = include(base_path() . '/resources/lang/en/applicantsStages.php');
				$applicantsStagesKeys = array_keys($applicantsStages);
			?>
			<div class="row">
				@if (getSegment(4) == "applicants")
    				<div class="col-sm-12">
    					<div class="card-group mb-3">
    						@foreach ($applicantsStagesKeys as $item)
    							<div class="card stages_filter" style="width: 18rem; cursor: pointer;" onclick="stageFilter({{$item}})">
    								<div class="card-body">
    									<p class="text-center mb-0 text-primary">{{(empty($item) || !isset($stagesCounts[$item]))?0:$stagesCounts[$item]}}</p>
    									<p class="stage_{{$item}} font-weight-bold text-center mt-1 mb-0">{{t($item,[],'applicantsStages')}}</p>
    								</div>
    							</div>
    						@endforeach
    					</div>
    				</div>
    			@endif
<!-- 				@if (Session::has('flash_notification')) -->
<!-- 					<div class="col-xl-12"> -->
<!-- 						<div class="row"> -->
<!-- 							<div class="col-xl-12"> -->
<!-- 								@include('flash::message') -->
<!-- 							</div> -->
<!-- 						</div> -->
<!-- 					</div> -->
<!-- 				@endif -->
				
				<div class="col-md-3 page-sidebar">
					@include('account.inc.sidebar')
				</div>
				<!--/.page-sidebar-->
				
				<div class="col-md-9 page-content">
					<div class="inner-box">
						<h2 class="title-2">
							<i class="icon-mail"></i> @if (getSegment(4) == "applicants")
								{{ t('Applicants') }}
							@else
								{{ t('My people') }}
								<span class="float-right">{{t('people count')}}: {{$totalCount}}</span>
							@endif
						</h2>
						<div id="reloadBtn" class="mb30" style="display: none;">
							<a href="" class="btn btn-primary" class="tooltipHere" title="" data-placement="{{ (config('lang.direction')=='rtl') ? 'left' : 'right' }}"
							   data-toggle="tooltip"
							   data-original-title="{{ t('Reload to see New Messages') }}"><i class="icon-arrows-cw"></i> {{ t('Reload') }}</a>
							<br><br>
						</div>
						
						<div style="clear:both"></div>
						
						<div class="table-responsive">
							<form name="listForm" method="POST" action="{{ lurl('account/'.$pagePath.'/delete') }}">
								{!! csrf_field() !!}
								<div class="table-action">
									<label for="checkAll">
										<input type="checkbox" id="checkAll">
										{{ t('Select') }}: {{ t('All') }} |
										<button type="submit" class="btn btn-sm btn-default delete-action">
											<i class="fa fa-trash"></i> {{ t('Delete') }}
										</button> |
										<a href="<?=strtok($_SERVER["REQUEST_URI"], '?');?>" class="btn btn-sm btn-default">Clear Filters</a>
									</label>
									{{-- <div class="table-search pull-right col-sm-7">
										<div class="form-group">
											<div class="row">
												<label class="col-sm-5 control-label text-right">{{ t('Search') }} <br>
													<a title="clear filter" class="clear-filter" href="#clear">[{{ t('clear') }}]</a>
												</label>
												<div class="col-sm-7 searchpan">
													<input type="text" class="form-control" id="filter">
												</div>
											</div>
										</div>
									</div> --}}
									<div class="form-row" style="width:100%">
										<div class="form-group col-xs-6 col-md-3">
											<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#applicantsEmailModal" data-whatever="@mdo">{{t('Send emails to applicants')}}</button>
										</div>
										<div class="form-group col-xs-6 col-md-3">
											<button type="button" class="btn btn-success" data-toggle="modal" data-target="#addCandidateModal" data-whatever="@mdo">{{t('Add candidate')}}</button>
										</div>
									</div>
								</div>
								
								<table id="addManageTable" class="table table-striped table-bordered add-manage-table table demo" data-filter-space="OR" data-filter="#filter" data-filtering="true">
									<thead>
										<tr>
											<form action="">
												<div class="form-group">
													<label class="form-label">{{t('multiple keywords')}}</label>
													<input type="text" class="form-control" id="search_field" value="<?=(isset($filterData['search_keyword']))?$filterData['search_keyword']:''?>" placeholder="{{t('keywords')}}">
												</div>
											</form>
										</tr>
										<tr>
											<th style="width:2%" data-type="numeric" data-sort-initial="true"></th>
											<th style="width:60%" data-sort-ignore="true">
												@if (getSegment(4) == "applicants")
													{{t('Applicants')}}
												@else
													{{ t('My people') }}
												@endif
											</th>
											<th style="width:10%">{{ t('Option') }}</th>
											<th style="width:28%">{{t('Screening tools')}}</th>
										</tr>
									</thead>
									<tbody>
									<?php
									if (isset($people) && $people->count() > 0):
										foreach($people as $key => $applicant):
									?>
									<?php
										if(empty($applicant->message_id))
											$applicant->message_id = $applicant->id;
									?>
									<tr>
										<td class="add-img-selector">
											<div class="checkbox">
												<label><input type="checkbox" name="entries[]" value="{{ $applicant->message_id }}"></label>
											</div>
										</td>
										<td>
											<div style="word-break:break-all;">
												<div style="font-size:15px">
													@if (!empty($applicant->photo))
														<img class="people-photo" src="{{ (substr($applicant->photo, 0, 1) != '/')?'/'.$applicant->photo:$applicant->photo }}" alt="user">&nbsp;
													@elseif (!empty(Session::get('image')))
														<img class="people-photo" src="{{ Session::get('image') }}" alt="user">
													@else
														<img class="people-photo" src="{{ url('images/user.jpg') }}" alt="user">
													@endif
													<a href="{{ lurl('profile/employer-view/'.$applicant->from_user_id) }}" class="people-name"><strong>{{ \Illuminate\Support\Str::limit($applicant->from_name, 50) }}</strong></a>
												</form>
													<form id="addCandidateRatingForm{{ $applicant->from_user_id }}">
														<div class="rating-div">
															@if (isset($userRatings[$applicant->from_user_id]) && !empty($userRatings[$applicant->from_user_id]))
																@for ($i = 1; $i <= 5; $i++)
																	@if ($i <= $userRatings[$applicant->from_user_id])
																		<input type="hidden" id="rating_{{ $applicant->from_user_id }}_{{$i}}_hidden" value="{{$i}}">
																		<img src="{{ url('images/site/star2.png')}}" onmouseover="changeRating(this.id,{{ $applicant->from_user_id }});" id="rating_{{ $applicant->from_user_id }}_{{$i}}" class="rating-star">
																	@else
																		<input type="hidden" id="rating_{{ $applicant->from_user_id }}_{{$i}}_hidden" value="{{$i}}">
																		<img src="{{ url('images/site/star1.png')}}" onmouseover="changeRating(this.id,{{ $applicant->from_user_id }});" id="rating_{{ $applicant->from_user_id }}_{{$i}}" class="rating-star">
																	@endif
																@endfor
															@else
																<input type="hidden" id="rating_{{ $applicant->from_user_id }}_1_hidden" value="1">
																<img src="{{ url('images/site/star1.png')}}" onmouseover="changeRating(this.id,{{ $applicant->from_user_id }});" id="rating_{{ $applicant->from_user_id }}_1" class="rating-star">
																<input type="hidden" id="rating_{{ $applicant->from_user_id }}_2_hidden" value="2">
																<img src="{{ url('images/site/star1.png')}}" onmouseover="changeRating(this.id,{{ $applicant->from_user_id }});" id="rating_{{ $applicant->from_user_id }}_2" class="rating-star">
																<input type="hidden" id="rating_{{ $applicant->from_user_id }}_3_hidden" value="3">
																<img src="{{ url('images/site/star1.png')}}" onmouseover="changeRating(this.id,{{ $applicant->from_user_id }});" id="rating_{{ $applicant->from_user_id }}_3" class="rating-star">
																<input type="hidden" id="rating_{{ $applicant->from_user_id }}_4_hidden" value="4">
																<img src="{{ url('images/site/star1.png')}}" onmouseover="changeRating(this.id,{{ $applicant->from_user_id }});" id="rating_{{ $applicant->from_user_id }}_4" class="rating-star">
																<input type="hidden" id="rating_{{ $applicant->from_user_id }}_5_hidden" value="5">
																<img src="{{ url('images/site/star1.png')}}" onmouseover="changeRating(this.id,{{ $applicant->from_user_id }});" id="rating_{{ $applicant->from_user_id }}_5" class="rating-star">
															@endif
															<input type="submit" class="btn btn-warning btn-sm rating-button" value="Save rating" name="submit_rating">
														</div>
														<input type="hidden" name="rating_user" value="{{ $applicant->from_user_id }}">
														<input type="hidden" name="{{ $applicant->from_user_id }}rating" id="{{ $applicant->from_user_id }}rating" value="0">
													</form>
												</div>
												<div class="people-data">
													<strong>{{ t('Received at') }}:</strong>
													{{ date('d M Y H:i',strtotime($applicant->created_at)) }}
													@if (\App\Models\Message::conversationHasNewMessages($applicant))
														<i class="icon-flag text-primary"></i> <span class="text-warning">{{ t('New') }}</span>
													@endif
													<br>
													@if (!empty($applicant->subject))
														<strong>{{ t('Subject') }}:</strong>&nbsp;{{ $applicant->subject }}
													@endif
													@if(!empty($applicant->job_role))
														<br><strong>{{ t('Job Role') }}:</strong>&nbsp;{{ t($applicant->job_role, [], 'roles') }}<br>
													@endif
													<p>
														<a class="text-primary" data-toggle="collapse" data-target="#collapseExample{{$applicant->message_id}}" aria-expanded="false" aria-controls="collapseExample">
    														{{t('view all candidate data')}}
    														<i class="fa fa-angle-down"></i>
														</a>
													</p>
													<div class="collapse" id="collapseExample{{$applicant->message_id}}">
														<div class="card card-body">
															@if(!empty($applicant->email))
																<strong>{{ t('Email Address') }}:&nbsp;{{ $applicant->email }}<br>
															@endif
															@if(!empty($applicant->nationality))
																<strong>{{ t('Nationality') }}:</strong>&nbsp;{{ t($user->nationality, [], 'residenceCountry') }}<br>
															@endif
															@if(!empty($applicant->residence_country))
																<strong>{{ t('Resident Country') }}:</strong>&nbsp;{{ t($user->residence_country, [], 'residenceCountry') }}<br>
															@endif
															@if(!empty($applicant->gender))
																<strong>{{ t('Gender') }}:</strong>&nbsp;{{ $applicant->gender }}<br>
															@endif
															@if(!empty($applicant->phone))
																<strong>{{ t('Mobile Number') }}:</strong>&nbsp;{{ $applicant->phone }}<br>
															@endif
															@if(!empty($applicant->industry))
																<strong>{{ t('Industry') }}:</strong>&nbsp;{{ t($applicant->industry, [], 'industry') }}<br>
															@endif
															@if(!empty($applicant->user_experience))
																<strong>{{ t('Experience Years') }}:</strong>&nbsp;{{ t($applicant->user_experience, [], 'experience') }}<br>
															@endif
														</div>
													</div>
													<p class="mt-2 mb-1">
														{!! (!empty($applicant->filename) and \Storage::exists($applicant->filename)) ? ' <i class="icon-attach-2"></i> ' : '' !!}&nbsp;|&nbsp;
														<a href="{{ lurl('account/conversations/' . $applicant->message_id . '/messages') }}">
															{{ t('Click here to read the messages') }}
														</a>
													</p>
												</div>
											</div>
										</td>
										<td class="action-td">
											<div>
												<p>
													<a class="btn btn-default btn-sm" href="{{ lurl('account/conversations/' . $applicant->message_id . '/messages') }}">
														<i class="icon-eye"></i> {{ t('view message') }}
													</a>
												</p>
												<p>
													<a class="btn btn-danger btn-sm delete-action" href="{{ lurl('account/conversations/' . $applicant->message_id . '/delete') }}">
														<i class="fa fa-trash"></i> {{ t('Delete') }}
													</a>
												</p>
												<p>
													<a class="btn btn-primary btn-sm" href="{{ lurl('profile/employer-view/'.$applicant->from_user_id) }}">
														<i class="fa fa-user"></i> {{t('View user profile')}}
													</a>
												</p>
												<p>
													@if (!empty($applicant->cvText))
        												<button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#cvTextModal{{$applicant->message_id}}">
        													{{t('View original CV')}}
        												</button>
        
        												<!-- Modal -->
        												<div class="modal fade" id="cvTextModal{{$applicant->message_id}}" tabindex="-1" role="dialog" aria-labelledby="cvTextLabel{{$applicant->message_id}}" aria-hidden="true">
        													<div class="modal-dialog" role="document">
        													<div class="modal-content">
        														<div class="modal-header">
        														<h5 class="modal-title" id="cvTextLabel{{$applicant->message_id}}">Original CV</h5>
        														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
        															<span aria-hidden="true">&times;</span>
        														</button>
        														</div>
        														<div class="modal-body">
        															{!! nl2br($applicant->cvText) !!}
        														</div>
        														<div class="modal-footer">
        														<button type="button" class="btn btn-secondary" data-dismiss="modal">{{t('Close')}}</button>														</div>
        													</div>
        													</div>
        												</div>
        											@endif
												</p>
												<p>
													@if (!empty($applicant->filename) and \Storage::exists($applicant->filename))
        												<a class="btn btn-info btn-sm" target="_blank" href="{{ \Storage::url($applicant->filename) }}">{{ t('Download') }}</a>
        											@endif
												</p>
											</div>
										</td>
										<td>
											<!-- add note modal button -->
											@if(!empty($applicant->applicant_note))
												<!-- Button trigger modal -->
												<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#viewNoteModal{{$applicant->message_id}}">
													{{t('View applicant note')}}
												</button>
												
												<!-- Modal -->
												<div class="modal fade" id="viewNoteModal{{$applicant->message_id}}" tabindex="-1" role="dialog" aria-labelledby="viewNoteLabel{{$applicant->message_id}}" aria-hidden="true">
													<div class="modal-dialog" role="document">
														<div class="modal-content">
															<div class="modal-header">
															<h5 class="modal-title" id="viewNoteLabel{{$applicant->message_id}}">applicant note</h5>
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
															</div>
															<div class="modal-body">
																{{ $applicant->applicant_note }}
															</div>
														</div>
													</div>
												</div>
												<button type="button" class="btn btn-primary my-2 btn-sm" data-toggle="modal" data-target="#addNoteModal{{$applicant->message_id}}">
													{{t('Edit Note')}}
												</button>
											@else
												<button type="button" class="btn btn-primary mb-2 btn-sm" data-toggle="modal" data-target="#addNoteModal{{$applicant->message_id}}">
													{{t('Add Note')}}
												</button>
											@endif
											<br>
											@if (getSegment(4) == "applicants")
    											<div class="form-group col-xs-12" id="applicantsStages{{ $applicant->message_id }}">
    												<select class="form-control" onchange="applicantStage({{ $applicant->message_id }})" name="applicant_stage" id="applicant_stage_{{ $applicant->message_id }}" placeholder="{{t('Applicant stages')}}">
    													<option value="" selected="" disabled>{{t('Applicant stages')}}</option>
    													@foreach ($applicantsStagesKeys as $item)
    														<option value="{{$item}}" {{ (!empty($applicant->applicant_stage))?(($applicant->applicant_stage == $item)? "selected":""):'' }} >{{t($item,[],'applicantsStages')}}</option>
    													@endforeach
    												</select>
    											</div>
											@endif
											<div class="form-group col-xs-12" id="assignToJob{{ $applicant->message_id }}">
												<select class="form-control" onchange="assignToJob({{ $applicant->message_id }})" name="assign_to_job" id="assign_to_job_{{ $applicant->message_id }}" placeholder="{{t('Assign to job')}}">
													<option value="" selected="">{{ t('Assign to job') }}</option>
													<?php $assignAddedJobs = []; ?>
													@foreach ($posts as $job)
														@if (!in_array($job->id,$assignAddedJobs))
															<option value="{{$job->id}}" {{ (!empty($applicant->post_id))?(($applicant->post_id == $job->id)? "selected":""):'' }}>{{$job->title}}</option>
														@endif
														<?php 
															$assignAddedJobs[] = $job->id;
														?>
													@endforeach
												</select>
											</div>
										</td>
									</tr>
									<?php endforeach; ?>
									<?php endif; ?>
									</tbody>
								</table>
							</form>

							<div class="modal fade" id="applicantsEmailModal" tabindex="-1" role="dialog" aria-labelledby="applicantsEmailModalLabel" aria-hidden="true">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="applicantsEmailModalLabel">{{t('New email message')}}</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<form id="sendApplicantsEmailForm">
											<div class="form-group">
												<label for="subject" class="col-form-label">{{t('Subject')}}:</label>
												<input type="text" name="email_subject" id="applicantsEmailSubject" class="form-control">
											</div>
											<div class="form-group">
												<label for="message-text" class="col-form-label">{{t('Message')}}:</label>
												<textarea class="form-control" name="email_message" id="pageContent" rows="15"></textarea>
											</div>
										</form>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">{{t('Close')}}</button>
										<button type="button" id="bulkSendApplicantsEmailBtn" class="btn btn-primary">{{t('Send message')}}</button>
									</div>
									</div>
								</div>
							</div>

							<div class="modal fade" id="addCandidateModal" tabindex="-1" role="dialog" aria-labelledby="addCandidateModalLabel" aria-hidden="true">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="addCandidateModalLabel">{{t('Add candidate')}}</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<h6>{{t('Upload the candidate CV')}}</h6>
										<form id="upCandidateResumeForm" enctype="multipart/form-data">
											<?php $resumeFilenameError = (isset($errors) and $errors->has('resume')) ? ' is-invalid' : ''; ?>
											<div class="form-group">
												<label class="form-label{{ $resumeFilenameError }}" for="resume"> {{ t('Candidate resume') }} </label>
												<div class="mb10">
													<input id="resumeFilename" name="resume" type="file" class="file{{ $resumeFilenameError }}">
												</div>
												<small id="" class="form-text text-muted">{{ t('File types: :file_types', ['file_types' => showValidFileTypes('file')]) }}</small>
											</div>
											<input type="submit" id="upCandidateResume" value="Upload" class="btn btn-primary"/>
										</form>
										<hr color="#0B2271">
										<form id="addCandidateForm">
											<div class="form-group required">
												<?php $nameError = (isset($errors) and $errors->has('name')) ? ' is-invalid' : ''; ?>
												<label class="form-label">{{ t('Name') }} <sup>*</sup></label>
												<input name="name" id="name" placeholder="{{ t('Name') }}" class="form-control {{ $nameError }}" type="text" value="{{ old('name') }}">
											</div>
											@if (isEnabledField('phone'))
												<!-- phone -->
												<?php $phoneError = (isset($errors) and $errors->has('phone')) ? ' is-invalid' : ''; ?>
												<div class="form-group">
													<label class="form-label">{{ t('Phone') }}
													</label>
													<div class="input-group">
														<input name="phone"
															id="phone"
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
											@endif
											
											@if (isEnabledField('email'))
												<!-- email -->
												<?php $emailError = (isset($errors) and $errors->has('email')) ? ' is-invalid' : ''; ?>
												<div class="form-group required">
													<label class="form-label" for="email">{{ t('Email') }}
														@if (!isEnabledField('phone'))
															<sup>*</sup>
														@endif
														<sup>*</sup>
													</label>
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
											@endif
                    											
                        					<?php $roles = include(base_path() . '/resources/lang/en/roles.php'); 
                        						asort($roles);
                        						$rolesKeys = array_keys($roles);
                        					?>
                        					<?php $regJobRoleError = (isset($errors) and $errors->has('regJobRole')) ? ' is-invalid' : ''; ?>
                        					<div class="form-group required" id="jobRoleField">
                        						<label class="form-label" for="email">{{ t('Main Job Role') }} <sup>*</sup></label>
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
    					
    										<?php $residenceCountry = include(base_path() . '/resources/lang/en/residenceCountry.php');
												asort($residenceCountry); 
												$residenceCountryKeys = array_keys($residenceCountry);
											?>
											<?php $residenceCountryError = (isset($errors) and $errors->has('residence_country')) ? ' is-invalid' : ''; ?>
											<div class="form-group required" id="residenceCountry">
												<label class="form-label">{{ t('residence country') }} <sup>*</sup></label>
												<div class="form-group">
													<select class="form-control {{$residenceCountryError}}" name="residence_country" id="cand_residence_country" placeholder="{{ t('What is your residence country?') }}"
													value="{{ old('residence_country') }}"  onchange="showDiv('city', this)">
														<option value="" selected="">{{ t('What is your residence country?') }}</option>
														@foreach ($residenceCountryKeys as $item)
															<option value="{{$item}}" {{ (old("residence_country") == $item ? "selected":"") }} >{{t($item,[],'residenceCountry')}}</option>
														@endforeach
													</select>
												</div>
											</div>
    					
                        					<?php $cityIdError = (isset($errors) and $errors->has('city_id')) ? ' is-invalid' : ''; ?>
                        					<div id="cityBox" class="form-group required">
                        						<label class="form-label" for="city_id">
                        							{{ t('City') }} <sup>*</sup>
                        						</label>
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
											

											<div class="form-group required">
												<?php $sourceError = (isset($errors) and $errors->has('source_type')) ? ' is-invalid' : ''; ?>
												<label class="form-label">{{ t('Source type') }} <sup>*</sup></label>
												<select class="form-control {{$sourceError}}" name="source_type" id="source_type" placeholder="{{ t('Select') }}"
												value="{{ old('source_type') }}">
													<option value="" selected="">{{ t('Select') }}</option>
													<option value="2" {{ (old("source_type") == $item ? "selected":"") }} >{{t('referral')}}</option>
												</select>
											</div>
											<?php $candidateJobError = (isset($errors) and $errors->has('candidate_job')) ? ' is-invalid' : ''; ?>
											<div class="form-group required" id="candidateJob">
												<label class="form-label">{{ t('Candidate job') }} <sup>*</sup></label>
												<div class="form-group">
													<select class="form-control {{$candidateJobError}}" name="candidate_job" id="candidate_job" placeholder="{{ t('Select') }}"
													value="{{ old('candidate_job') }}">
														<option value="" selected="">{{ t('Select') }}</option>
														<?php $addedJobs = []; ?>
														@foreach ($posts as $job)
															@if (!in_array($job->id,$addedJobs))
																<option value="{{$job->id}}" {{ (old("candidate_job") == $job->id ? "selected":"") }} >{{$job->title}}</option>
															@endif
															<?php 
																$addedJobs[] = $job->id;
															?>
														@endforeach
													</select>
												</div>
											</div>
											<input type="hidden" name="free_text" id="freeText"/>
											<input type="hidden" name="candidate_resume" id="canResume"/>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">{{t('Close')}}</button>
												<input type="submit" id="addCandidatelBtn" value="{{t('Add candidate')}}" class="btn btn-primary"/>
											</div>
										</form>
									</div>
									</div>
								</div>
							</div>

							<?php
							if (isset($people) && $people->count() > 0):
								foreach($people as $key => $applicant):
							?>
							<?php 
								if(empty($applicant->message_id))
									$applicant->message_id = $applicant->id;
							?>
							<form method="POST" id="addNoteForm{{$applicant->message_id}}" action="{{ lurl('account/conversations/add-note') }}">
								{!! csrf_field() !!}
								<!-- Modal -->
								<div class="modal fade" id="addNoteModal{{$applicant->message_id}}" tabindex="-1" role="dialog" aria-labelledby="addNoteModal{{$applicant->message_id}}" aria-hidden="true">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header">
											<h5 class="modal-title" id="addNoteLabel{{$applicant->message_id}}">{{ $applicant->applicant_note?'Edit Note':'Add Note' }}</h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
											</div>
											<div class="modal-body">
												<div class="form-group">
													<label for="note">Add applicant note:</label>
													<input type="hidden" name="message_id" value="{{ $applicant->message_id }}">
													<textarea class="form-control" name="applicant_note" cols="30" rows="10" required>{{ (!empty($applicant->applicant_note))?$applicant->applicant_note:null }}</textarea>
												</div>
											</div>
											<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
											<button type="submit" class="btn btn-primary">Save changes</button>
											</div>
										</div>
									</div>
								</div>
							</form>
							<?php endforeach; ?>
							<?php endif; ?>
						</div>
						
						<nav class="" aria-label="" id="paginationLinks">
							{{ (isset($people)) ? $people->links() : '' }}
						</nav>
						
						<div style="clear:both"></div>
					
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

@section('after_scripts')
	<script src="{{ url('assets/js/footable.min.js?v=2-0-1') }}" type="text/javascript"></script>
	{{-- <script src="{{ url('assets/js/footable.filter.js?v=2-0-1') }}" type="text/javascript"></script> --}}
	<script type="text/javascript">
		var searchRequest = null;
		var noteSearchRequest = null;
		$(function () {
			$('#addManageTable').footable().bind('footable_filtering', function (e) {
				var selected = $('.filter-status').find(':selected').text();
				if (selected && selected.length > 0) {
					e.filter += (e.filter && e.filter.length > 0) ? ' ' + selected : selected;
					e.clear = !e.filter;
				}
			});

			if($("#cand_residence_country").val() == 'iq')
			{
				$("#city").removeAttr("disabled");
			}
			
			$(".footable-filtering .form-inline").hide();
			
			$('.clear-filter').click(function (e) {
				e.preventDefault();
				$('.filter-status').val('');
				$('table.demo').trigger('footable_clear_filter');
			});
			
			$('#checkAll').click(function () {
				checkAll(this);
			});
			
			$('a.delete-action, button.delete-action').click(function(e)
			{
				e.preventDefault(); /* prevents the submit or reload */
				var confirmation = confirm("{{ t('Are you sure you want to perform this action?') }}");
				
				if (confirmation) {
					if( $(this).is('a') ){
						var url = $(this).attr('href');
						if (url !== 'undefined') {
							redirect(url);
						}
					} else {
						$('form[name=listForm]').submit();
					}
				}
				
				return false;
			});

			function check_box_values(check_box_class){
				var values = new Array();
				$("."+check_box_class+":checked").each(function() {
					values.push($(this).val());
				});
				return values;
			}
			
            function updateQueryStringParameter(uri, key, value) {
                  var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
                  var separator = uri.indexOf('?') !== -1 ? "&" : "?";
                  if (uri.match(re)) {
                    return uri.replace(re, '$1' + key + "=" + value + '$2');
                  }
                  else {
                    return uri + separator + key + "=" + value;
                  }
            }
            
            var urlObject = new URL(document.location.href);
            var paramsObj = new URLSearchParams(urlObject.search);
            paramsObj.delete("page");

			if(paramsObj.toString() != '')
			{
                $('a.page-link').each(function(i, obj) {
                   obj.href = obj.href + '&' + paramsObj.toString();
                });
			}
			$('.sort_range').change(function(){
				var keywords=$("#search_field").val();;
				var rating=check_box_values('rating');
				var experience=check_box_values('exp');
				var eduDegree=check_box_values('degree');
				var regJobRole=check_box_values('role');
				var residenceCountry=check_box_values('country');
				var age=check_box_values('age');
				var city=check_box_values('city');
				var note_keyword = $("#note_filter").val();
				
				window.history.pushState("", "My People", window.location.href.split('?')[0]);
				
				var params = {
					rating:rating,
					user_experience: experience,
					edu_degree: eduDegree,
					job_role: regJobRole,
					residence_country: residenceCountry,
					birthday: age,
					city_id: city,
					note_keyword : note_keyword,
					search_keyword : keywords,
					is_search : 1
				};
				
				$.ajax({
					method: "GET",
					url: "{{ $filterAjaxUrl }}",
					data: params,
				})
				.done(function( data ) {
					$("#addManageTable").html(data.html);
					$("#paginationLinks").html(data.pagination);
					$('a.page-link').each(function(i, obj) {
                        obj.href = obj.href + '&' + jQuery.param( params );
                    });
                    if(window.location.href.includes("?"))
                    {
                    	var newUrl = window.location.href + '&' + jQuery.param( params );
                    }
                    else
                    {
                    	var newUrl = window.location.href + '?' + jQuery.param( params );
                    }
     				window.history.pushState("", "My People", newUrl);
				});
			});

			$("#note_filter").keyup(function () {
				var keywords=$("#search_field").val();;
				var rating=check_box_values('rating');
				var experience=check_box_values('exp');
				var eduDegree=check_box_values('degree');
				var regJobRole=check_box_values('role');
				var residenceCountry=check_box_values('country');
				var age=check_box_values('age');
				var city=check_box_values('city');
				var that = this,
				value = $(this).val();
				
				window.history.pushState("", "My People", window.location.href.split('?')[0]);
				
				var params = {
					rating:rating,
					user_experience: experience,
					edu_degree: eduDegree,
					job_role: regJobRole,
					residence_country: residenceCountry,
					birthday: age,
					city_id: city,
					note_keyword : value,
					search_keyword : keywords,
					is_search : 1
				};

				if (noteSearchRequest != null) 
					noteSearchRequest.abort();
				noteSearchRequest = $.ajax({
					type: "GET",
					url: "{{ $filterAjaxUrl }}",
					data:params,
					dataType: "text",
				}).done(function(data){
					data = JSON.parse(data);
					$("#addManageTable").html(data.html);
					$("#paginationLinks").html(data.pagination);
					$('a.page-link').each(function(i, obj) {
                        obj.href = obj.href + '&' + jQuery.param( params );
                    });
                    if(window.location.href.includes("?"))
                    {
                    	var newUrl = window.location.href + '&' + jQuery.param( params );
                    }
                    else
                    {
                    	var newUrl = window.location.href + '?' + jQuery.param( params );
                    }
     				window.history.pushState("", "My People", newUrl);
				});
			});

			/* send applicants emails */
			$('#bulkSendApplicantsEmailBtn').click(function(e) {
				e.preventDefault();
                var formData = $('#sendApplicantsEmailForm').serializeArray();
				var atLeastOneItemIsSelected = $('input[name="entries[]"]:checked').length > 0;

				if (atLeastOneItemIsSelected) {
					if (confirm("Are you sure you want to send this email to the selected users?") == true) {
                        var entryValues = [];
                        $('input[name="entries[]"]:checked').each(function(){
                            entryValues.push($(this).val());
                        });
                        formData.push({name:"entryIds", value:entryValues});
						formData.push({name:"applicants", value:1});
                        var verifyUrl = "{{ url('/admin/custom-email/user/resend/email') }}"
                        $.ajax({
                            url: verifyUrl,
                            type: 'POST',
                            data:formData,
                            success: function(result) {
                                // Show an alert with the result
								alert("The email has been sent to the selected users");
                            },
                            error: function(result) {
								/* Show an alert with the result */
								/* console.log(result.responseText); */
								if (typeof result.responseText !== 'undefined') {
									if (result.responseText.indexOf("{{ trans('admin::messages.unauthorized') }}") >= 0) {
										alert("Not authorized to send");
										return false;
									}
								}
								alert("Please select at least one item below");
                            }
                        });

					}
				} else {
					alert("Please select at least one item below");
				}
                
				return false;
			});

			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
			});
			$('#upCandidateResumeForm').submit(function(e) {
				e.preventDefault();
				var formData = new FormData(this);
				var files = $('#resumeFilename')[0].files;
				// Check file selected or not
				if(files.length > 0 ){
					formData.append('resume',files[0]);
				}

				$.ajax({
					type:'POST',
					url: "{{ url('read-resume')}}",
					data: formData,
					cache:false,
					contentType: false,
					processData: false,
					success: (data) => {
						if($("#upResumeMessage").length){
							$("#upResumeMessage").remove();
						}
						if(data.message !== undefined && data.success !== undefined)
						{
							if(data.success == 0)
							{
								$("#upCandidateResumeForm").prepend("<div class=\"alert alert-danger\" id=\"upResumeMessage\" role=\"alert\">\
								"+data.message+"</div>");
							}
							else if ( data.success == 1 )
							{
								$("#upCandidateResumeForm").prepend("<div class=\"alert alert-success\" id=\"upResumeMessage\" role=\"alert\">\
								"+data.message+"</div>");
							}
							else
							{
								$("#upCandidateResumeForm").prepend("<div class=\"alert alert-warning\" id=\"upResumeMessage\" role=\"alert\">\
								"+data.message+"</div>");
							}
						}

						if(data.name !== undefined && data.name != "")
							$('#name').val(data.name);

						if(data.email !== undefined && data.email != "")
							$('#email').val(data.email);

						if(data.phone !== undefined && data.phone != "")
							$('#phone').val(data.phone);

						if(data.country !== undefined && data.country != "")
							$('#cand_residence_country option[value='+data.country+']').attr('selected','selected');
							
						if(data.cvText !== undefined && data.cvText != "")
							$('#freeText').val(data.cvText);
							
						if(data.filepath !== undefined && data.filepath != "")
							$('#canResume').val(data.filepath);

					},
					error: function(data){
						if($("#upResumeMessage").length){
							$("#upResumeMessage").remove();
						}
						if(data.message !== undefined)
						{
							$("#upCandidateResumeForm").prepend("<div class=\"alert alert-danger\" id=\"upResumeMessage\" role=\"alert\">\
								"+data.message+"</div>");
						}
					}
				});
			});

			$("#addCandidateForm").submit(function(e) {
				e.preventDefault();
				
				var formData = new FormData();
				formData.append('name',$("#name").val());
				formData.append('email',$("#email").val());
				formData.append('residence_country',$("#cand_residence_country").val());
				formData.append('phone',$("#phone").val());
				formData.append('candidate_job',$("#candidate_job").val());
				formData.append('source_type',$("#source_type").val());
				formData.append('free_text',$("#freeText").val());
				formData.append('regJobRole',$("#regJobRole").val());
				formData.append('city_id',$("#city").val());
				formData.append('candidate_resume',$("#canResume").val());
				//var formData = new FormData(this);
// 				var files = $('#resumeFilename')[0].files;
				// Check file selected or not
// 				if(files.length > 0 ){
// 					formData.append('resume',files[0]);
// 				}
				$.ajax({
					type:'POST',
					url: "{{ url('add-candidate')}}",
					data: formData,
					processData: false,
					cache:false,
					contentType: false,
                    enctype: 'multipart/form-data',
					success: (data) => {
						if(data.success == 1)
						{
							if($("#upResumeMessage").length){
								$("#upResumeMessage").remove();
							}

							if($(".addCandidateErrMessage").length){
								$(".addCandidateErrMessage").remove();
							}
							$("#addCandidateForm").prepend("<div class=\"alert alert-success\" id=\"addCandidateMessage\" role=\"alert\">\
								"+data.message+"</div>");

							$("#addCandidateForm").append("<div class=\"alert alert-success\" id=\"addCandidateMessage\" role=\"alert\">\
								"+data.message+"</div>");

							setTimeout(function(){ $("#addCandidateModal").modal('hide'); }, 3000);
						}
						else
						{
							var validationMessages = "";
							if($("#upResumeMessage").length){
								$("#upResumeMessage").remove();
							}
							
							if(typeof data.message === 'object')
							{
    							if(data.message.candidate_job !== undefined && data.message.candidate_job[0] !== undefined && data.message.candidate_job[0] != "")
    								$("#addCandidateForm").prepend("<div class=\"alert alert-danger addCandidateErrMessage\" role=\"alert\">\
    									"+data.message.candidate_job[0]+"</div>");
    
    							if(data.message.email !== undefined && data.message.email[0] !== undefined && data.message.email[0] != "")
    								$("#addCandidateForm").prepend("<div class=\"alert alert-danger addCandidateErrMessage\" role=\"alert\">\
    									"+data.message.email[0]+"</div>");
    
    							if(data.message.name !== undefined && data.message.name[0] !== undefined && data.message.name[0] != "")
    								$("#addCandidateForm").prepend("<div class=\"alert alert-danger addCandidateErrMessage\" role=\"alert\">\
    									"+data.message.name[0]+"</div>");
    
    							if(data.message.source_type !== undefined && data.message.source_type[0] !== undefined && data.message.source_type[0] != "")
    								$("#addCandidateForm").prepend("<div class=\"alert alert-danger addCandidateErrMessage\" role=\"alert\">\
    									"+data.message.source_type[0]+"</div>");
    									
    							if(data.message.residence_country !== undefined && data.message.residence_country[0] !== undefined && data.message.residence_country[0] != "")
    								$("#addCandidateForm").prepend("<div class=\"alert alert-danger addCandidateErrMessage\" role=\"alert\">\
    									"+data.message.residence_country[0]+"</div>");
    									
    							if(data.message.resume !== undefined && data.message.resume[0] !== undefined && data.message.resume[0] != "")
    								$("#addCandidateForm").prepend("<div class=\"alert alert-danger addCandidateErrMessage\" role=\"alert\">\
    									"+data.message.resume[0]+"</div>");
    									
    							if(data.message.regJobRole !== undefined && data.message.regJobRole[0] !== undefined && data.message.regJobRole[0] != "")
    								$("#addCandidateForm").prepend("<div class=\"alert alert-danger addCandidateErrMessage\" role=\"alert\">\
    									"+data.message.regJobRole[0]+"</div>");
							}
							else
							{
								$("#addCandidateForm").prepend("<div class=\"alert alert-danger\" id=\"addCandidateMessage\" role=\"alert\">\
								"+data.message+"</div>");
							}
						}
					},
					error: function(data){

					}
				});
			});

			$("*[id*=addCandidateRatingForm]:visible").submit(function(e) {
				e.preventDefault();
				var formData = $(this).serializeArray().reduce(function(obj, item) {
					obj[item.name] = item.value;
					return obj;
				}, {});

				$.ajax({
					type:'POST',
					url: "{{ url('add-candidate-rating')}}",
					data: formData,
					dataType: "json",
     				encode: true,
					success: (data) => {
						if(data.success !== undefined && data.success == 1)
						{
							if($("#addCandidateRatingMessage").length){
								$("#addCandidateRatingMessage").remove();
							}
							$(this).append("<span class=\"alert alert-success\" id=\"addCandidateRatingMessage\" role=\"alert\">\
									"+data.message+"</span>");
							
							setTimeout(function(){
								$("#addCandidateRatingMessage").remove();
							}, 3000);
						}
					},
					error: function(data){
						if($("#addCandidateRatingMessage").length){
								$("#addCandidateRatingMessage").remove();
						}
						$(this).append("<span class=\"alert alert-danger\" id=\"addCandidateRatingMessage\" role=\"alert\">\
								Error Occured</span>");

						setTimeout(function(){
							$("#addCandidateRatingMessage").remove();
						}, 3000);
					}
				});
				return false;
			});

			$("#search_field").keyup(function () {
				var rating=check_box_values('rating');
				var experience=check_box_values('exp');
				var eduDegree=check_box_values('degree');
				var regJobRole=check_box_values('role');
				var residenceCountry=check_box_values('country');
				var age=check_box_values('age');
				var city=check_box_values('city');
				var note_keyword = $("#note_filter").val();
				var that = this,
				value = $(this).val();
				
				window.history.pushState("", "My People", window.location.href.split('?')[0]);
				
				var params = {
							rating:rating,
							user_experience: experience,
							edu_degree: eduDegree,
							job_role: regJobRole,
							residence_country: residenceCountry,
							birthday: age,
							city_id: city,
							note_keyword : note_keyword,
    						search_keyword : value,
    						is_search : 1
				};

				if (searchRequest != null) 
					searchRequest.abort();
				searchRequest = $.ajax({
					type: "GET",
					url: "{{ $filterAjaxUrl }}",
					data:params,
					dataType: "text",
				}).done(function(data){
					data = JSON.parse(data);
					$("#addManageTable").html(data.html);
					$("#paginationLinks").html(data.pagination);
					$('a.page-link').each(function(i, obj) {
                        obj.href = obj.href + '&' + jQuery.param( params );
                    });
                    if(window.location.href.includes("?"))
                    {
                    	var newUrl = window.location.href + '&' + jQuery.param( params );
                    }
                    else
                    {
                    	var newUrl = window.location.href + '?' + jQuery.param( params );
                    }
     				window.history.pushState("", "My People", newUrl);
				});
			});
		});
	</script>
	<!-- include custom script for ads table [select all checkbox]  -->
	<script>

		function changeRating(id, rated_user_id)
		{
			var cname=document.getElementById(id).className;
			var cval = document.getElementById(id).value;
			var ab=document.getElementById(id+"_hidden").value;
			document.getElementById(rated_user_id+"rating").value=ab;

			for(var i=ab;i>=1;i--)
			{
				document.getElementById("rating_"+rated_user_id+"_"+i).src="{{ url('images/site/star2.png')}}";
			}
			var id=parseInt(ab)+1;
			for(var j=id;j<=5;j++)
			{
				document.getElementById("rating_"+rated_user_id+"_"+j).src="{{ url('images/site/star1.png')}}";
			}
		}

		function checkAll(bx) {
			var chkinput = document.getElementsByTagName('input');
			for (var i = 0; i < chkinput.length; i++) {
				if (chkinput[i].type == 'checkbox' && chkinput[i].name == 'entries[]') {
					chkinput[i].checked = bx.checked;
				}
			}
		}

		function applicantStage(conversationId)
		{
			var applicantStage = document.getElementById("applicant_stage_"+conversationId).value;
			$.ajax({
				method: "GET",
				url: "/account/conversations/"+conversationId+"/stage",
				data: { 
						applicant_stage: applicantStage,
					}
			})
			.done(function( data ) {
				$("#applicantsStages"+data.result).prepend("<span class='badge badge-success mb-2' id='stageChanged"+data.result+"'>The stage changed successfully</span>");
				setTimeout(function(){
					var element = document.getElementById("stageChanged"+data.result);
    				element.parentNode.removeChild(element);
				}, 5000);
			});
		}

		function assignToJob(conversationId)
		{
			var jobToAssign = document.getElementById("assign_to_job_"+conversationId).value;
			$.ajax({
				method: "POST",
				url: "{{ url('assign-to-job')}}",
				data: {
						conversation_id: conversationId,
						job_id: jobToAssign,
					}
			})
			.done(function( data ) {
				$("#assignToJob"+data.result).prepend("<span class='badge badge-success mb-2' id='jobAssigned"+data.result+"'>The applicant has assigned to the job successfully</span>");
				setTimeout(function(){
					var element = document.getElementById("jobAssigned"+data.result);
    				element.parentNode.removeChild(element);
				}, 5000);
			});
		}

		function stageFilter(stageId)
		{
			$.ajax({
				method: "GET",
				url: "{{ $filterAjaxUrl }}",
				data: {
						stage:stageId
					}
			})
			.done(function( data ) {
				$("#addManageTable").html(data.html);
				$("#paginationLinks").html(data.pagination);
			});
		}
		
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