<?php

namespace App\Http\Controllers\API\V0;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

use App\Models\WellBeingQuestionnaire;


class WellBeingQuestionnaireController extends Controller
{
    /**
        * @return Response
    */
    public function index()
    {
        $wellBeingQuestionnaire = WellBeingQuestionnaire::where('user_id', auth()->id())->get();
        return response()->json(
            $wellBeingQuestionnaire, 
            Response::HTTP_OK
        ); 
    }


    /**
        * @param  Request  $request
        * @return Response
    */
    public function store(Request $request)
    {
        $request['user_id'] = auth()->id();

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'type' => 'required|string',
            'data' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()], 
                Response::HTTP_BAD_REQUEST
            ); 
        }

        $wellBeingQuestionnaire = WellBeingQuestionnaire::create($request->all());

        return response()->json(
            $wellBeingQuestionnaire, 
            Response::HTTP_CREATED
        ); 
    }


    /**
        * @param  Request  $request
        * @return Response
    */
    public function show(Request $request)
    {
        $wellBeingQuestionnaire = WellBeingQuestionnaire::where('id', $request->id)->first();

        return response()->json(
            $wellBeingQuestionnaire, 
            Response::HTTP_OK
        ); 
    }

    /**
        * @param  Request  $request
        * @return Response
    */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string',
            'data' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()], 
                Response::HTTP_BAD_REQUEST
            ); 
        }

        WellBeingQuestionnaire::where('id', $request->id)->update($request->all());
        $wellBeingQuestionnaire = WellBeingQuestionnaire::where('id', $request->id)->first();
        if ($wellBeingQuestionnaire == NULL) {
            return response()->json(
                ['error' => "Registro com id $request->id não encontrado"],
                Response::HTTP_NOT_FOUND
            ); 
        }

        return response()->json(
            $wellBeingQuestionnaire, 
            Response::HTTP_OK
        ); 
    }

    /**
        * @param  Request  $request
        * @return Response
    */
    public function destroy(Request $request)
    {
        $result = WellBeingQuestionnaire::where('id', $request->id)->delete();

        if ($result != 1) {
            return response()->json(
                ['error' => "Registro com id $request->id não encontrado"],
                Response::HTTP_NOT_FOUND
            ); 
        }

        return response()->json(
            NULL, 
            Response::HTTP_OK
        ); 
    }
}
