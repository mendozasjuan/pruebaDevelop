<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Encargo;
use Excel;

class ExcelController extends Controller
{
    public function import(Request $request)
	{
		$datos = [];
	    Excel::load($request->excel, function($reader) use (&$datos) {

	        $excel = $reader->get();
	        //dd($excel->toJson());
	        // iteracción
	        
	        $reader->each(function($sheet) use (&$datos) {
	        	$sheet->each(function($row) use (&$datos) {
	        		array_push($datos,
	        			[ 
	        				$row->albaran,
	        				$row->destinatario,
	        				$row->direccion,
	        				$row->poblacion,
	        				$row->cp,
	        				$row->provincia,
	        				(empty($row->telefono)) ? 0 : $row->telefono,
	        				$row->observaciones,
	        				$row->fecha
	        			]
	        		);
	        		
    			});
    			 
	        });
	       
	    
	    });

	    if($request->ajax()) {
			return response()->json($datos);
		}
	    
	}
}
