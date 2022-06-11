<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Carbon\Carbon;
use DataTables;
use PDF;
use Validator;

use App\Models\User;
use App\Models\Parking;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $data['title']      =   'Laporan Parkir';
        $parking            =   Parking::join('users as created_by', 'created_by.id', 'parkings.created_by')
                                        ->join('users as updated_by', 'updated_by.id', 'parkings.updated_by')
                                        ->select('parkings.*', 'created_by.fullname as created_by', 'updated_by.fullname as updated_by')
                                        ->orderBy('parkings.created_at', 'desc');

        if($request->startEntrance && $request->endEntrance && $request->startOut && $request->endOut) {
            $startEntrance  =   $request->startEntrance;
            $endEntrance    =   $request->endEntrance;
            $startOut       =   $request->startOut;
            $endOut         =   $request->endOut;

            $data['parking']    =   $parking->whereBetween('start_date', [$startEntrance, $endEntrance])
                                            ->whereBetween('end_date', [$startOut, $endOut])
                                            ->get();
        } else {
            $data['parking']    =   $parking->get();
        }

        return view('report.index', $data);
    }

    public function printReport()
    {
        $parking    =   Parking::join('users as created_by', 'created_by.id', 'parkings.created_by')
                                ->join('users as updated_by', 'updated_by.id', 'parkings.updated_by')
                                ->select('parkings.*', 'created_by.fullname as created_by', 'updated_by.fullname as updated_by')
                                ->orderBy('parkings.created_at', 'desc')->get();

        $pdf        =   PDF::loadview('report.print', ['parking' => $parking])->setPaper('letter', 'landscape');
        // return $pdf->download('report.pdf');
        return $pdf->stream();
    }
}
