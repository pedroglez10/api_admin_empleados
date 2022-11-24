<?php
// validar metodo correcto
if ($method != 'GET'){
    $utils->SendResponse(405, "Método no permitido.", $method." no soportado. favor de utilizar GET.");
}

require ('classes/Employees.php');
$obj = new Employees();

$id_employee = isset($matches[3]) ? $matches[3] : '';
$id = isset($matches[5]) ? $matches[5] : '';

if($id != '')
    $data = $obj->getBeneficiary($id_employee, $id);
else
    $data = $obj->getBeneficiaries($id_employee);

if (empty($data)){
    $utils->SendResponse(404, "No encontró", "No hay resultado.");
}
else{
    $utils->SendResponse(200, "", "", $data);
}
?>