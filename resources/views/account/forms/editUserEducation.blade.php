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
                        {{t('Add new education')}}
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('account.updateUserEducation', ['id'=>$userEducation->id]) }}">
                            @method('PUT')
                            @csrf
                            <?php $degrees = include(base_path() . '/resources/lang/en/degreesList.php');
                                asort($degrees); 
                                $degreesKeys = array_keys($degrees);
                            ?>
                            <div class="form-group required">
                                <label for="edu_degree">{{t('Degree')}} <sup>*</sup></label>
                                <select class="form-control" name="edu_degree" id="edu_degree" placeholder="{{ t('Choose degree') }}"
                                      value="{{ old('edu_degree', $userEducation->edu_degree) }}">
                                    <option value="" selected="">{{ t('Choose degree') }}</option>
                                    @foreach ($degreesKeys as $item)
                                        <option value="{{$item}}" {{ (old("edu_degree", $userEducation->edu_degree) == $item ? "selected":"") }} >{{t($item,[],'degreesList')}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group required">
                                <label for="edu_institution">{{t('Organization/Institutes')}} <sup>*</sup></label>
                                <input type='text' placeholder="Ex. Harvard University" value="{{ old('edu_institution', $userEducation->edu_institution) }}" name="edu_institution" class="form-control" />
                            </div>
                            <div class="form-group" id="toggle_el" @if(!$userEducation->on_going) style="display:block" @else style="display:none" @endif>
                                <label for="edu_grad_date">{{t('Graduation date')}}</label>
                                <input type='date' value="{{ (!empty(old('edu_grad_date', $userEducation->edu_grad_date)))?date('Y-m-d', strtotime(old('edu_grad_date', $userEducation->edu_grad_date))):'' }}" name="edu_grad_date" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label for="on_going">{{t('Still On Going')}}</label>
                                <input type='checkbox' onchange="checkToggler()" id="toggler_input" class="mt-1" value="1" {{ (old('on_going',$userEducation->on_going ))?'checked':'' }} name="on_going" />
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