<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Wordle mundial</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='estilos.css'>
    <script src='main.js'></script>
</head>
<body>
<?php
    include('funciones.php');
    $funciones = new funciones(); // Creamos un objeto de la clase funciones
    if(isset($_POST['bton'])){ //Si se pulsa el botón, comenzamos el programa
        //Recogemos cada letra
        $l1 = $_POST['letra1'];
        $l2 = $_POST['letra2'];
        $l3 = $_POST['letra3'];
        $l4 = $_POST['letra4'];
        $l5 = $_POST['letra5'];

        $palabra = $l1.$l2.$l3.$l4.$l5;
        
        $funciones->renovarIntentos();
        $funciones->agregarPalabra($palabra);
        $tablaResultados = $funciones->pintarPalabra($l1,$l2,$l3,$l4,$l5);
    }
?>
    <div id=palabra>

        <form action="index.php" method="POST">
            <label>Palabra</label>
            <br>
            <input type="text" maxlength="1" minlength="1" name="letra1" required>
            <input type="text" maxlength="1" minlength="1" name="letra2" required>
            <input type="text" maxlength="1" minlength="1" name="letra3" required>
            <input type="text" maxlength="1" minlength="1" name="letra4" required>
            <input type="text" maxlength="1" minlength="1" name="letra5" required>
            <br>
            <?php 
                //Al sexto intento deshabilitaremos el boton hasta el próximo día
                if(empty($_COOKIE['intentos']) || $_COOKIE['intentos']<4){
                    echo '<input type="submit" name=bton value=Validar class=buttonS>';
                }else{
                    echo '<input type="submit" name=bton value="Se acabó" class=buttonD disabled>';
                }
            ?>
        </form>

    </div>
    <div id=resultado>
        <?php
        if(!empty($tablaResultados)){
            echo $tablaResultados;
            echo $palabra;
        }
            
        ?>
    </div>
    <div id=estadisticasPersonales>

    </div>
    <div id=estadisticasGlobales>

    </div>
    
</body>
</html>