<!-- Navbar Start -->
<nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
    <a href="{{ route('index') }}" class="navbar-brand d-flex align-items-center text-center py-0 px-4 px-lg-5">
        <h1 class="m-0 text-primary"> Tas'heel </h1>
    </a>
    <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto p-4 p-lg-0">
            <!-- Home Link -->
            <a href="{{ route('index') }}" class="nav-item nav-link">Home</a>
            <!-- About Link -->
            <a href="{{ route('about') }}" class="nav-item nav-link">About</a>
            
            <!-- Jobs Dropdown -->
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle active" data-bs-toggle="dropdown">Jobs</a>
                <div class="dropdown-menu rounded-0 m-0">
                <a href="{{ route('page.technicians.bid') }}" class="dropdown-item">Job Postings</a> 
                <a href="{{ route('page.clients.hire') }}" class="dropdown-item">Job Bids </a>  
                    <a href="{{ route('page.clients.contract') }}" class="dropdown-item">Sign Contracts</a>
                </div>
            </div>

            <!-- Client Dropdown -->
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Client</a>
                <div class="dropdown-menu rounded-0 m-0">
                    <a href="{{ route('page.clients.post') }}" class="dropdown-item">Create a Job Post</a>
                    <a href="{{ route('page.clients.contract') }}" class="dropdown-item">Sign Contracts</a>
                    <a href="{{ route('page.clients.hire') }}" class="dropdown-item">Hire A Technician </a>
                    <!-- You can add more routes here -->
                </div>
            </div>

            <!-- Technician Dropdown -->
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Technician</a>
                <div class="dropdown-menu rounded-0 m-0">
                    <a href="{{ route('page.technicians.profile') }}" class="dropdown-item">Create Profile</a>
                    <a href="{{ route('page.technicians.bid') }}" class="dropdown-item">Bids On Jobs</a>
                    <a href="{{ route('page.technicians.contract') }}" class="dropdown-item">Manage Contracts  </a>
                    <!-- Add other routes as necessary -->
                </div>
            </div>

            <!-- Contact Link -->
            <a href="{{ route('contact') }}" class="nav-item nav-link">Contact</a>
            <a href="{{ route('profile.edit') }}" class="nav-item nav-link">
                <i class="fas fa-user"></i>
            </a>
        </div>

        <a href="{{ route('page.clients.post') }}" class="btn btn-primary rounded-0 py-4 px-lg-5 d-none d-lg-block">
            Post What You Want <i class="fa fa-arrow-right ms-3"></i>
        </a>
    </div>
</nav>
<!-- Navbar End -->
