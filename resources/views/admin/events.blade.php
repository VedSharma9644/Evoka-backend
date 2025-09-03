@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('All Events') }}</div>

                <div class="card-body">
                    <div class="table-responsive">
            <table class="table table-bordered table-striped" id="example2">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fetured</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Start Date</th>
                        <th>Start Time</th>
                        <th>End Date</th>
                        <th>End Time</th>
                        <th>Is Public</th>
                        <th>Notification Email</th>
                        <th>Address</th>
                        <th>Is Free</th>
                        <th>Price</th>
                        <th>Max Participants</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Images</th>
                        <th>Document</th>
                        <th>User ID</th>
                        <th>Status</th>
                        <th>Status Reason</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $event)
                    <tr>
                        <td>{{ $event->id }}</td>
                        <td>
                          <input type="checkbox" class="form-checkbox" {{$event->is_fetured}} {{$event->is_fetured==1 ? 'checked' : ''}} onclick="window.location='?cng_fetured={{($event->id) }}'">
                        </td>
                        <td>{{ $event->title }}</td>
                        <td>{{ $event->category }}</td>
                        <td>{{ Str::limit($event->description, 50) }}</td>
                        <td>{{ $event->start_date }}</td>
                        <td>{{ $event->start_time }}</td>
                        <td>{{ $event->end_date }}</td>
                        <td>{{ $event->end_time }}</td>
                        <td>{{ $event->is_public ? 'Yes' : 'No' }}</td>
                        <td>{{ $event->notification_email }}</td>
                        <td>{{ Str::limit($event->address, 30) }}</td>
                        <td>{{ $event->is_free ? 'Yes' : 'No' }}</td>
                        <td>{{ $event->price ?? '-' }}</td>
                        <td>{{ $event->max_participants }}</td>
                        <td>{{ $event->latitude ?? '-' }}</td>
                        <td>{{ $event->longitude ?? '-' }}</td>
                        <td>
                            @if($event->images)
                                <a href="#" data-toggle="modal" data-target="#imagesModal{{ $event->id }}">View</a>
                                <!-- Images Modal -->
                                <div class="modal fade" id="imagesModal{{ $event->id }}" tabindex="-1" role="dialog" aria-labelledby="imagesModalLabel{{ $event->id }}" aria-hidden="true">
                                  <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="imagesModalLabel{{ $event->id }}">Event Images</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>
                                      <div class="modal-body">
                                        <div class="row">
                                          @php
                                            $images = is_array($event->images) ? $event->images : json_decode($event->images, true);
                                          @endphp
                                          @if(is_array($images) && count($images))
                                            @foreach($images as $img)
                                              <div class="col-md-4 mb-3">
                                                <img src="{{ asset('storage/' . $img) }}" class="img-fluid img-thumbnail" alt="Event Image">
                                              </div>
                                            @endforeach
                                          @else
                                            <div class="col-12">No images found.</div>
                                          @endif
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($event->document)
                                <a href="{{ asset('storage/' . $event->document) }}" target="_blank">Download</a>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $event->user_id }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.events.updateStatus', $event->id) }}" class="form-inline">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
                                    @foreach(['pending', 'approved', 'completed', 'rejected'] as $status)
                                        <option value="{{ $status }}" {{ $event->status === $status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td>{{ $event->status_reason ?? '-' }}</td>
                        <td>{{ $event->created_at }}</td>
                        <td>{{ $event->updated_at }}</td>
                                                    <td><a href="?del={{ $event->id }}" class="btn btn-info">Delete</a></td>

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
