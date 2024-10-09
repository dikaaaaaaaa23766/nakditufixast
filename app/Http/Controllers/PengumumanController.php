<?php

// app/Http/Controllers/PengumumanController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengumuman;
use Illuminate\Notifications\Notifiable;

class PengumumanController extends Controller

{
    public function index()
    {
        return Pengumuman::all();
    }

    public function store(Request $request)
    {
        $pengumuman = Pengumuman::create($request->all());
        return response()->json($pengumuman, 201);
    }

    public function show($id)
    {
        return Pengumuman::find($id);
    }

    public function update(Request $request, $id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->update($request->all());

        return response()->json($pengumuman, 200);
    }

    public function destroy($id)
    {
        Pengumuman::destroy($id);

        return response()->json(null, 204);
    }
}

