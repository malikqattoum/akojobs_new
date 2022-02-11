<div class="panel-group">
    <div class="card mb-3 col-xs-12 px-3 pt-4">
        @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
        </div>
    @endif
        <div class="row no-gutters">
            <div class="col-md-4">
                @if(!isset($isEmployerView) && empty($isEmployerView))
                    <a href="{{ route('account.editProfileHead')}}">
                        @if (!empty($userPhoto))
                            <img class="card-img profile-image" src="{{ (substr($userPhoto, 0, 1) != '/')?'/'.$userPhoto:$userPhoto }}" alt="user">&nbsp;
                        @elseif (!empty(Session::get('image')))
                            <img class="card-img profile-image" src="{{ Session::get('image') }}" alt="user">
                        @else
                            <img class="card-img profile-image" src="{{ url('images/user.jpg') }}" alt="user">
                        @endif
                    </a>
                @else
                    @if (!empty($userPhoto))
                        <img class="card-img profile-image" src="{{ (substr($userPhoto, 0, 1) != '/')?'/'.$userPhoto:$userPhoto }}" alt="user">&nbsp;
                    @elseif (!empty(Session::get('image')))
                        <img class="card-img profile-image" src="{{ Session::get('image') }}" alt="user">
                    @else
                        <img class="card-img profile-image" src="{{ url('images/user.jpg') }}" alt="user">
                    @endif
                @endif
            </div>
            <div class="col-md-8">
                <div class="card-body pb-1">
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <h4 class="card-title">{{$user->name}}</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <p class="mb-1">{{$user->current_job_title}}</p>
                            <?php $companyName = '';?>
                            @if (!empty($userExperiences))
                                @foreach ($userExperiences as $exp)
                                    <?php $companyName = $exp->company_name; break;  ?>
                                @endforeach
                            @endif
                            <p class="text-muted">{{($companyName)?$companyName:'--'}}</p>
                        </div>
                    </div>
                    <dl class="row">
                        <?php 
                            $residenceCountry = include(base_path() . '/resources/lang/en/residenceCountry.php');
                            asort($residenceCountry); 
                            $residenceCountryKeys = array_keys($residenceCountry);
                        ?>
                        <dt class="col-sm-4">{{ t('Location') }}</dt>
                        <dd class="col-sm-8">
                            {{($user->residence_country)?t($user->residence_country,[],'residenceCountry'):'--'}}
                        </dd>

                        <dt class="col-sm-4">{{ t('Education') }}</dt>
                        <?php $userEducInstitutions = [];?>
                        @if (!empty($userEducations))
                            @foreach ($userEducations as $edu)
                                <?php $userEducInstitutions[] = $edu->edu_institution ?>
                            @endforeach
                        @endif
                        <dd class="col-sm-8">{{($userEducInstitutions)?implode(", ", $userEducInstitutions):'--'}}</dd>

                        <?php
                            $experience = include(base_path() . '/resources/lang/en/experience.php'); 
                        ?>
                        <dt class="col-sm-4">{{ t('Experience') }}</dt>
                        <dd class="col-sm-8">
                            {{$user->total_exp}}
                        </dd>

                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>