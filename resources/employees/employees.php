<?php
// validar metodo correcto
if ($method != 'GET'){
    $utils->SendResponse(405, "Método no permitido.", $method." no soportado. favor de utilizar GET.");
}

require ('classes/Employees.php');
$obj = new Employees();

$id = isset($matches[3]) ? $matches[3] : '';

if($id != '')
    $data = $obj->getEmployee($id);
else
    $data = $obj->getEmployees();

if (empty($data)){
    $utils->SendResponse(404, "No encontró", "No hay resultado.");
}
else{
    $utils->SendResponse(200, "", "", $data);
}
?>