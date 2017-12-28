<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Encargo;
use Excel;
use Validator;
use Carbon\Carbon;

class ExcelController extends Controller
{
    public function import(Request $request)
	{
		$datos = [];
		if($request->ajax()) {
			 $rules = [
			 	'excel' => 'required'
			 ];

    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
        return response()->json([
        	'archivoVacio' => true,
        	'mensaje' => 'Es Necesario seleccionar un archivo'
        ]);
    }

	    Excel::load($request->excel, function($reader) use (&$datos) {
	    	$excel = $reader->get();
	    	$reader->formatDates(true, 'd/m/Y');
	       
	        $reader->each(function($sheet) use (&$datos,$reader) {
	        	//exit($datos);
	        	$sheet->each(function($row) use (&$datos,$reader) {
	        		$fecha = explode('-',$row->fecha);
	        		//exit(print_r($fecha));
	        		/*if(!is_array($fecha)){
	        			exit($row->fecha);
	        		}
	        		if(checkdate($fecha[0],$fecha[1],$fecha[2])){

	        			$fecha = Carbon::createFromDate($fecha[2], $fecha[0], $fecha[1]);
	        			exit("fecha: ".$row->fecha." nueva: ".$fecha);
	        			//$fecha = Carbon::createFromFormat('d/m/Y', $row->fecha);	
	        		}*/

	        		//$row->fecha->format('d/m/Y');
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
	        				'fecha' => [
	        					'dato' => $row->fecha,
	        					'longitud' => 0,
	        				]
	        			];
	        			//exit(print_r($arrayDatos));
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
			//if($clave != 'fecha')
				$error = $this->validar($clave,$valor['dato'],$valor['longitud'],'serv');

			array_push($errors[$clave],$error);
		}
		return $errors;
	}

	public function validar($campo=null,$dato=null,$longitud=null,$lugar='client'){
		if($lugar == 'client')
		{
			$request = request()->all();
			//exit(print_r($request));
			$campo = $request['campo'];
			$dato = $request['dato'];
			$longitud = $request['longitud'];
		}
		
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
		}else{
			//exit(Carbon::parse($dato)->format('d/m/Y h:i'));
			if(empty($dato)){
				array_push($error,
					[
						'vacio' => true,
						'dato' => '',
						'mensaje' => 'El campo no puede estar vacio'
				]);
			}else{
				$regexFecha = '/^([0-2][0-9]|3[0-1])(\/|-)(0[1-9]|1[0-2])\2(\d{4})(\s)([0-1][0-9]|2[0-3])(:)([0-5][0-9])$/';

				$fecha = $dato;

				if ( !preg_match($regexFecha, $fecha, $matchFecha) ) {
			        array_push($error,
						[
							'mensaje' => 'El Valor no tiene formato valido de Fecha dd/mm/aaaa hh:mm',
							'dato' => $fecha
					]);
				}
			}
		}

		if($lugar=='serv')
			return $error;
		else
			return response()->json($error);
	}

	public function exportar(Request $request)
	{
		$data = $request->all();
		//exit(print_r($data));
		$datos = [];
		foreach ($data['albaran'] as $llave => $encargo) {
			//for ($i=0; $i < count($encargo); $i++) { 
			   //$fecha = explode('/', $data['fecha'][$llave]);
			   $fecha = Carbon::createFromFormat('d/m/Y H:i', $data['fecha'][$llave])->toDateTimeString();
			   //exit(print_r($fecha));
				array_push($datos,[
					'albaran' => $data['albaran'][$llave],
					'destinatario' =>$data['destinatario'][$llave],
					'direccion' =>$data['direccion'][$llave],
					'poblacion' =>$data['poblacion'][$llave],
					'cp' =>$data['cp'][$llave],
					'provincia' =>$data['provincia'][$llave],
					'telefono' =>$data['telefono'][$llave],
					'observaciones' =>$data['observaciones'][$llave],
					'fecha' => $fecha,
				]);
			//}
			
			/*Encargo::create([
				'albaran' => $encargo['albaran'],
				/*'destinatario' =>$ecargo->destinatario,
				'direccion' =>$encargo->direccion,
				'poblacion' =>$encargo->poblacion,
				'cp' =>$encargo->cp,
				'provincia' =>$encargo->provincia,
				'telefono' =>$encargo->telefono,
				'observaciones' =>$encargo->observaciones,
				'fecha' => $encargo->fecha,
     		]);*/
		}
		if(Encargo::insert($datos)){
			return json_encode([
				'exito' => true,
				'mensaje' => 'Datos Exportados con Exito'
			]);
		}
	}
}
