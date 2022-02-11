<div class="panel-group">
    <div class="card mb-3 col-xs-12 px-3 pt-4">
        <div class="row no-gutters">
            <div class="col-md-12">
                <div class="card-body pb-1">
                    <div class="row">
                        <div class="col-xs-8 col-md-8">
                            <h4 class="card-title">{{t('Personal Information')}}</h4>
                        </div>
                        @if (!isset($isEmployerView) && empty($isEmployerView))
                            <div class="col-xs-4 col-md-4">
                                <!-- Profile Sammury Modal -->
                                <!-- Button trigger modal -->
                                <a href="{{ route('account.editPersonalInfo')}}">
                                    <i class="fas fa-edit fa-2x float-right"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <p>{{t('Add your personal details')}}</p>
                        </div>
                    </div>
                    <dl class="row">
                        <dt class="col-sm-4">{{ t('Name') }}</dt>
                        <dd class="col-sm-8">{{$user->name}}</dd>

                        <dt class="col-sm-4">{{ t('Birth Date') }}</dt>
                        <dd class="col-sm-8">{{$user->birthday}}</dd>

                        <dt class="col-sm-4">{{ t('Gender') }}</dt>
                        <dd class="col-sm-8">{{$user->gender_id==1?t('Male'):t('Female')}}</dd>

                        <dt class="col-sm-4">{{ t('Nationality') }}</dt>
                        <dd class="col-sm-8">{{($user->nationality)?t($user->nationality, [], 'residenceCountry'):'--'}}</dd>

                        <dt class="col-sm-4">{{ t('Resident Country') }}</dt>
                        <dd class="col-sm-8">{{($user->residence_country)?t($user->residence_country,[],'residenceCountry'):'--'}}</dd>

                        <dt class="col-sm-4">{{ t('Matial Status') }}</dt>
                        <dd class="col-sm-8">{{ucfirst($user->martial_status)}}</dd>

                        <dt class="col-sm-4">{{ t('Number of dependents') }}</dt>
                        <dd class="col-sm-8">{{$user->num_dependents}}</dd>

                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>