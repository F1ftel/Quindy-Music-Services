@extends('layouts.app')

@section('title', 'Admin Panel - Manage Orders')

@section('content')
<h1 class="text-center display-5 mb-4">Manage All Orders</h1>
<table class="table table-bordered w-auto mb-4">
    <thead class="table-dark">
        <tr>
            <th>Status</th>
            <th>Count</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Pending Orders</td>
            <td class="text-warning fw-bold">{{ $pending }}</td>
        </tr>
        <tr>
            <td>In Progress Orders</td>
            <td class="text-info fw-bold">{{ $in_progress }}</td>
        </tr>
        <tr>
            <td>Completed Orders</td>
            <td class="text-success fw-bold">{{ $completed }}</td>
        </tr>
    </tbody>
</table>

@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-striped">
    <thead>
        <tr>
            <th>№</th>
            <th>Client Name</th>
            <th>Client Email</th>
            <th>Order Name</th>
            <th>Status</th>
            <th>Google Drive Link</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($orders as $order)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $order->user->name ?? 'Unknown' }}</td>
            <td>{{ $order->user->email ?? 'Unknown' }}</td>
            <td>@if($order->service)
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
            <td>
                <form method="POST" action="{{ route('admin.orders.update', $order->id) }}">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="form-select">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ $order->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary mt-2">Update</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>

</table>
@endsection
