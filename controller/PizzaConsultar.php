<?php

require_once "./clases/Pizza.php";

$sabor = isset($_POST["sabor"]) ? $_POST["sabor"] : null;
$tipo = isset($_POST["tipo"]) ? $_POST["tipo"] : null;

//si existe la pizza
if(Pizza::BuscarPizzaPorTipo_Sabor("./archivos/pizza.json",$sabor,$tipo) != false)
    $retorno["mensaje"] = "Si hay";
else
    $retorno["mensaje"] = "No se encontro coincidencia con sabor y tipo";

echo json_encode($retorno);
