<div class="panel-group">
    <div class="card mb-3 col-xs-12 px-3 pt-4">
        <div class="row no-gutters">
            <div class="col-md-12">
                <div class="card-body pb-1">
                    <div class="row">
                        <div class="col-xs-8 col-md-8">
                            <h4 class="card-title">{{t('References')}}</h4>
                        </div>
                        @if (!isset($isEmployerView) && empty($isEmployerView))
                            <div class="col-xs-4 col-md-4">
                                <!-- Profile Sammury Modal -->
                                <!-- Button trigger modal -->
                                <a href="{{route('account.createUserReference')}}">
                                    <i class="fas fa-plus fa-2x float-right"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                    @if (!empty($userReferences))
                        @foreach ($userReferences as $ref)
                            <div>
                                <dl class="row">
                                    <dt class="col-sm-4"><b>{{$ref->ref_name}}</b></dt>
                                    <dd class="col-sm-8">
                                        @if (!isset($isEmployerView) && empty($isEmployerView))
                                            <a href="{{route('account.deleteUserReference', ['id'=>$ref->id])}}"><i class="fas fa-times fa-2x float-right"></i></a>
                                            <a href="{{route('account.editUserReference', ['id'=>$ref->id])}}"><i class="fas fa-edit fa-2x float-right mr-2"></i></a>
                                        @endif
                                    </dd>
                                </dl>
                                <p>{{$ref->ref_position}}</p>
                                <p class="mb-1">{{$ref->ref_company}}</p>
                                <p class="mb-1">{{$ref->ref_phone}}</p>
                                <p>{{$ref->ref_email}}</p>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>