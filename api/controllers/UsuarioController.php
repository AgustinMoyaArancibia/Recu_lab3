<?php
require_once './models/Usuario.php';

class UsuarioController{

    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $mail = $parametros['mail'];
        $usuario = $parametros['usuario'];
        $clave = $parametros['clave'];
        $perfil = $parametros['perfil'];
        $foto = $_FILES['foto'];
        $fecha = date('Y-m-d');
        // Creamos el usuario
        $user = new Usuario();
        $user->mail = $mail;
        $user->usuario = $usuario;
        $user->clave = $clave;
        $user->perfil = $perfil;
        $user->fechaAlta =$fecha;
        
        $user->foto = $nombreArchivo = $usuario . '_' . $perfil . '_' . $fecha  . '.jpg';
        $userId = $user->crearUsuario();

            // Crear el directorio si no existe
            $directorio = './ImagenesDeUsuarios/2024/';
            if (!is_dir($directorio)) {
                mkdir($directorio, 0777, true);
            }
    
            // Guardar la imagen en la dirección especificada
            $rutaDestino = $directorio . $nombreArchivo;
    
            if (move_uploaded_file($foto['tmp_name'], $rutaDestino)) {
                Usuario:: guardarFoto($userId, $rutaDestino);
                $payload = json_encode(array("mensaje" => "Usuario cargado con éxito"));
            } else {
                $payload = json_encode(array("mensaje" => "Error al guardar la imagen"));
            }



        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }


    public function ValidarUsuario($request, $response, $args)
    {
  
      $parametros = $request->getParsedBody();
  
      var_dump("usuario pasado", $parametros);
      $usuario = $parametros['usuario'];
      $clave = $parametros['clave'];
      /* $payload = json_encode(array("mensaje" => "Error de autenticacion")); */
  
      $auxUser = Usuario::loginUsuario($usuario);
      var_dump("usuario base de datos", $auxUser);

      if ($auxUser != false ) {
  
        $clave = password_verify($clave, $auxUser->clave);
  
        var_dump($clave == $auxUser->clave);

        if ($clave == $auxUser->clave) {
  
          $datos = array('usuario' => $usuario, 'clave' => $auxUser->clave, 'perfil' => $auxUser->perfil);
          $token = AutentificadorJWT::CrearToken($datos);
          $payload = json_encode(array("jwt" => $token, "response" => "ok", "perfil de usuario" => $auxUser->perfil));

        }
      }
  
  
      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }
}