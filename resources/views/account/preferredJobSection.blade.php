<div class="panel-group">
    <div class="card mb-3 col-xs-12 px-3 pt-4">
        <div class="row no-gutters">
            <div class="col-md-12">
                <div class="card-body pb-1">
                    <div class="row">
                        <div class="col-xs-8 col-md-8">
                            <h4 class="card-title">{{t('Preferred Job')}}</h4>
                        </div>
                        @if (!isset($isEmployerView) && empty($isEmployerView))
                            <div class="col-xs-4 col-md-4">
                                <!-- Profile Sammury Modal -->
                                <!-- Button trigger modal -->
                                <a href="{{route('account.editPreferredJob')}}">
                                    <i class="fas fa-edit fa-2x float-right"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                    <dl class="row">
                        <dt class="col-sm-4">{{ t('Job Titles') }}</dt>
                        <dd class="col-sm-8">{{$user->preferred_job_title}}</dd>

                        <dt class="col-sm-4">{{ t('job location') }}</dt>
                        <dd class="col-sm-8">{{($user->trgt_job_location)?t($user->trgt_job_location, [], 'residenceCountry'):'--'}} {{!empty($user->trgt_city) && !empty($user->trgt_job_location)?','.$user->trgt_city:''}}</dd>

                        <dt class="col-sm-4">{{ t('Target Monthly Salary') }}</dt>
                        <dd class="col-sm-8">{{$user->trgt_month_sal}}</dd>

                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>