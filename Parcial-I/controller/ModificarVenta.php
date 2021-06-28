<?php

require_once "./clases/Venta.php";
require_once "./clases/Pizza.php";

//parseo a variable put
$method = $_SERVER['REQUEST_METHOD'];
if ('PUT' === $method) 
    parse_str(file_get_contents('php://input'), $_PUT);

$numeroPedido = isset($_PUT["numero_pedido"]) ? $_PUT["numero_pedido"] : null;
$email = isset($_PUT["mail"]) ? $_PUT["mail"] : null;
$sabor = isset($_PUT["sabor"]) ? $_PUT["sabor"] : null;
$tipo = isset($_PUT["tipo"]) ? $_PUT["tipo"] : null;
$cantidad = isset($_PUT["cantidad"]) ? $_PUT["cantidad"] : null;

//si el numero de pedido existe
if(Venta::ExistenciaPorNumeroPedido((int)$numeroPedido) != false)
{
    $pizzaMatch = Pizza::BuscarPizzaPorTipo_Sabor("./archivos/pizza.json",$sabor,$tipo);
    //si la pizza existe se crea una nueva venta y se modifica
    if($pizzaMatch != false)
    {
        $venta = new Venta($email,$pizzaMatch->id,$pizzaMatch->sabor,$pizzaMatch->tipo,$cantidad,$numeroPedido,date("Y-m-d",time()));
        if($venta->Modificar())
            $retorno["mensaje"] = "Modificacion exitosa";
        else
            $retorno["mensaje"] = "Modificacion fallida";
    }
    else
        $retorno["mensaje"] = "Modificacion fallida, tipo y sabor de pizza incorrectos";
}
else
    $retorno["mensaje"] = "Numero de pedido no valido";

echo json_encode($retorno);