<div class="panel-group">
    <div class="card mb-3 col-xs-12 px-3 pt-4">
        <div class="row no-gutters">
            <div class="col-md-12">
                <div class="card-body pb-1">
                    <div class="row">
                        <div class="col-xs-8 col-md-8">
                            <h4 class="card-title">{{t('video')}}</h4>
                        </div>
                        @if (!isset($isEmployerView) && empty($isEmployerView) && $userVideo->count() == 0)
                            <div class="col-xs-4 col-md-4">
                                <!-- Profile Sammury Modal -->
                                <!-- Button trigger modal -->
                                <a href="{{route('account.createVideo')}}">
                                    <i class="fas fa-plus fa-2x float-right"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                    @if (!empty($userVideo))
                        @foreach ($userVideo as $video)
                            <div>
                                <dl class="row">
                                    <dt class="col-sm-4"><b>{{$video->ref_name}}</b></dt>
                                    <dd class="col-sm-8">
                                        @if (!isset($isEmployerView) && empty($isEmployerView))
                                            <a href="{{route('account.deleteVideo', ['id'=>$video->id])}}"><i class="fas fa-times fa-2x float-right"></i></a>
                                            <a href="{{route('account.editVideo', ['id'=>$video->id])}}"><i class="fas fa-edit fa-2x float-right mr-2"></i></a>
                                        @endif
                                    </dd>
                                </dl>
                                <p>
                                    <video width="400" controls>
                                        <source src="{{lurl('uploads/videos/'.$video->video)}}" type="video/mp4">
                                        <source src="{{lurl('uploads/videos/'.$video->video)}}" type="video/ogg">
                                        Your browser does not support HTML video.
                                    </video>
                                </p>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>