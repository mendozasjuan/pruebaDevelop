<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Encargo;
use Excel;
use Validator;

class ExcelController extends Controller
{
    public function import(Request $request)
	{
		$datos = [];
		if($request->ajax()) {
	    Excel::load($request->excel, function($reader) use (&$datos) {

	        $excel = $reader->get();
 
        
	        //dd($excel->toJson());
	        // iteracción
	        
	        $reader->each(function($sheet) use (&$datos) {
	        	$sheet->each(function($row) use (&$datos) {
	        		$arrayDatos = [ 
	        				'albaran' => [
	        					'dato' => $row->albaran,
	        					'longitud' => 10
	        				],
	        				'destinatario' => [
	        					'dato' => $row->destinatario,
	        					'longitud' => 28
	        				],
	        				'direccion' => [
	        					'dato' => $row->direccion,
	        					'longitud' => 250
	        				],
	        				'poblacion' => [
	        					'dato' => $row->poblacion,
	        					'longitud' => 10
	        				],
	        				'cp' => [
	        					'dato' => $row->cp,
	        					'longitud' => 5
	        				],
	        				'provincia' => [
	        					'dato' => $row->provincia,
	        					'longitud' => 20
	        				],
	        				'telefono' => [
	        					'dato' => $row->telefono,
	        					'longitud' => 10
	        				],
	        				'observaciones' => [
	        					'dato' => $row->observaciones,
	        					'longitud' => 500
	        				],
	        				'fecha' => $row->fecha,
	        			];
	        		//'errors' => $errors
	        			$errors = $this->validarDatos($arrayDatos);
	        			//exit(print_r($errors));

	        		array_push($datos,
        				[
	        				'albaran' => (empty($errors['albaran'])) ? $row->albaran : $errors['albaran'][0]['dato'],
	        				'destinatario' => (empty($errors['destinatario'])) ? $row->destinatario : $errors['destinatario'][0]['dato'],
	        				'direccion' => (empty($errors['direccion'])) ? $row->direccion : $errors['direccion'][0]['dato'],
	        				'poblacion' => (empty($errors['poblacion'])) ? $row->poblacion : $errors['poblacion'][0]['dato'],
	        				'cp' => (empty($errors['cp'])) ? $row->cp : $errors['cp'][0]['dato'],
	        				'provincia' => (empty($errors['provincia'])) ? $row->provincia : $errors['provincia'][0]['dato'],
	        				'telefono' => (empty($errors['telefono'])) ? $row->telefono : $errors['telefono'][0]['dato'],
	        				'observaciones' => (empty($errors['observaciones'])) ? $row->observaciones : $errors['observaciones'][0]['dato'],
	        				'fecha' => $row->fecha,
	        				'errors' => $errors
	        			]
	        		);
	        		
    			});
    			 
	        });
	       
	    
	    });

	    
			return response()->json($datos);
		}
	    
	}

	public function validarDatos(Array $datos)
	{
		$errors = [
			'albaran' => [],
			'destinatario' => [],
			'direccion' => [],
			'poblacion' => [],
			'cp' =>  [],
			'provincia' => [],
			'telefono' => [],
			'observaciones' => [],
			'fecha' => []

		];

		foreach ($datos as $clave => $valor){

			if($clave != 'fecha'){
				if(empty($valor['dato'])){
					array_push($errors[$clave],
						[
							'vacio' => true,
							'dato' => $valor['dato']
					]);
				}else{
					if(strlen($valor['dato']) > $valor['longitud']){
						array_push($errors[$clave],
							[
								'longitud' => strlen($valor['dato']),
								'dato' => substr($valor['dato'],0,$valor['longitud']).'<span class="errorData">'.substr($valor['dato'],$valor['longitud']).'</span>'
						]);
					}

					if($clave == 'albaran'){
						if(!is_numeric($valor['dato'])){
							array_push($errors['albaran'],
								[
									'numerico' => false
							]);
						}	
					}else{
						if(!is_string($valor['dato'])){
							array_push($errors[$clave],
								[
									'texto' => false
							]);
						}
					}
				}
			}
			
			
		}
		return $errors;
	}
}
