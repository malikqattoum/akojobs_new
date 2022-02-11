<div class="panel-group">
    <div class="card mb-3 col-xs-12 px-3 pt-4">
        <div class="row no-gutters">
            <div class="col-md-12">
                <div class="card-body pb-1">
                    <div class="row">
                        <div class="col-xs-8 col-md-8">
                            <h4 class="card-title">{{t('Work Experience')}}</h4>
                        </div>
                        @if (!isset($isEmployerView) && empty($isEmployerView))
                            <div class="col-xs-4 col-md-4">
                                <!-- Profile Sammury Modal -->
                                <!-- Button trigger modal -->
                                <a href="{{route('account.createUserExperience')}}">
                                    <i class="fas fa-plus fa-2x float-right"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                    <div class="row mb-2">
                        <div class="col-xs-12 col-md-12">
                            @if (!empty($user->total_exp))
                                <p class="mb-1"><?=sprintf(t('you have added x experience'),$user->total_exp)?></p>
                                <p>
                                    <?=sprintf(t('total years of experience'),$user->total_exp)?>
                                    @if (!isset($isEmployerView) && empty($isEmployerView))
                                        <a href="{{route('account.editTotalExperience')}}"><i class="fas fa-edit fa-2x float-right"></i></a>
                                    @endif
                                </p>
                            @elseif(!isset($isEmployerView) && empty($isEmployerView))
                                <p>
                                    <b><?=t('update your total experience')?></b>
                                    <a href="{{route('account.editTotalExperience')}}"><i class="fas fa-edit fa-2x float-right"></i></a>
                                </p>
                            @endif
                        </div>
                    </div>
                    <!-- First Experience -->
                    @if (!empty($userExperiences))
                        @foreach ($userExperiences as $exp)
                            <div>
                                <h5>
                                    {{$exp->exp_title}}
                                    @if(!isset($isEmployerView) && empty($isEmployerView))
                                        <a href="{{route('account.deleteUserExperience', ['id'=>$exp->id])}}"><i class="fas fa-times fa-2x float-right"></i></a>
                                        <a href="{{route('account.editUserExperience',  ['id'=>$exp->id]) }}"><i class="fas fa-edit fa-2x float-right mr-2"></i></a>
                                    @endif
                                </h5>
                                <p>{{$exp->company_name}}</p>
                                <p class="mb-1 text-muted">{{t($exp->exp_country, [], 'residenceCountry')}}</p>
                                <p class="text-muted">{{t('from')}} {{date('Y-m-d', strtotime($exp->from_date))}} {{t('to')}} {{(!empty($exp->to_date))?date('Y-m-d', strtotime($exp->to_date)):t('present')}}</p>
                                <p>{{$exp->exp_tasks}}</p>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>