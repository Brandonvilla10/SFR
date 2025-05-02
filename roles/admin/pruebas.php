
<?php

require_once('funciones.php');



$consulta = new consultas;

$user = 1107978187;
$consulta->SelectUser($user);

print_r($consulta->SelectUser($user));

$id ='id_serial';
$name ='objetos';
$where ='id_documento';


$valor2 = $consulta->PersonalisedConsulte($id,$name,$where,$user);
echo $valor2['id_serial'];