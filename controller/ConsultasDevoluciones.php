<?php
require_once "./clases/Devoluciones.php";
require_once "./clases/Cupon.php";

$puntoA = Devolucion::TraerDevolucionConCupon("./archivos/devoluciones.json","./archivos/cupones.json");
$puntoB = Cupon::TraerJSON("./archivos/cupones.json");
$puntoC = Devolucion::TraerDevolucionConCupon("./archivos/devoluciones.json","./archivos/cupones.json",true);

if($puntoA == false)
    $puntoA = "No hay listado para mostrar";
if($puntoB == false)
    $puntoB = "No hay listado para mostrar";
if($puntoC == false)
    $puntoC = "No hay listado para mostrar";

var_dump(array(
    "a"=> $puntoA,
    "b"=> $puntoB,
    "c"=> $puntoC));
