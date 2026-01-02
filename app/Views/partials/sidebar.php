<?php
// Active menu helper
function isActive($path) {
    $current = $_SERVER['REQUEST_URI'];
    // Handle both /admin/users.php and users.php
    $basename = basename($current);
    return ($basename === $path || strpos($current, "/admin/$path") !== false) ? 'active' : '';
}
?>
<!-- Sidebar -->
<aside id="layout-menu" class="sidebar">
    <div class="sidebar-header">
        <a href="/admin/dashboard.php" class="app-brand-text text-decoration-none">
            <span class="text-primary me-2"><i class='bx bxs-shield-plus'></i></span> Insurance Guide
        </a>
    </div>

    <ul class="menu-inner">
        <li class="menu-item <?php echo isActive('dashboard.php'); ?>">
            <a href="/admin/dashboard.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div>Dashboard</div>
            </a>
        </li>
        
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Apps & Pages</span></li>
        
        <li class="menu-item <?php echo isActive('users.php'); ?>">
            <a href="/admin/users.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div>Users</div>
            </a>
        </li>
        <li class="menu-item <?php echo isActive('chapters.php'); ?>">
            <a href="/admin/chapters.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-book"></i>
                <div>Chapters</div>
            </a>
        </li>
        <li class="menu-item <?php echo isActive('topics.php'); ?>">
            <a href="/admin/topics.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div>Topics</div>
            </a>
        </li>
        <li class="menu-item <?php echo isActive('questions.php'); ?>">
            <a href="/admin/questions.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-help-circle"></i>
                <div>Questions</div>
            </a>
        </li>
        <li class="menu-item <?php echo isActive('terminologies.php'); ?>">
            <a href="/admin/terminologies.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-collection"></i>
                <div>Terminologies</div>
            </a>
        </li>
        <li class="menu-item <?php echo isActive('tests.php'); ?>">
            <a href="/admin/tests.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-clipboard"></i>
                <div>Tests</div>
            </a>
        </li>
        
        <li class="menu-header small text-uppercase"><span class="menu-header-text">System</span></li>
        
        <li class="menu-item <?php echo isActive('settings.php'); ?>">
            <a href="/admin/settings.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div>Settings</div>
            </a>
        </li>
         <li class="menu-item">
            <a href="#" class="menu-link" id="logoutBtn">
                <i class="menu-icon tf-icons bx bx-power-off"></i>
                <div>Log Out</div>
            </a>
        </li>
    </ul>
</aside>
<!-- / Sidebar -->
