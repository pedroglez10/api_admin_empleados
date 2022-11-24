<?php
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Allow: GET, POST, PUT, DELETE");

include 'classes/Utils.php';
$utils = new Utils();

$allowedResources = [
	'employees'
];

$allowedSubResources = [
	'beneficiaries'
];

$matches = explode("/", $_SERVER["REQUEST_URI"]);
$_GET['resource'] = $matches[2];
$_GET['subresource'] = isset($matches[4]) ? $matches[4] : '';

if ( !in_array( $_GET['resource'], $allowedResources ) ) {
	$utils->SendResponse(400, "Petición erronea.", "Recurso solicitado '".$_GET['resource']."' desconocido.");
}

if ( !in_array( $_GET['subresource'], $allowedSubResources ) && isset($matches[4])) {
	$utils->SendResponse(400, "Petición erronea.", "Sub-recurso solicitado '".$_GET['subresource']."' desconocido.");
}

$method = $_SERVER['REQUEST_METHOD'];

switch ( strtoupper( $method ) ) {	
    case 'GET':
		if($_GET['subresource'] != '' && isset($matches[4]))
			require_once('resources/'.$_GET['resource'].'/'.$_GET['subresource'].'/'.$_GET['subresource'].'.php');
		else
			require_once('resources/'.$_GET['resource'].'/'.$_GET['resource'].'.php');
        break;	
	case 'POST':
		$data = json_decode(file_get_contents('php://input'), true);
		if($_GET['subresource'] != '' && isset($matches[4]))
			require_once('resources/'.$_GET['resource'].'/'.$_GET['subresource'].'/add.php');
		else
			require_once('resources/'.$_GET['resource'].'/add.php');
		break;
	case 'PUT':
		$data = json_decode(file_get_contents('php://input'), true);
		if($_GET['subresource'] != '' && isset($matches[4]))
			require_once('resources/'.$_GET['resource'].'/'.$_GET['subresource'].'/edit.php');
		else
			require_once('resources/'.$_GET['resource'].'/edit.php');
		break;
	case 'DELETE':
		if($_GET['subresource'] != '' && isset($matches[4]))
			require_once('resources/'.$_GET['resource'].'/'.$_GET['subresource'].'/delete.php');
		else
			require_once('resources/'.$_GET['resource'].'/delete.php');
		break;
}
?>