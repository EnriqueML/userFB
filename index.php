<?php 
	
	$metodo = $_SERVER[ 'REQUEST_METHOD' ];

	// $metodo es POST_
	if( $metodo == 'POST' ){
		// Capturamos contenido de la peticion
		$requesBody = file_get_contents( 'php://input' );


		// decodificamos contenido a json
		$json = json_decode( $requesBody );

		// Obtenemos datos de facebook
		if( ! empty( $json->originalRequest->data->sender->id ) ){
			// Capturamos ID sender y traemos datos de usuario
			$senderId = $json->originalRequest->data->sender->id;
			$FBdata =  GetDataFB( $senderId );
			$FBIdUser = ' ' . $FBdata->id;
			$FBNameUser = ' ' . $FBdata->first_name;
			$FBApellidoUser = ' ' . $FBdata->last_name;

		}else{
			$FBNameUser = '';
			$FBApellidoUser = '';
			
		}


		// Verificamos variables a remplazar
		if( TRUE ){
			// Campos a buscar
			$search = array(
						'[[name_user]]'
					, '[[apellido_user]]'
				);

			// Campos de reemplazo en la busqueda
			$replace = array(
						$FBNameUser
					, $FBApellidoUser
				);


			// Reemplazamos valores en el HTML
			$requesBody = str_replace( $search, $replace, $requesBody );

			// Decodificamos contenido a json
			$json = json_decode( $requesBody );

		}


		// Creamos respuesta 
		$response = new \stdClass();
		$response->speech = $json->result->fulfillment->speech;
		$response->source = "webhook";

		// Salvamos msn por defecto
		$msnDefault = $json->result->fulfillment->messages;
		$response->messages = $msnDefault;



		// Respondemos peticion
		echo json_encode( $response );


	}else{
		echo 'Metodo no aceptado';

	}



	/*
	|-------------------------------------------------------------------------------
	| Funcion procesar tipo producto
	|-------------------------------------------------------------------------------
	*/

	function GetDataFB( $senderId ){
		$access_token = 'EAABuSxlAYZAYBAFr2IYZCyfCCDCYntPVfAo2t8qIEJG1PUNd7ZA0XGmg3tHDedHVDQZBuOomvB4CFZBeh6LZBYwZCGKW7DQwJOltoJWiHqaSEVtLGldlVYfQe4wp7s6xTYIlYk7ikoZC61xzitwNMRN38vKS0dWYj6eBr3W5DjzoRX5JgqviwLPye4KbeGWXev4ZD';
		$api_url = "https://graph.facebook.com/v2.12/$senderId?fields=first_name%2Clast_name&access_token=" . $access_token;

		$json_return = file_get_contents( $api_url );
		$res = json_decode( $json_return );
		return $res;

	}



?>
