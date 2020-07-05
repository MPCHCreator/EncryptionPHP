<?php

class EncryptionPHP
{
    /**
     * Almacena la llave de encriptacion
     * @var string
     */
    private $key;
    /**
     * Almacena el nombre de la tabla
     * @var string
     */
    private $tableName;
    /**
     * Almacena los nombres de las columnas
     * @var array 
     */
    private $colums;
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
        $this->colums = $colums;
    }

    /**
     * @param array $values_p
     * 
     * @return void
     */
    public function encryptDB($values_p){

        $colums = $this->concat($this->colums);
        $value = $this->concat($values_p, $this->key);
        $sentencia = $this->conexion->prepare("CALL encrypt(\"$this->tableName\",\"$colums\",\"$value\")");
        $sentencia->execute();
        
    }

    /**
     * @param array $array
     * @param null $key
     * 
     * @return string
     */
    function concat($array, $key = null){
        for ($i = 0, $c = ""; $i < count($array); $i++) {
            if ($i == 0) {
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
