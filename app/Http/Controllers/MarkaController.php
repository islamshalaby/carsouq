<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Helpers\APIHelpers;
use App\Marka;
use App\MarkaType;
use App\TypeModel;

class MarkaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api' , ['except' => ['get_marka','get_marka_types','get_type_model']]);
    }

    //nasser code
    // get ad categories for create ads
    public function get_marka(Request $request) {
        if($request->lang == 'en'){
            $data['markat'] = Marka::where('deleted' , '0')->select('id' , 'title_en as title')->get();
        }else{
            $data['markat'] = Marka::where('deleted' , '0')->select('id' , 'title_ar as title')->get();
        }
        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
        return response()->json($response , 200);
    }
    public function get_marka_types(Request $request,$marka_id) {
        if($request->lang == 'en'){
            $data['marka_types'] = MarkaType::where('marka_id',$marka_id)->where('deleted' , '0')->select('id' , 'title_en as title')->get();
        }else{
            $data['marka_types'] = MarkaType::where('marka_id',$marka_id)->where('deleted' , '0')->select('id' , 'title_ar as title')->get();
        }
        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
        return response()->json($response , 200);
    }
    public function get_type_model(Request $request,$marka_type_id) {
        if($request->lang == 'en'){
            $data['year_models'] = TypeModel::where('marka_type_id',$marka_type_id)->where('deleted' , '0')->select('id' , 'year as title')->get();
        }else{
            $data['year_models'] = TypeModel::where('marka_type_id',$marka_type_id)->where('deleted' , '0')->select('id' , 'year as title')->get();
        }
        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
        return response()->json($response , 200);
    }
}
