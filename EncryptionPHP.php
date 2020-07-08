<?php

require_once "vendor/autoload.php";

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;
use Defuse\Crypto\Key\Exception\WrongKeyOrModifiedCiphertextException;

class EncryptionPHP
{
    /**
     * @var string
     */
    private $key;
    /**
     * @var string
     */
    private $tableName;
    /**
     * @var array 
     */
    private $columnNames;
    /**
     * @var PDO
     */
    private $conexion;

    /**
     * @param null $key
     * @param null $tableName
     * @param null $colums
     */
    public function __construct($keyDirectory = null, $tableName = null){
        if($keyDirectory!==null):$this->key = file_get_contents($keyDirectory);endif;
        $this->tableName = $tableName;
    }

    /**
     * @param string $host
     * @param string $dbname
     * @param string $user
     * @param string $pwd
     * 
     * @return void
     */
    public function setConexion($host = 'localhost', $dbname, $user, $pwd){
        try {
            $pdo = new PDO('mysql:host='.$host.';dbname='.$dbname, $user, $pwd);
            $this->conexion = $pdo;
        } catch (PDOException $e) {
            $this->conexion = null;
        }
    }

    /**
     * @param string $key
     * 
     * @return void
     */
    public function setKeyDirectory($keyDirectory){
        $this->key = file_get_contents($keyDirectory);
    }

    /**
     * @param string $tableName
     * 
     * @return void
     */
    public function setTableName($tableName){
        $this->tableName = $tableName;
        $statement = $this->conexion->prepare("SELECT * from $tableName");
        $statement->execute();
        # Obtenemos los nombres de las columnas
        for ($i = 0; $i < $statement->columnCount(); $i++) {
            $col = $statement->getColumnMeta($i);
            $columns[] = $col['name'];
        }
        # Omitimos la columna “id”
        $this->columnNames = array_diff($columns, array('id'));
        # Indexamos nuevamente el array (índice empieza de 0)
        $this->columnNames = array_values($this->columnNames);
        # Concatenamos los nombres de las columnas
        $this->columnNames = implode(",", $this->columnNames);
    }

    /**
     * @param array $values_p
     * 
     * @return void
     */
    public function encryptDB($values_p){
         # Concatenamos los valores recibidos
        $values = implode(",", $values_p);
        # Llamamos y ejecutamos al procedimiento almacenado
        $sentencia = $this->conexion->prepare("CALL encrypt(\"$this->tableName\",\"$this->columnNames\",\"$values\",\"$this->key\")");
        $sentencia->execute();
    }

    /**
     * @return array
     */
    public function decryptDB(){
        # Llamamos y ejecutamos al procedimiento almacenado
        $sentencia = $this->conexion->prepare("call decrypt(\"$this->tableName\",\"$this->columnNames\",\"$this->key\")");
        $sentencia->execute();
        # Guardamos el resultado en un arreglo numerico
        $result = $sentencia->fetchAll(PDO::FETCH_NUM);
        return $result;
    }

    /**
     * @param string $data
     * @param string $keyDirectory
     * 
     * @return string
     */
    public static function encode($data, $keyDirectory) {
        # obtenemos la clave que se encuentra en el directorio 
        $contenido = file_get_contents($keyDirectory);
        $key = Key::loadFromAsciiSafeString($contenido);
        # encriptamos los datos y le asignamos su llave
        $mensaje = Crypto::encrypt($data, $key);
        # retornamos el mensaje encriptado
        return $mensaje;
    }
        
    
    /**
     * @param string $mensajeCifrado
     * @param string $keyDirectory
     * 
     * @return void
     */
    public static function decode($mensajeCifrado, $keyDirectory) {
        # obtenemos la clave que se encuentra en el directorio 
        $contenido = file_get_contents($keyDirectory);
        $key = Key::loadFromAsciiSafeString($contenido);
        # desencriptamos los datos y proporcionamos su llave correspondiente
        $mensajeOriginal = Crypto::decrypt($mensajeCifrado, $key);
        # retornamos el mensaje desencriptado
        return $mensajeOriginal;
    }
}