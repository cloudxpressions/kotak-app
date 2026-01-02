        </div>
    </div>
    <!-- / Content Wrapper -->
</div>
<!-- / Layout Page -->
</div>

<!-- Generic CRUD Modal (Global) -->
<div class="modal fade" id="crudModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add New Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="crudForm">
                    <input type="hidden" id="id" name="id">
                    <div class="dynamic-fields">
                         <!-- Fields injected via JS -->
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveBtn">Save changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- App Script -->
<script src="/assets/js/admin.js"></script>

<script>
    // Global Logout Logic
    $('#logoutBtn, #logoutLink').on('click', function(e) {
        e.preventDefault();
        localStorage.removeItem('admin_token');
        window.location.href = '/login.php';
    });
</script>
</body>
</html>
