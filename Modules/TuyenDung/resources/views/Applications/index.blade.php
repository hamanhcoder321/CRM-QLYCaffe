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

<!-- Modal Xem Chi Tiết Ứng Viên -->
<div class="modal fade" id="modalViewApplication" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-white"><i class="fas fa-id-card mr-2"></i> Chi tiết hồ sơ ứng viên</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted mb-1 small">Họ và tên</label>
                        <div class="font-weight-bold" id="view_name"></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted mb-1 small">Email</label>
                        <div class="font-weight-bold" id="view_email"></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted mb-1 small">Số điện thoại</label>
                        <div class="font-weight-bold" id="view_phone"></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted mb-1 small">Vị trí ứng tuyển</label>
                        <div class="font-weight-bold" id="view_position"></div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="text-muted mb-1 small">Kinh nghiệm làm việc</label>
                        <div class="p-3 bg-light rounded" id="view_experience" style="white-space: pre-wrap;"></div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="text-muted mb-1 small">Kỹ năng nổi bật</label>
                        <div class="p-3 bg-light rounded" id="view_skills" style="white-space: pre-wrap;"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
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

        window.viewApplication = function(id) {
            $.get("{{ url('tuyen-dung/applications') }}/" + id, function(res) {
                $('#view_name').text(res.name || '—');
                $('#view_email').text(res.email || '—');
                $('#view_phone').text(res.phone || '—');
                $('#view_position').text(res.recruitment?.position?.name || '—');
                $('#view_experience').text(res.experience || 'Chưa cập nhật kinh nghiệm.');
                $('#view_skills').text(res.skills || 'Chưa cập nhật kỹ năng.');
                $('#modalViewApplication').modal('show');
            });
        }
    });
</script>
@endpush

@include('layouts/parts/footer')
