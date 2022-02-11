<div class="panel-group">
    <div class="card mb-3 col-xs-12 px-3 pt-4">
        <div class="row no-gutters">
            <div class="col-md-12">
                <div class="card-body pb-1">
                    <div class="row">
                        <div class="col-xs-8 col-md-8">
                            <h4 class="card-title">{{t('Contact Information')}}</h4>
                        </div>
                        @if (!isset($isEmployerView) && empty($isEmployerView))
                            <div class="col-xs-4 col-md-4">
                                <!-- Profile Sammury Modal -->
                                <!-- Button trigger modal -->
                                <a href="{{route('account.editContactInfo')}}">
                                    <i class="fas fa-edit fa-2x float-right"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <p>{{t('contact section caption')}}</p>
                        </div>
                    </div>
                    <dl class="row">
                        <dt class="col-sm-4">{{ t('Email Address') }}</dt>
                        <dd class="col-sm-8">{{$user->email}}</dd>

                        <dt class="col-sm-4">{{ t('Mobile Number') }}</dt>
                        <dd class="col-sm-8">{{$user->phone}}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>