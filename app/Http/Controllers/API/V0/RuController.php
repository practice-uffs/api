<?php

namespace App\Http\Controllers\API\V0;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\RuScraping;
use Illuminate\Validation\Rule;

class RuController extends Controller
{
    protected RuScraping $ruScraping;

    public function __construct()
    {
        $this->ruScraping = new RuScraping();
    }

    public function index(Request $request) {
        $validator = Validator::make($request->all(), [
            'campus' => [
                'required',
                'string',
                Rule::in(['cerro-largo', 'chapeco', 'erechim', 'laranjeiras-do-sul', 'passo-fundo', 'realeza']),
            ]
        ], [
            'in' => 'The :attribute must be one of the following values: :values'
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()], 
                Response::HTTP_BAD_REQUEST
            ); 
        }

        $menu = $this->ruScraping->getMenuByCampus($request['campus']);

        return response()->json(
            $menu, 
            Response::HTTP_OK
        );
    }

    public function getByDate(Request $request) {
        $validator = Validator::make($request->all(), [
            'date' => [
                'required',
                'date_format:d/m/Y'
            ],
            'campus' => [
                'required',
                'string',
                Rule::in(['cerro-largo', 'chapeco', 'erechim', 'laranjeiras-do-sul', 'passo-fundo', 'realeza']),
            ]
        ], [
            'in' => 'The :attribute must be one of the following values: :values'
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()], 
                Response::HTTP_BAD_REQUEST
            ); 
        }

        $menu = $this->ruScraping->getMenuByDate($request['campus'], $request['date']);

        return response()->json(
            $menu, 
            Response::HTTP_OK
        ); 
    }

    public function getByWeekDay(Request $request) {
        $validator = Validator::make($request->all(), [
            'week-day' => [
                'required',
                'string',
                Rule::in(['seg', 'ter', 'qua', 'qui', 'sex', 'sab', 'dom']),
            ],
            'campus' => [
                'required',
                'string',
                Rule::in(['cerro-largo', 'chapeco', 'erechim', 'laranjeiras-do-sul', 'passo-fundo', 'realeza']),
            ]
        ], [
            'in' => 'The :attribute must be one of the following values: :values'
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()], 
                Response::HTTP_BAD_REQUEST
            ); 
        }

        $menu = $this->ruScraping->getMenuByWeekDay($request['campus'], $request['week-day']);

        return response()->json(
            $menu, 
            Response::HTTP_OK
        ); 
    }
}
