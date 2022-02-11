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
                        {{t('Update Your Video')}}
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('account.updateVideo', ['id'=>$userVideo->id]) }}" enctype="multipart/form-data">
                            <div class="form-group">
                                @method('PUT')
                                @csrf
                                @if (!empty($userVideo->video))
                                    <video width="400" controls>
                                        <source src="{{lurl('uploads/videos/'.$userVideo->video)}}" type="video/mp4">
                                        <source src="{{lurl('uploads/videos/'.$userVideo->video)}}" type="video/ogg">
                                        Your browser does not support HTML video.
                                    </video>
                                @endif
                                <input type="file" class="form-control" name="video" />
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