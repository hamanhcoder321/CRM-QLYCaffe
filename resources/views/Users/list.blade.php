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
                                <th>id</th>
                                <th>name</th>
                                <th>Email</th>
                                <th>Created At</th>
                                <th>Update At</th>
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
        ajax: {
            url: '{{ route('users.data') }}',
            dataSrc: 'data' // 🔥 thêm dòng này
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'email' },
            { data: 'created_at' },
            { data: 'updated_at' }
        ]
    });
});
</script>