@extends('layouts.app')
@section('title', 'Admin Panel - Add Project')

@section('content')

<a href="{{ route('portfolio.create') }}" class="btn btn-primary">Add Project</a>

<div class="container">
    <h1 class="text-center display-5 mb-4">Add Portfolio Project</h1>

    <form method="POST" action="{{ route('portfolio.store') }}">
        @csrf
        <div class="mb-3">
            <label for="item_id" class="form-label">Select Service or Package:</label>
            <select name="item_id" id="item_id" class="form-control" required>
                <optgroup label="Services">
                    @foreach($services as $service)
                    <option value="App\Models\Service-{{ $service->id }}">{{ $service->name }}</option>
                    @endforeach
                </optgroup>
                <optgroup label="Packages">
                    @foreach($packages as $package)
                    <option value="App\Models\Package-{{ $package->id }}">{{ $package->name }}</option>
                    @endforeach
                </optgroup>
            </select>
        </div>
        <div class="mb-3">
            <label for="review" class="form-label">Customer Review (optional)</label>
            <textarea name="review" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label for="track_link" class="form-label">SoundCloud Track URL</label>
            <input type="url" name="track_link" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Save Project</button>
    </form>
</div>

@endsection
