<?php
// validar metodo correcto
if ($method != 'PUT'){
    $utils->SendResponse(405, "Método no permitido.", $method." no soportado. favor de utilizar PUT.");
}

$date = date("Y-m-d");

$id = isset($matches[5]) ? $matches[5] : '';
$id_employee = isset($matches[3]) ? $matches[3] : '';
$name = isset($data['name']) ? $data['name'] : '';
$relationship = isset($data['relationship']) ? $data['relationship'] : '';
$birth_date = isset($data['birth_date']) ? $data['birth_date'] : $date;
$gender = isset($data['gender']) ? $data['gender'] : '';

// validar parametros requeridos
if(
    $id == ''
    || $id_employee == ''
    || $name == ''
    || $relationship == ''
    || $gender == ''
){
    $utils->SendResponse(400, "Petición erronea.", "Faltan datos en la petición.");
}

require ('classes/Employees.php');

$obj = new Employees();
$obj->saveBeneficiary($id, $id_employee, $name, $relationship, $birth_date, $gender);
?>