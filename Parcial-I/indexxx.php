<?php

if (isPizzaCarga())
    require_once "./controller/PizzaCarga.php";
else if (IsPizzaConsultar())
    require_once "./controller/PizzaConsultar.php";
else if (IsAltaVenta())
    require_once "./controller/AltaVenta.php";
else if (IsConsultasVenta())  
    require_once "./controller/ConsultasVentas.php";
else if(IsModificarVenta())
    require_once "./controller/ModificarVenta.php";
else if(IsBorrarVenta())
    require_once "./controller/BorrarVenta.php";



function isPizzaCarga() 
{
    return isset( $_POST["sabor"] ) &&
            isset( $_POST["precio"] ) &&
            isset( $_POST["tipo"] ) && 
            isset( $_POST["cantidad"]) &&
            isset( $_FILES["foto"]);
}

function IsPizzaConsultar()
{
    return isset( $_POST["sabor"] ) &&
            isset( $_POST["tipo"] ) &&
            !isset($_POST["mail"]) &&
            !isset($_POST["precio"]);
}

function IsAltaVenta() 
{
    return isset ( $_POST["mail"] ) && 
            isset ( $_POST["sabor"] ) &&
            isset ( $_POST["tipo"] ) &&
            isset ( $_POST["cantidad"] ) &&
            isset ( $_FILES["foto"] );
}

function IsConsultasVenta() 
{
    return isset ($_POST["fechaAntigua"]) &&
            isset ($_POST["fechaNueva"]) &&
            isset ($_POST["mail"]) &&
            isset ($_POST["sabor"]);
}

function IsModificarVenta()
{
    $method = $_SERVER['REQUEST_METHOD'];
    if ('PUT' === $method) 
        parse_str(file_get_contents('php://input'), $_PUT);
    
    return isset($_PUT["numero_pedido"]) &&
            isset($_PUT["mail"]) &&
            isset($_PUT["tipo"]) &&
            isset($_PUT["sabor"]) &&
            isset($_PUT["cantidad"]);
}


function IsBorrarVenta()
{
    $method = $_SERVER['REQUEST_METHOD'];
    if ('DELETE' === $method)
        parse_str(file_get_contents('php://input'), $_DELETE);
    
    return isset($_DELETE["numero_pedido"]);
}