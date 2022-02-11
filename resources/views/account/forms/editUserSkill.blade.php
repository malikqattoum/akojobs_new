@extends('layouts.master')

@section('content')
@include('common.spacer')
<div class="main-container">
    <div class="container">
        <div class="row">
            <div class="col-md-3 page-sidebar">
                @include('account.inc.sidebar')
            </div>
            <!--/.page-sidebar-->

            <div class="col-md-9 page-content">

                @include('flash::message')

                @if (isset($errors) and $errors->any())
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><strong>{{ t('Oops ! An error has occurred. Please correct the red fields in the form') }}</strong>
                    </h5>
                    <ul class="list list-check">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <style>
                    .uper {
                        margin-top: 40px;
                    }
                </style>
                <div class="card uper">
                    <div class="card-header">
                        {{t('Update skill')}}
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('account.updateUserSkill', ['id'=>$userSkill->id]) }}"
                            enctype="multipart/form-data">
                            @method('PUT')
                            @csrf
                            <?php $skillLevels = include(base_path() . '/resources/lang/en/skillLevels.php');
                                asort($skillLevels); 
                                $levelsKeys = array_keys($skillLevels);
                            ?>
                            <div class="form-group required">
                                <label for="skill_name">{{t('Skill')}} <sup>*</sup></label>
                                <input type='text' placeholder="{{t('Skill')}}" value="{{ old('skill_name', $userSkill->skill_name) }}" name="skill_name" class="form-control" />
                            </div>
                            <div class="form-group required">
                                <label for="skill_level">{{t('Level')}} <sup>*</sup></label>
                                <select class="form-control" name="skill_level" id="skill_level" placeholder="{{ t('Select') }}"
                                      value="{{ old('skill_level') }}">
                                    <option value="" selected="">{{ t('Select') }}</option>
                                    @foreach ($levelsKeys as $item)
                                        <option value="{{$item}}" {{ (old("skill_level", $userSkill->skill_level) == $item ? "selected":"") }} >{{t($item,[],'skillLevels')}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">{{t('Done')}}</button>
                        </form>
                    </div>
                </div>

            </div>
            <!--/.page-content-->
        </div>
        <!--/.row-->
    </div>
    <!--/.container-->
</div>
<!-- /.main-container -->
@endsection

@section('after_styles')
<link href="{{ url('assets/plugins/bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet">
@if (config('lang.direction') == 'rtl')
<link href="{{ url('assets/plugins/bootstrap-fileinput/css/fileinput-rtl.min.css') }}" rel="stylesheet">
@endif
<style>
    .krajee-default.file-preview-frame:hover:not(.file-preview-error) {
        box-shadow: 0 0 5px 0 #666666;
    }
</style>
@endsection

@section('after_scripts')
<script src="{{ url('assets/plugins/bootstrap-fileinput/js/plugins/sortable.min.js') }}" type="text/javascript">
</script>
<script src="{{ url('assets/plugins/bootstrap-fileinput/js/fileinput.min.js') }}" type="text/javascript"></script>
<script src="{{ url('assets/plugins/bootstrap-fileinput/themes/fa/theme.js') }}" type="text/javascript"></script>
@if (file_exists(public_path() .
'/assets/plugins/bootstrap-fileinput/js/locales/'.ietfLangTag(config('app.locale')).'.js'))
<script src="{{ url('assets/plugins/bootstrap-fileinput/js/locales/'.ietfLangTag(config('app.locale')).'.js') }}"
    type="text/javascript"></script>
@endif
@endsection