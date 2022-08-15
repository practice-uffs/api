<?php

namespace App\Http\Controllers\API\V0;
use App\Http\Controllers\Controller;
use App\Services\AcademicCalendarService;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AcademicCalendarController extends Controller
{
    protected AcademicCalendarService $academicCalendarService;

    public function __construct()
    {
        $this->academicCalendarService = new AcademicCalendarService();
    }

    /**
    * @return Response
    */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'campus' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()], 
                Response::HTTP_BAD_REQUEST
            ); 
        }

        $calendars = $this->academicCalendarService->getCalendars();

        return response()->json(
            $calendars, 
            Response::HTTP_OK
        ); 
    }

    public function getByMonth(Request $request) {
        $validator = Validator::make($request->all(), [
            'month' => 'required|numeric|min:0|max:11',
            'year' => 'required|numeric|digits:4',
            'campus' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()], 
                Response::HTTP_BAD_REQUEST
            ); 
        }

        $calendars = $this->academicCalendarService->getCalendarEventsByMonth($request['month'], $request['year'], $request['campus']);

        return response()->json(
            $calendars, 
            Response::HTTP_OK
        ); 
    }

    public function getByDate(Request $request) {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date_format:Y-m-d',
            'campus' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()], 
                Response::HTTP_BAD_REQUEST
            ); 
        }

        $calendars = $this->academicCalendarService->getCalendarEventsByDate($request['date'], $request['campus']);

        return response()->json(
            $calendars, 
            Response::HTTP_OK
        ); 
    }

}