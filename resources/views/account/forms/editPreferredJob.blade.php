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
                        {{t('Update Your Preferred Job')}}
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('account.updatePreferredJob') }}"
                            enctype="multipart/form-data">
                            <div class="form-group required" id="preferred_titles_container">
                                @method('PUT')
                                @csrf
                                <label for="preferred_job_title">{{t('Preferred Job Title')}} <sup>*</sup></label>
                                @if (!empty($preferredJobTitles))
                                    <?php $counter = 0;?>
                                    @foreach ($preferredJobTitles as $jobTitle)
                                        @if ($counter == 0)
                                            <input type="text" placeholder="Ex. Head of Human Resources" value="{{ $jobTitle }}" class="form-control" name="preferred_job_title[]" />
                                            <a href="javascript:;" class="add_preferred_job_title_field"><i class="fas fa-plus"></i>{{t('Add another job title')}}</a>
                                        @else
                                            <div>
                                                <input type="text" placeholder="Ex. Head of Human Resources" value="{{ $jobTitle }}" class="my-2 form-control" name="preferred_job_title[]"/>
                                                <a href="javascript:;" class="delete_preferred_job_title"><i class="fas fa-window-close"></i></a>
                                            </div>
                                        @endif
                                        <?php $counter++; ?>
                                    @endforeach
                                @else
                                    <input type="text" placeholder="Ex. Head of Human Resources" value="{{ $user->preferred_job_title }}" class="form-control" name="preferred_job_title[]" />
                                    <a href="javascript:;" class="add_preferred_job_title_field"><i class="fas fa-plus"></i>{{t('Add another job title')}}</a>
                                @endif
                            </div>
                            <?php $countries = include(base_path() . '/resources/lang/en/residenceCountry.php');
                                asort($countries); 
                                $countriesKeys = array_keys($countries);
                            ?>
                            <div class="form-group required">
                                <label for="trgt_job_location">{{t('job location')}} <sup>*</sup></label>
                                <select class="form-control" name="trgt_job_location" id="trgt_job_location" placeholder="{{ t('Select') }}"
                                      value="{{ old('job_location',$user->trgt_job_location) }}">
                                    <option value="" selected="">{{ t('Select') }}</option>
                                    @foreach ($countriesKeys as $item)
                                        <option value="{{$item}}" {{ (old("trgt_job_location",$user->trgt_job_location) == $item ? "selected":"") }} >{{t($item,[],'residenceCountry')}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="city">{{t('Job City')}}</label>
                                <input type="text" value="{{ old('trgt_city',$user->trgt_city) }}" class="form-control" name="trgt_city" />
                            </div>
                            <div class="form-group">
                                <label for="trgt_month_sal">{{t('Target Monthly Salary')}}</label>
                                @if (!empty($user->trgt_month_sal))
                                    <span>{{t('Your target monthly salary is')}}: {{$user->trgt_month_sal}}</span>
                                @endif
                                <select name="currency" class="form-control mb-2">
                                    <option value="" disabled selected>{{t('Choose currency')}}</option>
                                    <option value="USD">United States Dollars</option>
                                    <option value="EUR">Euro</option>
                                    <option value="GBP">United Kingdom Pounds</option>
                                    <option value="DZD">Algeria Dinars</option>
                                    <option value="ARP">Argentina Pesos</option>
                                    <option value="AUD">Australia Dollars</option>
                                    <option value="ATS">Austria Schillings</option>
                                    <option value="BSD">Bahamas Dollars</option>
                                    <option value="BBD">Barbados Dollars</option>
                                    <option value="BEF">Belgium Francs</option>
                                    <option value="BMD">Bermuda Dollars</option>
                                    <option value="BRR">Brazil Real</option>
                                    <option value="BGL">Bulgaria Lev</option>
                                    <option value="CAD">Canada Dollars</option>
                                    <option value="CLP">Chile Pesos</option>
                                    <option value="CNY">China Yuan Renmimbi</option>
                                    <option value="CYP">Cyprus Pounds</option>
                                    <option value="CSK">Czech Republic Koruna</option>
                                    <option value="DKK">Denmark Kroner</option>
                                    <option value="NLG">Dutch Guilders</option>
                                    <option value="XCD">Eastern Caribbean Dollars</option>
                                    <option value="EGP">Egypt Pounds</option>
                                    <option value="FJD">Fiji Dollars</option>
                                    <option value="FIM">Finland Markka</option>
                                    <option value="FRF">France Francs</option>
                                    <option value="DEM">Germany Deutsche Marks</option>
                                    <option value="XAU">Gold Ounces</option>
                                    <option value="GRD">Greece Drachmas</option>
                                    <option value="HKD">Hong Kong Dollars</option>
                                    <option value="HUF">Hungary Forint</option>
                                    <option value="ISK">Iceland Krona</option>
                                    <option value="INR">India Rupees</option>
                                    <option value="IDR">Indonesia Rupiah</option>
                                    <option value="IEP">Ireland Punt</option>
                                    <option value="ILS">Israel New Shekels</option>
                                    <option value="ITL">Italy Lira</option>
                                    <option value="JMD">Jamaica Dollars</option>
                                    <option value="JPY">Japan Yen</option>
                                    <option value="JOD">Jordan Dinar</option>
                                    <option value="KRW">Korea (South) Won</option>
                                    <option value="LBP">Lebanon Pounds</option>
                                    <option value="LUF">Luxembourg Francs</option>
                                    <option value="MYR">Malaysia Ringgit</option>
                                    <option value="MXP">Mexico Pesos</option>
                                    <option value="NLG">Netherlands Guilders</option>
                                    <option value="NZD">New Zealand Dollars</option>
                                    <option value="NOK">Norway Kroner</option>
                                    <option value="PKR">Pakistan Rupees</option>
                                    <option value="XPD">Palladium Ounces</option>
                                    <option value="PHP">Philippines Pesos</option>
                                    <option value="XPT">Platinum Ounces</option>
                                    <option value="PLZ">Poland Zloty</option>
                                    <option value="PTE">Portugal Escudo</option>
                                    <option value="ROL">Romania Leu</option>
                                    <option value="RUR">Russia Rubles</option>
                                    <option value="SAR">Saudi Arabia Riyal</option>
                                    <option value="XAG">Silver Ounces</option>
                                    <option value="SGD">Singapore Dollars</option>
                                    <option value="SKK">Slovakia Koruna</option>
                                    <option value="ZAR">South Africa Rand</option>
                                    <option value="KRW">South Korea Won</option>
                                    <option value="ESP">Spain Pesetas</option>
                                    <option value="XDR">Special Drawing Right (IMF)</option>
                                    <option value="SDD">Sudan Dinar</option>
                                    <option value="SEK">Sweden Krona</option>
                                    <option value="CHF">Switzerland Francs</option>
                                    <option value="TWD">Taiwan Dollars</option>
                                    <option value="THB">Thailand Baht</option>
                                    <option value="TTD">Trinidad and Tobago Dollars</option>
                                    <option value="TRL">Turkey Lira</option>
                                    <option value="VEB">Venezuela Bolivar</option>
                                    <option value="ZMK">Zambia Kwacha</option>
                                    <option value="EUR">Euro</option>
                                    <option value="XCD">Eastern Caribbean Dollars</option>
                                    <option value="XDR">Special Drawing Right (IMF)</option>
                                    <option value="XAG">Silver Ounces</option>
                                    <option value="XAU">Gold Ounces</option>
                                    <option value="XPD">Palladium Ounces</option>
                                    <option value="XPT">Platinum Ounces</option>
                                </select>
                                <input type="text" placeholder="000" value="{{ old('trgt_month_sal',(!empty($user->trgt_month_sal))?explode(' ', $user->trgt_month_sal)[0]:'') }}" class="form-control" name="trgt_month_sal" />
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