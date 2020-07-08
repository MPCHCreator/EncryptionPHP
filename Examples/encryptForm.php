<html>
    <head>

        <title>ENCODE AND DECODE</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <style>

            html,body, .container, .row{
                height: 40%;
                text-align: center;
            }
            .contanier{
                border:1px solid red;
            }
            .row{
                border:0px solid gray;
            }
            form{

                border:1px solid #000000;
                position:absolute;
                text-align: center;
                border-radius: 15px;
                padding: 40px;
            }
            table {
                border-collapse: collapse;
                width: 100%;
            }

            td, th {
                border: 1px solid #dddddd;
                text-align: left;
                padding: 8px;
            }

            tr:nth-child(even) {
                background-color: #dddddd;
            }
        </style>

    </head>
    <body>
        <?php
        include_once"../EncryptionPHP.php";

        echo" <h3>CIFRAR Y DESCIFRAR INFORMACIÓN</h3><br/>"
        . "<div class='container'>" .
        "<div class='row'></div>" .
        "<div class='row'>" .
        "<div class='col-4'></div>" .
        "<div class='col-4 my-auto'>" .
        " <form method=post action=encryptForm.php>
                             <h3>Ingrese el texto a encriptar</h3><br/>
                             <input type=" . "text" . " placeholder=" . "Ingrese el texto" . " name=" . "code" . ">
                             <button class='btn btn-primary dropdown-togget'type='submit' >Encode</button> <br/>" .
        "</form>" .
        "</div>" .
        "</div>" .
        "</div>";

        if (isset($_POST['code'])) {
            $code = $_POST['code'];

            echo "<div ></br></br></br></br></br></br></br></br></br></br></br></br>" .
            "<div class='contenido'>" .
            "<table>" .
            "<tr>" .
            "<th>Mensaje Cifrado</th>" .
            "</tr>" .
            "<tr>" .
            "<td>" . $result = EncryptionPHP::encode($code,"../clave.txt");
            "</td>" .
                    "</tr>" .
                    "</table>" .
                    "</div>" .
                    "</div>";
        }
        ?>

    </body>
</html>
