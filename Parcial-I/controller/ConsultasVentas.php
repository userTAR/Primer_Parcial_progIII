<?php

require_once "./clases/Venta.php";

$fechaAntigua = isset($_POST["fechaAntigua"]) ? $_POST["fechaAntigua"] : null;
$fechaNueva = isset($_POST["fechaNueva"]) ? $_POST["fechaNueva"] : null;
$email = isset($_POST["mail"]) ? $_POST["mail"] : null;
$sabor = isset($_POST["sabor"]) ? $_POST["sabor"] : null;

//si los parámetros fueron ingresados
if($fechaAntigua != null && $fechaNueva != null && $email != null && $sabor != null)
    var_dump(array(
        "a"=> Venta::CantidadPizzasVendidas(),
        "b"=>Venta::ListadoVentasPorFechas($fechaAntigua,$fechaNueva),
        "c"=>Venta::VentasPorUsuario($email),
        "d"=>Venta::VentasPorSabor($sabor)));
