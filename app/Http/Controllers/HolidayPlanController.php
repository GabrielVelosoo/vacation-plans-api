<?php

namespace App\Http\Controllers;

use App\Models\HolidayPlan;
use App\Http\Controllers\Controller;
use App\Http\Requests\HolidayPlan as HolidayPlanRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

class HolidayPlanController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    //GET Retrieve all holiday plans
    public function index()
    {
        
        $plans = HolidayPlan::all();

        if($plans == '[]'){
            return response()->json([
                'message' => 'No holiday plans found'
            ], 404);
        }

        return response()->json($plans, 200);

    }

    /**
     * Store a newly created resource in storage.
     */
    //POST Create a new holiday plan
    public function store(HolidayPlanRequest $request)
    {

        $plan = HolidayPlan::create($request->all());

        return response()->json($plan, 201);

    }

    /**
     * Display the specified resource.
     */
    //GET Retrieve a specific holiday plan by ID
    public function show($id)
    {

        $plan = HolidayPlan::find($id);

        if($plan === null){
            return response()->json([
                'message' => 'Holiday plan not found'
            ], 404);
        }

        return response()->json($plan, 200);

    }

    /**
     * Update the specified resource in storage.
     */
    //PUT Full update of an existing holiday plan
    public function update(Request $request, $id)
    {

        $plan = HolidayPlan::find($id);

        if($plan === null) {
            return response()->json([
                'message' => 'Unable to update. Holiday plan not found'
            ], 404);
        }

        $rules = [
            'title' => 'required|string|max:150',
            'description' => 'required|string',
            'date' => [
                'required',
                'date_format:Y-m-d',
                Rule::unique('holiday_plans')->ignore($id),
            ],
            'location' => 'required|string|max:150',
            'participants' => 'nullable',
        ];

        //PATCH Partial update of an existing holiday plan
        if($request->method() === 'PATCH'){
            
            $dynamicRules = array();

            //going through all the defined rules
            foreach($rules as $input => $rule){

                //collect only the rules applicable to the arbitrary parameters of the PATCH request
                if(array_key_exists($input, $request->all())){
                    $dynamicRules[$input] = $rule;
                }

            }

            $request->validate($dynamicRules);

        }else{

            $request->validate($rules);

        }

        $plan->update($request->all());

        return response()->json($plan, 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    //DELETE Delete a holiday plan
    public function destroy($id)
    {
        
        $plan = HolidayPlan::find($id);

        if($plan === null){
            return response()->json([
                'message' => 'Unable to delete. Holiday plan not found',
            ], 404);
        }

        $plan->delete();

        return response()->json([
            'message' => 'Plan deleted',
        ], 200);

    }

    //GET Trigger PDF generation for a specific holiday plan
    public function generatePdf($id){

        $plan = HolidayPlan::find($id);

        if($plan === null){
            return response()->json([
                'message' => 'Unable to generate pdf. Holiday plan not found',
            ], 404);
        }

        $pdf = Pdf::loadView('api.pdf.vacation_plan', ['plan' => $plan]);

        return $pdf->download('vacation_plan_' . $id . '.pdf');

    }

}
