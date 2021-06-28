<?php
require_once "AccesoDatos.php";
require_once "Pizza.php";

//clase orientada a base de datos
class Venta
{
    public $id;
    public $email;
    public $idPizza;
    public $sabor;
    public $tipo;
    public $cantidad;
    public $numero_pedido;
    public $fecha;
    public $pathFoto; 

    function __construct($email, $idPizza, $sabor, $tipo, $cantidad, $numeroPedido,$fecha, $pathFoto = null, $id = null)
    {
        $this->id = $id;
        $this->email = $email;
        $this->idPizza = $idPizza;
        $this->sabor = $sabor;
        $this->tipo = $tipo;
        $this->cantidad = $cantidad;
        $this->numero_pedido = $numeroPedido;
        $this->fecha = $fecha;
        $this->pathFoto = $pathFoto;
    }

    function Agregar()
    {
        $retorno = true;
        
        $acceso = AccesoDatos::DameUnObjetoAcceso();

        $consulta = $acceso->RetornarConsulta("INSERT INTO ventas (email, id_pizza, sabor, tipo, cantidad, numero_pedido, fecha, path_foto) VALUES (:email, :id_pizza, :sabor, :tipo, :cantidad, :numero_pedido, :fecha, :path_foto)");
        $consulta->bindParam(":email", $this->email, PDO::PARAM_STR);
        $consulta->bindParam(":id_pizza", $this->idPizza, PDO::PARAM_INT);
        $consulta->bindParam(":sabor", $this->sabor, PDO::PARAM_STR);
        $consulta->bindParam(":tipo", $this->tipo, PDO::PARAM_STR);
        $consulta->bindParam(":cantidad", $this->cantidad, PDO::PARAM_INT);
        $consulta->bindParam(":numero_pedido", $this->numero_pedido, PDO::PARAM_INT);
        $consulta->bindParam(":fecha", $this->fecha, PDO::PARAM_STR);
        $consulta->bindParam(":path_foto", $this->pathFoto, PDO::PARAM_STR);

        if(!($consulta->execute()))
            $retorno = false;

        return $retorno;
    }

    static function Eliminar($numeroPedido)
    {
        $retorno = false;
            if($numeroPedido != null)
            {
                $acceso = AccesoDatos::DameUnObjetoAcceso();
                
                $consulta = $acceso->RetornarConsulta("DELETE FROM ventas WHERE numero_pedido = :num");
                $consulta->bindParam(':num', $numeroPedido, PDO::PARAM_INT);

                if(!$consulta->execute())
                    $retorno = false;
                else
                    $retorno = true;
            }
            return $retorno;
    }

    function Modificar()
    {
        $retorno = true;
            
        $acceso = AccesoDatos::DameUnObjetoAcceso();
        $consulta = $acceso->RetornarConsulta("UPDATE ventas SET email = :email , id_pizza = :id_pizza, sabor = :sabor, tipo = :tipo, cantidad = :cantidad, fecha = :fecha WHERE numero_pedido = :numero_pedido");
        $consulta->bindParam(':email', $this->email, PDO::PARAM_STR);
        $consulta->bindParam(':id_pizza', $this->idPizza, PDO::PARAM_STR);
        $consulta->bindParam(":sabor", $this->sabor, PDO::PARAM_STR);
        $consulta->bindParam(":tipo", $this->tipo, PDO::PARAM_STR);
        $consulta->bindParam(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindParam(':numero_pedido', $this->numero_pedido, PDO::PARAM_INT);
        $consulta->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);

        if(!($consulta->execute()))
            $retorno = false;
    
        return $retorno;
    }

    static function ExistenciaPorNumeroPedido($numeroPedido) // hay error acá
    {   
        $acceso = AccesoDatos::DameUnObjetoAcceso();
        $consulta = $acceso->RetornarConsulta("SELECT * FROM ventas WHERE numero_pedido = :num");
        $consulta->bindParam(':num', $numeroPedido, PDO::PARAM_INT);

        if($consulta->execute())
            $retorno = $consulta->fetchAll(PDO::FETCH_OBJ);
        else
            $retorno = false;

        return $retorno;

    }

    //------------------------------------------------CONSULTAS---------------------------------------------------

    static function CantidadPizzasVendidas()
    {
        $acceso = AccesoDatos::DameUnObjetoAcceso();

        $consulta = $acceso->RetornarConsulta("SELECT SUM(cantidad) FROM ventas");

        if($consulta->execute())
            $retorno = $consulta->fetchAll();
        else
            $retorno = "Error en la consulta";
        
        return json_encode($retorno);
    }

    static function ListadoVentasPorFechas($fechaMasVieja, $fechaMasNueva)
    {
        
        if(strtotime($fechaMasVieja) < strtotime($fechaMasNueva))
        {
            $acceso = AccesoDatos::DameUnObjetoAcceso();

            $consulta = $acceso->RetornarConsulta("SELECT * FROM ventas WHERE fecha BETWEEN :fechaAntigua AND :fechaNueva ORDER BY sabor ASC");
            $consulta->bindParam(":fechaAntigua", $fechaMasVieja, PDO::PARAM_STR);
            $consulta->bindParam(":fechaNueva", $fechaMasNueva, PDO::PARAM_STR);

            if($consulta->execute())
                $retorno = $consulta->fetchAll(PDO::FETCH_OBJ);
            else
                $retorno = "Error en la consulta";

            return json_encode($retorno);
        }
    }

    static function VentasPorUsuario($email)
    {
        $acceso = AccesoDatos::DameUnObjetoAcceso();
        $consulta = $acceso->RetornarConsulta("SELECT * FROM ventas WHERE email = :email");
        $consulta->bindParam(":email", $email, PDO::PARAM_STR);
        
        if($consulta->execute())
            $retorno = $consulta->fetchAll(PDO::FETCH_OBJ);
        else 
            $retorno = "Error en la consulta";

        return json_encode($retorno);
    }

    static function VentasPorSabor($sabor)
    {
        $acceso = AccesoDatos::DameUnObjetoAcceso();
        $consulta = $acceso->RetornarConsulta("SELECT * FROM ventas WHERE sabor = :sabor");
        $consulta->bindParam(":sabor", $sabor, PDO::PARAM_STR);

        if($consulta->execute())
            $retorno = $consulta->fetchAll(PDO::FETCH_OBJ);
        else 
            $retorno = "Error en la consulta";


        return json_encode($retorno);
    } 
}