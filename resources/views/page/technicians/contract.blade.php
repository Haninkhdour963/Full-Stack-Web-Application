@extends('layouts.masterPage')

@section('content')
    <!-- Header Section -->
    <div class="container-xxl py-5 page-header mb-5">
        <div class="container my-5 pt-5 pb-4">
            <h1 class="display-3 text-white mb-3 animated slideInDown">Contracts</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb text-uppercase">
                    <li class="breadcrumb-item"><a href="#" class="text-white">Home</a></li>
                    <li class="breadcrumb-item"><a href="#" class="text-white">Pages</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">Technician</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Header End -->

    <!-- Filter and Table Section -->
   <!-- Filter Form Start -->
   <div class="container-xxl py-5">
        <div class="container">
            <!-- Card Wrapper for Filter Form -->
            <div class="card p-4 shadow-sm">
                <h4 class="text-center mb-4">Filter Jobs</h4>
                <form id="filterForm" class="mb-4">
                    <div class="row">
                        <div class="col-12 col-md-3 mb-3">
                            <input type="text" name="location" class="form-control form-control-lg" placeholder="Location" value="{{ request('location') }}">
                        </div>
                        <div class="col-12 col-md-3 mb-3">
                            <input type="text" name="duration" class="form-control form-control-lg" placeholder="Duration" value="{{ request('duration') }}">
                        </div>
                        <div class="col-12 col-md-2 mb-3">
                            <input type="number" name="min_budget" class="form-control form-control-lg" placeholder="Min Budget" value="{{ request('min_budget') }}">
                        </div>
                        <div class="col-12 col-md-2 mb-3">
                            <input type="number" name="max_budget" class="form-control form-control-lg" placeholder="Max Budget" value="{{ request('max_budget') }}">
                        </div>
                        <div class="col-12 col-md-2">
                            <button type="submit" class="btn btn-primary w-100 btn-lg">Search A Job</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Card Wrapper End -->
        </div>
    </div>
    <!-- Filter Form End -->

    <!-- Bids Status Table -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h2 class="mb-4 font-weight-bold text-dark">Bids Status</h2>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Job Title</th>
                            <th>Location</th>
                            <th>Bid Amount</th>
                            <th>Status</th>
                            <th>Bid Date</th>
                        </tr>
                    </thead>
                    <tbody id="bidTableBody">
                        @foreach ($bids as $bid)
                            <tr>
                                <td>{{ $bid->job ? $bid->job->title : 'No Job' }}</td>
                                <td>{{ $bid->job ? $bid->job->location : 'No Location' }}</td>
                                <td>${{ number_format($bid->bid_amount, 2) }}</td>
                                <td>
                                    <span class="badge 
                                        @if($bid->status == 'pending') bg-warning 
                                        @elseif($bid->status == 'accepted') bg-success 
                                        @elseif($bid->status == 'rejected') bg-danger 
                                        @endif">
                                        {{ ucfirst($bid->status) }}
                                    </span>
                                </td>
                                <td>{{ optional($bid->bid_date)->format('Y-m-d H:i') ?? now() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination Links (Centered) -->
    <div class="d-flex justify-content-center">
        {{ $bids->links('vendor.pagination.custom') }}
    </div>
</div>

<script>
    document.getElementById('filterForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent page reload

        const formData = new FormData(this);
        const params = new URLSearchParams(formData).toString(); // Serialize form data to query string

        fetch("{{ route('page.technicians.contract') }}?" + params, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest', // Indicate that it's an AJAX request
            }
        })
        .then(response => response.json())
        .then(data => {
            // Update the table with new data
            updateTable(data.bids);
            // Update pagination links
            updatePagination(data.pagination);
        })
        .catch(error => console.error('Error:', error));
    });

    function updateTable(bids) {
        const tbody = document.getElementById('bidTableBody');
        tbody.innerHTML = ''; // Clear current table content

        bids.forEach(bid => {
            const row = `
                <tr>
                    <td>${bid.job ? bid.job.title : 'No Job'}</td>
                    <td>${bid.job ? bid.job.location : 'No Location'}</td>
                    <td>$${parseFloat(bid.bid_amount).toFixed(2)}</td>
                    <td>
                        <span class="badge ${getStatusClass(bid.status)}">
                            ${capitalizeStatus(bid.status)}
                        </span>
                    </td>
                    <td>${bid.bid_date ? new Date(bid.bid_date).toLocaleString() : 'No Date'}</td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
    }

    function getStatusClass(status) {
        switch (status) {
            case 'pending': return 'bg-warning';
            case 'accepted': return 'bg-success';
            case 'rejected': return 'bg-danger';
            default: return '';
        }
    }

    function capitalizeStatus(status) {
        return status.charAt(0).toUpperCase() + status.slice(1);
    }

    function updatePagination(paginationHtml) {
        const paginationContainer = document.querySelector('.pagination');
        if (paginationContainer) {
            paginationContainer.innerHTML = paginationHtml;
        }
    }
</script>
@endsection
