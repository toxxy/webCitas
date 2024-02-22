<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Citas2;

class Citas2Controller extends Controller
{

    public function index()
    {
        return Citas2::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'orden' => 'required',
            'descripcion' => '  ',
            'status' => 'required',
            'FechaProgramada' => 'required',
            'FechaRealizada' => 'required',
            'RFC' => 'required',
            'Nombre' => 'required',
        ]);
        return Citas2::create($request->all());
    }

    public function show(string $id)
    {
        return Citas2::find($id);
    }

    public function update(Request $request, string $id)
    {
        $actualizar = Citas2::find($id);
        $actualizar->update($request->all());
        return $actualizar;
    }

    public function destroy(string $id)
    {
        return Citas2::destroy($id);
    }

    public function searchn($name)
    {
        return Citas2::where('Nombre', 'like', '%' . $name . '%')->get();
    }

    public function searcho($orden)
    {
        return Citas2::where('orden', 'like', '%' . $orden . '%')->get();
    }
}
