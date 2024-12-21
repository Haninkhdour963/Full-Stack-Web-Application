@extends('layouts.master')

@section('title', 'Contacts List')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Contacts List</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Job Title</th>
                                <th>Technician</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Message</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contacts as $contact)
                                <tr id="contact-row-{{ $contact->id }}" class="{{ $contact->deleted_at ? 'text-muted' : '' }}">
                                    <td>{{ $contact->id }}</td>
                                    <td>{{ $contact->job->title ?? 'N/A' }}</td>
                                    <td>{{ $contact->technician->name ?? 'N/A' }}</td>
                                    <td>{{ $contact->name }}</td>
                                    <td>{{ $contact->email }}</td>
                                    <td>{{ $contact->subject }}</td>
                                    <td>{{ $contact->message }}</td>
                                   
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endpush
