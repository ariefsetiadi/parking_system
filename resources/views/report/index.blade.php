@extends('layouts.master')

@push('css')
    <!-- Custom styles for this page -->
    <link href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="card shadow mb-4">
        <div class="card-body">

            <form action="{{ route('report.index') }}" method="get">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <h5 class="text-center">Range Waktu Masuk</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="date" name="startEntrance" id="startEntrance" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="date" name="endEntrance" id="endEntrance" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <h5 class="text-center">Range Waktu Keluar</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="date" name="startOut" id="startOut" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="date" name="endOut" id="endOut" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success mr-3"><i class="fas fa-fw fa-filter"></i> Filter</button>
                    <a href="{{ route('report.printReport') }}" class="btn btn-primary" target="_blank"><i class="fas fa-fw fa-print"></i> Print</a>
                </div>
            </form>

            <hr>

            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0" id="parkingTable">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nomor Parkir</th>
                            <th>Nomor Polisi</th>
                            <th>Biaya</th>
                            <th>Petugas Masuk</th>
                            <th>Petugas Keluar</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $no=1; $no<=count($parking) ?>
                        @foreach ($parking as $row)
                            <tr>
                                <td class="text-center">{{ $no++ }}</td>
                                <td>{{ $row->unique_number }}</td>
                                <td>{{ $row->police_number }}</td>
                                <td class="text-right">
                                    @if($row->cost)
                                        @currency($row->cost)
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $row->created_by }}</td>
                                <td>
                                    @if($row->cost)
                                        {{ $row->updated_by }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
    </script>
@endpush
