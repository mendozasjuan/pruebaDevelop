<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Encargo;
use Excel;

class ExcelController extends Controller
{
    public function import(Request $request)
	{
		$datos = array();
	    Excel::load($request->excel, function($reader) use (&$datos) {

	        $excel = $reader->get();
	        //dd($excel->toJson());
	        // iteracción
	        
	        $reader->each(function($sheet) use (&$datos) {
	        	$sheet->each(function($row) use (&$datos) {
	        		array_push($datos,
	        			[
		        			'albaran' => $row->albaran,
		        			'destinatario' => $row->destinatario,
		        			'direccion' => $row->direccion,
			            	'poblacion' => $row->poblacion,
			            	'cp' => $row->cp,
			            	'provincia' => $row->provincia,
			            	'telefono' => (empty($row->telefono)) ? 0 : $row->telefono,
			            	'observaciones' => $row->observaciones,
			            	'fecha' => $row->fecha
	        			]
	        		);
	        		//$datos.=$row;
		            /*$encargo = new Encargo;
		            $encargo->albaran = $row->albaran;
		            $encargo->destinatario = $row->destinatario;
		            $encargo->direccion = $row->direccion;
		            $encargo->poblacion = $row->poblacion;
		            $encargo->cp = $row->cp;
		            $encargo->provincia = $row->provincia;
		            $encargo->telefono = (empty($row->telefono)) ? 0 : $row->telefono;
		            $encargo->observaciones = $row->observaciones;
		            $encargo->fecha = $row->fecha;
		            $encargo->save();*/
    			});
    			 
	        });
	       
	    
	    });
	    $datos = json_encode($datos);
	    return view('layouts.default', compact('datos'));
//return json_encode($datos);
	    
	}
}
