<?php
require_once "./clases/Pizza.php";
require_once "./clases/Venta.php";
require_once "./clases/Devoluciones.php";
require_once "./clases/Cupon.php";

$mail = isset($_POST["mail"]) ? $_POST["mail"] : null;
$sabor = isset($_POST["sabor"]) ? $_POST["sabor"] : null;
$tipo = isset($_POST["tipo"]) ? $_POST["tipo"] : null;
$cantidad = isset($_POST["cantidad"]) ? $_POST["cantidad"] : null;
$foto = isset($_FILES["foto"]) ? $_FILES["foto"] : null;
$numeroCupon = isset($_FILES["cupon"]) ? $_POST["cupon"] : null;


$pizzaMatch = Pizza::BuscarPizzaPorTipo_Sabor("./archivos/pizza.json",$sabor,$tipo);
//si existe la pizza
if($pizzaMatch != false)
{
    //si hay stock
    if($pizzaMatch->cantidad >= $cantidad)
    {
        $codigoPedido = random_int(0,100000);
        //punto 10:
        if($cupon != null)
        {
            $arrayCupon = Cupon::TraerJSON("./archivos/cupones.json");
            $cup = Cupon::BuscarCuponPorCodigo("./archivos/cupones.json",$numeroCupon);
            if($cup != false && $arrayCupon != false)
            {
                //calculo el total y guardo el importe en el archivo
                $precioTotal = $pizzaMatch->precio * $cantidad - $pizzaMatch->precio * ($cup->descuento/100);
                if(Cupon::GuardarImporte($codigoPedido,$precioTotal))
                {
                    //modifico el archivo para cambiarle el estado
                    unlink("./archivos/importes.json");
                    foreach ($arrayCupon as $value) {
                        if($value->id == $cup->id)
                            $value->estado = "obsoleto";
                    }
                    //guardo el array en el json de nuevo
                    foreach ($arrayCupon as $value) {
                        $cuponAGuardar = new Cupon($value->codigo,$value->descuento,$value->estado,$value->id);
                        $cuponAGuardar->GuardarJSON("./archivos/cupones.json");
                    }
                }
            }
        }
        else
        {
            $precioTotal = $pizzaMatch->precio * $cantidad;
            Cupon::GuardarImporte($codigoPedido,$precioTotal);
        }


        $date = date("Y-m-d",time());
        $path = $tipo ."-" .$sabor ."-" .explode("@", $mail)[0] ."-" .$date ."." .pathinfo($foto["name"],PATHINFO_EXTENSION);
        $venta = new Venta($mail,$pizzaMatch->id,$pizzaMatch->sabor,$pizzaMatch->tipo,$cantidad,$codigoPedido,$date,$path);
        //si se pudo agregar
        if($venta->Agregar())
        {
            //muevo la imagen / traigo array
            move_uploaded_file($foto["tmp_name"], "./ImagenesDeLaVenta/" .$path);
            $arrayPizzas = Pizza::TraerJSON("./archivos/pizza.json");
            //elimino foto y resto el stock
            unlink("./archivos/pizza.json");
            foreach ($arrayPizzas as $value) {
                if($value->sabor == $sabor && $value->tipo == $tipo)
                {
                    $value->cantidad -= $cantidad;
                    $retorno["mensaje"] = "Producto agregado y stock discriminado";
                    break;
                }
            }
            //vuelvo a guardar el array en el archivo
            foreach ($arrayPizzas as $value) 
            {
                $pizza = new Pizza($value->sabor,$value->precio,$value->tipo,$value->cantidad,$value->id);
                $pizza->GuardarJSON("./archivos/pizza.json");
            }
            
        }
    }
    else
        $retorno["mensaje"] = "Lo sentimos, no hay stock";
}
else
    $retorno["mensaje"] = "No hay coincidencia con tipo y sabor";


echo json_encode($retorno);
