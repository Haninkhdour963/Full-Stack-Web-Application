@extends('layouts.masterPage')

@section('content')
    <!-- Header End -->
    <div class="container-xxl py-5 bg-dark page-header mb-5">
        <div class="container my-5 pt-5 pb-4">
            <h1 class="display-3 text-white mb-3 animated slideInDown">Contracts </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb text-uppercase">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Pages</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">Technician</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Header End -->

   

    <!-- Display the list of bids -->
    <div class="container">
        <h2>Bids Status</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Job Title</th>
                    <th>Technician</th>
                    <th>Bid Amount</th>
                    <th>Status</th>
                    <th>Bid Date</th>
                   
                </tr>
            </thead>
            <tbody>
                @foreach ($bids as $bid)
                    <tr>
                        <td>
                            <!-- Check if the job exists before trying to access its title -->
                            {{ $bid->job ? $bid->job->title : 'No Job' }}
                        </td>
                        <td>{{ optional($bid->technician)->name ?? 'No Technician' }}</td> <!-- Safe check for null technician -->
                        <td>${{ $bid->bid_amount }}</td>
                        <td>{{ ucfirst($bid->status) }}</td> <!-- ucfirst to capitalize the first letter of status -->
                        <td>
                            {{ optional($bid->bid_date)->format('Y-m-d H:i') ?? 'No Date' }} <!-- Safe check for null bid_date -->
                        </td>
                      
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
