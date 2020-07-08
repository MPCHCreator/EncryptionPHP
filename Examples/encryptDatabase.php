
<?php
# incluimos la libreria
require_once "../EncryptionPHP.php";
# instanciamos la clase EncryptionPHP
$e = new EncryptionPHP();
# establecemos la conexión a las base de datos
$e->setConexion("localhost","security_db","root","");
# establecemos el directorio donde esta la llave para encriptar y desencriptar
$e->setKeyDirectory("../clave.txt");
# establecemos el nombre de la tabla donde se encriptara la informacion
$e->setTableName("alumno");
# utlizamos la funcion encryptDB y le asignamos los datos que se encriptaran
$e->encryptDB(["3518110263", "Miguel","Pérez","Chamorro","20","M","2481286971","C. Venustiano Carranza"]);
$e->encryptDB(["3518110263", "Ana","Barrientos","Fuentes","19","F","2481286988","C. Francisco I.Madero"]);
$e->encryptDB(["3518110263", "Alberto","Díaz","Cuellar","22","M","2481286944","C. Emiliano Zapata"]);
# utilizamos la funcion desencryptDB para desencriptar todos los datos que correspondan a la llave antes establecida
# guardamos el array que contiene los datos desencriptados
$result = $e->decryptDB();
// # recorremos el array e ingresamos los resultados en una tabla
echo '<table border=1;>
    <tbody>'; 
        for($i=0; $i<count($result); $i++){
            echo "<tr>";
            for ($a=0; $a < count($result[$i]); $a++) { 
                echo "<td>".$result[$i][$a]."</td>";
            }
            echo "</tr>";
        }echo'
    </tbody>
</table>';


