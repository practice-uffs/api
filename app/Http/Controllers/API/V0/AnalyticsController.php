<?php

namespace App\Http\Controllers\API\V0;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Analytics;
use Illuminate\Support\Facades\Validator;


class AnalyticsController extends Controller
{
    /**
    * @return Response
    */
    public function index()
    {
        $analytics = Analytics::all();
        return response()->json(
            $analytics, 
            Response::HTTP_OK
        ); 
    }

    /**
        * @return Response
        */
    public function store(Request $request)
    {
        $request['user_id'] = auth()->id();

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'app_id' => 'required|exists:apps,id',
            'action' => 'required|string',
            'key' => 'required|string',
            'value' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()], 
                Response::HTTP_BAD_REQUEST
            ); 
        }

        $analytics = Analytics::create($request->all());

        return response()->json(
            $analytics, 
            Response::HTTP_CREATED
        ); 
    }

    /**
        * @param  int  $id
        * @return Response
        */
    public function show(Request $request)
    {
        $analytics = Analytics::where('id', $request->id)->first();

        return response()->json(
            $analytics, 
            Response::HTTP_OK
        ); 
    }

    /**
        * @param  int  $id
        * @return Response
        */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'app_id' => 'sometimes|exists:apps,id',
            'action' => 'sometimes|string',
            'key' => 'sometimes|string',
            'value' => 'sometimes'
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()], 
                Response::HTTP_BAD_REQUEST
            ); 
        }

        Analytics::where('id', $request->id)->update($request->all());
        $analytics = Analytics::where('id', $request->id)->first();
        if ($analytics == NULL) {
            return response()->json(
                ['error' => "Registro com id $request->id não encontrado"],
                Response::HTTP_NOT_FOUND
            ); 
        }

        return response()->json(
            $analytics, 
            Response::HTTP_OK
        ); 
    }

    /**
        * @param  int  $id
        * @return Response
        */
    public function destroy(Request $request)
    {
        Analytics::where('id', $request->id)->delete();

        return response()->json(
            NULL, 
            Response::HTTP_OK
        ); 
    }
}
