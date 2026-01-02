<?php require_once __DIR__ . '/../../app/Views/partials/header.php'; ?>
<?php require_once __DIR__ . '/../../app/Views/partials/sidebar.php'; ?>
<?php require_once __DIR__ . '/../../app/Views/partials/navbar.php'; ?>

<!-- Content -->
<div class="card">
    <div class="card-header">
        <h5>System Settings</h5>
    </div>
    <div class="card-body">
        <form>
            <div class="mb-3">
                <label class="form-label">App Name</label>
                <input type="text" class="form-control" name="app_name" value="Insurance Guide">
            </div>
            <div class="mb-3">
                <label class="form-label">Support Email</label>
                <input type="email" class="form-control" name="support_email" value="support@insurancenguide.com">
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</div>
<!-- / Content -->

<?php require_once __DIR__ . '/../../app/Views/partials/footer.php'; ?>
