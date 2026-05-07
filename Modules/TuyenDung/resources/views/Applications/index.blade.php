@include('layouts/parts/header')
@include('layouts/parts/sidebar')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold">Danh sách ứng tuyển</h1>
                    <p class="text-muted mb-0">Quản lý và duyệt hồ sơ ứng viên từ trang chủ.</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Danh sách ứng tuyển</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="applicationTable" class="table table-hover mb-0 w-100">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 50px">STT</th>
                                    <th>Ứng viên</th>
                                    <th>Email</th>
                                    <th>SĐT</th>
                                    <th>Vị trí</th>
                                    <th>Bộ phận</th>
                                    <th>Ngày ứng tuyển</th>
                                    <th>Trạng thái</th>
                                    <th style="width: 100px">Thao tác</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(function () {
        let table = $('#applicationTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('tuyendung.applications.data') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'phone', name: 'phone' },
                { data: 'position_name', name: 'position_name' },
                { data: 'part_name', name: 'part_name' },
                { data: 'created_at', name: 'created_at' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
            order: [[6, 'desc']],
            language: {
                url: 'https://cdn.datatables.net/plug-ins/2.3.7/i18n/vi.json'
            }
        });

        window.updateStatus = function(id, status) {
            let title = status == 1 ? 'Duyệt đạt?' : 'Đánh dấu không đạt?';
            let text = status == 1 ? 'Hệ thống sẽ gửi email chúc mừng đến ứng viên.' : 'Hệ thống sẽ gửi email thông báo không đạt đến ứng viên.';
            
            Swal.fire({
                title: title,
                text: text,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: status == 1 ? '#28a745' : '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Đang xử lý...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: "{{ route('tuyendung.applications.update-status', ':id') }}".replace(':id', id),
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            status: status
                        },
                        success: function(res) {
                            Swal.fire('Thành công!', res.message, 'success');
                            table.ajax.reload();
                        },
                        error: function() {
                            Swal.fire('Lỗi!', 'Có lỗi xảy ra trong quá trình xử lý.', 'error');
                        }
                    });
                }
            });
        }
    });
</script>
@endpush

@include('layouts/parts/footer')
