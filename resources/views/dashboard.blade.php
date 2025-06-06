@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Your Orders</h1>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($orders->count() > 0)
    <table class="table">
        <thead>
            <tr>
                <th>№</th>
                <th>Order Name</th>
                <th>Status</th>
                <th>Google Drive Link</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $index => $order)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    @if($order->service)
                    {{ $order->service->name }}
                    @elseif($order->package)
                    {{ $order->package->name }}
                    @else
                    Unknown
                    @endif
                </td>
                <td>
    {{ match($order->status) {
        'pending' => 'Pending',
        'completed' => 'Completed',
        'in_progress' => 'In Progress',
        default => ucfirst(str_replace('_', ' ', $order->status))
    } }}
</td>
                <td><a href="{{ $order->google_drive_link }}" target="_blank">View Files</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>No orders yet.</p>
    @endif
</div>
@endsection
