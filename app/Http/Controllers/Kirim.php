<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use App\Kirim as MdlKirim;

class Kirim extends Controller
{
    public $input_analog;
    public $input_digital;

    private $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function index() {
        $validator = Validator::make($this->request->all(), [
            'analog' => 'required',
            'digital' => 'required',
        ], [
            'analog.required' => 10,
            'digital.required' => 20
        ]);

        $this->input_analog = $this->request->input('analog');
        $this->input_digital = $this->request->input('digital');

        if($validator && $this->input_analog >= 0 && $this->input_analog <= 1024 && ($this->input_digital == "0" || $this->input_digital == "1")){
            
            $sendData = MdlKirim::Kirim($this->input_analog, $this->input_digital);
            if($sendData){
                return response()->json([
                    'status' => 1,
                    'message' => 'Success'
                ], 200);
            }
            return response()->json([
                'status' => 0,
                'message' => 'Something went wrong'
            ], 200);
        }
        return response()->json([
            'status' => 0,
            'message' => 'Something required'
        ], 200);
    }
}
