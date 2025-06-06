@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center display-5 mb-4">My Orders</h1>

    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Service / Package</th>
                <th>Status</th>
                <th>Google Drive Link</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>
                    @if ($order->service)
                    {{ $order->service->name }}
                    @elseif ($order->package)
                    {{ $order->package->name }}
                    @else
                    N/A
                    @endif
                </td>
                <td>{{ ucfirst($order->status) }}</td>
                <td><a href="{{ $order->google_drive_link }}" target="_blank">Link</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
