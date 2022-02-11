@extends('layouts.master')

@section('content')
    @include('common.spacer')
    <div class="main-container">
		<div class="container">
			<div class="row">
                <div class="inner-box default-inner-box">
                    @include('account.profileHead') 
                    @include('account.personalInformationSection') 
                    @include('account.contactSection') 
                    @include('account.preferredJobSection') 
                    @include('account.experienceSection') 
                    @include('account.educationSection') 
                    @include('account.skillsSection') 
                    @include('account.languagesSection') 
                    @include('account.trainingSection') 
                    @include('account.referencesSection') 
                </div>
            </div>
        </div>
    </div>
@endsection