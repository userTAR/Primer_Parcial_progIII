<?php

//clase orientada a JSON
class Pizza
{
    public $id;
    public $sabor;
    public $precio;
    public $tipo;
    public $cantidad;

    public function __construct($sabor,$precio,$tipo,$cantidad,$id)
    {
        $this->id = (int)$id;
        $this->sabor = $sabor;
        $this->precio = (int)$precio;
        $this->tipo = $tipo;
        $this->cantidad = (int)$cantidad;
    }

    public function ToJSON($path)
    {
        $retorno = new stdClass();
        $retorno->id = $this->id;
        $retorno->sabor = $this->sabor;
        $retorno->precio = $this->precio;
        $retorno->tipo = $this->tipo;
        $retorno->cantidad = $this->cantidad;

        return json_encode($retorno);
    }

    public function GuardarJSON($path)
    {
        $retorno = new stdClass();

        $archivo = fopen($path,'a');
        if(!fwrite($archivo, $this->ToJSON($path) ."\r\n"))
        {
            $retorno->exito = false;
            $retorno->mensaje = "Guardado fallido";
        }
        else
        {
            $retorno->exito = true;
            $retorno->mensaje = "Guardado exitoso";
        }
        
        fclose($archivo);
        return json_encode($retorno);
    }

    static function TraerJSON($path)
    {
        $array = array();
        if(file_exists($path))
        {
            $archivo = fopen($path, 'r');

            while(!feof($archivo))
            {
                $line = json_decode(Trim(fgets($archivo)));
                if($line == "" || $line == null)
                    continue;
                else
                {
                    $pizza = new Pizza($line->sabor,$line->precio,$line->tipo,$line->cantidad,(int)$line->id);
                    array_push($array,$pizza);
                }
            }
            fclose($archivo);
        }
        else 
            $array = false;

        return $array;
    }

    //en base a un array
    static function EmularID($arrayDeBusqueda)
    {
        $nuevoID = null;
        if($arrayDeBusqueda != false)
        {
            $nuevoID = (int)$arrayDeBusqueda[count($arrayDeBusqueda)-1]->id + 1;
        }
        return $nuevoID;
    }

    static function BuscarPizzaPorTipo_Sabor($path,$sabor,$tipo)
    {
        $array = Pizza::TraerJSON($path);
        $retorno = false;

        if($array != null)
        {
            foreach ($array as $value) 
            {
                if($value->sabor == $sabor && $value->tipo == $tipo)
                {
                    $retorno = $value;
                    break;
                } 
            }
        }
        return $retorno;
    }

    static function BuscarPizzaPorID($path,$id)
    {
        $array = Pizza::TraerJSON($path);
        $retorno = false;

        if($array != null)
        {
            foreach ($array as $value) 
            {
                if($value->id == $id)
                {
                    $retorno = $value;
                    break;
                } 
            }
        }
        return $retorno;
    }
}