<?php
// validar metodo correcto
if ($method != 'DELETE'){
    $utils->SendResponse(405, "Método no permitido.", $method." no soportado. favor de utilizar DELETE.");
}

$id = isset($matches[3]) ? $matches[3] : '';

// validar parametros requeridos
if($id == ''){
    $utils->SendResponse(400, "Petición erronea.", "Faltan datos en la petición.");
}

require ('classes/Employees.php');

$obj = new Employees();
$obj->deleteEmployee($id);
?>