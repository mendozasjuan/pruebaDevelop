<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Excel;

class ExcelController extends Controller
{
    public function import(Request $request)
	{
	    Excel::load($request->excel, function($reader) {

	        $excel = $reader->get();
	        dd($excel->toJson());
	        // iteracción
	        /*$reader->each(function($row) {

	            $user = new User;
	            $user->name = $row->nombre;
	            $user->email = $row->email;
	            $user->password = bcrypt('secret');
	            $user->save();

	        });*/
	    
	    });

	    return "Terminado";
	}
}
