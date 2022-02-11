<div class="panel-group">
    <div class="card mb-3 col-xs-12 px-3 pt-4">
        <div class="row no-gutters">
            <div class="col-md-12">
                <div class="card-body pb-1">
                    <div class="row">
                        <div class="col-xs-8 col-md-8">
                            <h4 class="card-title">{{t('Languages')}}</h4>
                        </div>
                        @if (!isset($isEmployerView) && empty($isEmployerView))
                            <div class="col-xs-4 col-md-4">
                                <!-- Profile Sammury Modal -->
                                <!-- Button trigger modal -->
                                <a href="{{route('account.createUserLang')}}">
                                    <i class="fas fa-plus fa-2x float-right"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                    <!-- Skills -->
                    <dl class="row">
                        @if (!empty($userLanguages))
                            @foreach ($userLanguages as $lang)
                                <dt class="col-sm-4">{{t($lang->language, [], 'languages')}}</dt>
                                <dd class="col-sm-8">
                                    {{t('Level')}}: {{t($lang->lang_level, [], 'langLevels')}}
                                    @if (!isset($isEmployerView) && empty($isEmployerView))
                                        <a href="{{route('account.deleteUserLang', ['id'=>$lang->id])}}"><i class="fas fa-times fa-2x float-right"></i></a>
                                        <a href="{{route('account.editUserLang', ['id'=>$lang->id])}}"><i class="fas fa-edit fa-2x float-right mr-2"></i></a>
                                    @endif
                                </dd>
                            @endforeach
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>