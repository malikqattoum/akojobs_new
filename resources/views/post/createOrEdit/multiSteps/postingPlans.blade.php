
@extends('layouts.master')

@section('content')
	@include('common.spacer')
	<div class="main-container">
		<div class="container">				
			@include('post.inc.notification')
			<div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
				<h1 class="display-4">{{t('Pricing')}}</h1>
				<p class="lead">{{t('Choose your job posting plan')}}</p>
			</div>
			<div class="card-deck mb-3 text-center">
				@foreach ($postPackages as $package)
					<?php if($hasFreePackage && $package->price == 0)
							continue;
					?>
					<div class="card mb-4 box-shadow">
						<div class="card-header">
						<h4 class="my-0 font-weight-normal">{{ $package->name }}</h4>
						</div>
						<div class="card-body" style="position: relative; min-height:300px">
							<?php 
								$exp = explode('/',$_SERVER['REQUEST_URI']); // explode by slash
								$language = $exp[1];
							?>
							@if ($package->price != 0 && $package->currency != 'USD')
								<h1 class="card-title pricing-card-title">{{ $package->price.' '.$package->currency }} <small class="text-muted" {{($language == "ar")?"dir='rtl'":''}}></small></h1>
							@elseif($package->price != 0 && $package->currency == 'USD')
								<h1 class="card-title pricing-card-title">{{ $package->price.'$' }} <small class="text-muted" {{($language == "ar")?"dir='rtl'":''}}></small></h1>
							@else
								<h1 class="card-title pricing-card-title">{{t('Free')}} <small class="text-muted" {{($language == "ar")?"dir='rtl'":''}}></small></h1>
							@endif
							<p>{{ $package->description }}</p>
							<form method="POST" action="{{ lurl('posts/request-package') }}">
								@csrf
								<input type="hidden" name="package_id" value="{{ $package->id }}">
								<button type="submit" class="btn btn-lg btn-block btn-primary" style="position: absolute; bottom: 0;
									right: 0; width:90%; margin-right:10px; margin-bottom:20px">
									{{($package->price != 0)?t('Request now'):t('Get started')}}
								</button>
							</form>
						</div>
					</div>
				@endforeach
			</div>
		</div>
	</div>
@endsection
