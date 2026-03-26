@include('layouts/parts/header')

@include('layouts/parts/sidebar')
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="container" style="min-height: 100vh;">
                    <h2>User List</h2>
                    <table id="users-table" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>name</th>
                                <th>Email</th>
                                <th>Ngày sinh</th>
                                <th>Giới tính</th>
                                <th>Bộ phận</th>
                                <th>vị trí</th>
                                <th>Hình thức</th>
                                <th>Đội nhóm</th>
                                <th>SĐT</th>
                                <th>Địa chỉ</th>
                                <th>Trạng thái</th>
                                <th>Ngày bắt đầu</th>
                                <th>Ngày nghỉ việc</th>
                                <th>Loại tài khoản</th>
                                <th>Chi nhánh</th>
                                <!-- <th>Tác vụ</th> -->
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts/parts/footer')

<script>
    $(function () {
        $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('users.data') !!}',
            columns: [{ data: 'DT_RowIndex', name: 'DT_RowIndex', sortable: false, searchable: false },
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
            { data: 'branch_id', name: 'branch_id' }
        ]
        });
    }); 
</script>