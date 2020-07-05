<?php

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
    private $columNames;
    /**
     * @var PDO
     */
    private $conexion;

    /**
     * @param null $key
     * @param null $tableName
     * @param null $colums
     */
    public function __construct($key = null, $tableName = null, $colums = null){
        $this->key = $key;
        $this->tableName = $tableName;
        $this->colums = $colums;
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
    public function setKey($key){
        $this->key = $key;
    }

    /**
     * @param string $tableName
     * 
     * @return void
     */
    public function setTableName($tableName){
        $this->tableName = $tableName;
    }

    /**
     * @param array $colums
     * 
     * @return void
     */
    public function setColums($colums){
        $this->columNames = $colums;
    }

    /**
     * @param array $values_p
     * 
     * @return void
     */
    public function encryptDB($values_p){
        # Concatenamos los nombres de las columnas
        $colums = $this->concat($this->columNames);
        # Concatenamos los valores recibidos
        $values = $this->concat($values_p, $this->key);
        # Llamamos y ejecutamos al procedimiento almacenado
        $sentencia = $this->conexion->prepare("CALL encrypt(\"$this->tableName\",\"$colums\",\"$values\")");
        $sentencia->execute();
    }

    /**
     * @param array $array
     * @param null $key
     * 
     * @return string
     */
    function concat($array, $key = null){
        # Concatena los valores dentro del array
        for ($i = 0, $c = ""; $i < count($array); $i++) {
            if ($i == 0) {
                # si existe una llave añade la función aes_encrypt a los valores concatenados
                if($key == null){
                    $c = $array[$i];
                }else{
                    $c = "hex(aes_encrypt('$array[$i]','" . $key . "'))";
                }
            } else {
                if($key == null){
                    $c = $c . "," . $array[$i];
                }else{
                    $c = $c . "," . "hex(aes_encrypt('$array[$i]','" . $key . "'))";
                }   
            }
        }
        return $c;
    }

    public static function desencryptDB(){
    }

    public static function encode($data, $key){
    }

    public static function decode($data, $key){
    }
}
