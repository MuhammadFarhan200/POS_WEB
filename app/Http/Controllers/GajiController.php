<?php

namespace App\Http\Controllers;

use App\Models\User;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GajiController extends Controller
{
    /**
     * Display a listing of the resource.

     */
    public function index()
    {
        $pegawai = User::where('level', '!=', '1')->get();
        return view('gaji.index', compact('pegawai'));
    }

    public function data(Request $request)
    {
        // $listpo = Listpo::with('status')->orderBy('id', 'desc')->get();
        $gaji = DB::table('list_po')
            ->join('users', 'users.id', '=', 'list_po.assigne')
            ->join('penggajian', 'list_po.id', '=', 'penggajian.id_list_po')
            ->select('list_po.kode_po as kode', 'users.name as pegawai', 'list_po.updated_at as selesai',
                'penggajian.harga as harga', 'penggajian.bonus as bonus',
                DB::raw('penggajian.harga + penggajian.bonus as total'));

        if (Auth::user()->level == 1) {
            $awal = isset($request->tanggal_awal) ? $request->tanggal_awal : '';
            $akhir = isset($request->tanggal_akhir) ? $request->tanggal_akhir : '';
            // dd($awal, $akhir);

            $gaji = $gaji->where('list_po.id_statuses', '=', '3');

            if ($awal != null && $akhir != null && $request->assigne != null) {
                if ($request->tanggal_awal <= $request->tanggal_akhir) {
                    $gaji = $gaji->where('list_po.id_statuses', '=', '3')
                        ->where('list_po.assigne', '=', $request->assigne)
                        ->whereBetween('list_po.updated_at', [date($awal), date($akhir)]);
                }
            } elseif ($awal != null && $akhir != null && $request->assigne == null) {
                if ($request->tanggal_awal <= $request->tanggal_akhir) {
                    $gaji = $gaji->where('list_po.id_statuses', '=', '3')
                        ->whereBetween('list_po.updated_at', [date($awal), date($akhir)]);
                }
            } elseif ($request->assigne != null && $awal == null && $akhir == null) {
                $gaji = $gaji->where('list_po.id_statuses', '=', '3')
                    ->where('list_po.assigne', '=', $request->assigne);
            }
        } else {
            if ($request->tanggal_awal != null && $request->tanggal_akhir != null) {
                if ($request->tanggal_awal <= $request->tanggal_akhir) {
                    $gaji = $gaji->where('list_po.id_statuses', '=', '3')
                        ->where('list_po.assigne', '=', Auth::user()->id)
                        ->whereBetween('list_po.updated_at', [date($request->tanggal_awal), date($request->tanggal_akhir)]);
                }
            } else {
                $gaji = $gaji->where('list_po.id_statuses', '=', '3')
                    ->where('list_po.assigne', '=', Auth::user()->id);
            }
        }

        return datatables()
            ->of($gaji->get())
            ->addIndexColumn()
            // ->addColumn('aksi', function ($gaji) {
            //     return '
            //     <div class="btn-group">
            //         <button onclick="editForm(`'. route('gaji.update', $gaji->id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
            //         <button onclick="deleteData(`'. route('gaji.destroy', $gaji->id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
            //     </div>
            //     ';
            // })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function cetak(Request $request) {
        $awal = isset($request->tanggal_awal) ? $request->tanggal_awal : '';
        $akhir = isset($request->tanggal_akhir) ? $request->tanggal_akhir : '';

        $gaji = DB::table('list_po')
            ->join('users', 'users.id', '=', 'list_po.assigne')
            ->join('penggajian', 'list_po.id', '=', 'penggajian.id_list_po')
            ->select('list_po.kode_po as kode', 'users.name as pegawai', 'list_po.updated_at as selesai',
                'penggajian.harga as harga', 'penggajian.bonus as bonus',
                DB::raw('penggajian.harga + penggajian.bonus as total'))
            ->where('list_po.id_statuses', '=', '3');

        if (Auth::user()->level == 1) {
            if ($awal != null && $akhir != null && $request->assigne != null) {
                if ($request->tanggal_awal <= $request->tanggal_akhir) {
                    $gaji = $gaji->where('list_po.id_statuses', '=', '3')
                        ->where('list_po.assigne', '=', $request->assigne)
                        ->whereBetween('list_po.updated_at', [date($awal), date($akhir)])->get();
                }
            }
            $pegawai = DB::table('users')->where('id', '=', $request->assigne)->first();
        } else {
            if ($request->tanggal_awal != null && $request->tanggal_akhir != null) {
                if ($request->tanggal_awal <= $request->tanggal_akhir) {
                    $gaji = $gaji->where('list_po.id_statuses', '=', '3')
                        ->where('list_po.assigne', '=', Auth::user()->id)
                        ->whereBetween('list_po.updated_at', [date($request->tanggal_awal), date($request->tanggal_akhir)])->get();
                }
            } else {
                $gaji = $gaji->where('list_po.id_statuses', '=', '3')
                    ->where('list_po.assigne', '=', Auth::user()->id)->get();
            }
            $pegawai = DB::table('users')->where('id', '=', Auth::user()->id)->first();
        }

        // dd($request->all());
        // dd($pegawai);
        $html = view('gaji.cetak', ['gaji' => $gaji, 'pegawai' => $pegawai])->render();
        PDF::setOptions(['isHtml5ParserEnabled' => true]);
        $pdf = PDF::loadHTML($html);
        $pdf->setPaper('A5', 'potrait');
        return $pdf->stream('slip-gaji.pdf');
    }
}
