
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
							<i class="icon-mail"></i> {{ t('Conversations') }}
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
										</button>
									</label>
									<div class="table-search pull-right col-sm-7">
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
									</div>
										<div class="form-group col-xs-6 col-md-3">
											<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#applicantsEmailModal" data-whatever="@mdo">{{t('Send emails to applicants')}}</button>
										</div>
									</div>
								</div>
								
								<table id="addManageTable" class="table table-striped table-bordered add-manage-table table demo" data-filter="#filter" data-filter-text-only="true">
									<thead>
									<tr>
										<th style="width:2%" data-type="numeric" data-sort-initial="true"></th>
										<th style="width:60%" data-sort-ignore="true">{{ t('Conversations') }}</th>
										<th style="width:10%">{{ t('Option') }}</th>
										<th style="width:28%">{{t('Screening tools')}}</th>
									</tr>
									</thead>
									<tbody>
									<?php
									if (isset($conversations) && $conversations->count() > 0):
										foreach($conversations as $key => $conversation):
									?>
									<tr>
										<td class="add-img-selector">
											<div class="checkbox">
												<label><input type="checkbox" name="entries[]" value="{{ $conversation->id }}"></label>
											</div>
										</td>
										<td>
											<div style="word-break:break-all;">
												<strong>{{ t('Received at') }}:</strong>
												{{ $conversation->created_at->formatLocalized(config('settings.app.default_datetime_format')) }}
												@if (\App\Models\Message::conversationHasNewMessages($conversation))
													<i class="icon-flag text-primary"></i>
												@endif
												<br>
												<strong>{{ t('Subject') }}:</strong>&nbsp;{{ $conversation->subject }}<br>
												<strong>{{ t('Created by') }}:</strong>&nbsp;{{ \Illuminate\Support\Str::limit($conversation->from_name, 50) }}
												@if(!empty($conversation->job_role))
													<br><strong>{{ t('Job Role') }}:</strong>&nbsp;{{ t($conversation->job_role, [], 'roles') }}<br>
												@endif;
												{!! (!empty($conversation->filename) and \Storage::exists($conversation->filename)) ? ' <i class="icon-attach-2"></i> ' : '' !!}&nbsp;|&nbsp;
												<a href="{{ lurl('account/conversations/' . $conversation->id . '/messages') }}">
													{{ t('Click here to read the messages') }}
												</a>
											</div>
										</td>
										<td class="action-td">
											<div>
												<p>
													<a class="btn btn-default btn-sm" href="{{ lurl('account/conversations/' . $conversation->id . '/messages') }}">
														<i class="icon-eye"></i> {{ t('View') }}
													</a>
												</p>
												<p>
													<a class="btn btn-danger btn-sm delete-action" href="{{ lurl('account/conversations/' . $conversation->id . '/delete') }}">
														<i class="fa fa-trash"></i> {{ t('Delete') }}
													</a>
												</p>
												<p>
													<a class="btn btn-primary btn-sm" href="{{ lurl('profile/employer-view/'.$conversation->from_user_id) }}">
														<i class="fa fa-user"></i> {{t('View user profile')}}
													</a>
												</p>
											</div>
										</td>
										<td>
											<!-- add note modal button -->
											@if(!empty($conversation->applicant_note))
												<!-- Button trigger modal -->
												<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#viewNoteModal{{$conversation->id}}">
													{{t('View applicant note')}}
												</button>
												
												<!-- Modal -->
												<div class="modal fade" id="viewNoteModal{{$conversation->id}}" tabindex="-1" role="dialog" aria-labelledby="viewNoteLabel{{$conversation->id}}" aria-hidden="true">
													<div class="modal-dialog" role="document">
														<div class="modal-content">
															<div class="modal-header">
															<h5 class="modal-title" id="viewNoteLabel{{$conversation->id}}">applicant note</h5>
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
															</div>
															<div class="modal-body">
																{{ $conversation->applicant_note }}
															</div>
														</div>
													</div>
												</div>
												<button type="button" class="btn btn-primary my-2 btn-sm" data-toggle="modal" data-target="#addNoteModal{{$conversation->id}}">
													{{t('Edit Note')}}
												</button>
											@else
												<button type="button" class="btn btn-primary mb-2 btn-sm" data-toggle="modal" data-target="#addNoteModal{{$conversation->id}}">
													{{t('Add Note')}}
												</button>
											@endif
											<br>
											<?php $applicantsStages = include(base_path() . '/resources/lang/en/applicantsStages.php');
												asort($applicantsStages);
												$applicantsStagesKeys = array_keys($applicantsStages);
											?>
											<div class="form-group col-xs-12" id="applicantsStages{{ $conversation->id }}">
												<select class="form-control" onchange="applicantStage({{ $conversation->id }})" name="applicant_stage" id="applicant_stage_{{ $conversation->id }}" placeholder="{{t('Applicant stages')}}">
													<option value="" selected="" disabled>{{t('Applicant stages')}}</option>
													@foreach ($applicantsStagesKeys as $item)
														<option value="{{$item}}" {{ (!empty($conversation->applicant_stage))?(($conversation->applicant_stage == $item)? "selected":""):'' }} >{{t($item,[],'applicantsStages')}}</option>
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
										<h5 class="modal-title" id="applicantsEmailModalLabel">New email message</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<form id="sendApplicantsEmailForm">
											<div class="form-group">
												<label for="subject" class="col-form-label">Subject:</label>
												<input type="text" name="email_subject" id="applicantsEmailSubject" class="form-control">
											</div>
											<div class="form-group">
												<label for="message-text" class="col-form-label">Message:</label>
												<textarea class="form-control" name="email_message" id="pageContent" rows="15"></textarea>
											</div>
										</form>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
										<button type="button" id="bulkSendApplicantsEmailBtn" class="btn btn-primary">Send message</button>
									</div>
									</div>
								</div>
							</div>

							<?php
							if (isset($conversations) && $conversations->count() > 0):
								foreach($conversations as $key => $conversation):
							?>
							<form method="POST" id="addNoteForm{{$conversation->id}}" action="{{ lurl('account/conversations/add-note') }}">
								{!! csrf_field() !!}
								<!-- Modal -->
								<div class="modal fade" id="addNoteModal{{$conversation->id}}" tabindex="-1" role="dialog" aria-labelledby="addNoteModal{{$conversation->id}}" aria-hidden="true">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header">
											<h5 class="modal-title" id="addNoteLabel{{$conversation->id}}">{{ $conversation->applicant_note?'Edit Note':'Add Note' }}</h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
											</div>
											<div class="modal-body">
												<div class="form-group">
													<label for="note">Add applicant note:</label>
													<input type="hidden" name="message_id" value="{{ $conversation->id }}">
													<textarea class="form-control" name="applicant_note" cols="30" rows="10" required>{{ (!empty($conversation->applicant_note))?$conversation->applicant_note:null }}</textarea>
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
							{{ (isset($conversations)) ? $conversations->links() : '' }}
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
		$(function () {
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
			$('.sort_range').change(function(){
				var experience=check_box_values('exp');
				var eduDegree=check_box_values('degree');
				var regJobRole=check_box_values('role');
				var residenceCountry=check_box_values('country');
				var age=check_box_values('age');
				var city=check_box_values('city');
				console.log({
						user_experience: experience,
						edu_degree: eduDegree,
						job_role: regJobRole,
						residence_country: residenceCountry,
						birthday: age,
						city_id: city,
						});
				$.ajax({
				method: "GET",
				url: "{{ lurl('account/my-people') }}",
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

			$("#userExperience, #edu_degree, #regJobRole, #residence_country, #age, #city").change(function(){
				var experience = $("#userExperience").val();
				var eduDegree = $("#edu_degree").val();
				var regJobRole = $("#regJobRole").val();
				var residenceCountry = $("#residence_country").val();
				var age = $("#age").val();
				var city = $("#city").val();
				$.ajax({
					method: "GET",
					url: "{{ lurl('account/conversations/'.$postId.'/applicants') }}",
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
                                console.log(result);
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
						console.log(data);
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

						this.reset();
					},
					error: function(data){
						console.log(data);
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
				var formData = {
					name: $("#name").val(),
					email: $("#email").val(),
					residence_country: $("#cand_residence_country").val(),
					phone: $("#phone").val(),
					canddidate_job: $("#candidate_job").val(),
					source_type: $("#source_type").val()
				};

				$.ajax({
					type:'POST',
					url: "{{ url('add-candidate')}}",
					data: formData,
					dataType: "json",
     				encode: true,
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
							if(data.message.canddidate_job !== undefined && data.message.canddidate_job[0] !== undefined && data.message.canddidate_job[0] != "")
								$("#addCandidateForm").prepend("<div class=\"alert alert-danger addCandidateErrMessage\" role=\"alert\">\
									"+data.message.canddidate_job[0]+"</div>");

							if(data.message.email !== undefined && data.message.email[0] !== undefined && data.message.email[0] != "")
								$("#addCandidateForm").prepend("<div class=\"alert alert-danger addCandidateErrMessage\" role=\"alert\">\
									"+data.message.email[0]+"</div>");

							if(data.message.name !== undefined && data.message.name[0] !== undefined && data.message.name[0] != "")
								$("#addCandidateForm").prepend("<div class=\"alert alert-danger addCandidateErrMessage\" role=\"alert\">\
									"+data.message.name[0]+"</div>");

							if(data.message.source_type !== undefined && data.message.source_type[0] !== undefined && data.message.source_type[0] != "")
								$("#addCandidateForm").prepend("<div class=\"alert alert-danger addCandidateErrMessage\" role=\"alert\">\
									"+data.message.source_type[0]+"</div>");
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
						console.log(data);
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
			});
		});
	</script>
	<!-- include custom script for ads table [select all checkbox]  -->
	<script>
		function checkAll(bx) {
			var chkinput = document.getElementsByTagName('input');
			for (var i = 0; i < chkinput.length; i++) {
				if (chkinput[i].type == 'checkbox') {
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
	</script>
@endsection