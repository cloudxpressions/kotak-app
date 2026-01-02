<div class="layout-page">
    <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
        <div class="container-fluid d-flex align-items-center justify-content-between">
            <!-- Sidebar Toggle (Mobile) -->
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)" id="layout-menu-toggle">
                    <i class="bx bx-menu bx-sm"></i>
                </a>
            </div>

            <div class="navbar-nav-left d-flex align-items-center">
                <div class="nav-item d-flex align-items-center">
                    <i class="bx bx-search fs-4 lh-0 me-2 text-muted"></i>
                    <input type="text" class="form-control border-0 shadow-none bg-transparent" placeholder="Search..." aria-label="Search...">
                </div>
            </div>

            <div class="navbar-nav-right d-flex align-items-center">
                <ul class="navbar-nav flex-row align-items-center ms-auto">
                    <!-- Notifications -->
                    <li class="nav-item dropdown dropdown-notifications navbar-dropdown me-3">
                        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bx bx-bell bx-sm"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end py-0">
                            <li class="dropdown-menu-header border-bottom">
                                <div class="dropdown-header d-flex align-items-center py-3">
                                    <h5 class="text-body mb-0 me-auto">Notifications</h5>
                                    <a href="javascript:void(0)" class="dropdown-notifications-all text-body"><i class="bx fs-4 bx-envelope-open"></i></a>
                                </div>
                            </li>
                            <li class="dropdown-notifications-list scrollable-container">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item text-center p-3">
                                        <small class="text-muted">No new notifications</small>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <!-- User Dropdown -->
                    <li class="nav-item navbar-dropdown dropdown-user dropdown">
                        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                            <div class="avatar avatar-online">
                                <div class="avatar-initial rounded-circle bg-label-primary">AD</div>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="javascript:void(0);">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar avatar-online">
                                                <div class="avatar-initial rounded-circle bg-label-primary">AD</div>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <span class="fw-semibold d-block">Sudhanshu</span>
                                            <small class="text-muted">Master Admin</small>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li><div class="dropdown-divider"></div></li>
                            <li>
                                <a class="dropdown-item" href="javascript:void(0);">
                                    <i class="bx bx-user me-2"></i>
                                    <span class="align-middle">My Profile</span>
                                </a>
                            </li>
                            <li><div class="dropdown-divider"></div></li>
                            <li>
                                <a class="dropdown-item" href="#" id="logoutLink">
                                    <i class="bx bx-power-off me-2"></i>
                                    <span class="align-middle">Log Out</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="content-wrapper">
        <div class="breadcrumb-container">
            <h4 class="breadcrumb-header py-3 mb-4" id="pageBreadcrumb">
                <span class="text-muted fw-light">Dashboard /</span> Users
            </h4>
        </div>
        <div class="container-xxl flex-grow-1 container-p-y pt-0">
