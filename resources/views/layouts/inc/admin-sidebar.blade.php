<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>
        <!-- Users -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.users.index') }}">
                <span class="menu-title">Users</span>
                <i class="mdi mdi-account-group menu-icon"></i>
            </a>
        </li>
        <!-- Technicians -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.technicians.index') }}">
                <span class="menu-title">Technicians</span>
                <i class="mdi mdi-wrench menu-icon"></i>
            </a>
        </li>
        <!-- Categories -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.categories.index') }}">
                <span class="menu-title">Categories</span>
                <i class="mdi mdi-tag-multiple menu-icon"></i>
            </a>
        </li>
        <!-- Job Postings -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.jobPostings.index') }}">
                <span class="menu-title">Job Postings</span>
                <i class="mdi mdi-briefcase menu-icon"></i>
            </a>
        </li>
            <!-- job Bids -->
            <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.jobBids.index') }}">
                <span class="menu-title">Job Bids</span>
                <i class="mdi mdi-briefcase menu-icon"></i>
            </a>
        </li>
        <!-- Payments -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.payments.index') }}">
                <span class="menu-title">Payments</span>
                <i class="mdi mdi-credit-card menu-icon"></i>
            </a>
        </li>
        
         <!-- Escrow Payments -->
         <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.escrowPayments.index') }}">
                <span class="menu-title">Escrow Payments</span>
                <i class="mdi mdi-credit-card menu-icon"></i>
            </a>
        </li>
          <!-- Disputes -->
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.disputes.index') }}">
                <span class="menu-title">Disputes</span>
                <i class="mdi mdi-credit-card menu-icon"></i>
            </a>
        </li>
        <!-- Reviews -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.reviews.index') }}">
                <span class="menu-title">Reviews</span>
                <i class="mdi mdi-star menu-icon"></i>
            </a>
        </li>
         <!-- Admins -->
         <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.admins.index') }}">
                <span class="menu-title">Admin</span>
                <i class="mdi mdi-star menu-icon"></i>
            </a>
        </li>
          <!-- Admin Actions -->
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.adminActions.index') }}">
                <span class="menu-title">Admin Action</span>
                <i class="mdi mdi-star menu-icon"></i>
            </a>
        </li>


        <!-- Contacts -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.contacts.index') }}">
                <span class="menu-title">Contacts</span>
                <i class="mdi mdi-email menu-icon"></i>
            </a>
        </li>
    </ul>
</nav>