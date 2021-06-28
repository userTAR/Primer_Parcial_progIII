<?php

class Cupon
{
    public $id;
    public $codigo;
    public $descuento;
    public $estado;

    public function __construct($codigo,$descuento,$estado, $id = null) {
        $this->id = $id;
        $this->codigo = $codigo;
        $this->descuento = $descuento;
        $this->estado = $estado;
    }

    public function ToJSON($path)
    {
        $retorno = new stdClass();
        $retorno->id = $this->id;
        $retorno->codigo = $this->codigo;
        $retorno->descuento = $this->descuento;
        $retorno->estado = $this->estado;

        return json_encode($retorno);
    }

    public function GuardarJSON($path)
    {
        $retorno = new stdClass();

        $archivo = fopen($path,'a');
        if(!fwrite($archivo, $this->ToJSON($path) ."\r\n"))
        {
            $retorno = false;
        }
        else
        {
            $retorno = true;
        }
        
        fclose($archivo);
        return $retorno;
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
                    $cupon = new Cupon($line->codigo,$line->descuento,$line->estado,(int)$line->id);
                    array_push($array,$cupon);
                }
            }
            fclose($archivo);
        }
        else 
            $array = false;

        return $array;
    }

    static function GuardarImporte($id, $precio)
    {
        $importe = new stdClass();
        $importe->id = $id;
        $importe->precio = $precio;

        $archivo = fopen("./archivos/importes.json",'a');
        if(!fwrite($archivo,json_encode($importe) ."\r\n"))
        {
            $retorno = false;
        }
        else
        {
            $retorno = true;
        }
        
        fclose($archivo);
        return $retorno;
    }
    static function TraerImporte()
    {
        $array = array();
        if(file_exists("./archivos/importes.json"))
        {
            $archivo = fopen("./archivos/importes.json", 'r');

            while(!feof($archivo))
            {
                $line = json_decode(Trim(fgets($archivo)));
                if($line == "" || $line == null)
                    continue;
                else
                {
                    $importe = new stdClass($line->id,$line->precio);
                    array_push($array,$importe);
                }
            }
            fclose($archivo);
        }
        else 
            $array = false;

        return $array;
    }

    static function BuscarCuponPorCodigo($path,$codigoCupon)
    {
        $array = self::TraerJSON($path);
        $retorno = false;

        if($array != null)
        {
            foreach ($array as $value) 
            {
                if($value->codigo == $codigoCupon)
                {
                    $retorno = $value;
                    break;
                } 
            }
        }
        return $retorno;
    }
}
