<?php

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}
	
	protected function responseJson($content,$statusCode=200,$type="json"){
		// $responseType = $this::getResponseType($type);
		// if(!$this::isJson($content) and ($type == "json")){
		// 	$content = json_encode($content);
		// } 
		// $response = Response::make($content, $statusCode);
		// $response->header('Content-Type', $responseType);
		
		// return $response;

		return Response::json($content,$statusCode);
	
	}

	protected function responseJsonError($content,$statusCode=400,$type="json"){
		// $responseType = $this::getResponseType($type);
		// if(!$this::isJson($content) and ($type == "json")){
		// 	$content = json_encode($content);
		// }
		// $response = Response::make($content, $statusCode);
		// $response->header('Content-Type', $responseType);
		
		// return $response;

		return Response::json($content,$statusCode);
	
	}

	protected function responseJsonErrorValidation($messages,$statusCode=400,$type="text"){
		$responseType = $this::getResponseType($type);
		$output = "<ul>";
		foreach ($messages->all('<li>:message</li>') as $message){
		    $output .= $message;
		}
		$output .= "</ul>";
		// if(!$this::isJson($output) and ($type == "json")){
		// 	$output = json_encode($output);
		// }
		// $response = Response::make($output, $statusCode);
		// $response->header('Content-Type', $responseType);
		
		// return $response;
		return Response::json($output,$statusCode);
	
	}

	protected function _getStatusCodeMessage($status)
	{
		// these could be stored in a .ini file and loaded
		// via parse_ini_file()... however, this will suffice
		// for an example
		$codes = Array(
				200 => 'OK',
				400 => 'Bad Request',
				401 => 'Unauthorized',
				402 => 'Payment Required',
				403 => 'Forbidden',
				404 => 'Not Found',
				500 => 'Internal Server Error',
				501 => 'Not Implemented',
		);
		return (isset($codes[$status])) ? $codes[$status] : '';
	}



	private function isJson($string) {
		if(is_array($string)){
			return false;
		} else {
			json_decode($string);
		 	return (json_last_error() == JSON_ERROR_NONE);
		}
		 
	}

	private function getResponseType($type){

		switch ($type) {
			case 'text':
				return "text/plain";
				break;
			case 'json':
				return "application/json";
				break;
			
			default:
				return "text/plain";
				break;
		}
	}

    public function checkPermissions($user,$user2){
	
	    if($user == $user2){
	         return true;
	    } else {
	    	return false;
	    }
    }



}
