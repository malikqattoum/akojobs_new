<div class="panel-group">
    <div class="card mb-3 col-xs-12 px-3 pt-4">
        <div class="row no-gutters">
            <div class="col-md-12">
                <div class="card-body pb-1">
                    <div class="row">
                        <div class="col-xs-8 col-md-8">
                            <h4 class="card-title">{{t('Education')}}</h4>
                        </div>
                        @if (!isset($isEmployerView) && empty($isEmployerView))
                            <div class="col-xs-4 col-md-4">
                                <!-- Profile Sammury Modal -->
                                <!-- Button trigger modal -->
                                <a href="{{route('account.createUserEducation')}}">
                                    <i class="fas fa-plus fa-2x float-right"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                    <!-- First Education -->
                    @if (!empty($userEducations))
                        @foreach ($userEducations as $edu)
                            <div>
                                <h5>
                                    {{t($edu->edu_degree, [], 'degreesList')}}
                                    @if (!isset($isEmployerView) && empty($isEmployerView))
                                        <a href="{{route('account.deleteUserEducation', ['id'=>$edu->id])}}"><i class="fas fa-times fa-2x float-right"></i></a>
                                        <a href="{{route('account.editUserEducation', ['id'=>$edu->id])}}"><i class="fas fa-edit fa-2x float-right mr-2"></i></a>
                                    @endif
                                </h5>
                                <p>{{$edu->edu_institution}}</p>
                                @if (!empty($edu->on_going))
                                    <p class="text-muted">{{t('Still On Going')}}</p>
                                @else
                                    <p class="text-muted">{{date('Y-m-d', strtotime($edu->edu_grad_date))}}</p>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>