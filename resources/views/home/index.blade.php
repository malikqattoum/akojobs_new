
@extends('layouts.master')

@section('search')
	@parent
@endsection

@section('content')
	<div class="main-container" id="homepage">
		
		@if (Session::has('message'))
			@include('common.spacer')
			<?php $paddingTopExists = true; ?>
			<div class="container">
				<div class="row">
					<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						{{ session('message') }}
					</div>
				</div>
			</div>
		@endif
		
		@if (Session::has('flash_notification'))
			@include('common.spacer')
			<?php $paddingTopExists = true; ?>
			<div class="container">
				<div class="row">
					<div class="col-xl-12">
						@include('flash::message')
					</div>
				</div>
			</div>
		@endif
		
		@if (isset($sections) and $sections->count() > 0)
			@foreach($sections as $section)
				@if (view()->exists($section->view) && $section->view == 'home.inc.search')
					@include($section->view, ['firstSection' => $loop->first])
				@endif
			@endforeach
		@endif

		<?php
		 	if(!auth()->check())
			{
				$jobsByDesktopCol = 'col-md-4';
				$latestJobsDesktopCol = 'col-md-4';
			}
			else
			{
				$jobsByDesktopCol = 'col-md-5';
				$latestJobsDesktopCol = 'col-md-6';
			}
		?>

		<div class="container mt-4">
			<div class="row">
				<div class="col-sm home_image">
					<a href="{{ url('jobs/baghdad/98182') }}">
						<img src="{{ url('images/site/baghdad.jpeg') }}" class="img-responsive home_city_image" style="width: 100%;
						height: 300px;">
						<div class="overlay">
							<h2 style="text-align:center;margin:40% 0 0 0;color:#FFF">{{t('baghdad')}}</h2>
							<h2 style="text-align:center;margin:5% 0 0 0;color:#FFF">{{$citiesPostsCounts['98182']}}</h2>
						</div>
					</a>
				</div>
				<div class="col-sm home_image">
					<a href="{{ url('jobs/arbil/6765396') }}">
						<img src="{{ url('images/site/erbil.jpeg') }}" class="img-responsive home_city_image" style="width: 100%;
						height: 300px;">
						<div class="overlay">
							<h2 style="text-align:center;margin:40% 0 0 0;color:#FFF">{{t('erbil')}}</h2>
							<h2 style="text-align:center;margin:5% 0 0 0;color:#FFF">{{$citiesPostsCounts['6765396']}}</h2>
						</div>
					</a>
				</div>
				<div class="col-sm home_image">
					<a href="{{ url('jobs/al-basrah/99532') }}">
						<img src="{{ url('images/site/basra.jpeg') }}" class="img-responsive home_city_image" style="width: 100%;
						height: 300px;">
						<div class="overlay">
							<h2 style="text-align:center;margin:40% 0 0 0;color:#FFF">{{t('basra')}}</h2>
							<h2 style="text-align:center;margin:5% 0 0 0;color:#FFF">{{$citiesPostsCounts['99532']}}</h2>
						</div>
					</a>
				</div>
			  </div>
		</div>

		@if (isset($categoriesOptions) and isset($categoriesOptions['type_of_display']))
		@include('home.inc.spacer')
		<div class="container-fluid mt-5 mt-md-0">
			<div class="col-xl-12 layout-section p-4">
				<div class="row row-featured row-featured-category justify-content-sm-center">
					
				<!-- Start first section -->
				<div class="col-xs-12 col-sm-12 <?=$latestJobsDesktopCol?> margin-separator content-box p-4 mr-4 mb-0">
						<div class="col-title mt-2">
							<a class="btn btn-link pull-right" href="/latest-jobs" role="button">
								{{t('Browse Jobs')}}
							</a>
							<h4>{{t('Latest Jobs')}}</h4>
						</div>
						<div class="col-xs-12">
								@if (isset($latest) and !empty($latest) and !empty($latest->posts))
								<ul class="list-group pull-right" style="max-height:350px;overflow-y:scroll">
								<?php
								foreach($latest->posts as $key => $post):
									
									// Get the Post's City
									$cacheId = config('country.code') . '.city.' . $post->city_id;
									$city = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post) {
										$city = \App\Models\City::find($post->city_id);
										return $city;
									});
									if (empty($city)) continue;
				
									// Get the Post's Type
									$cacheId = 'postType.' . $post->post_type_id . '.' . config('app.locale');
									$postType = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post) {
										$postType = \App\Models\PostType::findTrans($post->post_type_id);
										return $postType;
									});
									if (empty($postType)) continue;
									
									// Get the Post's Salary Type
									$cacheId = 'salaryType.' . $post->salary_type_id . '.' . config('app.locale');
									$salaryType = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post) {
										$salaryType = \App\Models\SalaryType::findTrans($post->salary_type_id);
										return $salaryType;
									});
									if (empty($salaryType)) continue;
			
									// Convert the created_at date to Carbon object
									$post->created_at = \Date::parse($post->created_at)->timezone(config('timezone.id'));
									$post->created_at = $post->created_at->ago();
									?>
									<li class="list-group-item">
										<h5 class="company-title ">
											@if (!empty($post->company_id))
												<?php $attr = ['countryCode' => config('country.icode'), 'id' => $post->company_id]; ?>
												<a href="{{ lurl(trans('routes.v-search-company', $attr), $attr) }}">
													{{ $post->company_name }}
												</a>
											@else
												<strong>{{ $post->company_name }}</strong>
											@endif
										</h5>
										<h4 class="job-title">
											<?php $attr = ['slug' => slugify($post->title), 'id' => $post->id]; ?>
											<a href="{{ lurl($post->uri, $attr) }}">
												{{ $post->title }}
											</a>
										</h4>
										<span class="info-row">
											<span class="date"><i class="icon-clock"></i> {{ $post->created_at }}</span>
											<span class="item-location">
												<i class="icon-location-2"></i>&nbsp;
												{{ $city->name }}
											</span>
											<span class="date"><i class="icon-clock"></i> {{ $postType->name }}</span>
											<span class="salary">
												<i class="icon-money"></i>&nbsp;
												@if ($post->salary_min > 0 or $post->salary_max > 0)
													@if ($post->salary_min > 0)
														{{$post->salary_min.' '.$post->salary_currency}}
														{{-- {!! \App\Helpers\Number::money($post->salary_min) !!} --}}
													@endif
													@if ($post->salary_max > 0)
														@if ($post->salary_min > 0)
															&nbsp;-&nbsp;
														@endif
														{{$post->salary_max.' '.$post->salary_currency}}
														{{-- {!! \App\Helpers\Number::money($post->salary_max) !!} --}}
													@endif
												@else
													{{'-- '.$post->salary_currency}}
													{{-- {!! \App\Helpers\Number::money('--') !!} --}}
												@endif
												@if (!empty($salaryType))
													{{ t('per') }} {{ $salaryType->name }}
												@endif
											</span>
										</span>
		
										{{-- <div class="jobs-desc">
											{!! \Illuminate\Support\Str::limit(strCleaner($post->description), 180) !!}
										</div> --}}
		
										{{-- <div class="job-actions">
											<ul class="list-unstyled list-inline">
												@if (auth()->check())
													@if (\App\Models\SavedPost::where('user_id', auth()->user()->id)->where('post_id', $post->id)->count() <= 0)
														<li id="{{ $post->id }}">
															<a class="save-job" id="save-{{ $post->id }}" href="javascript:void(0)">
																<span class="far fa-heart"></span>
																{{ t('Save Job') }}
															</a>
														</li>
													@else
														<li class="saved-job" id="{{ $post->id }}">
															<a class="saved-job" id="saved-{{ $post->id }}" href="javascript:void(0)">
																<span class="fa fa-heart"></span>
																{{ t('Saved Job') }}
															</a>
														</li>
													@endif
												@else
													<li id="{{ $post->id }}">
														<a class="save-job" id="save-{{ $post->id }}" href="javascript:void(0)">
															<span class="far fa-heart"></span>
															{{ t('Save Job') }}
														</a>
													</li>
												@endif
												<li>
													<a class="email-job" data-toggle="modal" data-id="{{ $post->id }}" href="#sendByEmail" id="email-{{ $post->id }}">
														<i class="fa fa-envelope"></i>
														{{ t('Email Job') }}
													</a>
												</li>
											</ul>
										</div> --}}
										</li>
										<?php endforeach; ?>
									</ul>
								@endif
							</div>
						</div>
						<!-- end first section -->
						<!-- Start second section -->
						<div class="col-xs-12 col-sm-12 <?=$jobsByDesktopCol?> margin-separator content-box p-4 mr-4 mb-0">
							<div class="col-title">
								<a class="btn btn-link pull-right" href="/latest-jobs" role="button">
									{{t('Browse Jobs')}}
								</a>
								<h4>{{t('Search Jobs by')}}</h4>
							</div>
	
							<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
									<li class="nav-item">
										<a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">{{t('City')}}</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">{{t('Job Role')}}</a>
									</li>
								</ul>
		
								<div class="tab-content" id="pills-tabContent">
									<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
										<?php
										
										// Default Map's values
										$loc = [
											'show'       		=> false,
											'itemsCols'  		=> 3,
											'showButton' 		=> false,
											'countCitiesPosts' 	=> false,
										];
										$map = ['show' => false];
										$leftClassCol = '';
										$rightClassCol = '';
										$ulCol = 'col-md-3 col-sm-12'; // Cities Columns
									
									// Get Admin Map's values
									if (isset($citiesOptions)) {
										if (isset($citiesOptions['show_cities']) and $citiesOptions['show_cities'] == '1') {
											$loc['show'] = true;
										}
										if (isset($citiesOptions['items_cols']) and !empty($citiesOptions['items_cols'])) {
											$loc['itemsCols'] = (int)$citiesOptions['items_cols'];
										}
										if (isset($citiesOptions['show_post_btn']) and $citiesOptions['show_post_btn'] == '1') {
											$loc['showButton'] = true;
										}
										
										if (file_exists(config('larapen.core.maps.path') . config('country.icode') . '.svg')) {
											if (isset($citiesOptions['show_map']) and $citiesOptions['show_map'] == '1') {
												$map['show'] = true;
											}
										}
										
										if (isset($citiesOptions['count_cities_posts']) and $citiesOptions['count_cities_posts'] == '1') {
											$loc['countCitiesPosts'] = true;
										}
									}
									?>
								@if ($loc['show'] || $map['show'])
								{{-- @include('home.inc.spacer') --}}
									<div class="col-xl-12 page-content p-0">	
											<div class="row">
												@if (!$map['show'])
													<div class="row">
														<div class="col-xl-12 col-sm-12">
															<h2 class="title-3 pt-1 pr-3 pb-3 pl-3" style="white-space: nowrap;">
																<i class="icon-location-2"></i>&nbsp;{{ t('Choose a city') }}
															</h2>
														</div>
													</div>
												@endif
												<?php
												
												
												if ($loc['show'] && $map['show']) {
													// Display the Cities & the Map
													$leftClassCol = 'col-lg-8 col-md-12';
													$rightClassCol = 'col-lg-3 col-md-12';
													$ulCol = 'col-md-4 col-sm-6 col-xs-12';
													
													if ($loc['itemsCols'] == 2) {
														$leftClassCol = 'col-md-6 col-sm-12';
														$rightClassCol = 'col-md-5 col-sm-12';
														$ulCol = 'col-md-6 col-sm-12';
													}
													if ($loc['itemsCols'] == 1) {
														$leftClassCol = 'col-md-3 col-sm-12';
														$rightClassCol = 'col-md-8 col-sm-12';
														$ulCol = 'col-xl-12';
													}
												} else {
													if ($loc['show'] && !$map['show']) {
														// Display the Cities & Hide the Map
														$leftClassCol = 'col-xl-12';
													}
													if (!$loc['show'] && $map['show']) {
														// Display the Map & Hide the Cities
														$rightClassCol = 'col-xl-12';
													}
												}
												?>
												@if ($loc['show'])
													@if (isset($cities))
														<?php //die($cities);?>
														{{-- <div class="relative location-content"> --}}
															<div class="col-xl-12 tab-inner">
																<div class="row">
																		<div class="col-sm-12 category-links" style="max-height:350px; overflow-y:scroll">
																		@foreach ($cities as $key => $items)
																			<ul class="list-group">
																				@foreach ($items as $k => $city)
																				@if ($city->id != 999999999)
																					@if ($loc['countCitiesPosts'] && !empty($city->posts->count()))
																						<li>
																							<?php $attr = ['countryCode' => config('country.icode'), 'city' => slugify($city->name), 'id' => $city->id]; ?>
																								<a href="{{ lurl(trans('routes.v-search-city', $attr), $attr) }}">
																									<?php $locale = config('app.locale'); ?>
																									@if($locale == 'en')
																										{{ $city->name }}
																									@else
																										{{ $city->ar_name }}
																									@endif
																								</a>
																								@if ($loc['countCitiesPosts'])
																									&nbsp;({{ $city->posts->count() }})
																								@endif
																						</li>
																					@endif
																				@endif
																				@endforeach
																			</ul>
																	
																		@endforeach
																	</div>
																	{{-- <div class="col-xl-6">
																		@include('layouts.inc.tools.svgmap')
																	</div> --}}
																</div>
															</div>
									
														{{-- </div> --}}
													@endif
												@endif
												
												
											</div>
		
									</div>
								@endif
									</div>
		
									<div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
										@if (isset($categoriesOptions) and isset($categoriesOptions['type_of_display']))
										@if ($categoriesOptions['type_of_display'] == 'c_picture_icon')
							
										@if (isset($categories) and $categories->count() > 0)
											@foreach($categories as $key => $cat)
												<div class="col-lg-2 col-md-3 col-sm-3 col-xs-4 f-category">
													<?php $attr = ['countryCode' => config('country.icode'), 'catSlug' => $cat->slug]; ?>
													<a href="{{ lurl(trans('routes.v-search-cat', $attr), $attr) }}">
														<img src="{{ \Storage::url($cat->picture) . getPictureVersion() }}" class="img-fluid" alt="{{ $cat->name }}">
														<h6>
															{{ $cat->name }}
															@if (isset($categoriesOptions['count_categories_posts']) and $categoriesOptions['count_categories_posts'])
																@if ($cat->children->count() > 0)
																	&nbsp;({{ $cat->posts->count() + $cat->childrenPosts->count() }})
																@else
																	&nbsp;({{ $cat->childrenPosts->count() }})
																@endif
															@endif
														</h6>
													</a>
												</div>
											@endforeach
										@endif
									
									@elseif (in_array($categoriesOptions['type_of_display'], ['cc_normal_list', 'cc_normal_list_s']))
										
										<div style="clear: both;"></div>
										<?php $styled = ($categoriesOptions['type_of_display'] == 'cc_normal_list_s') ? ' styled' : ''; ?>
										
										@if (isset($categories) and $categories->count() > 0)
											<div class="col-xl-12">
												<div class="list-categories-children{{ $styled }}">
													<div class="row">
														@foreach ($categories as $key => $cols)
															<div class="col-md-4 col-sm-4 {{ (count($categories) == $key+1) ? 'last-column' : '' }}">
																@foreach ($cols as $iCat)
																	
																	<?php
																		$randomId = '-' . substr(uniqid(rand(), true), 5, 5);
																	?>
																
																	<div class="cat-list">
																		<h3 class="cat-title rounded">
																			@if (isset($categoriesOptions['show_icon']) and $categoriesOptions['show_icon'] == 1)
																				<i class="{{ $iCat->icon_class ?? 'icon-ok' }}"></i>&nbsp;
																			@endif
																			<?php $attr = ['countryCode' => config('country.icode'), 'catSlug' => $iCat->slug]; ?>
																			<a href="{{ lurl(trans('routes.v-search-cat', $attr), $attr) }}">
																				{{ $iCat->name }}
																				@if (isset($categoriesOptions['count_categories_posts']) and $categoriesOptions['count_categories_posts'])
																					@if ($iCat->children->count() > 0)
																						&nbsp;({{ $iCat->posts->count() + $iCat->childrenPosts->count() }})
																					@else
																						&nbsp;({{ $iCat->childrenPosts->count() }})
																					@endif
																				@endif
																			</a>
																			<span class="btn-cat-collapsed collapsed"
																					data-toggle="collapse"
																					data-target=".cat-id-{{ $iCat->id . $randomId }}"
																					aria-expanded="false"
																			>
																				<span class="icon-down-open-big"></span>
																			</span>
																		</h3>
																		<ul class="cat-collapse collapse show cat-id-{{ $iCat->id . $randomId }} long-list-home">
																			@if (isset($subCategories) and $subCategories->has($iCat->tid))
																				@foreach ($subCategories->get($iCat->tid) as $iSubCat)
																					<li>
																						<?php $attr = ['countryCode' => config('country.icode'), 'catSlug' => $iCat->slug, 'subCatSlug' => $iSubCat->slug]; ?>
																						<a href="{{ lurl(trans('routes.v-search-subCat', $attr), $attr) }}">
																							{{ $iSubCat->name }}
																						</a>
																						@if (isset($categoriesOptions['count_categories_posts']) and $categoriesOptions['count_categories_posts'])
																							&nbsp;({{ $iSubCat->childrenPosts->count() }})
																						@endif
																					</li>
																				@endforeach
																			@endif
																		</ul>
																	</div>
																@endforeach
															</div>
														@endforeach
													</div>
												</div>
												<div style="clear: both;"></div>
											</div>
										@endif
									
									@else
										
										<?php
										$listTab = [
											'c_circle_list' => 'list-circle',
											'c_check_list'  => '',
											'c_border_list' => 'list-border',
										];
										$catListClass = (isset($listTab[$categoriesOptions['type_of_display']])) ? 'list-group' . $listTab[$categoriesOptions['type_of_display']] : 'list';
										?>
										@if (isset($cats) and $cats->count() > 0)
											<div class="col-xl-12 pt-5">
												<div class="list-categories">
													<div class="row">
														<div class="col-sm-12 category-links" style="max-height:350px; overflow-y:scroll">
															<ul class="{{ $catListClass }}">
															@if ($cats->groupBy('parent_id')->has(0))
																@foreach ($cats->groupBy('parent_id')->get(0) as $iCat)
																	<?php $attr = ['countryCode' => config('country.icode'), 'catSlug' => $iCat->slug]; ?>
																	@if(!empty($countCatPosts->get($iCat->tid)->total))
																		<li>
																			@if ((isset($uriPathCatSlug) and $uriPathCatSlug == $iCat->slug) or (request()->input('c') == $iCat->tid))
																				<strong>
																					<a href="{{ lurl(trans('routes.v-search-cat', $attr), $attr) }}" title="{{ $iCat->name }}">
																						<span class="title">{{ $iCat->name }}</span>
																						<span class="count">&nbsp;{{ $countCatPosts->get($iCat->tid)->total ?? 0 }}</span>
																					</a>
																				</strong>
																			@else
																				<a href="{{ lurl(trans('routes.v-search-cat', $attr), $attr) }}" title="{{ $iCat->name }}">
																					<span class="title">{{ $iCat->name }}</span>
																					<span class="count">&nbsp;{{ $countCatPosts->get($iCat->tid)->total ?? 0 }}</span>
																				</a>
																			@endif
																		</li>
																	@endif
																@endforeach
																@endif
															</ul>
														</div>
													</div>
												</div>
											</div>
										@endif
									@endif
								@endif
									</div>
								</div>
						</div>
						@if(!auth()->check())
							<div class="col-xs-12 col-md-3 pb-0 px-0 register-image">
								<a href="/register" class="home-reg-sec">{{t('Register Now')}}</a>
							</div>
						@endif
					</div>

					<!-- End second Section -->

				</div>
			</div>
		</div>
	@endif
	
	@section('before_scripts')
		@parent
		@if (isset($categoriesOptions) and isset($categoriesOptions['max_sub_cats']) and $categoriesOptions['max_sub_cats'] >= 0)
			<script>
				var maxSubCats = {{ (int)$categoriesOptions['max_sub_cats'] }};
			</script>
		@endif
	@endsection




		<?php $hiddenSections = ['home.inc.search', 'home.inc.categories', 'home.inc.locations', 'home.inc.latest'];?>
		@if (isset($sections) and $sections->count() > 0)
			@foreach($sections as $section)
				@if (view()->exists($section->view) && !in_array($section->view, $hiddenSections))
					@include($section->view, ['firstSection' => $loop->first])
				@endif
			@endforeach
		@endif
		
	</div>
@endsection

@section('after_scripts')
@endsection
