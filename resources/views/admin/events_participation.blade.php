@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('All Events') }}</div>

                <div class="card-body">
                    <div class="table-responsive">
            <table id="example2" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Number of bookings</th>
                        <th>Email</th>
                        <th>Telephone</th>
                        <th>Event ID</th>
                        <th>Event Title</th>
                        <th>Status</th>
                        <th>Status Reason</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Change Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events_participation as $participation)
                    <tr>
                        <td>{{ $participation->id }}</td>
                        <td>{{ $participation->user_id }}</td>
                        <td>{{ $participation->user->name ?? '-' }}</td>
                        <td>
                            @if($participation->number_of_participants > 1)
                                <span class="badge bg-info">Booked for {{ $participation->number_of_participants }}</span>
                            @else
                                <span class="badge bg-secondary">1 person</span>
                            @endif
                        </td>
                        <td>{{ $participation->user->email ?? '-' }}</td>
                        <td>{{ $participation->user->telephone ?? '-' }}</td>
                        <td>{{ $participation->event_id }}</td>
                        <td>{{ $participation->event->title ?? '-' }}</td>
                        <td>{{ ucfirst($participation->status) }}</td>
                        <td>{{ $participation->status_reason }}</td>
                        <td>{{ $participation->created_at }}</td>
                        <td>{{ $participation->updated_at }}</td>
                        <td>
                            <form action="{{ route('admin.events_participation.update', $participation->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                                    <option value="pending" {{ $participation->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ $participation->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ $participation->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
