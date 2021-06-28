<?php
require_once "Cupon.php";

class Devolucion
{
    public $id;
    public $numeroPedido;
    public $causa;
    public $pathFoto;

    public function __construct($numeroPedido,$causa,$pathFoto, $id = null) {
        $this->id = $id;
        $this->numeroPedido = $numeroPedido;
        $this->causa = $causa;
        $this->pathFoto = $pathFoto;
    }

    public function ToJSON($path)
    {
        $retorno = new stdClass();
        $retorno->id = $this->id;
        $retorno->numeroPedido = $this->numeroPedido;
        $retorno->causa = $this->causa;
        $retorno->pathFoto = $this->pathFoto;

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
                    $devolucion = new Devolucion($line->numeroPedido,$line->causa,$line->pathFoto,(int)$line->id);
                    array_push($array,$devolucion);
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

    static function BuscarCuponPorID($path,$id)
    {
        $array = self::TraerJSON($path);
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

    static function TraerDevolucionConCupon($pathDev, $pathCupon, $estadoVa = false)
    {
        $arrayDev = self::TraerJSON($pathDev);
        $arrayCupon = Cupon::TraerJSON($pathCupon);
        $retorno = array();

        if($arrayCupon != false && $arrayDev != false)
        {
            echo "entro";
            foreach ($arrayDev as $dev) {
                foreach ($arrayCupon as $cupon) {
                    if($dev->id == $cupon->id)
                    {
                        $ret = new stdClass();
                        $ret->idDev = $dev->id;
                        $ret->numeroPedido = $dev->numeroPedido;
                        $ret->causa = $dev->causa;
                        $ret->pathFoto = $dev->pathFoto;
                        $ret->idCupon = $cupon->id;
                        $ret->codigoCupon = $cupon->codigo;
                        if($estadoVa == true)
                            $ret->estado = $cupon->estado;
                        array_push($retorno,$ret);
                    }
                }
            }
        }
        else
            $retorno = false;

        return $retorno;
    }

}