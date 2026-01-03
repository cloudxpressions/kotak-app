@extends('admin.layouts.master')

@section('page-title', 'Translation Manager')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="#">System</a></li>
  <li class="breadcrumb-item active" aria-current="page">Translations</li>
</ol>
@endsection

@section('page-actions')
<div class="btn-list">
    <button class="btn btn-primary d-none d-sm-inline-block" id="btn-extract">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-scan" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7v-1a2 2 0 0 1 2 -2h2" /><path d="M4 17v1a2 2 0 0 0 2 2h2" /><path d="M16 4h2a2 2 0 0 1 2 2v1" /><path d="M16 20h2a2 2 0 0 0 2 -2v-1" /><path d="M5 12l14 0" /></svg>
        Scan & Extract
    </button>

    <button class="btn btn-warning d-none d-sm-inline-block" id="btn-export">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-export" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M11.5 21h-6.5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v5m-5 6h7m-3 -3l3 3l-3 3" /></svg>
        Export Files
    </button>
    <a href="{{ route('admin.system.translations.import') }}" class="btn btn-info d-none d-sm-inline-block">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-upload" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 9l5 -5l5 5" /><path d="M12 4l0 12" /></svg>
        Import Excel
    </a>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">Translation Keys</h3>
            <p class="card-subtitle">Manage system translations, auto-translate, and sync files.</p>
        </div>
        <div class="card-actions">
            <select class="form-select" id="module-filter">
                <option value="">All Modules</option>
                @foreach($modules as $module)
                    <option value="{{ $module }}">{{ ucfirst($module) }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="card-body border-bottom py-3">
        <div class="d-flex">
            <div class="text-secondary">
                Show
                <div class="mx-2 d-inline-block">
                    <select id="per-page" class="form-select form-select-sm">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                entries
            </div>
            <div class="ms-auto text-secondary">
                Search:
                <div class="ms-2 d-inline-block">
                    <input type="text" class="form-control form-control-sm" id="search-input" placeholder="Search keys...">
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap datatable" id="translations-table">
            <thead>
                <tr>
                    <th>Key</th>
                    <th>Module</th>
                    @foreach($languages as $language)
                        <th>{{ $language->name }} ({{ strtoupper($language->code) }})</th>
                    @endforeach

                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div class="card-footer d-flex align-items-center">
        <p class="m-0 text-secondary" id="table-info">Showing <span id="start">0</span> to <span id="end">0</span> of <span id="total">0</span> entries</p>
        <ul class="pagination m-0 ms-auto" id="pagination"></ul>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        var table = $('#translations-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.system.translations.index') }}",
                data: function (d) {
                    d.module = $('#module-filter').val();
                }
            },
            columns: [
                { data: 'key', name: 'key' },
                { data: 'module', name: 'module' },
                @foreach($languages as $language)
                {
                    data: 'translations',
                    name: 'translations',
                    orderable: false,
                    render: function(data, type, row) {
                        var value = data['{{ $language->id }}'] || '';
                        return `<input type="text" class="form-control translation-input"
                                    data-key-id="${row.id}"
                                    data-language-id="{{ $language->id }}"
                                    value="${value}"
                                    onblur="saveTranslation(this)">`;
                    }
                },
                @endforeach

            ],
            pageLength: 10,
            dom: 'rt', // Custom DOM to hide default search/length
            drawCallback: function(settings) {
                var api = this.api();
                var info = api.page.info();

                $('#start').text(info.recordsDisplay > 0 ? info.start + 1 : 0);
                $('#end').text(info.end);
                $('#total').text(info.recordsTotal);

                updatePagination(info);
            }
        });

        // Custom Filters
        $('#module-filter').change(function() {
            table.draw();
        });

        $('#search-input').on('keyup', function() {
            table.search(this.value).draw();
        });

        $('#per-page').on('change', function() {
            table.page.len(this.value).draw();
        });

        // Action Buttons
        $('#btn-extract').click(function() {
            var btn = $(this);
            btn.addClass('btn-loading');
            $.post("{{ route('admin.system.translations.extract') }}", { _token: "{{ csrf_token() }}" })
                .done(function(res) {
                    toastr.success(res.message, 'Success');
                    table.draw();
                })
                .fail(function() {
                    toastr.error('Extraction failed.', 'Error');
                })
                .always(function() {
                    btn.removeClass('btn-loading');
                });
        });



        $('#btn-export').click(function() {
            var btn = $(this);
            btn.addClass('btn-loading');
            $.post("{{ route('admin.system.translations.export') }}", { _token: "{{ csrf_token() }}" })
                .done(function(res) {
                    toastr.success(res.message, 'Success');
                })
                .fail(function() {
                    toastr.error('Export failed.', 'Error');
                })
                .always(function() {
                    btn.removeClass('btn-loading');
                });
        });

        // Pagination Logic
        function updatePagination(info) {
            var pagination = $('#pagination');
            pagination.empty();

            if (info.pages <= 1) return;

            var currentPage = info.page;
            var totalPages = info.pages;

            pagination.append(`
                <li class="page-item ${currentPage === 0 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${currentPage - 1}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>
                        prev
                    </a>
                </li>
            `);

            for (var i = 0; i < totalPages; i++) {
                if (i === 0 || i === totalPages - 1 || (i >= currentPage - 1 && i <= currentPage + 1)) {
                    pagination.append(`
                        <li class="page-item ${i === currentPage ? 'active' : ''}">
                            <a class="page-link" href="#" data-page="${i}">${i + 1}</a>
                        </li>
                    `);
                } else if (i === currentPage - 2 || i === currentPage + 2) {
                    pagination.append(`<li class="page-item disabled"><span class="page-link">…</span></li>`);
                }
            }

            pagination.append(`
                <li class="page-item ${currentPage === totalPages - 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${currentPage + 1}">
                        next
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>
                    </a>
                </li>
            `);
        }

        $(document).on('click', '#pagination a', function(e) {
            e.preventDefault();
            if (!$(this).parent().hasClass('disabled') && !$(this).parent().hasClass('active')) {
                table.page(parseInt($(this).data('page'))).draw('page');
            }
        });


    });

    function saveTranslation(input) {
        var $input = $(input);
        var keyId = $input.data('key-id');
        var languageId = $input.data('language-id');
        var value = $input.val();

        $input.addClass('is-valid');

        $.post("{{ route('admin.system.translations.update') }}", {
            _token: "{{ csrf_token() }}",
            key_id: keyId,
            language_id: languageId,
            value: value
        }).done(function() {
            setTimeout(function() { $input.removeClass('is-valid'); }, 1000);
        }).fail(function() {
            $input.addClass('is-invalid');
            toastr.error('Failed to save translation.', 'Error');
        });
    }
</script>
@endpush
