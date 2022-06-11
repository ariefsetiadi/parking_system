<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Carbon\Carbon;
use DataTables;
use Validator;

use App\Models\User;
use App\Models\Parking;

class ParkingController extends Controller
{
    public function getIn()
    {
        $data['title']      =   'Parkir Masuk';

        if(request()->ajax()) {
            return datatables()->of(Parking::with(['created_by'])->where('status', FALSE)->orderBy('created_at', 'desc')->get())
                ->editColumn('datetime', function($data) {
                    return date('d F Y, H:i:s', strtotime($data->start_date));
                })
                ->rawColumns(['datetime'])->addIndexColumn()->make(true);
        }

        return view('parking.entrance', $data);
    }

    public function postIn(Request $request)
    {
        $validate   =   Validator::make($request->all(), [
                            'police_number' =>  'required|min:3|regex:/^[a-zA-Z0-9]*$/'
                        ],
                        [
                            'police_number.required'    =>  'Nomor Polisi wajib diisi',
                            'police_number.min'         =>  'Nomor Polisi minimal 3 karakter',
                            'police_number.regex'       =>  'Nomor Polisi hanya boleh huruf dan angka',
                        ]);

        if($validate->fails()) {
            return response()->json(['errors' => $validate->errors()]);
        }

        $parkir                 =   new Parking;
        $parkir->unique_number  =   strtoupper(uniqid());
        $parkir->police_number  =   strtoupper($request->police_number);
        $parkir->start_date     =   Carbon::now()->toDateTimeString();
        $parkir->created_by     =   Auth::user()->id;
        $parkir->updated_by     =   Auth::user()->id;
        $parkir->save();

        return response()->json(['success' => 'Kendaraan berhasil masuk']);
    }

    public function getOut()
    {
        $data['title']      =   'Parkir Keluar';

        if(request()->ajax()) {
            return datatables()->of(Parking::with(['updated_by'])->where('status', TRUE)->orderBy('updated_at', 'desc')->get())
                ->editColumn('start_date', function($data) {
                    return date('d F Y, H:i:s', strtotime($data->start_date));
                })
                ->editColumn('end_date', function($data) {
                    return date('d F Y, H:i:s', strtotime($data->end_date));
                })
                ->rawColumns(['start_date', 'end_date'])->addIndexColumn()->make(true);
        }

        return view('parking.out', $data);
    }

    public function postOut(Request $request)
    {
        $parkir =   array(
                        'end_date'      =>    $request->end_date,
                        'status'        =>    TRUE,
                        'cost'          =>    $request->cost,
                        'updated_by'    =>    Auth::user()->id,
                    );

        Parking::whereId($request->parkir_id)->update($parkir);

        return response()->json(['success' => 'Kendaraan berhasil keluar']);
    }

    public function getData(Request $request)
    {
        if($request->ajax()) {
            $output =   "";
            $data   =   Parking::where('status', FALSE)->where('unique_number', 'LIKE', '%' . $request->search . '%')->first();

            if($data) {
                $start  =   Carbon::parse($data->start_date);
                $end    =   Carbon::now();
                $diff   =   $start->diffInHours($end);

                if($diff < 1) {
                    $diff = '1';
                } else {
                    $diff;
                }

                $cost       =   '3000';
                $totalCost  =   $diff * $cost;

                $output .=  '<input type="hidden" name="parkir_id" id="parkir_id" value="' . $data->id . '" readonly>' .

                            '<div class="form-group">' .
                                '<label>Nomor Polisi</label>' .
                                '<input type="text" class="form-control" value="' . $data->police_number . '" readonly>' .
                            '</div>' .

                            '<div class="row">'.
                                '<div class="col-md-6">'.
                                    '<div class="form-group">' .
                                        '<label>Waktu Masuk</label>' .
                                        '<input type="text" class="form-control" value="' . $data->start_date . '" readonly>' .
                                    '</div>' .
                                '</div>' .
                                '<div class="col-md-6">'.
                                    '<div class="form-group">' .
                                        '<label>Waktu Keluar</label>' .
                                        '<input type="text" class="form-control" name="end_date" id="end_date" value="' . Carbon::now() . '" readonly>' .
                                    '</div>' .
                                '</div>' .
                            '</div>' .

                            '<div class="row">' .
                                '<div class="col-md-6">' .
                                    '<div class="form-group form-group-lg">' .
                                        '<label>Waktu Parkir (Jam)</label>' .
                                        '<input type="text" class="form-control" value="' . $diff . '" readonly>' .
                                    '</div>' .
                                '</div>' .
                                '<div class="col-md-6">' .
                                    '<div class="form-group form-group-lg">' .
                                        '<label>Biaya (Rp.)</label>' .
                                        '<input type="hidden" name="cost" id="cost" value="' . $totalCost . '" readonly>' .
                                        '<input type="text" class="form-control" value="Rp. ' . number_format($totalCost, 0,',','.') . '" readonly>' .
                                    '</div>' .
                                '</div>' .
                            '</div>';

                return response($output);
            }
        }
    }
}
