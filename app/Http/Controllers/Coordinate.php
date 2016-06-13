<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class Coordinate extends Controller
{
    protected $coordinate;
    public function __construct($coordinate=0) {
        $this->middleware('guess');
        $this->coordinate=0;
    }
    
    public function saveCoordinate($coordinate) {
        $this->coordinate=$coordinate;
    }
    public function getCoordinate() {
        return $this->coordinate;
    }
}
