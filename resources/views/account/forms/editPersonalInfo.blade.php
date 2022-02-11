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
                        {{t('Update Your Personal Information')}}
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('account.updatePersonalInfo') }}"
                            enctype="multipart/form-data">
                            <div class="form-group required">
                                @method('PUT')
                                @csrf
                                <label for="name">{{t('Full name (English)')}} <sup>*</sup></label>
                                <input type="text" value="{{ old('name', $user->name) }}" class="form-control" name="name" />
                            </div>
                            <div class="form-group">
                                <label for="name_ar">{{t('Full name (Arabic)')}}</label>
                                <input type="text" value="{{ old('name_ar', $user->name_ar) }}" class="form-control" name="name_ar" />
                            </div>
                            <div class="form-group required">
                                <label for="birthday">{{t('Birth Date')}} <sup>*</sup></label>
                                <input type='date' value="{{ old('birthday', $user->birthday) }}" name="birthday" class="form-control" />
                            </div>
                            <div class="form-group required">
                                <label for="gender">{{t('Gender')}} <sup>*</sup></label>
                                <div class="radio">
                                    <label><input type="radio" {{ (old("gender_id",$user->gender_id) == 1 ? "checked":"") }} name="gender_id" class="mr-1" value="1">{{t('Male')}}</label>
                                    <span class="mr-2"></span>
                                    <label><input type="radio" {{ (old("gender_id",$user->gender_id) == 2 ? "checked":"") }} name="gender_id" class="mr-1" value="2">{{t('Female')}}</label>
                                </div>
                            </div>
                            <div class="form-group required">
                                <?php $nationalites = include(base_path() . '/resources/lang/en/residenceCountry.php');
											asort($nationalites); 
											$nationalitesKeys = array_keys($nationalites);
                                        ?>
                                <label for="nationality">{{t('Your nationality')}} <small>{{t('your national ID')}}</small> <sup>*</sup></label>
                                <select class="form-control" name="nationality" id="nationality"
                                            value="{{ old('nationality',$user->nationality) }}">
                                    <option value="" selected="">{{ t('Select') }}</option>
                                    @foreach ($nationalitesKeys as $item)
                                        <option value="{{$item}}" {{ (old("nationality",$user->nationality) == $item ? "selected":"") }} >{{t($item,[],'residenceCountry')}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group required">
                                <label for="nationality">{{t('Resident Country')}} <sup>*</sup></label>
                                <select class="form-control" name="residence_country" id="residence_country" placeholder="{{ t('What is your residence country?') }}"
                                      value="{{ old('residence_country',$user->residence_country) }}">
                                    <option value="" selected="">{{ t('What is your residence country?') }}</option>
                                    @foreach ($nationalitesKeys as $item)
                                        <option value="{{$item}}" {{ (old("residence_country",$user->residence_country) == $item ? "selected":"") }} >{{t($item,[],'residenceCountry')}}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- <div class="form-group">
                                <label for="city">City</label>
                                <input type="text" class="form-control" name="city" />
                            </div> --}}
                            <div class="form-group">
                                <label for="martial_status">{{t('Matial Status')}}</label>
                                <select class="form-control" name="martial_status" id="martial_status"
                                            value="{{ old('martial_status',$user->martial_status) }}">
                                    <option value="" selected="">{{ t('Select') }}</option>
                                    <option value="married" {{ (old("martial_status",$user->martial_status) == "married" ? "selected":"") }} >{{t('Married')}}</option>
                                    <option value="single" {{ (old("martial_status",$user->martial_status) == "single" ? "selected":"") }} >{{t('Single')}}</option>
                                    <option value="unspecified" {{ (old("martial_status",$user->martial_status) == "unspecified" ? "selected":"") }} >{{t('Unspecified')}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="num_dependents">{{t('Number of dependents')}}</label>
                                <select class="form-control" name="num_dependents" id="num_dependents"
                                            value="{{ old('num_dependents',$user->num_dependents) }}">
                                    <option value="" selected="">{{ t('Select') }}</option>
                                    <?php $dependencies = range(0,20); ?>
                                    @foreach ($dependencies as $item)
                                        <option value="{{$item}}" {{ (old("num_dependents",$user->num_dependents) == $item && $user->num_dependents !== null ? "selected":"") }} >{{$item}}</option>
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