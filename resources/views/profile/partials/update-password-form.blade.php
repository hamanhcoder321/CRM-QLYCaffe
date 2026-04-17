<section>
    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="form-group">
            <label for="update_password_current_password">Mật khẩu hiện tại</label>
            <input id="update_password_current_password" name="current_password" type="password" class="form-control" autocomplete="current-password" />
            @if($errors->updatePassword->has('current_password'))
                <small class="text-danger">{{ $errors->updatePassword->first('current_password') }}</small>
            @endif
        </div>

        <div class="form-group">
            <label for="update_password_password">Mật khẩu mới</label>
            <input id="update_password_password" name="password" type="password" class="form-control" autocomplete="new-password" />
            @if($errors->updatePassword->has('password'))
                <small class="text-danger">{{ $errors->updatePassword->first('password') }}</small>
            @endif
        </div>

        <div class="form-group">
            <label for="update_password_password_confirmation">Xác nhận mật khẩu</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" />
            @if($errors->updatePassword->has('password_confirmation'))
                <small class="text-danger">{{ $errors->updatePassword->first('password_confirmation') }}</small>
            @endif
        </div>

        <div class="d-flex justify-content-end align-items-center">
            <button type="submit" class="btn btn-success">Lưu</button>
            @if (session('status') === 'password-updated')
                <span class="badge badge-success ml-3">Đã lưu.</span>
            @endif
        </div>
    </form>
</section>
