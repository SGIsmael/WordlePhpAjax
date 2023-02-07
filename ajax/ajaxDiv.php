<?php
    echo "<span id=relojCentral>".date('H:i:s')."</span>"; //El reloj es indispensable
    $conex = new mysqli('localhost','root','','wordle');
    $sentencia = "SELECT * FROM palabras
                  WHERE fecha = '".date('Y-m-d')."'";
    $resultado = $conex->query($sentencia);
    $datos = $resultado->fetch_array();
    $arrayAciertos = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,0=>0);
    $total = 0;
    for($i = 0 ; $i<7 ; $i++){
        $posicion = 'p'.$i;
        $arrayAciertos[$i] = $datos[$posicion];
        $total+=$datos[$posicion];
    }

    $respuesta = " <h1>Estadisticas Globales</h1>
    <table border=1px>
                    <thead>
                        <tr>
                            <td>Intentos</td>
                            <td>Puntuación</td>
                        </tr>
                    </thead>
                    </tbody>";
    foreach($arrayAciertos as $intento=>$cuantos){
        if($intento == 0){
            $intento = "Incompleto";
        }
        $porcentaje = ($cuantos*100)/$total;//Calculamos porcentaje para pintar la barrita
        $respuesta.="<tr>
                        <td>".$intento."</td>
                        <td><div style=width:".($porcentaje*3+30)."px; class=porcentajes>".$cuantos."<div></td>
                    </tr>";
        }
    $respuesta.="</tbody>
                    </table>";
    echo $respuesta;
?>
