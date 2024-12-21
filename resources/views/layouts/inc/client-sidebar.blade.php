<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('client.dashboard') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>
        <!-- Users -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('client.users.index') }}">
                <span class="menu-title">Users</span>
                <i class="mdi mdi-account-group menu-icon"></i>
            </a>
        </li>
        <!-- Job Postings -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('client.jobPostings.index') }}">
                <span class="menu-title">Job Postings</span>
                <i class="mdi mdi-briefcase menu-icon"></i>
            </a>
        </li>
        <!-- Payments -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('client.payments.index') }}">
                <span class="menu-title">Payments</span>
                <i class="mdi mdi-credit-card menu-icon"></i>
            </a>
        </li>
        <!-- Reviews -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('client.reviews.index') }}">
                <span class="menu-title">Reviews</span>
                <i class="mdi mdi-star menu-icon"></i>
            </a>
        </li>
        <!-- Contacts -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('client.contacts.index') }}">
                <span class="menu-title">Contacts</span>
                <i class="mdi mdi-email menu-icon"></i>
            </a>
        </li>
    </ul>
</nav>