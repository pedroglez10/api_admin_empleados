<?php
require_once('Utils.php');
//Clase principal
class Main extends Utils {
	//propiedades
	private $server="localhost";
	private $user="root";
	private $password="";
	private $DB="ksp_admin_empleados";
	/*constructor
	inicializa al ser instaciada la clase
	*/
	public function __construct() 
	{
		//invoca un metodo de la misma clase y parametros de la misma clase
		$this->Connection($this->server, $this->DB, $this->user, $this->password);
	}

	//metodos
	private function Connection($server, $DB, $user, $password)
	{
		$dsn = "mysql:host=" . $server . ";dbname=" . $DB . ";charset=utf8;";
		$options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
		//validamos que no haya errores en la conexion
		try {
			$this->connection = new PDO($dsn, $user, $password, $options);
			//echo "Conexion exitosa"; 
		}
		catch(PDOException $e){
			$this->SendResponse(500, "Error de servidor", "Fallo conexion: " . $e->getMessage());
		}
	}

	/**
	* Ejecuta cualquier query; select, insert, update o delete
	*
	* @author Pedro Garcia Glez <garciaglez@outlook.com>
	* @param $query (string) Sentencia del query
	* @param $params (array) Datos que se enviaran al sp
	* @param $type (int) 1=Devuelve 1 registro | 2=Devuelve 1 o más registros | default=true o false
	* @return (array) Resultado del query
	*/
	protected function Query($query, $params, $type=1){
		//validamos que no haya errores
		try {
			//Prepara una sentencia para su ejecución y devuelve un objeto sentencia
			$sth = $this->connection->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
			if(isset($params)){
				//recorre el arreglo de los parametros
				foreach ($params as $key => $value)
				{	
					if (is_integer($value))
						$sth->bindValue(":$key", $value, PDO::PARAM_INT);
					else
						$sth->bindValue(":$key", $value, PDO::PARAM_STR);
				}
			}

			$execute = $sth->execute(); //Ejecuta una sentencia preparada

			switch($type) {
				case 1:
					$result = $sth->fetch(PDO::FETCH_ASSOC); //Devuelve un solo row
					break;
				case 2:
					$result = $sth->fetchAll(PDO::FETCH_ASSOC); //Devuelve 1 o mas rows
					break;
			}
			$sth->closeCursor(); //Cierra un cursor, habilitando a la sentencia para que sea ejecutada otra vez

			return $result;
		}
		catch (Exception $e){
			$this->SendResponse(500, "Error de servidor", "Fallo ejecución: " . $e->getMessage());
		}
	}

	/**
	* Ejecuta un procedimiento almacenado
	*
	* @author Pedro Garcia Glez <garciaglez@outlook.com>
	* @param $name (string) Nombre del procedimiento que se ejecutara
	* @param $params (array) Datos que se enviaran al sp
	* @param $type (int) 0=No devuelve resultado | 1=Devuelve 1 registro | 2=Devuelve 1 o más registros | 3=Devuelve multiples resultados
	* @return (array) Resultado del SP
	*/
	protected function CallProcedure($name, $params, $type=0)
	{
		try {
			$last_key = array_keys($params)[count($params)-1];
			$query = "CALL ".$name."(";
			foreach ($params as $key => $value) {
				// concatenar nombre de los parametros
				$query.=":".$key;
				if ($key!=$last_key) {
					$query.=",";
				}
			}
			$query.=");";
			
			$sth = $this->connection->prepare($query); //Prepara una sentencia para su ejecución y devuelve un objeto sentencia
			

			foreach ($params as $key => $valor)
			{
				if (is_integer($valor))
					$sth->bindValue(":$key", $valor, PDO::PARAM_INT);
				else
					$sth->bindValue(":$key", $valor, PDO::PARAM_STR);
			}
			
			$sth->execute();

			if ($type)
			{
				if($type == 1)
				{
					$result = $sth->fetch(PDO::FETCH_ASSOC);
				}
				else if($type == 2)
				{
					$result = $sth->fetchAll(PDO::FETCH_ASSOC);	
				}
				else /*Entrará aqui cuando necesitemos obtener multiples consultas con multiples registros (una matris de datos)*/
				{
					$count=0;
					do
					{
						try
						{						
							$rows = $sth->fetchAll(\PDO::FETCH_ASSOC); // Me regresa un arreglo con arreglos
						   	if ($rows>=0)
						   	{
						   		if(isset($rows[0]["key_arreglo"]))
						   		{
						   			$result[$rows[0]["key_arreglo"]]=$rows; //Se agrega un campo en cada consulta para identificar la consulta con esto si nos cambian la posicion de la consulta no afectará.	
						   		}
						   		else
						   		{
						   			$result[$count]=$rows; //Estará colocando los comandos segun los vaya leyendo.
						   		}
						      	
						      	$count++;
						  	}
						}
						catch (\Exception $ex) {}						   
					} 
					while ($sth->nextRowset());
				}
			}
			else
			{
				$result = true;
			}

			$sth->closeCursor();
		}
		catch (Exception $e){
			$this->SendResponse(500, "Error de servidor", "Fallo ejecución: " . $e->getMessage());
		}
		
		return $result;
	}
}
?>