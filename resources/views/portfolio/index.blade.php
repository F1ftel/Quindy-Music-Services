@extends('layouts.app')

@section('title', 'Portfolio')

@section('content')
<div class="container">
    <h1 class="text-center display-5 mb-4">Portfolio</h1>

    @auth
    @if(auth()->user()->role === 'admin')
    <a href="{{ route('portfolio.create') }}" class="btn btn-primary">Add Project</a>
    @endif
    @endauth

    <table class="table">
        <thead>
            <tr>
                <th>Service Name</th>
                <th>Review</th>
                <th>SoundCloud Player</th>
            </tr>
        </thead>
        <tbody>
            @foreach($portfolios as $item)
            <tr>
                <td>{{ $item->service->name ?? $item->package->name ?? 'Unknown' }}</td>
                <td>{{ $item->review }}</td>
                <td>@if($item->track_link)<iframe width="100%" height="166" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url={{ urlencode($item->track_link) }}&color=%23ff5500&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&show_teaser=true&visual=true"></iframe>@endif
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
