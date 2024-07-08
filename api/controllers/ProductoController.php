<?php

require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';
class ProductoController
{

    public function TraerUno($request, $response, $args)
    {
        $params = $request->getParsedBody();
        $nombre = $params['nombre'] ?? null;
        $tipo = $params['tipo'] ?? null;
        $color = $params['color'] ?? null;

        $producto = Producto::obtenerProducto($nombre, $tipo, $color);

        if ($producto) {
            $payload = json_encode($producto);
        } else {
            $payload = json_encode(array("mensaje" => "Producto no encontrado"));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }


    public function TraerTodos($request, $response, $args)
    {
        $productos = Producto::obtenerProductos();
        $payload = json_encode($productos);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function BorrarProducto($request, $response, $args)
    {
        $id = $args['id'] ?? null;
        var_dump($id);
        if ($id) {
            $rowCount = Producto::borrarProducto($id);

            if ($rowCount > 0) {
                $payload = json_encode(array("mensaje" => "Producto borrado exitosamente"));
            } else {
                $payload = json_encode(array("mensaje" => "Producto no encontrado"));
            }
        } else {
            $payload = json_encode(array("mensaje" => "Faltan parámetros"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ModificarProducto($request, $response, $args)
    {
        $id = $args['id'] ?? null;
        $params = $request->getParsedBody();
        $nombre = $params['nombre'] ?? null;
        $precio = $params['precio'] ?? null;
        $tipo = $params['tipo'] ?? null;
        $talla = $params['talla'] ?? null;
        $color = $params['color'] ?? null;
        $stock = $params['stock'] ?? null;

        var_dump($id);

        if ($id && $nombre && $precio && $tipo && $talla && $color && $stock) {
            $rowCount = Producto::modificarProducto($id, $nombre, $precio, $tipo, $talla, $color, $stock);

            if ($rowCount > 0) {
                $payload = json_encode(array("mensaje" => "Producto modificado exitosamente"));
            } else {
                $payload = json_encode(array("mensaje" => "Producto no encontrado o sin cambios"));
            }
        } else {
            $payload = json_encode(array("mensaje" => "Faltan parámetros"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function CargarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();
        $nombre = $params['nombre'];
        $precio = $params['precio'];
        $tipo = $params['tipo'];
        $talla = $params['talla'];
        $color = $params['color'];
        $stock = $params['stock'];
        $imagen = $_FILES['imagen'];

        $producto = Producto::obtenerProducto($nombre, $tipo, $color);

       
        if ($producto) {
            $nuevoStock = $producto->stock + $stock;
            Producto::modificarProducto($producto->id, $nombre, $precio, $tipo, $talla, $color, $nuevoStock);
        } else {
            $nuevoProducto = new Producto();
            $nuevoProducto->nombre = $nombre;
            $nuevoProducto->precio = $precio;
            $nuevoProducto->tipo = $tipo;
            $nuevoProducto->talla = $talla;
            $nuevoProducto->color = $color;
            $nuevoProducto->stock = $stock;
            $nuevoProducto->imagen = $nombreArchivo = $nombre . '_' . $tipo . '.jpg';
            $nuevoProducto->crearProducto();
            $producto = Producto::obtenerProducto($nombre, $tipo, $color); // Obtener el producto recién creado para actualizar su imagen
        }

        // Crear el directorio si no existe
        $directorio = './ImagenesDeRopa/2024/';
        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        // Guardar la imagen en la dirección especificada
        $nombreArchivo = $nombre . '_' . $tipo . '.jpg';
        $rutaDestino = $directorio . $nombreArchivo;

        if (move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {
            Producto::guardarImagen($producto->id, $rutaDestino);
            $payload = json_encode(array("mensaje" => "Producto cargado con éxito"));
        } else {
            $payload = json_encode(array("mensaje" => "Error al guardar la imagen"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

}
