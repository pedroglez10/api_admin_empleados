## Rest API Admin empleados

ksp_admin_empleados.sql esta es el script BD

## Configurar accesos DB
    Conexion se encuentra en el archivo classes/Main.php en las siguientes propiedades:
    private $server="localhost";
	private $user="root";
	private $password="";
	private $DB="ksp_admin_empleados";

## Códigos de respuesta
    200: OK. Todo funciono correctamente (GET, POST ó PUT)
    201: CREATED. El recurso se ha creado ó modificado con éxito. (POST ó PUT)
    202: ACCEPTED. Se acepto el proceso pero no se completo. (GET, POST, PUT ó DELETE)
    204: NO CONTENT. El recurso se ha eliminado con éxito. (DELETE)
    304: NOT MODIFIED. El recurso no ha sido modificado. (POST ó PUT)
    400: BAD REQUEST. Petición erronea por el cliente, falta alguna información en el cuerpo. (POST ó PUT)
    401: UNAUTHORIZED. Autenticación fallida. (GET, POST, PUT ó DELETE)
    403: FORBIDDEN. El cliente no tiene permitido el acceso al recurso. (GET, POST, PUT ó DELETE)
    404: NOT FOUND. El recurso no se encontro. (GET, POST, PUT ó DELETE)
    405: METHOD NOT ALLOWED. Método no permitido. (GET, POST, PUT ó DELETE)
    500: INTERNAL SERVER ERROR. Se ha producido un error interno en el servidor. (GET, POST, PUT ó DELETE)
