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
	        					'dato' => (empty($row->telefono)) ? 0 : $row->telefono,
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
	        			//exit(!array_key_exists('dato',$errors['albaran']));

	        		array_push($datos,
	        				[
	        				'albaran' => (empty($errors['albaran'])) ? $row->albaran : $errors['albaran'][0]['dato'],
	        				'destinatario' => (empty($errors['albaran'])) ? $row->destinatario : $errors['destinatario'][0]['dato'],
	        				'direccion' => (empty($errors['direccion'])) ? $row->direccion : $errors['direccion'][0]['dato'],
	        				'poblacion' => (empty($errors['poblacion'])) ? $row->poblacion : $errors['poblacion'][0]['dato'],
	        				'cp' => $row->cp ,
	        				'provincia' => (empty($errors['provincia'])) ? $row->provincia : $errors['provincia'][0]['dato'],
	        				'telefono' => (empty($row->telefono)) ? 0 : $row->telefono,
	        				'observaciones' => (empty($errors['observaciones'])) ? $row->observaciones : $errors['observaciones'][0]['dato'],
	        				'fecha' => $row->fecha,
	        				'errors' => $errors]
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
			if($clave == 'albaran'){
				if(empty($valor['dato'])){
					array_push($errors['albaran'],
						[
							'vacio' => true
					]);
				}
				if(!is_numeric($valor['dato'])){
					array_push($errors['albaran'],
						[
							'numerico' => false
					]);
				}
				if(strlen($valor['dato']) > $valor['longitud']){
					array_push($errors['albaran'],
						[
							'longitud' => strlen($valor['dato']),
							'dato' => substr($valor['dato'],0,$valor['longitud']).'<span class="errorData">'.substr($valor['dato'],$valor['longitud']).'</span>'
					]);
				}
			}else if($clave != 'fecha'){
				if(empty($valor['dato'])){
				array_push($errors[$clave],
					[
						'vacio' => true
				]);
				}
				if(!is_string($valor['dato'])){
					array_push($errors[$clave],
						[
							'texto' => false
					]);
				}
				if(strlen($valor['dato']) > $valor['longitud']){
					array_push($errors[$clave],
						[
							'longitud' => strlen($valor['dato']),
							'dato' => substr($valor['dato'],0,$valor['longitud']).'<span class="errorData">'.substr($valor['dato'],$valor['longitud']).'</span>'
					]);
				}
			}
		}

		

		

		return $errors;
	}
}
