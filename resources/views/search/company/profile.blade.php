
<?php
$fullUrl = url(\Illuminate\Support\Facades\Request::getRequestUri());
$tmpExplode = explode('?', $fullUrl);
$fullUrlNoParams = current($tmpExplode);
?>
@extends('layouts.master')

@section('search')
	@parent
	@include('search.inc.form')
	@include('search.inc.breadcrumbs')
	@include('layouts.inc.advertising.top')
@endsection

@section('content')
	@include('common.spacer')
	<div class="main-container">
		<div class="container">
			
			<div class="section-content">
				<div class="inner-box">
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
				
				<div class="section-block" style="margin-top: 20px;">
					<div class="category-list">
						<div class="tab-box clearfix">
							
							<!-- Nav tabs -->
							<div class="col-lg-12 box-title no-border">
								<div class="inner">
									<h2>
										<small>{{ sprintf(t('Jobs Found'),$count->get('all')) }}</small>
									</h2>
								</div>
							</div>
							
							<!-- Mobile Filter bar -->
							<div class="mobile-filter-bar col-lg-12">
							
							</div>
							<div class="menu-overly-mask"></div>
							<!-- Mobile Filter bar End-->
							
							
							<div class="tab-filter hide-xs" style="padding-top: 6px; padding-right: 6px;">
							
							</div>
							<!--/.tab-filter-->
						
						</div>
						<!--/.tab-box-->
						
						<div class="listing-filter hidden-xs">
							<div class="pull-left col-sm-10 col-xs-12">
								<div class="breadcrumb-list text-center-xs">
									{!! (isset($htmlTitle)) ? $htmlTitle : '' !!}
								</div>
							</div>
							<div class="pull-right col-sm-2 col-xs-12 text-right text-center-xs listing-view-action">
								@if (!empty(\Illuminate\Support\Facades\Input::all()))
									<?php $attr = ['countryCode' => config('country.icode')]; ?>
									<a class="clear-all-button text-muted" href="{!! lurl(trans('routes.v-search', $attr), $attr) !!}">{{ t('Clear all') }}</a>
								@endif
							</div>
							<div style="clear:both;"></div>
						</div>
						<!--/.listing-filter-->
						
						<div class="jobs-list">
							@include('search.inc.posts')
						</div>
						<!--/.adds-wrapper-->
						
						{{-- <div class="tab-box save-search-bar text-center">
							@if (Request::filled('q') and Request::get('q') != '' and $count->get('all') > 0)
								<a name="{!! qsurl($fullUrlNoParams, Request::except(['_token', 'location'])) !!}" id="saveSearch" count="{{ $count->get('all') }}">
									<i class=" icon-star-empty"></i> {{ t('Save Search') }}
								</a>
							@else
								<a href="#"> &nbsp; </a>
							@endif
						</div> --}}
					</div>
					
					<div class="pagination-bar text-center">
						{!! $paginator->appends(request()->query())->render() !!}
					</div>
					<!--/.pagination-bar -->
				</div>
				
				<div style="clear:both;"></div>
				
				<!-- Advertising -->
				@include('layouts.inc.advertising.bottom')
			</div>
		
		</div>
	</div>
@endsection