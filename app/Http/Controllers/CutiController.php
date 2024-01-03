<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cuti;
use App\Http\Resources\CutiResource;

class CutiController extends Controller
{
    public function index()
    {
        $cutis = Cuti::orderBy('tanggal_cuti')->get();

        return CutiResource::collection($cutis);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_induk' => 'required',
            'tanggal_cuti' => 'required|date',
            'lama_cuti' => 'required|integer',
            'keterangan' => 'required',
        ]);

        $cuti = Cuti::create($request->all());

        return new CutiResource($cuti);
    }

    public function show($id)
    {
        $cuti = Cuti::findOrFail($id);

        return new CutiResource($cuti);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nomor_induk' => 'required',
            'tanggal_cuti' => 'required|date',
            'lama_cuti' => 'required|integer',
            'keterangan' => 'required',
        ]);

        $cuti = Cuti::findOrFail($id);
        $cuti->update($request->all());

        return new CutiResource($cuti);
    }

    public function destroy($id)
    {
        $cuti = Cuti::findOrFail($id);
        $cuti->delete();

        return response()->json(['message' => 'Cuti berhasil dihapus']);
    }
}
