<?php

namespace App\Http\Controllers;


use App\Models\Gaji;
use App\Models\User;
use App\Models\Listpo;
use App\Models\Status;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ListpoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listpo = Listpo::all()->pluck('nama', 'id_statuses');
        $pegawai = User::where('level', '!=', '1')->get();
        return view('list_po.index', compact('listpo', 'pegawai'));
    }

    public function data(Request $request)
    {
        // $listpo = Listpo::with('status')->orderBy('id', 'desc')->get();
        $listpo = Listpo::select(DB::Raw('list_po.id as id, list_po.kode_po as kode_po, start_date as mulai, end_date as selesai, assigne as penugasan, status.nama as status, users.name as nama_user'))
        ->join('status', 'status.id', '=', 'list_po.id_statuses')
        ->join('users', 'users.id', '=', 'list_po.id_user');
        if (Auth::user()->level == 1) {
            if ($request->assigne != null) {
                $listpo = $listpo->where('list_po.assigne', '=', $request->assigne);
            } else {
            }
        } else {
            $listpo = $listpo->where('list_po.assigne', '=', auth()->user()->id);
        }

        return datatables()
            ->of($listpo->get())
            ->addIndexColumn()
            ->addColumn('aksi', function ($listpo) {
                if ($listpo->status == 3 || strtolower($listpo->status) == "selesai") {
                    return '
                    <div class="btn-group">
                        <button onclick="deleteData(`'. route('listpo.destroy', $listpo->id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                    </div>
                    ';
                } else {
                    return '
                    <div class="btn-group">
                    <button onclick="deleteData(`'. route('listpo.destroy', $listpo->id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                    <button onclick="editForm(`'. route('listpo.update', $listpo->id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    </div>
                    ';
                }
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id_penjualan)
    {
        $query_kode_po = Listpo::latest()->first() ?? new listpo();
        $kode_po = 'PO'. tambah_nol_didepan((int)$query_kode_po->id +1, 6);

        $listpo = new Listpo();
        $listpo->kode_po = $kode_po;
        $listpo->id_user = auth()->id();
        $listpo->id_penjualan = $id_penjualan;
        $listpo->id_statuses = 1;

        $listpo->save();

        // $query_update_status =

        $penjualan = Penjualan::find($id_penjualan);
        $penjualan->status = 2;
        $penjualan->update();

        return response()->json(null, 200);
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
      $assigne = User::where('id', '!=', 1)->pluck('name', 'id')->all();
        $status = Status::all();
        $listpo = Listpo::find($id);
        $user_input = User::where('id', $listpo['id_user'])->pluck('name')->all();

        return response()->json(['list' => $listpo, 'assigne' => $assigne, 'status' => $status, 'user_input' => $user_input]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {




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
        $listpo = Listpo::find($id);
        $listpo->update($request->all());

        if ($request->id_statuses == 3) {
            $gaji = new Gaji();
            $gaji->id_list_po = $id;
            $gaji->tanggal_selesai = date('Y-m-d H:i:s');
            $gaji->harga = 20000;
            $gaji->bonus = 0;
            $gaji->total = $gaji->harga + $gaji->bonus;
            $gaji->save();
        }

        return response()->json('Data berhasil disimpan', 200);
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

}
