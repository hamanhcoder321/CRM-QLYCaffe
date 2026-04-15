@include('layouts/parts/header')

@include('layouts/parts/sidebar')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="d-flex justify-content-between align-items-center py-3 w-100">
                            <h4 class="mb-0 fw-bold">Chỉnh sửa thông tin tài khoản</h4>
                            <a href="{{ route('users.list') }}" class="btn btn-primary btn-sm" id="btn-open-create">
                                <i class=""></i> Quay lại danh sách
                            </a>
                        </div>

                        <form action="{{ route('users.update', $user->id) }}" method="POST">
                            @csrf
                            @include('users::users.form')
                            <button type="submit" class="btn btn-primary">cập nhật</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts/parts/footer')