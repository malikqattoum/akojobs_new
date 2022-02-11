<div class="panel-group">
    <div class="card mb-3 col-xs-12 px-3 pt-4">
        <div class="row no-gutters">
            <div class="col-md-12">
                <div class="card-body pb-1">
                    <div class="row">
                        <div class="col-xs-8 col-md-8">
                            <h4 class="card-title">{{t('Training and Certifications')}}</h4>
                        </div>
                        @if (!isset($isEmployerView) && empty($isEmployerView))
                            <div class="col-xs-4 col-md-4">
                                <!-- Profile Sammury Modal -->
                                <!-- Button trigger modal -->
                                <a href="{{route('account.createUserTraining')}}">
                                    <i class="fas fa-plus fa-2x float-right"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                    @if (!empty($userTrainings))
                        @foreach ($userTrainings as $training)
                            <div>
                                <div class="row">
                                    <div class="col-xs-8 col-md-8">
                                        <h5 class="card-title">{{$training->training_name}}</h5>
                                    </div>
                                    <div class="col-xs-4 col-md-4">
                                        <!-- Profile Sammury Modal -->
                                        <!-- Button trigger modal -->
                                        @if (!isset($isEmployerView) && empty($isEmployerView))
                                            <a href="{{route('account.deleteUserTraining', ['id'=>$training->id])}}"><i class="fas fa-times fa-2x float-right"></i></a>
                                            <a href="{{route('account.editUserTraining', ['id'=>$training->id])}}"><i class="fas fa-edit fa-2x float-right mr-2"></i></a>
                                        @endif
                                    </div>
                                </div>
                                <!-- Training -->
                                <dl class="row">
                                    <dt class="col-sm-4">{{t('Institution')}}:</dt>
                                    <dd class="col-sm-8">
                                        {{$training->training_institution}}
                                    </dd>
                                    <dt class="col-sm-4">{{t('Date of completion')}}:</dt>
                                    <dd class="col-sm-8">
                                        {{date('Y-m-d',strtotime($training->training_completion))}}
                                    </dd>
                                </dl>
                                {{-- <div class="col-xs-12 col-md-12">
                                    <a href="">
                                        <img src="https://www.gravatar.com/avatar/9ed4f76c2ca37de54d946a68f596c7ff.jpg?s=80&d=mm&r=g" alt="">
                                    </a>
                                </div> --}}
                            </div>
                        @endforeach                        
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>