@extends('layouts.app')

@section('title', 'Order')

@section('content')
<div class="container">
    <h1 class="text-center display-5 mb-4">Place a New Order</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        {{ implode('', $errors->all(':message')) }}
    </div>
    @endif

    <form method="POST" action="{{ route('payment.create') }}">
        @csrf

        <div class="mb-3">
            <label for="item_id" class="form-label">Choose Service or Package</label>
            <select name="item_id" id="item_id" class="form-select" required>
                <optgroup label="Services">
                    @foreach ($services as $service)
                    <option value="service-{{ $service->id }}">
                        {{ $service->name }} - ${{ $service->price }}
                    </option>
                    @endforeach
                </optgroup>

                <optgroup label="Packages">
                    @foreach ($packages as $package)
                    <option value="package-{{ $package->id }}">
                        {{ $package->name }} - ${{ $package->price }}
                    </option>
                    @endforeach
                </optgroup>
            </select>
        </div>

        <div class="mb-3">
            <label for="google_drive_link" class="form-label">Google Drive Link (Your Files)</label>
            <input type="url" name="google_drive_link" id="google_drive_link" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Submit Order & Pay with PayPal</button>
    </form>
</div>
@endsection
