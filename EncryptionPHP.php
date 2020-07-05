<?php

class EncryptionPHP
{
    private $key;
    private $tableName;
    private $colums;
    /**
     * @var PDO
     */
    private $conexion;

    public function __construct($key = null, $tableName = null, $colums = null){
        $this->key = $key;
        $this->tableName = $tableName;
        $this->colums = $colums;
    }

    public function setConexion($host = 'localhost', $dbname, $user, $pwd){
        try {
            $pdo = new PDO('mysql:host='.$host.';dbname='.$dbname, $user, $pwd);
            $this->conexion = $pdo;
        } catch (PDOException $e) {
            $this->conexion = null;
        }
    }

    public function setKey($key){
        $this->key = $key;
    }

    public function setTableName($tableName){
        $this->tableName = $tableName;
    }

    public function setColums($colums){
        $this->colums = $colums;
    }

    public function encryptDB($values_p){

        $colums = $this->concat($this->colums);

        $value = $this->concat($values_p, $this->key);
        
        $sentencia = $this->conexion->prepare("CALL encrypt(\"$this->tableName\",\"$colums\",\"$value\")");

        $sentencia->execute();
        
    }

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
