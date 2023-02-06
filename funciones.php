<?php
    class funciones{
        private $conex;
        
        //creamos la conexión en la clase
        function __construct(){
            $this->conex = new mysqli('localhost','root','','wordle');
        }

        //Nos devolverá el tiempo en segundos de lo que falta para que acabe el día
        private function diferencia(){
            //Creamos dos objetos de tip DateTime, en uno guardamos la hora 
            //actual y en otro la hora a la que queremos que acabe la cookie
            $fecha1 = new DateTime();
            $fecha1->format("H:i:s");

            $fecha2 = new DateTime("23:59:59");//Hora de acabar
            $fecha2->format("H:i:s");

            $diferencia = $fecha1->diff($fecha2)->format("%H:%i:%s");
            return strtotime($diferencia);
        }

        //Esta funcion guardará las palabras en un array dentro de las cookies
        function agregarPalabra($p){
            if(isset($_COOKIE['palabras'])){
                $a = $_COOKIE['intentos'];
                setcookie('palabras[m'.$a.']',$p);
            }else{
                setcookie('palabras[m0]', $p , time()+$this->diferencia());
            }
        }

        //Esta función seteará los intentos diarios
        function renovarIntentos(){
            //Si no ha intentado nada todavía, iniciamos la cookie
            if(isset($_COOKIE['intentos'])){
                // Caduca en un día
                setcookie('intentos',$_COOKIE['intentos']+1);
            }else{
                // Creo la cookie con el valor 0
                setcookie('intentos', 1, time()+$this->diferencia());
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
                    for($a=0;$a<5;$a++){
                        if(strtoupper($palabraDelDia[$a]) === strtoupper($palabra[$i])){
                            $esta = true;
                        }
                    }

                    if($esta){
                        $color = $falloPosicion ;
                    }else{
                        $color = $falloTotal ;
                    }
                }
                $respuesta.= "<td $color>".strtoupper($palabra[$i])."</td>";
            }
            $respuesta.="</tr>"; 
            $respuestaArray = array('respuesta'=>$respuesta,'contador'=>$contadorAciertos);
            return $respuestaArray;
        }
    }
?>