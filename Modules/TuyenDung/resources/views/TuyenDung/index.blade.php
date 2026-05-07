@include('layouts/parts/header')
@include('layouts/parts/sidebar')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold">Tuyển dụng</h1>
                    <p class="text-muted mb-0">Tạo và quản lý post tuyển dụng theo bộ phận / vị trí.</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Tuyển dụng</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card shadow-sm mb-4">
                <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h5 class="mb-1">Danh sách post tuyển dụng</h5>
                        <p class="text-muted mb-0">Quản lý các post tuyển dụng đã đăng.</p>
                    </div>
                    <a href="{{ route('tuyendung.create') }}" class="btn btn-primary mt-3 mt-md-0">
                        <i class="fas fa-plus mr-1"></i> Tạo post mới
                    </a>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="recruitment-table">
                            <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Bộ phận</th>
                                <th>Vị trí</th>
                                <th>Số lượng</th>
                                <th>Ưu tiên</th>
                                <th>Hạn chót</th>
                                <th>Người tạo</th>
                                <th>Trạng thái</th>
                                <th>Kết quả</th>
                                <th>Hành động</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<aside class="control-sidebar control-sidebar-dark"></aside>

@push('scripts')
<script>
    $(function () {
        $('#recruitment-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('tuyendung.data') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'part_name', name: 'part_name' },
                { data: 'position_name', name: 'position_name' },
                { data: 'number', name: 'number' },
                { data: 'prioritize', name: 'prioritize', orderable: false, searchable: false },
                { data: 'deadline', name: 'deadline' },
                { data: 'user_name', name: 'user_name' },
                { data: 'status', name: 'status', orderable: false, searchable: false },
                { data: 'result', name: 'result', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            language: {
                url: 'https://cdn.datatables.net/plug-ins/2.3.7/i18n/vi.json'
            },
            pageLength: 10,
            order: [[0, 'desc']]
        });
    });
</script>
@endpush

@include('layouts/parts/footer')
