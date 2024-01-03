<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Http\Resources\KaryawanResource;
use Illuminate\Support\Carbon;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawans = Karyawan::all();

        return KaryawanResource::collection($karyawans);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_induk' => 'required',
            'nama' => 'required',
            'alamat' => 'required',
            'tanggal_lahir' => 'required|date',
            'tanggal_bergabung' => 'required|date',
        ]);

        $karyawan = Karyawan::create($request->all());

        return new KaryawanResource($karyawan);
    }

    public function show($id)
    {
        $karyawan = Karyawan::findOrFail($id);

        return new KaryawanResource($karyawan);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nomor_induk' => 'required',
            'nama' => 'required',
            'alamat' => 'required',
            'tanggal_lahir' => 'required|date',
            'tanggal_bergabung' => 'required|date',
        ]);

        $karyawan = Karyawan::findOrFail($id);
        $karyawan->update($request->all());

        return new KaryawanResource($karyawan);
    }

    public function destroy($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $karyawan->delete();

        return response()->json(['message' => 'Karyawan berhasil dihapus']);
    }

    // TASK 2. Tampilkan 3 karyawan yang pertama kali bergabung.
    public function oldEmployee()
    {
        $karyawans = Karyawan::orderBy('tanggal_bergabung')->take(3)->get();

        if ($karyawans->isEmpty()) {
            return response()->json(['message' => 'No records found for the specified criteria.'], 404);
        }

        return KaryawanResource::collection($karyawans);
    }

    // TASK 3. Tampilkan daftar karyawan yang saat ini pernah mengambil cuti.
    public function havedCuties()
    {
        $karyawansOnLeave = Karyawan::whereHas('cutis')->get();

        $result = [];
        foreach ($karyawansOnLeave as $karyawan) {
            $totalCutiTaken = $karyawan->cutis()->count();

            $result[] = [
                'nomor_induk' => $karyawan->nomor_induk,
                'nama' => $karyawan->nama,
                'jumlah_cuti' => $totalCutiTaken,
            ];
        }

        return response()->json($result);
    }

    // TASK 4. Tampilkan sisa cuti setiap karyawan
    public function remainingLeaveQuota()
    {
        $karyawans = Karyawan::all();
        $result = [];

        foreach ($karyawans as $karyawan) {
            $cutis = $karyawan->cutis;

            // Filter cuti berdasarkan tahun sekarang
            $cutisThisYear = $cutis->filter(function ($cuti) {
                return Carbon::parse($cuti->tanggal_cuti)->year == date('Y');
            });

            // Jika ada cuti di tahun sekarang, hitung total cuti
            if ($cutisThisYear->count() > 0) {
                $totalCutiTaken = $cutisThisYear->sum('lama_cuti');
            } else {
                $totalCutiTaken = 0;
            }

            $sisaCuti = max(0, 12 - $totalCutiTaken);

            $result[] = [
                'nomor_induk' => $karyawan->nomor_induk,
                'nama' => $karyawan->nama,
                'sisa_cuti' => $sisaCuti,
                'total_cuti' => $totalCutiTaken
            ];
        }

        return response()->json($result);
    }

    public function findByNomorInduk($nomor_induk)
    {
        $karyawan = Karyawan::where('nomor_induk', $nomor_induk)->first();

        if (!$karyawan) {
            return response()->json(['message' => 'Karyawan not found'], 404);
        }

        return new KaryawanResource($karyawan);
    }


}
