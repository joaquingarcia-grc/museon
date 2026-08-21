<!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">¿Salir del sistema?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Tu sesión se cerrará y vas a tener que volver a iniciar sesión para acceder.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-primary" href='<?php echo base_url();?>login/sesion'>Salir</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo base_url();?>vendor/js/jquery.min.js"></script>
    <script src="vendor/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo base_url();?>vendor/js/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo base_url();?>vendor/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="<?php echo base_url();?>vendor/js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?php echo base_url();?>vendor/js/chart-area-demo.js"></script>
    <script src="vendor/js/chart-pie-demo.js"></script>
    <!--script de data table-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha384-VFQrHzqBh5qiJIU0uGU5CIW3+OWpdGGJM9LBnGbuIH2mkICcFZ7lPd/AAtI7SNf7" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js" integrity="sha384-/RlQG9uf0M2vcTw3CX7fbqgbj/h8wKxw7C3zu9/GxcBPRKOEcESxaxufwRXqzq6n" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/v/dt/jszip-3.10.1/dt-2.3.8/b-3.2.6/b-html5-3.2.6/datatables.min.js" integrity="sha384-EeU0n4QZjxAoReXoJNbWIsbYCFLxs2/K2hduD34zPKu6IIhf91rgg8v/AR12rnk0" crossorigin="anonymous"></script>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <!-- <script>
        var table = new DataTable('#tablaclientes',{
            language :{
                url:'https://cdn.datatables.net/plug-ins/2.2.2/i18n/es-ES.json'
            },
            layout: {
                bottomStart{
                    buttons:['excel', 'pdf', 'copy', 'csv', 'print']
                }
            },
        });
    </script>
    -->

    <script>
        $(document).ready(function() {
            $('#tablaclientes').DataTable({language:{url:"https://cdn.datatables.net/plug-ins/2.1.7/i18n/es-ES.json"},
            layout:{
                bottomStart:{
                    buttons: ['excel', 'pdf', 'copy', 'csv', 'print'],
                }
            }

        });
        });

    </script>
</body>

</html>