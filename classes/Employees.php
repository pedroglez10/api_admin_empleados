<?php
    require_once('Main.php');

    class Employees extends Main {
        /**
        * Consulta los empleados
        *
        * @author Pedro Garcia Glez <garciaglez@outlook.com, garciaglez9210@gmail.com>
        * @return (array[object]) Lista de empleados
        */
        public function getEmployees() {
            $query = "SELECT 
                id,
                nombre name,
                puesto role,
                salario salary,
                estatus status,
                fecha_contratacion hire_date
            FROM empleados LIMIT 500;";
            
            return $this->Query($query, [], 2);
        }

        /**
        * Consulta un empleado en específico por su id
        *
        * @author Pedro Garcia Glez <garciaglez@outlook.com, garciaglez9210@gmail.com>
        * @param (int) $id Identificador del empleado
        * @return (object) Datos del empleado
        */
        public function getEmployee($id) {
            $query = "SELECT 
                id,
                nombre name,
                puesto role,
                salario salary,
                estatus status,
                fecha_contratacion hire_date
            FROM empleados
            WHERE id=:id;";
            
            return $this->Query($query, ['id'=>$id], 1);
        }

        /**
        * Agrega o modifica un empleado
        *
        * @author Pedro Garcia Glez <garciaglez@outlook.com, garciaglez9210@gmail.com>
        * @param (int) $id Identificador del empleado
        * @param (string) $name Nombre del empleado
        * @param (string) $role Puesto del empleado
        * @param (float) $salary Salario del empleado
        * @param (int) $status Estatus del empleado
        * @param (string) $hire_date Fecha de contratación del empleado
        * @return (object) Respuesta de la acción
        */
        public function saveEmployee($id, $name, $role, $salary, $status, $hire_date) {
            $params = [
                'id' => $id,
                'name' => $name,
                'role' => $role,
                'salary' => floatval($salary),
                'status' => intval($status),
                'hire_date' => $hire_date
            ];
          
            $action = $this->CallProcedure('sp_empleados_guardar', $params, 1);

            $data = [];
            if (array_key_exists('status_code', $action)) {
                $status_code = intval($action['status_code']);
            }
            else {
                $status_code = 201;
                $data = [
                    'id' => $action['id']
                ];
            }

            $this->SendResponse($status_code, $action['response'], $action['msg'], $data);
        }

        /**
        * Eliminar empleado
        *
        * @author Pedro Garcia Glez <garciaglez@outlook.com, garciaglez9210@gmail.com>
        * @param (int) $id Identificador del empleado
        * @return (object) Respuesta de la acción
        */
        public function deleteEmployee($id) {
            $params = [
                'id' => $id
            ];
            $action = $this->CallProcedure('sp_empleados_eliminar',$params,1);

            $data = [];
            if (array_key_exists('status_code', $action)) {
                $status_code = intval($action['status_code']);
            }
            else {
                $status_code = 200;
            }

            $this->SendResponse($status_code, $action['response'], $action['msg']);
        }

        /**
        * Consulta los beneficiarios
        *
        * @author Pedro Garcia Glez <garciaglez@outlook.com, garciaglez9210@gmail.com>
        * @param (int) $id_employee Identificador del empleado
        * @return (array[object]) Lista de beneficiarios
        */
        public function getBeneficiaries($id_employee) {

            $query = "SELECT 
                id,
                nombre name,
                parentesco relationship,
                fecha_nacimiento birth_date,
                sexo gender
            FROM beneficiarios 
            WHERE id_empleado=:id_employee;";
            
            return $this->Query($query, ['id_employee'=>$id_employee], 2);
        }

        /**
        * Consulta un beneficiario en específico por su id
        *
        * @author Pedro Garcia Glez <garciaglez@outlook.com, garciaglez9210@gmail.com>
        * @param (int) $id_employee Identificador del empleado
        * @param (int) $id Identificador del beneficiario
        * @return (object) Datos del beneficiario
        */
        public function getBeneficiary($id_employee, $id) {
            $query = "SELECT 
                id,
                nombre name,
                parentesco relationship,
                fecha_nacimiento birth_date,
                sexo gender
            FROM beneficiarios
            WHERE id=:id AND id_empleado=:id_employee;";

            $params = [
                'id_employee'=>$id_employee,
                'id'=>$id
            ];
            
            return $this->Query($query, $params, 1);
        }

        /**
        * Agrega o modifica un beneficiario
        *
        * @author Pedro Garcia Glez <garciaglez@outlook.com, garciaglez9210@gmail.com>
        * @param (int) $id Identificador del beneficiario
        * @param (int) $id_employee Identificador del empleado
        * @param (string) $name Nombre del beneficiario
        * @param (string) $relationship Parentesco del beneficiario
        * @param (string) $birth_date Fecha de nacimiento del beneficiario
        * @param (string) $gender Sexo del beneficiario
        * @return (object) Respuesta de la acción
        */
        public function saveBeneficiary($id, $id_employee, $name, $relationship, $birth_date, $gender) {
            $params = [
                'id' => $id,
                'id_employee' => $id_employee,
                'name' => $name,
                'relationship' => $relationship,
                'birth_date' => $birth_date,
                'gender' => $gender
            ];
          
            $action = $this->CallProcedure('sp_beneficiarios_guardar', $params, 1);

            $data = [];
            if (array_key_exists('status_code', $action)) {
                $status_code = intval($action['status_code']);
            }
            else {
                $status_code = 201;
                $data = [
                    'id' => $action['id']
                ];
            }

            $this->SendResponse($status_code, $action['response'], $action['msg'], $data);
        }

        /**
        * Eliminar beneficiario
        *
        * @author Pedro Garcia Glez <garciaglez@outlook.com, garciaglez9210@gmail.com>
        * @param (int) $id Identificador del beneficiario
        * @return (object) Respuesta de la acción
        */
        public function deleteBeneficiary($id) {
            $params = [
                'id' => $id
            ];
            $action = $this->CallProcedure('sp_beneficiarios_eliminar',$params,1);

            $data = [];
            if (array_key_exists('status_code', $action)) {
                $status_code = intval($action['status_code']);
            }
            else {
                $status_code = 200;
            }

            $this->SendResponse($status_code, $action['response'], $action['msg']);
        }
    }
?>