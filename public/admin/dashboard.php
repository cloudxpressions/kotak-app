<?php require_once __DIR__ . '/../../app/Views/partials/header.php'; ?>
<?php require_once __DIR__ . '/../../app/Views/partials/sidebar.php'; ?>
<?php require_once __DIR__ . '/../../app/Views/partials/navbar.php'; ?>

<!-- Content -->
<div class="row">
    <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between flex-sm-row flex-column gap-3">
                    <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                        <div class="card-title">
                            <h5 class="text-nowrap mb-2">Total Users</h5>
                            <span class="badge bg-label-primary rounded-pill">All Time</span>
                        </div>
                        <div class="mt-sm-auto">
                            <h3 class="mb-0" id="total-users">0</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h5 class="text-nowrap mb-2">Chapters</h5>
                </div>
                <h3 class="mb-0" id="total-chapters">0</h3>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h5 class="text-nowrap mb-2">Total Questions</h5>
                </div>
                <h3 class="mb-0" id="total-questions">0</h3>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h5 class="text-nowrap mb-2">Total Tests</h5>
                </div>
                <h3 class="mb-0" id="total-tests">0</h3>
            </div>
        </div>
    </div>
</div>
<!-- / Content -->

<?php require_once __DIR__ . '/../../app/Views/partials/footer.php'; ?>

<script>
    // Initialize Dashboard logic
    $(document).ready(function() {
         initDashboard();
    });
</script>
