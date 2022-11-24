<?php
// validar metodo correcto
if ($method != 'POST'){
    $utils->SendResponse(405, "Método no permitido.", $method." no soportado. favor de utilizar POST.");
}

$date = date("Y-m-d");

$name = isset($data['name']) ? $data['name'] : '';
$role = isset($data['role']) ? $data['role'] : '';
$salary = isset($data['salary']) ? $data['salary'] : '';
$status = isset($data['status']) ? $data['status'] : 1;
$hire_date = isset($data['hire_date']) ? $data['hire_date'] : $date;

// validar parametros requeridos
if(
    $name == ''
    || $role == ''
    || $salary == ''
){
    $utils->SendResponse(400, "Petición erronea.", "Faltan datos en la petición.");
}

require ('classes/Employees.php');
$obj = new Employees();
$obj->saveEmployee(0, $name, $role, $salary, $status, $hire_date);
die();
?>