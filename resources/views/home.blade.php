@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">
    <div class="position-relative text-center text-white" style="background: url('{{ asset('images/hero.jpg') }}') center/cover no-repeat; height: 80vh;">
        <div class="overlay position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50"></div>
        <div class="position-relative d-flex flex-column justify-content-center align-items-center h-100">
            <h1 class="display-2 fw-bold">Quindy Music Services</h1>
            <p class="fs-4 mb-4">High‑quality Beats, Ghost Production, Mixing & Mastering</p>
            <div>
                <a href="/services" class="btn btn-lg btn-primary me-2 shadow">Browse Services</a>
                <a href="/packages" class="btn btn-lg btn-outline-light shadow">View Packages</a>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <h2 class="fs-1 text-center mb-3 display-5">Featured Services</h2>
        <div class="row justify-content-center">
            @foreach($featuredServices as $service)
            <div class="col-md-4 mb-4">
                <div class="card h-100 text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $service->name }}</h5>
                        <p class="card-text">{{ $service->description }}</p>
                        <a href="/services" class="btn btn-primary">Learn More</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="container mt-5">
        <h2 class="fs-1 text-center mb-3 display-5">Featured Packages</h2>
        <div class="row justify-content-center">
            @foreach($featuredPackages as $package)
            <div class="col-md-4 mb-4">
                <div class="card h-100 text-center shadow-sm border border-primary">
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ $package->name }}</h5>
                        <a href="/packages" class="btn btn-outline-primary">Learn More</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="mt-5">
        <h2 class="fs-1 text-center mb-3 display-5">Latest Projects</h2>
        <div class="row gy-4">
            @foreach($latestPortfolios as $project)
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-center">
                            {{ $project->service->name ?? $project->package->name ?? 'Unknown Service' }}
                        </h5>
                        <p class="card-text text-muted">{{ $project->review }}</p>
                        @if($project->track_link)
                        <div class="ratio ratio-16x9"><iframe width="100%" height="166" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url={{ urlencode($project->track_link) }}&color=%23ff5500&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&show_teaser=true&visual=true"></iframe></div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="container my-5">
    <div class="card border border-primary shadow-sm text-center text-dark">
        <div class="card-body py-5 rounded">
            <h3 class="mb-3 text-primary display-5 text-white">Ready to elevate your sound?</h3>
            <a href="/order" class="btn btn-primary btn-lg">Start Your Project Today</a>
        </div>
    </div>
</div>
@endsection
