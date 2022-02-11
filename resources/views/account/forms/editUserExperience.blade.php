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
                        {{t('Add new experience')}}
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('account.updateUserExperience', ['id'=>$userExperience->id]) }}"
                            enctype="multipart/form-data">
                            <div class="form-group required" >
                                @method('PUT')
                                @csrf
                                <label for="exp_title">{{t('Title')}} <sup>*</sup></label>
                                <input type="text" value="{{ old('exp_title', $userExperience->exp_title) }}" name="exp_title" class="form-control">
                            </div>
                            <div class="form-group required">
                                <label for="company_name">{{t('Company name')}} <sup>*</sup></label>
                                <input type="text" value="{{ old('company_name', $userExperience->company_name) }}" name="company_name" class="form-control">
                            </div>
                            <?php $countries = include(base_path() . '/resources/lang/en/residenceCountry.php');
                                asort($countries); 
                                $countriesKeys = array_keys($countries);
                            ?>
                            <div class="form-group required">
                                <label for="exp_country">{{t('Country')}} <sup>*</sup></label>
                                <select class="form-control" name="exp_country" id="exp_country" placeholder="{{ t('What is your experience location?') }}"
                                      value="{{ old('exp_country') }}">
                                    <option value="" selected="">{{ t('What is your experience location?') }}</option>
                                    @foreach ($countriesKeys as $item)
                                        <option value="{{$item}}" {{ (old("exp_country", $userExperience->exp_country) == $item ? "selected":"") }} >{{t($item,[],'residenceCountry')}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="from_date">{{t('From date')}}</label>
                                <input type='date' value="{{ date('Y-m-d', strtotime(old('from_date', $userExperience->from_date))) }}" name="from_date" class="form-control" />
                            </div>
                            <div class="form-group" id="toggle_el" @if(!$userExperience->present) style="display:block" @else style="display:none" @endif>
                                <label for="to_date">{{t('To date')}}</label>
                                <input type='date' value="{{ (!empty(old('to_date', $userExperience->to_date)))?date('Y-m-d', strtotime(old('to_date', $userExperience->to_date))):'' }}" name="to_date" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label for="present">{{t('present')}}</label>
                                <input type='checkbox' onchange="checkToggler()" id="toggler_input" class="mt-1" value="1" {{ (old('present', $userExperience->present))?'checked':'' }} name="present" />
                            </div>
                            <div class="form-group required">
                                <label for="exp_tasks">{{t('Tasks & responsibilities')}} <sup>*</sup></label>
                                <textarea name="exp_tasks" class="form-control">{{ old('exp_tasks', $userExperience->exp_tasks) }}</textarea>
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