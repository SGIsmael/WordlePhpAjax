<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Wordle mundial</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='css/estilos.css'>
    <script src="ajax/ajaxScript.js"></script>
</head>
<body>
<?php
    include('clases/funciones.php');

    $funciones = new funciones(); // Creamos un objeto de la clase funciones
    if(isset($_POST['bton'])){ //Si se pulsa el botón, comenzamos el programa
        //Recogemos cada letra
        $l1 = $_POST['letra1'];
        $l2 = $_POST['letra2'];
        $l3 = $_POST['letra3'];
        $l4 = $_POST['letra4'];
        $l5 = $_POST['letra5'];

        $palabra = $l1.$l2.$l3.$l4.$l5; //Montamos la palabra
        $funciones->renovarIntentos();
        $funciones->agregarPalabra($palabra);
        header('location:index.php');//Para recargar las cookies y el método de entrada
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
                //Cada vez que se recargue la pagina comprobaremos si ya ha acertado la palabra del día, de ser así no le dejamos seguir jugando
                if(!empty($_COOKIE['palabras'])){
                    foreach($_COOKIE['palabras'] as $clave=>$valor){
                        $contador = $funciones->pintarPalabra($valor)['contador'];
                        if($contador == 5){
                            $terminado = true;
                        }
                    }
                }

                //Al sexto intento deshabilitaremos el boton hasta el próximo día o en caso de haber terminado
                if((empty($_COOKIE['intentos']) || $_COOKIE['intentos']<6) && empty($terminado)){
                    echo '<input type="submit" name=bton value=Validar class=buttonS>';
                }else{
                    echo '<input type="submit" name=bton value="Se acabó" class=buttonD disabled>';
                    //De este modo, solo entraremos una vez por día
                    if(!isset($_COOKIE['estadisticas'][date('Y-m-d')])){
                        $funciones->registrarResultadoCookies($terminado);
                    }

                }
            ?>
        </form>

    </div>
    <div id=resultado>
        <?php
            if(!empty($_COOKIE['palabras'])){
                echo "<table border=1>
                        <tbody>";
                foreach($_COOKIE['palabras'] as $clave=>$valor){
                    echo $funciones->pintarPalabra($valor)['respuesta'];
                }
                echo "  </tbody>
                    </table>"; 
            }
            
        ?>
    </div>
    <div id=estadisticasPersonales>
        <?php
            if(isset($_COOKIE['estadisticas'])){
                echo $funciones->mostrarEstadisticasPersonales();
            }
        ?>
    </div>
    <div id=estadisticasGlobales>

    </div>
    
</body>
</html>