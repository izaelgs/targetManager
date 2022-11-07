<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Target;
use DateTime;
use Illuminate\Http\Request;

class TargetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return Target::get();
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();

            $diff = strtotime(date('Y-m-d')) - strtotime($data['deadline']);
            $interval = round($diff / 86400) + 30;

            $urgency = $interval > 0 || $interval < 60 ? 2 ** $interval : 1;
            $urgency_log = log($urgency, 2);
            $yield = (($data['gain'] / $data['cost']) * ($data['priority'] / 5)) / 10;
            $data['sugested_priority'] = round($urgency_log * $yield);

            $target = Target::create($data);

            if (isset($data['categories'])) {
                $target->categories()->sync($data['categories']);
            }

            return response()->json($target);
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
        try {
            $target = Target::find($id);
            return response()->json($target);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 401);
        }
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
        try {
            $target = Target::find($id);
            $target->update($request->all());

            if (isset($data['categories'])) {
                $target->categories()->sync($data['categories']);
            }

            return response()->json([
                'data' => [
                    'msg' => 'objetivo atualizado com suseso'
                ]
            ], 200);
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
            $target = Target::find($id);
            $target->delete();

            return response()->json([
                'data' => [
                    'msg' => 'objetivo deletado com suseso'
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 401);
        }
    }
}
