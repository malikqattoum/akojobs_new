<aside>
	<div class="inner-box">
		<div class="user-panel-sidebar">
            
            @if (isset($user))
                <div class="collapse-box">
                    <h5 class="collapse-title no-border">
                        {{ t('My Account') }}&nbsp;
                        <a href="#MyClassified" data-toggle="collapse" class="pull-right"><i class="fa fa-angle-down"></i></a>
                    </h5>
                    <div class="panel-collapse collapse show" id="MyClassified">
                        <ul class="acc-list">
                            <li>
                                <a {!! ($pagePath=='') ? 'class="active"' : '' !!} href="{{ lurl('account') }}">
                                    <i class="icon-home"></i> {{ t('Personal Home') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- /.collapse-box  -->
                
                @if (!empty($user->user_type_id) and $user->user_type_id != 0)
                    <div class="collapse-box">
                        <h5 class="collapse-title">
                            {{ t('My Ads') }}&nbsp;
                            <a href="#MyAds" data-toggle="collapse" class="pull-right"><i class="fa fa-angle-down"></i></a>
                        </h5>
                        <div class="panel-collapse collapse show" id="MyAds">
                            <ul class="acc-list">
                                <!-- COMPANY -->
                                @if (in_array($user->user_type_id, [1]))
                                    <li>
                                        <a href="{{ url(config('app.locale') . '/posts/create') }}" target="_blank">
                                            <i class="icon-docs"></i> {{ trans('admin::messages.Post New Ad') }}&nbsp;
                                        </a>
                                    </li>
                                    {{-- <li>
                                        <a href="{{ url(config('app.locale') . '/posts/posting-plans') }}" target="_blank">
                                            <i class="icon-docs"></i> {{t('Posting plans')}}&nbsp;
                                        </a>
                                    </li> --}}
                                    <li>
                                        <a {!! ($pagePath=='companies') ? ' class="active"' : '' !!} href="{{ lurl('account/companies') }}">
                                        <i class="icon-town-hall"></i> {{ t('My companies') }}&nbsp;
                                        <span class="badge badge-pill">
                                            {{ isset($countCompanies) ? \App\Helpers\Number::short($countCompanies) : 0 }}
                                        </span>
                                        </a>
                                    </li>
                                    @if(isset($isAdminPermission) && $isAdminPermission)
                                        <li>
                                            <a {!! (getSegment(2) == "allocate-to-employers") ? ' class="active"' : '' !!} href="{{ lurl('account/allocate-to-employers') }}">
                                            <i class="icon-town-hall"></i> {{ t('allocate candidates to employers') }}&nbsp;
                                            </a>
                                        </li>
                                    @endif
                                    <li>
                                        <a {!! (getSegment(2) == "my-sent-emails") ? ' class="active"' : '' !!} href="{{ lurl('account/my-sent-emails') }}">
                                            <i class="fas fa-envelope-open"></i> {{ t('My sent emails') }}&nbsp;
                                            <span class="badge badge-pill">
                                                {{ isset($myTrackedEmailsCount) ? \App\Helpers\Number::short($myTrackedEmailsCount) : 0 }}
                                            </span>
                                        </a>
                                    </li>
									<li>
										<a{!! ($pagePath=='my-posts') ? ' class="active"' : '' !!} href="{{ lurl('account/my-posts') }}">
										<i class="icon-docs"></i> {{ t('My ads') }}&nbsp;
										<span class="badge badge-pill">
											{{ isset($countMyPosts) ? \App\Helpers\Number::short($countMyPosts) : 0 }}
										</span>
										</a>
									</li>
                                    <li>
										<a{!! (getSegment(2) == "my-people") ? ' class="active"' : '' !!} href="{{ lurl('account/my-people') }}">
										    <i class="icon-users"></i> {{ t('My people') }}&nbsp;
										</a>
									</li>
<!-- 									<li> -->
<!-- 										<a{!! (getSegment(2) == "company-invitation") ? ' class="active"' : '' !!} href="{{ lurl('account/company-invitation') }}"> -->
<!-- 										    <i class="icon-users"></i> {{ t('add users') }}&nbsp; -->
<!-- 										</a> -->
<!-- 									</li> -->
                                    <li>
                                        <a{!! ($pagePath=='pending-approval') ? ' class="active"' : '' !!} href="{{ lurl('account/pending-approval') }}">
                                        <i class="icon-hourglass"></i> {{ t('Pending approval') }}&nbsp;
                                        <span class="badge badge-pill">
											{{ isset($countPendingPosts) ? \App\Helpers\Number::short($countPendingPosts) : 0 }}
										</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a{!! ($pagePath=='archived') ? ' class="active"' : '' !!} href="{{ lurl('account/archived') }}">
                                        <i class="icon-folder-close"></i> {{ t('Archived ads') }}&nbsp;
                                        <span class="badge badge-pill">
											{{ isset($countArchivedPosts) ? \App\Helpers\Number::short($countArchivedPosts) : 0 }}
										</span>
                                        </a>
                                    </li>
                                    {{-- <li>
                                        <a{!! ($pagePath=='conversations') ? ' class="active"' : '' !!} href="{{ lurl('account/conversations') }}">
                                        <i class="icon-mail-1"></i> {{ t('Conversations') }}&nbsp;
										<span class="badge badge-pill">
												{{ isset($countConversations) ? \App\Helpers\Number::short($countConversations) : 0 }}
											</span>&nbsp;
										<span class="badge badge-pill badge-important count-conversations-with-new-messages">0</span>
                                        </a>
                                    </li> --}}
                                    {{-- <li>
                                        <a{!! ($pagePath=='transactions') ? ' class="active"' : '' !!} href="{{ lurl('account/transactions') }}">
                                        <i class="icon-money"></i> {{ t('Transactions') }}&nbsp;
                                        <span class="badge badge-pill">
											{{ isset($countTransactions) ? \App\Helpers\Number::short($countTransactions) : 0 }}
										</span>
                                        </a>
                                    </li> --}}
                                @endif
								<!-- CANDIDATE -->
                                @if (in_array($user->user_type_id, [2]))
									<li>
										<a{!! ($pagePath=='resumes') ? ' class="active"' : '' !!} href="{{ lurl('account/resumes') }}">
										<i class="icon-attach"></i> {{ t('My resumes') }}&nbsp;
										<span class="badge badge-pill">
											{{ isset($countResumes) ? \App\Helpers\Number::short($countResumes) : 0 }}
										</span>
										</a>
									</li>
                                    <li>
                                        <a{!! ($pagePath=='favourite') ? ' class="active"' : '' !!} href="{{ lurl('account/favourite') }}">
                                        <i class="icon-heart"></i> {{ t('Favourite jobs') }}&nbsp;
                                        <span class="badge badge-pill">
											{{ isset($countFavouritePosts) ? \App\Helpers\Number::short($countFavouritePosts) : 0 }}
										</span>
                                        </a>
                                    </li>
                                    {{-- <li>
                                        <a{!! ($pagePath=='saved-search') ? ' class="active"' : '' !!} href="{{ lurl('account/saved-search') }}">
                                        <i class="icon-star-circled"></i> {{ t('Saved searches') }}&nbsp;
                                        <span class="badge badge-pill">
											{{ isset($countSavedSearch) ? \App\Helpers\Number::short($countSavedSearch) : 0 }}
										</span>
                                        </a>
                                    </li> --}}
									<li>
										<a{!! ($pagePath=='conversations') ? ' class="active"' : '' !!} href="{{ lurl('account/conversations') }}">
										<i class="icon-mail-1"></i> {{ t('Conversations') }}&nbsp;
										<span class="badge badge-pill">
											{{ isset($countConversations) ? \App\Helpers\Number::short($countConversations) : 0 }}
										</span>&nbsp;
										<span class="badge badge-important count-conversations-with-new-messages">0</span>
										</a>
									</li>
                                @endif
								@if (config('plugins.apijc.installed'))
									<li>
										<a{!! ($pagePath=='api-dashboard') ? ' class="active"' : '' !!} href="{{ lurl('account/api-dashboard') }}">
										<i class="icon-cog"></i> {{ trans('api::messages.Clients & Applications') }}&nbsp;
										</a>
									</li>
								@endif
                            </ul>
                        </div>
                    </div>
                    <!-- /.collapse-box  -->
                
                    <div class="collapse-box">
                        <h5 class="collapse-title">
                            {{ t('Deactivate Account') }}&nbsp;
                            <a href="#TerminateAccount" data-toggle="collapse" class="pull-right"><i class="fa fa-angle-down"></i></a>
                        </h5>
                        <div class="panel-collapse collapse show" id="TerminateAccount">
                            <ul class="acc-list">
                                <li>
                                    {{t('if you want to close account')}}
                                    {{-- <a {!! ($pagePath=='close') ? 'class="active"' : '' !!} href="{{ lurl('account/close') }}">
                                        <i class="icon-cancel-circled "></i> {{ t('Close account') }}
                                    </a> --}}
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- /.collapse-box  -->
                @endif
            @endif

		</div>
	</div>
	<!-- /.inner-box  -->

    @if (in_array($user->user_type_id, [1]) && (in_array(getSegment(2),["applicants", "my-people", "allocate-to-employers"]) || getSegment(4) == "applicants"))
        <h4 class="mt-3" style="color: #0B2271">{{t('Filter by')}}:</h4>
        <form method="get" id="search_form">
            <div id="accordion">
                @if(getSegment(2) != "allocate-to-employers")
                    <div class="card">
                        <div class="card-header" id="headingEight">
                        <h5 class="pb-0">
                            <a class="btn" data-toggle="collapse" data-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                                {{ t('note') }}
                            </a>
                        </h5>
                        </div>
                        <div id="collapseEight" class="collapse" aria-labelledby="headingEight" data-parent="#accordion">
                            <div class="card-body">
                                <form>
                                        <input class="form-control sort_range note" type="text" name="note_filter" value="<?=(isset($filterData['note_keyword']))?$filterData['note_keyword']:''?>" id="note_filter">
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingSeven">
                        <h5 class="pb-0">
                            <a class="btn" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                {{ t('rating') }}
                            </a>
                        </h5>
                        </div>

                        <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordion">
                            <div class="card-body">
                                <form>
                                    @for ($i = 1; $i <= 5; $i++)                                   
                                        <label class="form-check">
                                            <input class="form-check-input sort_range rating" type="checkbox" name="rating[]" value="{{$i}}" <?=(isset($filterData['rating']) && !empty($filterData['rating']) && in_array($i, $filterData['rating']))?'checked':''?>>
                                            <span class="form-check-label">
                                                @if ($i == 1)
                                                    {{$i." ".t('star')}}    
                                                @else
                                                    {{$i." ".t('stars')}}
                                                @endif
                                            </span>
                                        </label>
                                    @endfor
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="card">
                    <div class="card-header" id="headingOne">
                    <h5 class="pb-0">
                        <a class="btn" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            {{ t('Experience Years') }}
                        </a>
                    </h5>
                    </div>

                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            <form>
                                <?php $experience = include(base_path() . '/resources/lang/en/experience.php'); 
                                    $experienceKeys = array_keys($experience);
                                    $userExperienceError = (isset($errors) and $errors->has('userExperience')) ? ' is-invalid' : '';
                                ?>
                                @foreach ($experienceKeys as $item)
                                    <label class="form-check">
                                        <input class="form-check-input {{$userExperienceError}} sort_range exp" type="checkbox" name="userExperience[]" value="{{$item}}" <?=(isset($filterData['user_experience']) && !empty($filterData['user_experience']) && in_array($item, $filterData['user_experience']))?'checked':''?>>
                                        <span class="form-check-label">
                                            {{t($item,[],'experience')}}
                                        </span>
                                    </label>
                                @endforeach
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingTwo">
                    <h5 class="pb-0">
                        <a class="btn collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            {{ t('Education') }}
                        </a>
                    </h5>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="card-body">
                            <?php $degrees = include(base_path() . '/resources/lang/en/degreesList.php');
                                    asort($degrees); 
                                    $degreesKeys = array_keys($degrees);
                            ?>
                            @foreach ($degreesKeys as $item)
                                <label class="form-check">
                                    <input class="form-check-input sort_range degree" type="checkbox"  name="edu_degree[]" value="{{$item}}" <?=(isset($filterData['edu_degree']) && !empty($filterData['edu_degree']) && in_array($item, $filterData['edu_degree']) )?'checked':''?>>
                                    <span class="form-check-label">
                                        {{t($item,[],'degreesList')}}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingThree">
                    <h5 class="pb-0">
                        <a class="btn collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            {{ t('Main Job Role') }}
                        </a>
                    </h5>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                        <div class="card-body">
                            <?php $roles = include(base_path() . '/resources/lang/en/roles.php'); 
                                    asort($roles);
                                    $rolesKeys = array_keys($roles);
                                    $regJobRoleError = (isset($errors) and $errors->has('regJobRole')) ? ' is-invalid' : '';
                                ?>
                                @foreach ($rolesKeys as $item)
                                    <label class="form-check">
                                        <input class="form-check-input sort_range role" type="checkbox" name="regJobRole[]" value="{{$item}}" <?=(isset($filterData['job_role']) && !empty($filterData['job_role']) && in_array($item, $filterData['job_role']) )?'checked':''?>>
                                        <span class="form-check-label">
                                            {{t($item,[],'roles')}}
                                        </span>
                                    </label>
                                @endforeach
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" id="headingFour">
                    <h5 class="pb-0">
                        <a class="btn collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            {{ t('residence country') }}
                        </a>
                    </h5>
                    </div>
                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                        <div class="card-body">
                            <?php $residenceCountry = include(base_path() . '/resources/lang/en/residenceCountry.php');
                                asort($residenceCountry); 
                                $residenceCountryKeys = array_keys($residenceCountry);
                            ?>
                            @foreach ($residenceCountryKeys as $item)
                                <label class="form-check">
                                    <input class="form-check-input sort_range country" type="checkbox"  name="residence_country[]" value="{{$item}}" <?=(isset($filterData['residence_country']) && !empty($filterData['residence_country']) && in_array($item, $filterData['residence_country']) )?'checked':''?>>
                                    <span class="form-check-label">
                                        {{t($item,[],'residenceCountry')}}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" id="headingSix">
                    <h5 class="pb-0">
                        <a class="btn collapsed" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                            {{ t('City') }}
                        </a>
                    </h5>
                    </div>
                    <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion">
                        <div class="card-body">
                            @foreach ($cities as $city)
                                <label class="form-check">
                                    <input class="form-check-input sort_range city" type="checkbox" name="city_id[]" value="{{ $city->id }}" <?=(isset($filterData['city_id']) && !empty($filterData['city_id']) && in_array($city->id, $filterData['city_id']) )?'checked':''?>>
                                    <span class="form-check-label">
                                        {{(strpos(url()->current(), '/ar') !== false)?$city->ar_name:$city->name}}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" id="headingFive">
                    <h5 class="pb-0">
                        <a class="btn collapsed" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            {{t('Age')}}
                        </a>
                    </h5>
                    </div>
                    <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
                        <div class="card-body">
                            <?php $ageList = include(base_path() . '/resources/lang/en/ageList.php');
                                asort($ageList); 
                                $ageKeys = array_keys($ageList);
                            ?>
                            @foreach ($ageKeys as $item)
                                <label class="form-check">
                                    <input class="form-check-input sort_range age" type="checkbox"  name="age[]" value="{{$item}}" <?=(isset($filterData['birthday']) && !empty($filterData['birthday']) && in_array($item, $filterData['birthday']) )?'checked':''?>>
                                    <span class="form-check-label">
                                        {{t($item,[],'ageList')}}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endif
</aside>