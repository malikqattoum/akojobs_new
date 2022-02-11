
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
							<i class="icon-mail"></i> {{ t('My sent emails') }}
						</h2>
						<div style="clear:both"></div>
						
						<div class="table-responsive">
							<form>
								{!! csrf_field() !!}
								<table id="addManageTable" class="table table-striped table-bordered add-manage-table table demo" data-filter-space="OR" data-filter="#filter" data-filtering="true">
									<thead>
									<tr>
										<th style="width:80%" data-sort-ignore="true">{{ t('sent to') }}</th>
										<th style="width:20%">{{ t('Option') }}</th>
									</tr>
									</thead>
									<tbody>
									<?php
									if (isset($userTrackedEmails) && $userTrackedEmails->count() > 0):
										foreach($userTrackedEmails as $key => $trackedEmail):
									?>
									<tr>
										<td>
											<div style="word-break:break-all;">
												<div style="font-size:15px">
												<div class="people-data">
													<strong>{{ t('sent at') }}:</strong>
													{{ date('d M Y H:i',strtotime($trackedEmail->sent_date)) }}
                                                    @if(!empty($trackedEmail->subject))
                                                        <br><strong>{{ t('sent to') }}:</strong>&nbsp;{{ $trackedEmail->name }}
                                                    @endif
                                                    @if(!empty($trackedEmail->subject))
                                                        <br><strong>{{ t('Subject') }}:</strong>&nbsp;{{ $trackedEmail->subject }}
                                                    @endif
                                                    @if(!empty($trackedEmail->message))
                                                        <br><strong>{{ t('Message') }}:</strong>&nbsp;{{ $trackedEmail->message }}
                                                    @endif
													@if(!empty($trackedEmail->job_role))
														<br><strong>{{ t('Job Role') }}:</strong>&nbsp;{{ t($trackedEmail->job_role, [], 'roles') }}<br>
													@endif
													<p>
														<button class="btn btn-primary mt-2" type="button" data-toggle="collapse" data-target="#collapseExample{{$trackedEmail->id}}" aria-expanded="false" aria-controls="collapseExample">
															{{t('view all candidate data')}}
														</button>
													</p>
													<div class="collapse" id="collapseExample{{$trackedEmail->id}}">
														<div class="card card-body">
															@if(!empty($trackedEmail->email))
																<strong>{{ t('Email Address') }}:&nbsp;{{ $trackedEmail->email }}<br>
															@endif
															@if(!empty($trackedEmail->nationality))
																<strong>{{ t('Nationality') }}:</strong>&nbsp;{{ t($user->nationality, [], 'residenceCountry') }}<br>
															@endif
															@if(!empty($trackedEmail->residence_country))
																<strong>{{ t('Resident Country') }}:</strong>&nbsp;{{ t($user->residence_country, [], 'residenceCountry') }}<br>
															@endif
															@if(!empty($trackedEmail->gender))
																<strong>{{ t('Gender') }}:</strong>&nbsp;{{ $trackedEmail->gender }}<br>
															@endif
															@if(!empty($trackedEmail->phone))
																<strong>{{ t('Mobile Number') }}:</strong>&nbsp;{{ $trackedEmail->phone }}<br>
															@endif
															@if(!empty($trackedEmail->industry))
																<strong>{{ t('Industry') }}:</strong>&nbsp;{{ t($trackedEmail->industry, [], 'industry') }}<br>
															@endif
															@if(!empty($trackedEmail->user_experience))
																<strong>{{ t('Experience Years') }}:</strong>&nbsp;{{ t($trackedEmail->user_experience, [], 'experience') }}<br>
															@endif
														</div>
													</div>
												</div>
											</div>
										</td>
										<td class="action-td">
											<div>
												<p>
													<a class="btn btn-primary btn-sm" href="{{ lurl('profile/employer-view/'.$trackedEmail->id) }}">
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
							{{ (isset($userTrackedEmails)) ? $userTrackedEmails->links() : '' }}
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

            $(".footable-filtering .input-group-btn .dropdown-toggle").hide();
			$(".footable-filtering .input-group").addClass("mt-2");
			$(".footable-filtering .input-group-btn .btn-primary").addClass('btn-sm mt-2')
			$(".footable-filtering .input-group-btn .btn-primary span").removeClass('fooicon').removeClass('fooicon-search').addClass('icon-search');
			$(".footable-filtering .form-inline").prepend("<label class='mr-2 mb-4'>{{t('multiple keywords')}}</label>");
	</script>
@endsection