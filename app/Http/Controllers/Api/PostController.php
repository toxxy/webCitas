<?php

namespace App\Http\Controllers\Api;

use App\Models\Citas2;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Requests\StorePostRequest;

//***API SWAGGER DOCUMENTATION***
//https://www.bacancytechnology.com/blog/laravel-swagger-integration

/**
* @OA\Info(title="Novolex API", version="0.1")
*/ 
class PostController extends Controller
{
/**
 * @OA\Get(
 *     path="/api/Citas",
 *     summary="This is to vie cites",
 *       @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="User's email",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
 *     @OA\Response(response="200", description="An example endpoint")
 * )
 */

    public function index()
    {
        $Citas2s = Citas2::all();
        return 'DATA INSIDE' . '<br>' . '<br>' . "\n $Citas2s";
        /**Esto es un ejemplo */
    }

    public function store(StorePostRequest $request)
    {
        $Citas2s = Citas2::create($request->all());
        return new PostResource($Citas2s);
    }

    public function update(StorePostRequest $request, Citas2 $Citas2s)
    {
        $Citas2s->update($request->all());
        return new PostResource($Citas2s);
    }

    public function destroy(StorePostRequest $request, Citas2 $Citas2s)
    {
        $Citas2s->delete();
        return new PostResource('borraste' + $Citas2s);

    }

    public function show(Citas2 $Citas2s)
    {
        return new PostResource($Citas2s);
    }

    // public function create()
    // {
    //     //
    // }

    // public function edit(Citas2 $Citas2s)
    // {
    //     //
    // }
}
