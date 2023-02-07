var segundos = 1; //Tiempo de refresco
var divId = 'estadisticasGlobales'; //Id del div a actualizar
var url="ajax/ajaxDiv.php";

function refresca(){
    var x=new XMLHttpRequest(); //Permite iintercambiar datos entre php y ajax
    x.onreadystatechange=function(){
        if(x.readyState==4 && x.readyState!=null){//El estado 4 esta esperando orden.
            document.getElementById(divId).innerHTML=x.responseText;
            setTimeout('refresca()',segundos*1000);
        }
    }
    x.open('GET', url, true);
    x.send(null);
}
window.onload = function(){
    refresca();
}