@include('layouts.parts.header')
@include('layouts.parts.sidebar')

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 font-weight-bold">Hồ sơ cá nhân</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Hồ sơ cá nhân</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-7">
      <div class="card shadow-sm">
        <div class="card-header border-0">
          <h3 class="card-title font-weight-bold"><i class="fas fa-user-circle text-primary mr-2"></i>Thông tin cá nhân
          </h3>
        </div>
        <div class="card-body">
          @include('profile.partials.update-profile-information-form')
        </div>
      </div>
    </div>

    <div class="col-lg-5">
      <div class="card shadow-sm mb-4">
        <div class="card-header border-0">
          <h3 class="card-title font-weight-bold"><i class="fas fa-key text-success mr-2"></i>Đổi mật khẩu</h3>
        </div>
        <div class="card-body">
          @include('profile.partials.update-password-form')
        </div>
      </div>

      <div class="card shadow-sm">
        <div class="card-header border-0">
          <h3 class="card-title font-weight-bold"><i class="fas fa-user-slash text-danger mr-2"></i>Xóa tài khoản</h3>
        </div>
        <div class="card-body">
          @include('profile.partials.delete-user-form')
        </div>
      </div>
    </div>
  </div>
</div>

<aside class="control-sidebar control-sidebar-dark"></aside>

@include('layouts.parts.footer')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(session('status') === 'profile-updated')
        Swal.fire({
            icon: 'success',
            title: 'Thành công!',
            text: 'Cập nhật thông tin cá nhân thành công.',
            showConfirmButton: false,
            timer: 2000
        });
    @endif

    @if(session('status') === 'password-updated')
        Swal.fire({
            icon: 'success',
            title: 'Thành công!',
            text: 'Đổi mật khẩu thành công.',
            showConfirmButton: false,
            timer: 2000
        });
    @endif

    @if($errors->updatePassword->any() || $errors->has('name') || $errors->has('email'))
        Swal.fire({
            icon: 'error',
            title: 'Cập nhật thất bại',
            text: 'Vui lòng kiểm tra lại thông tin nhập vào!',
            showConfirmButton: true
        });
    @endif

    @if($errors->userDeletion->any())
        Swal.fire({
            icon: 'error',
            title: 'Xóa thất bại',
            text: '{{ $errors->userDeletion->first("password") }}',
            showConfirmButton: true
        });
        $('#confirm-user-deletion').modal('show');
    @endif
});
</script>