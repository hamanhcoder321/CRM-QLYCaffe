<section>
    <p class="text-muted">Khi tài khoản bị xóa, toàn bộ dữ liệu và tài nguyên sẽ bị xóa vĩnh viễn. Trước khi xóa, hãy tải xuống dữ liệu bạn muốn giữ lại.</p>

    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirm-user-deletion">
        Xóa tài khoản
    </button>

    <div class="modal fade" id="confirm-user-deletion" tabindex="-1" role="dialog" aria-labelledby="confirmUserDeletionLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <form method="post" action="{{ route('profile.destroy') }}">
            @csrf
            @method('delete')
            <div class="modal-header">
              <h5 class="modal-title" id="confirmUserDeletionLabel">Bạn có chắc muốn xóa tài khoản không?</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <p class="text-muted">Khi tài khoản bị xóa, toàn bộ dữ liệu sẽ bị xóa vĩnh viễn. Vui lòng nhập mật khẩu để xác nhận.</p>
              <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input id="password" name="password" type="password" class="form-control" placeholder="Mật khẩu" required />
                @if($errors->userDeletion->has('password'))
                    <small class="text-danger">{{ $errors->userDeletion->first('password') }}</small>
                @endif
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
              <button type="submit" class="btn btn-danger">Xóa tài khoản</button>
            </div>
          </form>
        </div>
      </div>
    </div>
</section>
