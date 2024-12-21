<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('technician.dashboard') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>
         <!-- My Profile -->
         <li class="nav-item">
            <a class="nav-link" href="{{ route('technician.technicians.index') }}">
                <span class="menu-title">My Profile </span>
                <i class="mdi mdi-gavel menu-icon"></i>
            </a>
        </li>
        <!-- Job Bids -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('technician.jobBids.index') }}">
                <span class="menu-title">Job Bids</span>
                <i class="mdi mdi-gavel menu-icon"></i>
            </a>
        </li>
        <!-- Payments -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('technician.payments.index') }}">
                <span class="menu-title">Payments</span>
                <i class="mdi mdi-credit-card menu-icon"></i>
            </a>
        </li>
        <!-- Reviews -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('technician.reviews.index') }}">
                <span class="menu-title">Reviews</span>
                <i class="mdi mdi-star menu-icon"></i>
            </a>
        </li>
        <!-- Contacts -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('technician.contacts.index') }}">
                <span class="menu-title">Contacts</span>
                <i class="mdi mdi-email menu-icon"></i>
            </a>
        </li>
    </ul>
</nav>