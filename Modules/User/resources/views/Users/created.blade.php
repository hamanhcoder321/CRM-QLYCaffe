@include('layouts/parts/header')

@include('layouts/parts/sidebar')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <h2>Thêm mới nhân sự</h2>

                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        @include('users::users.form')
                        <button type="submit" class="btn btn-primary">Tạo mới</button>
                    </form>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>

@include('layouts/parts/footer')