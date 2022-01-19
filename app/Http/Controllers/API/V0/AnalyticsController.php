<?php

namespace App\Http\Controllers\API\V0;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Analytics;


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

        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'app_id' => 'required|exists:apps,id',
            'action' => 'required|string',
            'key' => 'required|string',
            'value' => 'required|string'
        ]);

        $analytics = Analytics::create($data);

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
        
        $data = $request->validate([
            'app_id' => 'sometimes|exists:apps,id',
            'action' => 'sometimes|string',
            'key' => 'sometimes|string',
            'value' => 'sometimes|string'
        ]);

        Analytics::where('id', $request->id)->update($data);
        $analytics = Analytics::where('id', $request->id)->firstOrFail();

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
