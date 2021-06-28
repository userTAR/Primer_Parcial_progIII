<?php

require_once "./clases/Venta.php";
require_once "./clases/Devoluciones.php";
require_once "./clases/Cupon.php";

$numeroPedido = isset($_POST["numero_pedido"]) ? $_POST["numero_pedido"] : null;
$causa = isset($_POST["causa"]) ? $_POST["causa"] : null;
$foto = isset($_FILES["foto"]) ? $_FILES["foto"] : null;

echo $numeroPedido ."/" .$causa ."/" .$foto["name"] ."<br>";

$match = Venta::ExistenciaPorNumeroPedido($numeroPedido);
if($match != false)
{
    
    $destino = $numeroPedido ."-" .date("Y-m-d") ."." .pathinfo($foto["name"],PATHINFO_EXTENSION);
    move_uploaded_file($foto["tmp_name"],"./ImagenesDevoluciones/" .$destino);
    $arrayDev = Devolucion::TraerJSON("./archivos/devoluciones.json");
    if( $arrayDev == false || count($arrayDev) == 0)
        $nuevoID = 0;
    else
        $nuevoID = Devolucion::EmularID($arrayDev);

    $dev = new Devolucion($match[0]->numero_pedido,$causa,$destino,$nuevoID);
    $numeroCupon = random_int(0,100000);
    $cup = new Cupon($numeroCupon,10,"pendiente",(int)$nuevoID);

    if($dev->GuardarJSON("./archivos/devoluciones.json") != false && $cup->GuardarJSON("./archivos/cupones.json") != false)
        $retorno["mensaje"] = "Devolucion guardada";
    else
        $retorno["mensaje"] = "Error en el guardado";
}
else
    $retorno["mensaje"] = "Numero de pedido no válido";


echo json_encode($retorno);