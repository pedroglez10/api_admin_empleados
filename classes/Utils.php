<?php

class Utils {
    /**
	* Envia respuesta de la petición
	*
	* @author Pedro Garcia Glez <garciaglez@outlook.com>
	* @param $status_code (int) Estatus de la respuesta
	* @param $type_response (string) Tipo informativo de la respuesta
	* @param $msg (string) Mensaje informativo de la respuesta
	* @param $info (array) Información extra
	* @return (object) Respuesta de la petición
	*/
	public function SendResponse($status_code, $type_response, $msg, $info=[]){
		http_response_code($status_code);

		switch($status_code) {
			case 200:
			case 201:
			case 204:
				$success = true;
				break;
			default:
				$success =  false;
				break;
		}

		if($type_response != "" && !empty($info)) {
			$default_data = [
				'name' => $type_response,
				'message' => $msg,
				'status' => $status_code
			];

			$data = array_merge($default_data,$info);
		}
		else if(empty($info)) {
			$data = [
				'name' => $type_response,
				'message' => $msg,
				'status' => $status_code
			];
		}
		else {
			$data = $info;
		}

		echo json_encode(
			[
				'success' => $success,
				'data' => $data
			]
		);

		die();
	}
}