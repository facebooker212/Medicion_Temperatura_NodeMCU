<?php
class Mediciones_IoT{
 public $link='';
 function __construct($Temperatura, $Humedad, $CO2, $Alcohol){
  $this->connect();
  $this->storeInDB($Temperatura, $Humedad, $CO2, $Alcohol);
 }

 function connect(){
  $this->link = mysqli_connect('localhost','root','Slammer1212%') or die('Cannot connect to the DB');
  mysqli_select_db($this->link,'Mediciones_IoT') or die('Cannot select the DB');
 }

 function storeInDB($Temperatura, $Humedad, $CO2, $Alcohol){
  date_default_timezone_set('America/Mexico_City');
  $Fecha = date('Y-m-d');
  $Hora = date('H:i:s');
  $query = "insert into Mediciones set Temperatura='".$Temperatura."', Humedad='".$Humedad."', CO2='".$CO2."', Alcohol='".$Alcohol."', Fecha='$Fecha', Hora='$Hora'";
  $result = mysqli_query($this->link,$query) or die('Errant query:  '.$query);
 }

}
if($_GET['Temperatura'] != '' and  $_GET['Humedad'] != '' and  $_GET['CO2'] != '' and  $_GET['Alcohol'] != ''){
 $Mediciones_IoT = new Mediciones_IoT($_GET['Temperatura'],$_GET['Humedad'],$_GET['CO2'],$_GET['Alcohol']);
}
?>
