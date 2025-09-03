@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('All Users') }}</div>

                <div class="card-body">
                    <div class="table-responsive">
                <table class="table table-bordered" id="example2">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Account Type</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Telephone</th>
                            <th>Company Name</th>
                            <th>VAT Number</th>
                            <th>Address</th>
                            <th>Invoicing Code</th>
                            <th>Email Verified At</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->accountType }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->telephone }}</td>
                            <td>{{ $user->companyName }}</td>
                            <td>{{ $user->vatNumber }}</td>
                            <td>{{ $user->address }}</td>
                            <td>{{ $user->invoicingCode }}</td>
                            <td>{{ $user->email_verified_at }}</td>
                            <td>{{ $user->created_at }}</td>
                            <td>{{ $user->updated_at }}</td>
                            <td><a href="?del={{ $user->id }}" class="btn btn-info">Delete</a></td>
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
