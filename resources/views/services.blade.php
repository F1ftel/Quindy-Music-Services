@extends('layouts.app')

@section('title', 'Services')

@section('content')
<h1 class="text-center display-5 mb-4">Services</h1>

<div class="row">
    @foreach($services as $service)
    @php
    $images = [
    'Mixing' => '/images/mixing.jpg',
    'Mastering' => '/images/mastering.jpg',
    'Beat Production' => '/images/beat-production.jpg',
    ];
    $bgImage = $images[$service->name];
    @endphp
    <div class="col-md-4">
        <div class="card text-white mb-4 shadow" style="background: url('{{ $bgImage }}') center/cover no-repeat; min-height: 250px;">
            <div class="card-body d-flex flex-column justify-content-end bg-dark bg-opacity-50 rounded">
                <h5 class="card-title text-center">{{ $service->name }}</h5>
                <p class="card-text text-center">{{ $service->description }}</p>
                <p class="card-text text-center"><strong>Price: ${{ number_format($service->price, 2) }}</strong></p>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="container my-5">
    <div class="card border border-primary shadow-sm text-center text-dark" style="background: url('{{ asset('images/growth.jpg') }}') center/cover no-repeat; min-height: 250px;">
        <div class="card-body py-5 rounded">
            <h3 class="mb-3 text-primary display-5 text-white">Ready to elevate your sound?</h3>
            <a href="/order" class="btn btn-primary btn-lg">Start Your Project Today</a>
        </div>
    </div>
</div>
@endsection
