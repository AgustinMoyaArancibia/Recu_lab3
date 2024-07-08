<?php

class Usuario
{
    public $id;
    public $mail;
    public $usuario;
    public $clave;
    public $perfil;
    public $foto;
    public $fechaAlta;
    public $fechaBaja;

    public function crearUsuario()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuarios (mail,usuario, clave, perfil, foto ,fechaAlta) VALUES (:mail ,:usuario, :clave, :perfil, :foto ,:fechaAlta)");
        
        $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':mail', $this->mail, PDO::PARAM_STR);

        $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash);
        $consulta->bindValue(':perfil', $this->perfil, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
        $consulta->bindValue(':fechaAlta', $this->fechaAlta, PDO::PARAM_STR);

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function guardarFoto($idUsuario, $rutaFoto)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE usuarios SET foto = :foto WHERE id = :id");
        $consulta->bindValue(':foto', $rutaFoto, PDO::PARAM_STR);
        $consulta->bindValue(':id', $idUsuario, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function loginUsuario($usuario)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, mail,usuario, clave, perfil FROM usuarios WHERE usuario = :usuario");
        $consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Usuario');
    }
}