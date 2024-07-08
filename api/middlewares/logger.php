<?php
use Slim\Psr7\Response as Response;

class ConfirmarPerfil
{

    private $perfilesPermitidos;

    public function __construct(...$perfiles) {
        $this->perfilesPermitidos = $perfiles;
    }


    public function __invoke($request, $handler) {
        $token = $this->validarToken($request);
        if ($token === null) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(array('error' => 'token vacio')));
            return $response->withHeader('Content-Type', 'application/json');
        }

        try {
            $payload = AutentificadorJWT::ObtenerData($token);

        
            if (in_array($payload->perfil, $this->perfilesPermitidos)) {
                return $handler->handle($request);
            } else {
                $response = new \Slim\Psr7\Response();
                $response->getBody()->write(json_encode(array('error' => 'error de autenticacion')));
                return $response->withHeader('Content-Type', 'application/json');
            }
        } catch (Exception $e) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(array('error' => $e->getMessage())));
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    private function validarToken($request) {
        $header = $request->getHeaderLine('Authorization');

       
        if (empty($header)) {
            return null; // Token vacío
        }

        $token = trim(explode("Bearer", $header)[1]);
  
        return $token;
    }

}