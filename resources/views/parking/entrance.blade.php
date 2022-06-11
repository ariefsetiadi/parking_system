@extends('layouts.master')

@push('css')
    <!-- Custom styles for this page -->
    <link href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <button class="btn btn-primary" id="btnAdd"><i class="fas fa-plus"></i> Parkir Masuk</button>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0" id="parkingTable">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nomor Parkir</th>
                            <th>Nomor Polisi</th>
                            <th>Waktu Masuk</th>
                            <th>Petugas Masuk</th>
                        </tr>
                    </thead>

                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Form Modal -->
        <div class="modal fade" data-backdrop="static" data-keyboard="false" id="formModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalHeading"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form method="post" id="parkingForm" class="form-horizontal" enctype="multipart/form-data">
                        @csrf

                        <div class="modal-body">
                            <div class="form-group">
                                <label>Nomor Polisi</label>
                                <input type="text" name="police_number" id="police_number" class="form-control" placeholder="Nomor Polisi">
                                <span class="text-danger" id="police_number_error"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success" id="btnSave"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <!-- Page level plugins -->
    <script src="{{ asset('assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('assets/js/demo/datatables-demo.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            // Ajax Display Data to DataTables
            var table   =   $('#parkingTable').DataTable({
                processing: true,
                serverSide: true,

                ajax:{
                    url: "{{ route('parking.getIn') }}",
                },
                oLanguage: {
                    sEmptyTable: 'Data Masih Kosong',
                    sZeroRecords: 'Tidak Ada Data Yang Sesuai'
                },

                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false
                    },
                    {
                        data: 'unique_number',
                        name: 'unique_number'
                    },
                    {
                        data: 'police_number',
                        name: 'police_number'
                    },
                    {
                        data: 'datetime',
                        name: 'datetime'
                    },
                    {
                        data: 'created_by.fullname',
                        name: 'created_by.fullname'
                    }
                ],

                columnDefs: [
                    {
                        targets: 0,
                        className: 'text-center',
                        width: '10%'
                    },
                ]
            });

            // Ajax Display Add Modal
            $('#btnAdd').click(function() {
                $('.modal-title').text("Parkir Masuk");
                $('#btnSave').text("Masuk");
                $('#parkingForm').trigger("reset");
                $('#formModal').modal("show");
            });

            // Ajax Submit Form
            $('#parkingForm').on('submit', function (e) {
                e.preventDefault();
                if($('#btnSave').text() == 'Masuk') {
                    $('#police_number_error').html();

                    $.ajax({
                        url: "{{ route('parking.postIn') }}",
                        method: 'POST',
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        dataType: 'json',

                        success: function(data) {
                            if(data.errors) {
                                if(data.errors.police_number) {
                                    $("#police_number_error").html(data.errors.police_number[0]);
                                    $("#police_number").addClass("is-invalid");
                                }
                            }

                            if(data.success) {
                                $('#parkingForm')[0].reset();
                                $('#formModal').modal('hide');
                                $('#parkingTable').DataTable().ajax.reload();

                                Swal.fire({
                                    title: 'Sukses',
                                    text: data.success,
                                    icon: 'success',
                                    timer: 2000
                                });
                            }
                        }
                    });
                }
            })
        });
    </script>
@endpush
