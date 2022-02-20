<?php

namespace App\Http\Controllers;

use App\Helpers\APIHelpers;
use App\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VisitorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function __construct()
    {
        $this->middleware('auth:api' , ['except' => ['create']]);
    }

    public function index()
    {
        //
    }
    // create visitor
    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'unique_id' => 'required',
            'fcm_token' => 'required',
            'type' => 'required' // 1 -> iphone ---- 2 -> android
        ]);

        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 , 'Missing Required Fields' , 'بعض الحقول مفقودة' , null , $request->lang);
            return response()->json($response , 406);
        }

        $last_visitor = Visitor::where('unique_id' , $request->unique_id)->first();
        if($last_visitor){
            $visitor = $last_visitor;
        }else{
            $visitor = new Visitor();
            $visitor->unique_id = $request->unique_id;
            $visitor->fcm_token = $request->fcm_token;
            $visitor->type = $request->type;
            $visitor->save();
        }


        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $visitor , $request->lang);
        return response()->json($response , 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
