<?php
require_once "./clases/Pizza.php";
require_once "./clases/Venta.php";

$mail = isset($_POST["mail"]) ? $_POST["mail"] : null;
$sabor = isset($_POST["sabor"]) ? $_POST["sabor"] : null;
$tipo = isset($_POST["tipo"]) ? $_POST["tipo"] : null;
$cantidad = isset($_POST["cantidad"]) ? $_POST["cantidad"] : null;
$foto = isset($_FILES["foto"]) ? $_FILES["foto"] : null;

$pizzaMatch = Pizza::BuscarPizzaPorTipo_Sabor("./archivos/pizza.json",$sabor,$tipo);
//si existe la pizza
if($pizzaMatch != false)
{
    //si hay stock
    if($pizzaMatch->cantidad >= $cantidad)
    {
        $date = date("Y-m-d",time());
        $path = $tipo ."-" .$sabor ."-" .explode("@", $mail)[0] ."-" .$date ."." .pathinfo($foto["name"],PATHINFO_EXTENSION);
        $venta = new Venta($mail,$pizzaMatch->id,$pizzaMatch->sabor,$pizzaMatch->tipo,$cantidad,random_int(0,100000),$date,$path);
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



/* 
JUNIO DE 2024 -> IV 

JUNIO DE 2023 -> TECNICO

*/