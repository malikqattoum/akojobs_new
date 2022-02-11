
@extends('layouts.master')

@section('content')
	@include('common.spacer')
	<div class="main-container">
		<div class="container">
			<div class="row">
				@if (Session::has('flash_notification'))
					<div class="col-xl-12">
						<div class="row">
							<div class="col-xl-12">
								@include('flash::message')
							</div>
						</div>
					</div>
				@endif
				
				<div class="col-md-3 page-sidebar">
					@include('account.inc.sidebar')
				</div>
				<!--/.page-sidebar-->
				
				<div class="col-md-9 page-content">
					<div class="inner-box">
						<h2 class="title-2">
							<i class="icon-mail"></i> {{ t('allocate candidates') }}
						</h2>
						<div style="clear:both"></div>
						
						<div class="table-responsive">
							<form name="allocateForm" method="POST" action="{{ lurl('account/allocate-candidates') }}">
								{!! csrf_field() !!}
								<div class="table-action">
									<label for="checkAll">
										<input type="checkbox" id="checkAll">
										{{ t('Select') }}: {{ t('All') }} 
										<button type="submit" class="btn btn-md btn-default delete-action">
											<i class="fas fa-paper-plane"></i> {{ t('allocate') }}
										</button>
									</label>
									<div class="form-group">
                                        <label class="form-label">{{t('type num of candidates')}}</label>
                                        <input type="number" name="candidates_num" class="form-control" id="candidates_num" />
                                    </div>
                                    <div class="form-group">
                                        <label>{{t('Employers')}}</label>
                                        <select class="form-select form-control sselecter" name="employersIds[]" multiple>
                                            <option selected disabled>{{t('select employers')}}</option>
                                            @foreach ($employers as $employer)
                                                <option value="{{$employer->id}}">{{$employer->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
								</div>
								
								<table id="addManageTable" class="table table-striped table-bordered add-manage-table table demo" data-filter-space="OR" data-filter="#filter" data-filtering="true">
									<thead>
										<tr>
											<form action="">
												<div class="form-group">
													<label class="form-label">{{t('multiple keywords')}}</label>
													<input type="text" class="form-control" id="search_field" placeholder="{{t('keywords')}}">
												</div>
											</form>
										</tr>
										<tr>
											<th style="width:2%" data-type="numeric" data-sort-initial="true"></th>
											<th style="width:80%" data-sort-ignore="true">{{ t('Conversations') }}</th>
											<th style="width:18%">{{ t('Option') }}</th>
										</tr>
									</thead>
									<tbody>
									<?php
									if (isset($jobseekers) && $jobseekers->count() > 0):
										foreach($jobseekers as $key => $jobseeker):
									?>
									<tr>
										<td class="add-img-selector">
											<div class="checkbox">
												<label><input type="checkbox" name="entries[]" value="{{ $jobseeker->id }}"></label>
											</div>
										</td>
										<td>
											<div style="word-break:break-all;">
												<div style="font-size:15px">
													@if (!empty($jobseeker->photo))
														<img class="people-photo" src="{{ (substr($jobseeker->photo, 0, 1) != '/')?'/'.$jobseeker->photo:$jobseeker->photo }}" alt="user">&nbsp;
													@elseif (!empty(Session::get('image')))
														<img class="people-photo" src="{{ Session::get('image') }}" alt="user">
													@else
														<img class="people-photo" src="{{ url('images/user.jpg') }}" alt="user">
													@endif
													<a href="{{ lurl('profile/employer-view/'.$jobseeker->id) }}" class="people-name"><strong>{{ \Illuminate\Support\Str::limit($jobseeker->name, 50) }}</strong></a>
												</form>
												<div class="people-data">
													<strong>{{ t('registered at') }}:</strong>
													{{ date('d M Y H:i',strtotime($jobseeker->created_at)) }}
													@if(!empty($jobseeker->job_role))
														<br><strong>{{ t('Job Role') }}:</strong>&nbsp;{{ t($jobseeker->job_role, [], 'roles') }}<br>
													@endif
													<p>
														<button class="btn btn-primary mt-2" type="button" data-toggle="collapse" data-target="#collapseExample{{$jobseeker->id}}" aria-expanded="false" aria-controls="collapseExample">
															{{t('view all candidate data')}}
														</button>
													</p>
													<div class="collapse" id="collapseExample{{$jobseeker->id}}">
														<div class="card card-body">
															@if(!empty($jobseeker->email))
																<strong>{{ t('Email Address') }}:&nbsp;{{ $jobseeker->email }}<br>
															@endif
															@if(!empty($jobseeker->nationality))
																<strong>{{ t('Nationality') }}:</strong>&nbsp;{{ t($user->nationality, [], 'residenceCountry') }}<br>
															@endif
															@if(!empty($jobseeker->residence_country))
																<strong>{{ t('Resident Country') }}:</strong>&nbsp;{{ t($user->residence_country, [], 'residenceCountry') }}<br>
															@endif
															@if(!empty($jobseeker->gender))
																<strong>{{ t('Gender') }}:</strong>&nbsp;{{ $jobseeker->gender }}<br>
															@endif
															@if(!empty($jobseeker->phone))
																<strong>{{ t('Mobile Number') }}:</strong>&nbsp;{{ $jobseeker->phone }}<br>
															@endif
															@if(!empty($jobseeker->industry))
																<strong>{{ t('Industry') }}:</strong>&nbsp;{{ t($jobseeker->industry, [], 'industry') }}<br>
															@endif
															@if(!empty($jobseeker->user_experience))
																<strong>{{ t('Experience Years') }}:</strong>&nbsp;{{ t($jobseeker->user_experience, [], 'experience') }}<br>
															@endif
														</div>
													</div>
												</div>
											</div>
										</td>
										<td class="action-td">
											<div>
												<p>
													<a class="btn btn-primary btn-sm" href="{{ lurl('profile/employer-view/'.$jobseeker->id) }}">
														<i class="fa fa-user"></i> {{t('View user profile')}}
													</a>
												</p>
											</div>
										</td>
									</tr>
									<?php endforeach; ?>
									<?php endif; ?>
									</tbody>
								</table>
							</form>
						</div>
						
						<nav class="" aria-label="" id="paginationLinks">
							{{ (isset($jobseekers)) ? $jobseekers->links() : '' }}
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
    <script src="{{ url('assets/js/footable.js?v=2-0-1') }}" type="text/javascript"></script>
    <script src="{{ url('assets/js/footable.filter.js?v=2-0-1') }}" type="text/javascript"></script>
	<script type="text/javascript">
        	$('#addManageTable').footable().bind('footable_filtering', function (e) {
				var selected = $('.filter-status').find(':selected').text();
				if (selected && selected.length > 0) {
					e.filter += (e.filter && e.filter.length > 0) ? ' ' + selected : selected;
					e.clear = !e.filter;
				}
			});
			
			$('.clear-filter').click(function (e) {
				e.preventDefault();
				$('.filter-status').val('');
				$('table.demo').trigger('footable_clear_filter');
			});

            function checkAll(bx) {
                var chkinput = document.getElementsByTagName('input');
                for (var i = 0; i < chkinput.length; i++) {
                    if (chkinput[i].type == 'checkbox' && chkinput[i].name == 'entries[]') {
                        chkinput[i].checked = bx.checked;
                    }
                }
		    }
			
			$('#checkAll').click(function () {
				checkAll(this);
			});

			$(".footable-filtering .form-inline").hide();

            function check_box_values(check_box_class){
				var values = new Array();
				$("."+check_box_class+":checked").each(function() {
					values.push($(this).val());
				});
				return values;
			}
			$('.sort_range').change(function(){
				var experience=check_box_values('exp');
				var eduDegree=check_box_values('degree');
				var regJobRole=check_box_values('role');
				var residenceCountry=check_box_values('country');
				var age=check_box_values('age');
				var city=check_box_values('city');
				$.ajax({
					method: "POST",
					url: "{{ $filterAjaxUrl }}",
					data: {
							user_experience: experience,
							edu_degree: eduDegree,
							job_role: regJobRole,
							residence_country: residenceCountry,
							birthday: age,
							city_id: city,
						}
				})
				.done(function( data ) {
					$("#addManageTable").html(data.html);
					$("#paginationLinks").html(data.pagination);
				});
			});

			var searchRequest = null;
			$(function () {
				var minlength = 3;

				$("#search_field").keyup(function () {
					var that = this,
					value = $(this).val();

					if (value.length >= minlength ) {
						if (searchRequest != null) 
							searchRequest.abort();
						searchRequest = $.ajax({
							type: "POST",
							url: "{{ lurl('account/candidates-search') }}",
							data: {
								'search_keyword' : value
							},
							dataType: "text",
						}).done(function(data){
							data = JSON.parse(data);
							$("#addManageTable").html(data.html);
							$("#paginationLinks").html(data.pagination);
						});
					}
				});
			});
	</script>
@endsection