<?php
    class funciones{
        private $conex;
        
        //creamos la conexión en la clase
        function __construct(){
            $this->conex = new mysqli('localhost','root','','wordle');
        }

        //Nos devolverá el tiempo en segundos de lo que falta para que acabe el día
        private function diferencia(){
            return strtotime(date('23:59:59'));
        }

        //Esta funcion guardará las palabras en un array dentro de las cookies
        function agregarPalabra($p){
            if(isset($_COOKIE['palabras'])){
                $a = $_COOKIE['intentos'];
                setcookie('palabras[m'.$a.']',$p , $this->diferencia());
            }else{
                setcookie('palabras[m0]', $p , $this->diferencia());
            }
        }

        //Esta función seteará los intentos diarios
        function renovarIntentos(){
            //Si no ha intentado nada todavía, iniciamos la cookie
            if(isset($_COOKIE['intentos'])){
                // Caduca en un día
                setcookie('intentos',$_COOKIE['intentos']+1 , $this->diferencia());
            }else{
                // Creo la cookie con el valor 0
                setcookie('intentos', 1, $this->diferencia());
            }
        }

        //Esta funcion nos permitirá pintar la tabla con los aciertos y fallos haciendo comparaciones dobles
        //Nos devolverá el numero de aciertos por palabra, para poder deshabilitar el botón en caso de que ya haya acertado
        function pintarPalabra($palabra){
            $fecha = date('Y-m-d');
            $sentencia = "SELECT palabra FROM palabras
                         WHERE fecha LIKE '$fecha'";
            $conjuntoDatos = $this->conex->query($sentencia);
            $datos = $conjuntoDatos->fetch_row();
            $palabraDelDia = $datos[0]; //Palabra a comparar

            //Colores que pueden tener las letras según la posición que ocupen.
            $acierto = 'style=color:forestgreen';
            $falloTotal = 'style=color:grey';
            $falloPosicion = 'style=color:orange';

            $contadorAciertos = 0;
            $respuesta = "<tr>";
            for($i=0;$i<5;$i++){

                if(strtoupper($palabraDelDia[$i]) === strtoupper($palabra[$i])){
                    $color = $acierto;    
                    $contadorAciertos++;
                }else{

                    $esta = false; //Bandera de color
                    for($a=0;$a<5;$a++){//Comprobamos si la letra está en otra posición
                        if(strtoupper($palabraDelDia[$a]) === strtoupper($palabra[$i])){ //Recorremos la palabra diaria buscando coincidencias
                            $esta = true;
                        }
                    }

                    if($esta){
                        $color = $falloPosicion;
                    }else{
                        $color = $falloTotal ;
                    }
                }
                $respuesta.= "<td $color>".strtoupper($palabra[$i])."</td>";
            }
            $respuesta.="</tr>"; 
            $respuestaArray = array('respuesta'=>$respuesta,'contador'=>$contadorAciertos);//Contador para finalizar el juego
            return $respuestaArray;
        }

        //En una cookie que no caduca, guardaremos el resultado por fecha
        function registrarResultadoCookies($terminado){
            //La primera parte de la función nos permitirá agregar una cookie con nuestro resultado a nuestro equipo
            //Y la segunda parte hará una inserción a la base de datos, para obtener estadísticas globales

            $fecha = date('Y-m-d');
            //De este modo, sabremos si no lo consiguió a la última
            if($terminado){
                $intentos = $_COOKIE['intentos'];
            }else{
                $intentos = 0;
            }

            if(!isset($_COOKIE['estadisticas'][$fecha])){
                setcookie('estadisticas['.$fecha.']', $intentos);//No le pondremos tiempo, para que las estadísticas nunca caduquen
            }

            //seleccionamos los aciertos que tenemos en la base de datos, con los intentos similares al del usuario
            $sentencia = "SELECT p".$intentos." FROM palabras
                        WHERE fecha = '$fecha'";
            $datos = $this->conex->query($sentencia);
            $numero = $datos->fetch_array();
            $numero= $numero[0]+1;//Sumamos el resultado del usuario y lo subimos a la base de datos
            $sentencia = "UPDATE `palabras` SET `p".$intentos."` = '$numero' WHERE `palabras`.`fecha` = '$fecha'";
            $this->conex->query($sentencia);
            header('location:index.php');
        }

        //Usaremos esta función para dibujar las estadisticas del jugador
        function mostrarEstadisticasPersonales(){
            //Array para guardar el número de aciertos
            $arrayAciertos = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,0=>0);
            $respuesta = "<h1>Tu tabla</h1>
                        <table border=1px>
                            <thead>
                                <tr>
                                    <td>Intentos</td>
                                    <td>Puntuación</td>
                                </tr>
                            </thead>
                            </tbody>";
            $total = 0;//Sacamos el número de aciertos totales para hacer una estadística
            foreach($_COOKIE['estadisticas'] as $indice=>$valor){
                foreach($arrayAciertos as $intento=>$cuantos){
                   if($intento == $valor){
                        $arrayAciertos[$intento]++;
                        $total++;
                    } 
                } 
            }
            //Lo recorremos para montar la tabla con los porcentajes 
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
            $respuesta.="<tr>
                            <td colspan=2>Días totales acumulados: $total</td>
                        </tr>
                        </tbody>
                    </table>";
                    
            return $respuesta;
        }
    }
?>