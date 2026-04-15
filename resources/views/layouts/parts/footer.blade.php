 <!-- Main Footer -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2026-2030 <a href="https://cafe.com">caffe.com</a>.</strong>
    All rights reserved.
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

</body>
</html>
