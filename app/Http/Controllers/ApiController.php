<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ApiController extends Controller
{
    public function users(Request $req)
    {
        $crearuser = User::create([
            'Nombre' => 'test',
            'Apellidos' => '2',
            'Email' => 'test4@test.com',
            'Email_verified_at' => null,
            'Password' => 'test123',
            'rfc' => '123aaaoahsdha',
        ]);
        return "informacion del usuario:".$crearuser;
    }

    public function index()
    {
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
