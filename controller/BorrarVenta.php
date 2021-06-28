<?php
require_once "./clases/Venta.php";

$method = $_SERVER['REQUEST_METHOD'];
if ('DELETE' === $method)
    parse_str(file_get_contents('php://input'), $_DELETE);

$numeroPedido = isset($_DELETE["numero_pedido"]) ? $_DELETE["numero_pedido"] : null;

$matchVenta = Venta::ExistenciaPorNumeroPedido((int)$numeroPedido);
//si el numero de pedido existe
if($matchVenta != false)
{
    //si eliminó
    if(Venta::Eliminar($numeroPedido))
    {
        //muevo imagen
        rename("./ImagenesDeLaVenta/" .$matchVenta[0]->path_foto, "./BackUpVentas/" .$matchVenta[0]->path_foto);
        $retorno["mensaje"] = "Eliminacion exitosa";
    }
    else
        $retorno["mensaje"] = "Eliminacion fallida";

}
else
    $retorno["mensaje"] = "Numero de pedido inexistente";

echo json_encode($retorno);
