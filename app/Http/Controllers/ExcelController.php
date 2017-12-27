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
	        			//exit('TEl '.(bool)empty($errors['telefono'][0]));

	        		//array_push($datos,$errors);
	        		array_push($datos,
        				[
	        				'albaran' => (empty($errors['albaran'][0][0])) ? $row->albaran : $errors['albaran'][0][0]['dato'],
	        				'destinatario' => (empty($errors['destinatario'][0][0])) ? $row->destinatario : $errors['destinatario'][0][0]['dato'],
	        				'direccion' => (empty($errors['direccion'][0][0])) ? $row->direccion : $errors['direccion'][0][0]['dato'],
	        				'poblacion' => (empty($errors['poblacion'][0][0])) ? $row->poblacion : $errors['poblacion'][0][0]['dato'],
	        				'cp' => (empty($errors['cp'][0][0])) ? $row->cp : $errors['cp'][0][0]['dato'],
	        				'provincia' => (empty($errors['provincia'][0][0])) ? $row->provincia : $errors['provincia'][0][0]['dato'],
	        				'telefono' => (empty($errors['telefono'][0][0])) ? $row->telefono : $errors['telefono'][0][0]['dato'],
	        				'observaciones' => (empty($errors['observaciones'][0][0])) ? $row->observaciones : $errors['observaciones'][0][0]['dato'],
	        				'fecha' => $row->fecha,
	        				'errors' => $errors
	        			]
	        		);
	        		
    			});
    			 
	        });
	       
	    
	    });
	    //exit(print_r($datos));
	    
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
			if($clave != 'fecha')
				$error = $this->validar($clave,$valor['dato'],$valor['longitud']);

			array_push($errors[$clave],$error);
		}
		return $errors;
	}

	public function validar($campo,$dato,$longitud){
		$error = [];
		if($campo != 'fecha'){
			if(empty($dato)){
				array_push($error,
					[
						'vacio' => true,
						'dato' => '',
						'mensaje' => 'El campo no puede estar vacio'
				]);
			}else{
				if(strlen($dato) > $longitud){
					array_push($error,
						[
							'longitud' => strlen($dato),
							'dato' => substr($dato,0,$longitud).'<span class="errorData">'.substr($dato,$longitud).'</span>',
							'mensaje' => "El Valor debe Tener una Longitud maxima de $longitud caracteres. El resto sera desechado"
					]);
				}

				if($campo == 'albaran'){
					if(!is_numeric($dato)){
						array_push($error,
							[
								'numerico' => false,
								'mensaje' => 'El Valor debe ser de tipo Numerico'
						]);
					}	
				}else{
					if(!is_string($dato)){
						array_push($error,
							[
								'texto' => false, 
								'El Valor debe ser de tipo texto'
						]);
					}
				}
			}
		}
		return $error;
	}
}
