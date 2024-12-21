<!-- resources/views/layouts/sidebar.blade.php -->

<link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">

<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        @if(auth()->user()->isAdmin())
            <!-- Admin Sidebar -->
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}"><span class="menu-title">Dashboard</span><i class="mdi mdi-home menu-icon"></i></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}"><span class="menu-title">Users</span><i class="mdi mdi-account-group menu-icon"></i></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('technicians.index') }}"><span class="menu-title">Technicians</span><i class="mdi mdi-wrench menu-icon"></i></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admins.index') }}"><span class="menu-title">Admins</span><i class="mdi mdi-account-key menu-icon"></i></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('categories.index') }}"><span class="menu-title">Categories</span><i class="mdi mdi-tag-multiple menu-icon"></i></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('jobPostings.index') }}"><span class="menu-title">Job Postings</span><i class="mdi mdi-briefcase menu-icon"></i></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('jobBids.index') }}"><span class="menu-title">Job Bids</span><i class="mdi mdi-gavel menu-icon"></i></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('escrowPayments.index') }}"><span class="menu-title">Escrow Payments</span><i class="mdi mdi-cash-multiple menu-icon"></i></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('payments.index') }}"><span class="menu-title">Payments</span><i class="mdi mdi-credit-card menu-icon"></i></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('reviews.index') }}"><span class="menu-title">Reviews</span><i class="mdi mdi-star menu-icon"></i></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('disputes.index') }}"><span class="menu-title">Disputes</span><i class="mdi mdi-alert-circle menu-icon"></i></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('contacts.index') }}"><span class="menu-title">Contacts</span><i class="mdi mdi-email menu-icon"></i></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('adminActions.index') }}"><span class="menu-title">Admin Actions</span><i class="mdi mdi-shield-check menu-icon"></i></a></li>

        @elseif(auth()->user()->isClient())
            <!-- Client Sidebar -->
            <li class="nav-item"><a class="nav-link" href="{{ route('client.dashboard') }}"><span class="menu-title">Dashboard</span><i class="mdi mdi-home menu-icon"></i></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}"><span class="menu-title">My Profile</span><i class="mdi mdi-wrench menu-icon"></i></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('jobPostings.index') }}"><span class="menu-title">Job Postings</span><i class="mdi mdi-gavel menu-icon"></i></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('payments.index') }}"><span class="menu-title">Payments</span><i class="mdi mdi-credit-card menu-icon"></i></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('reviews.index') }}"><span class="menu-title">Reviews</span><i class="mdi mdi-star menu-icon"></i></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('contacts.index') }}"><span class="menu-title">Contacts</span><i class="mdi mdi-email menu-icon"></i></a></li>

        @elseif(auth()->user()->isTechnician())
            <!-- Technician Sidebar -->
            <li class="nav-item"><a class="nav-link" href="{{ route('technician.dashboard') }}"><span class="menu-title">Dashboard</span><i class="mdi mdi-home menu-icon"></i></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('technicians.index') }}"><span class="menu-title">Technicians</span><i class="mdi mdi-wrench menu-icon"></i></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('jobBids.index') }}"><span class="menu-title">Job Bids</span><i class="mdi mdi-gavel menu-icon"></i></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('escrowPayments.index') }}"><span class="menu-title">Escrow Payments</span><i class="mdi mdi-cash-multiple menu-icon"></i></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('payments.index') }}"><span class="menu-title">Payments</span><i class="mdi mdi-credit-card menu-icon"></i></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('reviews.index') }}"><span class="menu-title">Reviews</span><i class="mdi mdi-star menu-icon"></i></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('contacts.index') }}"><span class="menu-title">Contacts</span><i class="mdi mdi-email menu-icon"></i></a></li>
        @endif
    </ul>
</nav>
