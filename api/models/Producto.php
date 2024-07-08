<?php

require_once './db/accesoDatos.php';

class Producto {

    public $id;
    public $nombre;
    public $precio;
    public $tipo;
    public $talla;
    public $color;
    public $stock;
    public $imagen;

    
    public static function obtenerProducto($nombre, $tipo, $color)
    {
        $objetoAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objetoAccesoDato->prepararConsulta("SELECT * FROM tienda WHERE nombre = :nombre AND tipo = :tipo AND color = :color");
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $tipo, PDO::PARAM_STR);
        $consulta->bindValue(':color', $color, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }

    public static function obtenerProductos()
    {
        $objetoAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objetoAccesoDato->prepararConsulta("SELECT * FROM tienda ");
 
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function borrarProducto($id){

        $objetoAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objetoAccesoDato->prepararConsulta("DELETE FROM tienda WHERE id = :id ");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);

        $consulta->execute();

        return $consulta->rowCount(); // Devuelve el número de filas afectadas

    }

    public function crearProducto()
    {
        $objetoAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objetoAccesoDato->prepararConsulta("INSERT INTO tienda (nombre, precio, tipo, talla, color, stock,imagen) VALUES (:nombre, :precio, :tipo, :talla, :color, :stock, :imagen)");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':talla', $this->talla, PDO::PARAM_STR);
        $consulta->bindValue(':color', $this->color, PDO::PARAM_STR);
        $consulta->bindValue(':stock', $this->stock, PDO::PARAM_INT);
        $consulta->bindValue(':imagen', $this->imagen, PDO::PARAM_STR);
        $consulta->execute();

        return $objetoAccesoDato->obtenerUltimoId();
    }

    public static function modificarProducto($id, $nombre, $precio, $tipo, $talla, $color, $stock)
    {
        $objetoAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objetoAccesoDato->prepararConsulta("UPDATE tienda SET nombre = :nombre, precio = :precio, tipo = :tipo, talla = :talla, color = :color, stock = :stock WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $precio, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $tipo, PDO::PARAM_STR);
        $consulta->bindValue(':talla', $talla, PDO::PARAM_STR);
        $consulta->bindValue(':color', $color, PDO::PARAM_STR);
        $consulta->bindValue(':stock', $stock, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->rowCount();
    }
  
    public static function guardarImagen($idProducto, $rutaImagen)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE tienda SET imagen = :imagen WHERE id = :id");
        $consulta->bindValue(':imagen', $rutaImagen, PDO::PARAM_STR);
        $consulta->bindValue(':id', $idProducto, PDO::PARAM_INT);
        $consulta->execute();
    }

}