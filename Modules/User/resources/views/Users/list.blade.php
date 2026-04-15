@include('layouts/parts/header')

@include('layouts/parts/sidebar')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="d-flex justify-content-between align-items-center py-3 w-100">
                            <h4 class="mb-0 fw-bold">Danh sách Tài khoản</h4>
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <a href="{{ route('users.created') }}" class="btn btn-primary btn-sm" id="btn-open-create">
                                <i class="fas fa-plus"></i> Thêm mới
                            </a>
                        </div>

                        <div class="card shadow-sm">
                            <div class="card-body p-2">
                                <div style="width: 100%;">
                                    <table id="users-table" class="table table-bordered table-hover table-sm mb-0"
                                        style="width:100%; font-size: 13px;">
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
    window.DT = null;
    $(function () {
        window.DT = $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            ajax: {
                url: '{!! route('users.data') !!}',
                data: function (d) {
                    d.part_id = $('#f_part').val() || '';
                    d.status = $('#f_status').val() || '';
                    d.team_id = $('#f_team').val() || '';
                    d.type_account_id = $('#f_type_account').val() || '';
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, width: '40px' },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'birthday', name: 'birthday' },
                { data: 'sex', name: 'sex' },
                { data: 'part_id', name: 'part_id' },
                { data: 'position_id', name: 'position_id' },
                { data: 'type_work', name: 'type_work' },
                { data: 'team_id', name: 'team_id' },
                { data: 'phone', name: 'phone' },
                { data: 'address', name: 'address' },
                { data: 'status', name: 'status' },
                { data: 'start_day', name: 'start_day' },
                { data: 'end_day', name: 'end_day' },
                { data: 'type_accounts_id', name: 'type_accounts_id' },
                { data: 'branch_name', name: 'branches.name' },
                { data: 'action', name: 'action', orderable: false, searchable: false } // Tắt tìm kiếm cho cột này
            ],
            language: {
                processing: 'Đang tải...',
                search: 'Tìm kiếm:',
                lengthMenu: 'Hiển thị _MENU_ dòng',
                info: 'Hiển thị _START_ - _END_ / _TOTAL_ người',
                infoEmpty: 'Không có dữ liệu',
                zeroRecords: 'Không tìm thấy kết quả',
                paginate: {
                    first: 'Đầu',
                    last: 'Cuối',
                    next: 'Sau',
                    previous: 'Trước',
                }
            },
            dom: `
            <'row mb-3 align-items-center'
                <'col-md-2' l>
                <'col-md-10 d-flex justify-content-end align-items-center flex-wrap'
                    f
                    <'dt-toolbar d-flex ms-2'>
                >
            >
            rt
            <'row mt-2 justify-content-between'
                <'col-md-auto me-auto d-md-flex justify-content-between align-items-center dt-layout-start' i>
                <'col-md-auto me-auto d-md-flex justify-content-between align-items-center dt-layout-end' p>
            >
            `,
            initComplete: function () {
                const $bar = $('.dt-toolbar', this.api().table().container());

                // giao diện filters
                if (!$bar.children().length) {
                    $bar.html(`
                        <select id="f_status" class"form-select"><option value="">Trạng thái</option></select>
                        <select id="f_part" class"form-select"><option value="">Bộ phận</option></select>
                        <select id="f_team" class"form-select"><option value="">Đội nhóm</option></select>
                        <select id="f_type_account" class"form-select"><option value="">Loại tài khoản</option></select>
                        <button id="btn-clear-filters" class="btn btn-sm">xóa lọc</button>
                    `)
                }

                // load data server
                $.getJSON("{{ route('users.filters') }}")
                    .done(res => {
                        fill('#f_status', res.status_f);
                        fill('#f_part', res.part_f);
                        fill('#f_team', res.team_f);
                        fill('#f_type_account', res.role_f)
                    });
                // hàm đổ data-->dropdown
                function fill(selector, items) {
                    var element = $(selector);

                    //ktra items
                    if (!items) {
                        items = [];
                    }

                    items.forEach(function (item) {
                        // đổ data vào option --> tạo option
                        var option = new Option(item.text, item.id);

                        // thêm option vào trong select
                        element.append(option);
                    });
                }

                // thay đổi filter-->load lại data table
                $(document).on('change', '#f_status, #f_part, #f_team, #f_type_account', function () {
                    DT.ajax.reload();
                })

                // delete filter
                $('#btn-clear-filters').on('click', function () {
                    $('#f_status, #f_part, #f_team, #f_type_account').val(''); // giá trị về null
                    DT.ajax.reload(); //load lại filter 
                })

            }
        });
    });

    $(document).on('click', 'button.btn.btn-danger.btn-delete', function () {
        const $form = $(this).closest('form');
        Swal.fire({
            title: "Bạn có chắc chắn muốn xóa tài khoản này không",
            icon: "question",
            confirmButtonText: "xóa",
            cancelButtonText: "hủy",
            showCancelButton: true,
            showCloseButton: true
        }).then((result) => {
            if (!result.isConfirmed) return;
            $form.submit();
        });;
    });
</script>