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
    public $input_suhuf;
    public $input_suhuc;
    public $input_lembab;

    private $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function index() {
        $validator = Validator::make($this->request->all(), [
            'analog' => 'required',
            'digital' => 'required',
            'sc' => 'required',
            'sf' => 'required',
            'lb' => 'required',
        ], [
            'analog.required' => 10,
            'digital.required' => 20,
            'sc.required' => 30,
            'sf.required' => 40,
            'lb.required' => 50,
        ]);

        $this->input_analog = $this->request->input('analog');
        $this->input_digital = $this->request->input('digital');
        $this->input_suhuf = $this->request->input('sf');
        $this->input_suhuc = $this->request->input('sc');
        $this->input_lembab = $this->request->input('lb');

        if(
            $validator && 
            $this->input_analog >= 0 && 
            $this->input_analog <= 1024 && 
            ($this->input_digital == "0" || $this->input_digital == "1") &&
            (is_float($this->input_suhuc) || is_numeric($this->input_suhuc)) &&
            (is_float($this->input_suhuf) || is_numeric($this->input_suhuf)) &&
            $this->input_lembab >= 0 &&
            $this->input_lembab <= 100
        ){
            
            $sendData = MdlKirim::Kirim($this->input_analog, $this->input_digital, $this->input_suhuc, $this->input_suhuf, $this->input_lembab);
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
            'message' => 'Something required',
            'q' => $this->input_analog,
            'w' => $this->input_digital,
            'e' => $this->input_suhuc,
            'f' => $this->input_suhuf,
            'g' => $this->input_lembab
        ], 200);
    }
}
