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
            ajax: '{!! route('users.data') !!}',
            columns: [{ data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'created_at', name: 'created_at' },
            { data: 'updated_at', name: 'updated_at' }]
        });
    }); 
</script>