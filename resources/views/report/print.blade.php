<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <title>Print Report Parking</title>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    </head>

    <body>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nomor Parkir</th>
                    <th>Nomor Polisi</th>
                    <th>Biaya</th>
                    <th>Waktu Masuk</th>
                    <th>Waktu Keluar</th>
                    <th>Petugas Masuk</th>
                    <th>Petugas Keluar</th>
                </tr>
            </thead>

            <tbody>
                @php
                    $no = 1;
                @endphp

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
                        <td>{{ date('d F Y, H:i:s', strtotime($row->start_date)) }}</td>
                        <td>
                            @if($row->end_date)
                                {{ date('d F Y, H:i:s', strtotime($row->end_date)) }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $row->created_by }}</td>
                        <td>
                            @if($row->end_date)
                                {{ $row->updated_by }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>