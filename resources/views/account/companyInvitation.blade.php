
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
							<i class="icon-mail"></i> {{ t('add users') }}
						</h2>
						<div id="reloadBtn" class="mb30" style="display: none;">
							<a href="" class="btn btn-primary" class="tooltipHere" title="" data-placement="{{ (config('lang.direction')=='rtl') ? 'left' : 'right' }}"
							   data-toggle="tooltip"
							   data-original-title="{{ t('Reload to see New Messages') }}"><i class="icon-arrows-cw"></i> {{ t('Reload') }}</a>
							<br><br>
						</div>
						
						<div style="clear:both"></div>
						
						<div class="table-responsive">
							<div class="table-action">
								<h3>{{$companyData->name}} {{t('company')}}</h3>
								<form method="POST" action="{{ lurl('invite-company-members/send/email') }}">
									{!! csrf_field() !!}
									<div class="form-group">
										<label class="form-label">{{t('Invite your company members')}}</label>
										<input type="text" name="emails" class="form-control" placeholder="Enter emails saparated by comma ex: example@company.com,example2@company.com" />
										<input type="hidden" name="companyId" value="{{$companyData->id}}" />
									</div>
									<input type="submit" class="btn btn-primary" value="{{t('Send')}}" name="submit" />
								</form>
							</div>
							
							<table id="addManageTable" class="table table-striped table-bordered add-manage-table table demo" data-filter="#filter" data-filter-text-only="true">
								<thead>
								<tr>
									<th style="width:60%" data-sort-ignore="true">{{ t('Name') }}</th>
									<th style="width:10%">{{ t('E-mail') }}</th>
								</tr>
								</thead>
								<tbody>
								<?php
								if (isset($invitedEmployers) && $invitedEmployers->count() > 0):
								   foreach($invitedEmployers as $key => $invitedEmployer):
								?>
									<tr>
										<td>
											{{$invitedEmployer->name}}
										</td>
										<td>
											{{$invitedEmployer->email}}
										</td>
									</tr>
								<?php endforeach; ?>
								<?php endif; ?>
								</tbody>
							</table>
						</div>
						
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
	</script>
@endsection