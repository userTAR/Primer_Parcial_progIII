<?php

require_once "./clases/Pizza.php";

$sabor = isset($_POST["sabor"]) ? $_POST["sabor"] : null;
$precio = isset($_POST["precio"]) ? $_POST["precio"] : null;
$tipo = isset($_POST["tipo"]) ? $_POST["tipo"] : null;
$cantidad = isset($_POST["cantidad"]) ? $_POST["cantidad"] : null;
$foto = isset($_FILES["foto"]) ? $_FILES["foto"] : null;

if($sabor != null && $precio != null && $tipo != null && $cantidad != null)
{
    $flag = false;
    $arrayPizzas = Pizza::TraerJSON("./archivos/pizza.json");
    //si el archivo no está vacío
    if($arrayPizzas != false)
    {
        //si hay coincidencia con el array le actualizo el precio y sumo stock
        foreach ($arrayPizzas as $value) {
            if($value->sabor == $sabor && $value->tipo == $tipo)
            {
                $value->precio = $precio;
                $value->cantidad += $cantidad;
                $flag = true;
            }
        }
        //eliminamos el archivo
        if(unlink("./archivos/pizza.json"))
        {
            //si no hubo coincidencia antes la pizza no se encuentra en el archivo, por lo tanto: se agrega
            if($flag != true)
            {
                $nuevoID = Pizza::EmularID($arrayPizzas);
                $pizza = new Pizza($sabor,$precio,$tipo,$cantidad,$nuevoID);
                array_push($arrayPizzas,$pizza);
            }
            //guardado de array en el archivo
            foreach ($arrayPizzas as $value) 
            {
                $pizza = new Pizza($value->sabor,$value->precio,$value->tipo,$value->cantidad,$value->id);
                $pizza->GuardarJSON("./archivos/pizza.json");
            }
            //muevo imagen
            $destino = "./ImagenesDePizzas/" .$tipo ."-" .$sabor ."." .pathinfo($foto["name"],PATHINFO_EXTENSION);
            move_uploaded_file($foto["tmp_name"],$destino);
        
            $retorno["respuesta"] = "Pizza agregada e imagen guardada";
        }
        else
            $retorno["respuesta"] = "Guardado fallido";
    }
    //si es la primera pizza que se agrega al archivo
    else
    {
        //se crea la venta, se guarda y se mueve la imagen
        $nuevoID = 0;
        $pizza = new Pizza($sabor,$precio,$tipo,$cantidad,$nuevoID);
        $pizza->GuardarJSON("./archivos/pizza.json");
        $destino = "./ImagenesDePizzas/" .$tipo ."-" .$sabor ."." .pathinfo($foto["name"],PATHINFO_EXTENSION);
        move_uploaded_file($foto["tmp_name"],$destino);
        $retorno["respuesta"] = "Pizza agregada e imagen guardada";
    }
}
else
    $retorno["respuesta"] = "Datos faltantes";

echo json_encode($retorno);

