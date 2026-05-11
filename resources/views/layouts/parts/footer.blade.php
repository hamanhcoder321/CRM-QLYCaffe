 <!-- Main Footer -->
  <footer class="main-footer" style="background:#fff;border-top:1px solid #f3f4f6;color:#9ca3af;font-size:0.82rem;padding:12px 24px">
    <span style="color:#d1d5db">© 2026 <strong style="color:#7c3aed">M&T Cafe CRM</strong> — Hệ thống quản lý chuỗi cửa hàng</span>
    <span class="float-right d-none d-sm-inline-block" style="color:#d1d5db">
      {{ now()->format('H:i — d/m/Y') }}
    </span>
  </footer>
</div>

<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="{{ asset('Adminlte/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('Adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE -->
<script src="{{ asset('Adminlte/dist/js/adminlte.js') }}"></script>

  <!--datatable-->
<script src="https://cdn.datatables.net/2.3.7/js/dataTables.min.js"></script>

{{-- Chart.min.js và dashboard3.js chỉ load ở trang dashboard --}}
@stack('scripts')
<!-- cdn sweetalert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Truy cập bị từ chối!',
            text: '{{ session('error') }}',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Đã hiểu'
        });
    @endif
    
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Thành công!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif
</script>

@include('AI::partials.chat-bubble')
</body>
</html>
