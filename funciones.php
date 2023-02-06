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

        function agregarPalabra($p){
            $diferencia = $this->diferencia();
            if(isset($_COOKIE['palabras'])){
                // Caduca en un día
                setcookie('palabras[segunda]',$p);
            }else{
                $a = 1; //Usaremos el contador de las cookies para crear una matriz 
                // Creo la cookie con el valor 0
                setcookie('palabras[m'.$a.']', $p , time()+$diferencia);
            }
        }

        function renovarIntentos(){
            $diferencia = $this->diferencia();
            //Si no ha intentado nada todavía, iniciamos la cookie
            if(isset($_COOKIE['intentos'])){
                // Caduca en un día
                setcookie('intentos',$_COOKIE['intentos']+1);
            }else{
                // Creo la cookie con el valor 0
                setcookie('intentos', 0, time()+$diferencia);
            }
        }


        function pintarPalabra($l1,$l2,$l3,$l4,$l5){
            $respuesta = "<table border=1>
                            <tbody>
                                <tr>
                                    <td>".strtoupper($l1)."</td>
                                    <td>".strtoupper($l2)."</td>
                                    <td>".strtoupper($l3)."</td>
                                    <td>".strtoupper($l4)."</td>
                                    <td>".strtoupper($l5)."</td>
                                </tr>
                            </tbody>
                        </table>";            
            return $respuesta;
        }
    }
?>