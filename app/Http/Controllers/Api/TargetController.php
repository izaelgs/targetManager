<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\targetRequest;
use App\Models\Target;
use DateTime;
use Illuminate\Http\Request;

class TargetController extends Controller
{

    public function index()
    {
        try {
            // return Target::get();
            return auth('api')->user()->targets;
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 401);
        }
    }


    public function store(targetRequest $request)
    {
        try {
            $data = $request->all();

            $diff = strtotime(date('Y-m-d')) - strtotime($data['deadline']);
            $interval = round($diff / 86400) + 30;

            $data['user_id'] = auth('api')->user()->id;

            // $urgency = $interval > 0 || $interval < 60 ? 2 ** $interval : 1;
            // $urgency_log = log($urgency, 2);
            // $yield = (($data['gain'] / $data['cost']) * ($data['priority'] / 5)) / 10;
            // $data['sugested_priority'] = round($urgency_log * $yield);

            $target = Target::create($data);

            if (isset($data['categories'])) {
                $target->categories()->sync($data['categories']);
            }

            return response()->json([
                'message' => __('messages.createTarget'),
                'data' => $target,
            ]);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 401);
        }
    }


    public function show($id)
    {
        try {
            $target = auth('api')->user()->targets()->with('categories', 'stages')->find($id);
            return response()->json($target);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 401);
        }
    }


    public function update(targetRequest $request, $id)
    {
        try {
            $target = auth('api')->user()->targets::find($id);
            $target->update($request->all());

            if (isset($data['categories'])) {
                $target->categories()->sync($data['categories']);
            }

            return response()->json([
                'message' => __('messages.updateTarget')
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 401);
        }
    }


    public function destroy($id)
    {
        try {
            $target = auth('api')->user()->targets()->find($id);
            $target->delete();

            return response()->json([
                'message' => __('messages.deleteTarget')
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 401);
        }
    }
}
