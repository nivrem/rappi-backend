<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Coordinate;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
class Matrix extends Controller
{
    protected $matrix;
    protected $total;
    public function __construct() {
        
        $this->matrix=array();
        $this->total=0;
    }
    
    public function updateMatrix() {
        $x=Input::get('x');
        $y=Input::get('y');
        $z=Input::get('z');
        $w=Input::get('w');
        $this->getMatrix();
        $n=count($this->matrix);
        \Log::error("n= ".$n);
        $validation=Validator::make(["x"=>$x,"y"=>$y,"z"=>$z,"w"=>$w], ["x"=>"required|numeric|min:1|max:".  $n,
                "y"=>"required|numeric|min:1|max:".  $n,
                "z"=>"required|numeric|min:1|max:".  $n,
                "w"=>"required|numeric|integer"]);
        if (!$validation->passes())
            return Response::json(array("errors" => trans("lang.error.validation_updateMatrix")));
        --$x;--$y;--$z;
        for($i=0;$i<$n;$i++){
            for($j=0;$j<$n;$j++){
                for($k=0;$k<$n;$k++){
                    if ($i==$x && $j==$y && $k==$z){
                        $this->matrix[$i][$j][$k]->saveCoordinate($w);
                        $this->saveMatrix();
                        return Response::json(array("msg" => trans("lang.coordinate_saved",["x"=>($x+1),"y"=>($y+1),"z"=>($z+1),"w"=>$w]),"errors"=>""));
                    }
                }   
            }
        }
    }
    
    public function fetchMatrix() {
        $x1=Input::get('x1');
        $y1=Input::get('y1');
        $z1=Input::get('z1');
        $x2=Input::get('x2');
        $y2=Input::get('y2');
        $z2=Input::get('z2');
        $this->getMatrix();
        $n=count($this->matrix);
        $validation=Validator::make(["x1"=>$x1,"y1"=>$y1,"z1"=>$z1,"x2"=>$x2,"y2"=>$y2,"z2"=>$z2], ["x1"=>"required|numeric|min:1|max:".  $n,
                "y1"=>"required|numeric|min:1|max:".  $n,
                "z1"=>"required|numeric|min:1|max:".  $n,
                "x2"=>"required|numeric|min:1|max:".  $n,
                "y2"=>"required|numeric|min:1|max:".  $n,
                "z2"=>"required|numeric|min:1|max:".  $n,
                ]);
        if (!$validation->passes())
            return Response::json(array("errors" => trans("lang.error.validation_updateMatrix")));
        --$x1;--$y1;--$z1;--$x2;--$y2;--$z2;
        for($i=0;$i<$n;$i++){
            for($j=0;$j<$n;$j++){
                for($k=0;$k<$n;$k++){
                    if (($i>=$x1 && $j>=$y1 && $k>=$z1) && ($i<=$x2 && $j<=$y2 && $k<=$z2)){
                        $this->sumCoordinates($this->matrix[$i][$j][$k]->getCoordinate());
                    }
                }   
            }
        }
        return Response::json(array("msg" => trans("lang.query",["suma"=>$this->total]),"errors"=>""));
        
    }
    
    public function createMatrix() {
        $n=Input::get('matriz');
        $this->matrix=array();
        for($i=0;$i<$n;$i++){
            for($j=0;$j<$n;$j++){
                for($k=0;$k<$n;$k++){
                    $this->matrix[$i][$j][$k]=new Coordinate(0);
                }   
            }
        }
        $this->saveMatrix();
    }
    
    private function sumCoordinates($value){
        $this->total+=$value;
    }
    
    private function saveMatrix(){
        $path=base_path('database/persistencia');
        $f=  fopen($path."/matrix.txt", "w+");
        fwrite($f,  serialize($this->matrix));
        fclose($f);
    }
    
    private function getMatrix(){
        $f=  fopen(base_path('database/persistencia/')."matrix.txt", "r");
        $matrix="";
        while(!feof($f)){
            $matrix.=  fgets($f);
        }
        $this->matrix=  unserialize($matrix);
        fclose($f);
    }
    
}
