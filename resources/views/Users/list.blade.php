@include('layouts/parts/header')

@include('layouts/parts/sidebar')

<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid px-3">

            <div class="d-flex justify-content-between align-items-center py-3">
                <h4 class="mb-0 fw-bold">Danh sách Tài khoản</h4>
                <!-- <a href="#" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Thêm mới
                </a> -->
            </div>

            <div class="card shadow-sm">
                <div class="card-body p-2">
                    <div style="overflow-x: auto; width: 100%;">
                        <table id="users-table" class="table table-bordered table-hover table-sm mb-0" style="width:100%; font-size: 13px;">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th>STT</th>
                                    <th>Tên</th>
                                    <th>Email</th>
                                    <th>Ngày sinh</th>
                                    <th>Giới tính</th>
                                    <th>Bộ phận</th>
                                    <th>Vị trí</th>
                                    <th>Hình thức</th>
                                    <th>Đội nhóm</th>
                                    <th>SĐT</th>
                                    <th>Địa chỉ</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày bắt đầu</th>
                                    <th>Ngày nghỉ</th>
                                    <th>Loại TK</th>
                                    <th>Chi nhánh</th>
                                    <th>Tác vụ</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    /* Đảm bảo không tràn ra ngoài */
    .content-wrapper {
        overflow-x: hidden;
    }

    /* Header bảng căn giữa, không bị xuống dòng */
    #users-table thead th {
        white-space: nowrap;
        vertical-align: middle;
        font-size: 12px;
        padding: 6px 8px;
    }

    /* Nội dung bảng */
    #users-table tbody td {
        vertical-align: middle;
        white-space: nowrap;
        padding: 5px 8px;
    }

    /* Badge trạng thái */
    .badge-status-active {
        background-color: #198754;
        color: white;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 11px;
    }
    .badge-status-inactive {
        background-color: #dc3545;
        color: white;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 11px;
    }
</style>

@include('layouts/parts/footer')

<script>
    $(function () {
        $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            ajax: '{!! route('users.data') !!}',
            columns: [
                { data: 'DT_RowIndex',      name: 'DT_RowIndex',      orderable: false, searchable: false, width: '40px' },
                { data: 'name',             name: 'name' },
                { data: 'email',            name: 'email' },
                { data: 'birthday',         name: 'birthday' },
                { data: 'sex',              name: 'sex' },
                { data: 'part_id',          name: 'part_id' },
                { data: 'position_id',      name: 'position_id' },
                { data: 'type_work',        name: 'type_work' },
                { data: 'team_id',          name: 'team_id' },
                { data: 'phone',            name: 'phone' },
                { data: 'address',          name: 'address' },
                { data: 'status',           name: 'status' },
                { data: 'start_day',        name: 'start_day' },
                { data: 'end_day',          name: 'end_day' },
                { data: 'type_accounts_id', name: 'type_accounts_id' },
                { data: 'branch_name',      name: 'branch_name', },
                { data: 'action',           name: 'action',      }
            ],
            language: {
                processing:  'Đang tải...',
                search:      'Tìm kiếm:',
                lengthMenu:  'Hiển thị _MENU_ dòng',
                info:        'Hiển thị _START_ - _END_ / _TOTAL_ người',
                infoEmpty:   'Không có dữ liệu',
                zeroRecords: 'Không tìm thấy kết quả',
                paginate: {
                    first:    'Đầu',
                    last:     'Cuối',
                    next:     'Sau',
                    previous: 'Trước',
                }
            }
        });
    });
</script>