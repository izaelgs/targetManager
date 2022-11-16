<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\stageRequest;
use App\Models\Stage;
use Illuminate\Http\Request;

class StageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // try {
        //     return auth('api')->user()->targets()->stages;
        // } catch (\Throwable $th) {
        //     return response()->json($th->getMessage(), 401);
        // }

            return response()->json('aaaaaaaaaaaaaaaaaaa meu ombro ta doendo', 401);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(stageRequest $request)
    {
        try {
            $data = $request->all();

            $target = Stage::create($data);

            return response()->json(['message' => 'Etapa registrada  XD']);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 401);
        }
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(stageRequest $request, $id)
    {
        try {
            $stage = Stage::find($id);
            $stage->update($request->all());

            return response()->json(['message' => 'Etapa atualizada  :D'], 200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 401);
        }
    }

    public function patch(Request $request, $id)
    {
        try {
            $data = $request->all();
            $stage = Stage::find($id);
            $stage->update($data);

            $message = $data['status'] ? 'Concluída' : 'Reativada';

            return response()->json(['message' => 'Etapa ' . $message], 200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $stage = Stage::find($id);
            $stage->delete();

            return response()->json(['message' => 'Etapa apagada  :('], 200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 401);
        }
    }
}
