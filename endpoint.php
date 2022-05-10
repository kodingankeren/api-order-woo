<?php 
	require_once plugin_dir_path( __FILE__ ) . '/function.php';

	header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
	header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
	header('Access-Control-Max-Age: 1000');
	header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
	header('Content-Type: application/json');

	$method = $_SERVER['REQUEST_METHOD'];
	$response = [
		"status" => "404"
	];
	if ($method == "GET") {
		$pages = isset($_GET["pages"]) ? $_GET["pages"] : 1;
		$per_page = isset($_GET["per_page"]) ? $_GET["per_page"] : 1;
		$start = isset($_GET["start"]) ? $_GET["start"] : false;
		$end = isset($_GET["end"]) ? $_GET["end"] : false;
		$response = belajar_api_woo_get($pages,$per_page,$start,$end);
	}elseif ($method == "POST") {
		$param = array();
		$param['qty'] = isset($_POST["qty"]) ? $_POST["qty"] : 1;
		$param['id_produk'] = isset($_POST["id_produk"]) ? $_POST["id_produk"] : false;

		$param['first_name'] = isset($_POST["first_name"]) ? $_POST["first_name"] : false;
		$param['last_name'] = isset($_POST["last_name"]) ? $_POST["last_name"] : false;
		$param['company'] = isset($_POST["company"]) ? $_POST["company"] : false;
		$param['id_user'] = isset($_POST["id_user"]) ? $_POST["id_user"] : false;
		$param['email'] = isset($_POST["email"]) ? $_POST["email"] : false;
		$param['phone'] = isset($_POST["phone"]) ? $_POST["phone"] : false;
		$param['address_1'] = isset($_POST["address_1"]) ? $_POST["address_1"] : false;
		$param['address_2'] = isset($_POST["address_2"]) ? $_POST["address_2"] : false;
		$param['city'] = isset($_POST["city"]) ? $_POST["city"] : false;
		$param['state'] = isset($_POST["state"]) ? $_POST["state"] : false;
		$param['postcode'] = isset($_POST["postcode"]) ? $_POST["postcode"] : false;

		$response = belajar_api_woo_post($param);
	}elseif ($method == "PUT") {
		parse_str(file_get_contents("php://input"),$request);
		$param = array();
		$param['id_order'] = isset($request["id_order"]) ? $request["id_order"] : false;

		$param['qty'] = isset($request["qty"]) ? $request["qty"] : false;

		$param['first_name'] = isset($request["first_name"]) ? $request["first_name"] : false;
		$param['last_name'] = isset($request["last_name"]) ? $request["last_name"] : false;
		$param['company'] = isset($request["company"]) ? $request["company"] : false;
		$param['id_user'] = isset($request["id_user"]) ? $request["id_user"] : false;
		$param['email'] = isset($request["email"]) ? $request["email"] : false;
		$param['phone'] = isset($request["phone"]) ? $request["phone"] : false;
		$param['address_1'] = isset($request["address_1"]) ? $request["address_1"] : false;
		$param['address_2'] = isset($request["address_2"]) ? $request["address_2"] : false;
		$param['city'] = isset($request["city"]) ? $request["city"] : false;
		$param['state'] = isset($request["state"]) ? $request["state"] : false;
		$param['postcode'] = isset($request["postcode"]) ? $request["postcode"] : false;
		$param['status_order'] = isset($request["status_order"]) ? $request["status_order"] : false;

		if ($param['id_order'] == false) {
			$response["message"] = "id_order harus ada";
		}else{
			$response = belajar_api_woo_put($param);
		}

	}elseif ($method == "DELETE") {
		parse_str(file_get_contents("php://input"),$request);
		$param = array();
		$param['id_order'] = isset($request["id_order"]) ? $request["id_order"] : false;
		if ($param['id_order'] == false) {
			$response["message"] = "id_order harus ada";
		}else{
			$response = belajar_api_woo_delete($param['id_order']);
		}
	}


	echo json_encode($response);
