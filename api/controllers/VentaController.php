<?php

require_once './models/Venta.php';
require_once './models/Producto.php';

class VentaController
{

    public function CargarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();
        $uploadedFiles = $request->getUploadedFiles();

        $email = $params['email'];
        $nombre = $params['nombre'];
        $tipo = $params['tipo'];
        $color = $params['color'];
        $talla = $params['talla'];
        $stock = $params['stock'];
        $fecha = date('Y-m-d');
        $numero_pedido = rand(1000, 9999); // Generar un número de pedido aleatorio
        $imagen = $uploadedFiles['imagen'] ?? null;

        $producto = Producto::obtenerProducto($nombre, $tipo, $color);

        if ($producto && $producto->stock >= $stock) {
            // Crear una nueva venta
            $nuevaVenta = new Venta();
            $nuevaVenta->email = $email;
            $nuevaVenta->nombre = $nombre;
            $nuevaVenta->tipo = $tipo;
            $nuevaVenta->talla = $talla;
            $nuevaVenta->stock = $stock;
            $nuevaVenta->fecha = $fecha;
            $nuevaVenta->numero_pedido = $numero_pedido;
            $nuevaVenta->imagen = $nombreArchivo = $nombre . '_' . $tipo . '_' . $talla . '_' . $email . '_' . $fecha . '.jpg';
            $nuevaVenta->crearVenta();

            $nuevoStock = $producto->stock - $stock;
            Producto::modificarProducto($producto->id, $producto->nombre, $producto->precio, $producto->tipo, $producto->talla, $producto->color, $nuevoStock);

            // Crear el directorio si no existe
            $directorio = './ImagenesDeVenta/2024/';
            if (!is_dir($directorio)) {
                mkdir($directorio, 0777, true);
            }

            // Guardar la imagen en la dirección especificada
            $rutaDestino = $directorio . $nombreArchivo;

            if ($imagen && $imagen->getError() === UPLOAD_ERR_OK) {
                $imagen->moveTo($rutaDestino);
                Producto::guardarImagen($producto->id, $rutaDestino);
                $payload = json_encode(array("mensaje" => "Producto cargado con éxito"));
            } else {
                $payload = json_encode(array("mensaje" => "Error al guardar la imagen"));
            }

            $payload = json_encode(array("mensaje" => "Venta registrada con éxito"));
        } else {
            $payload = json_encode(array("mensaje" => "Stock insuficiente o producto no encontrado"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }


    public function VentasPorUsuario($request, $response, $args)
    {
        $email = $args['email'];
        $ventas = Venta::VentasPorUsuario($email);

        if ($ventas) {
            $payload = json_encode(array(
                "mensaje" => "Compras del usuario",
                "ventas" => $ventas
            ));
        } else {
            $payload = json_encode(array("mensaje" => "El usuario no tiene compras"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function VentasPorProducto($request, $response, $args)
    {
        $nombre = $args['nombre'];
        $tipo = $args['tipo'];
        $ventas = Venta::VentasPorProducto($nombre, $tipo);

        if ($ventas) {
            $payload = json_encode(array(
                "mensaje" => "Productos comprados",
                "ventas" => $ventas
            ));
        } else {
            $payload = json_encode(array("mensaje" => "No hay ventas de dicho producto"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function VentasPorFecha($request, $response, $args)
    {
        $fecha = $args['fecha'] ?? date('Y-m-d', strtotime('-1 day'));
      
        $ventas = Venta::VentasPorFecha($fecha);
     
        if ($ventas) {
            $cantidad = count($ventas);
            $mensaje = $args['fecha'] ? "Compras del usuario en la fecha $fecha" :
             "Compras del usuario en la fecha de ayer ($fecha)";
            $payload = json_encode(array(
                "mensaje" => $mensaje,
                "cantidad" => $cantidad,
                "ventas" => $ventas
            ));
        } else {
            $mensaje = $args['fecha'] ? "No hay compras para la fecha $fecha" : "No hay compras para la fecha de ayer ($fecha)";
            $payload = json_encode(array("mensaje" => $mensaje));
        }
    
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public function IngresosPorDia($request, $response, $args)
    {
        $fecha = $args['fecha'] ?? null;
   
        $ingresos = Venta::IngresosPorDia($fecha);
        $ventas = Venta::VentasPorFecha($fecha);
     

        $payload = json_encode(array(
            "ingresos" => $ingresos,
            "ventas" => $ventas,
        ));

       

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ProductoMasVendido($request, $response, $args)
    {
        $producto = Venta::ProductoMasVendido();
        $payload = json_encode($producto);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }


    public function ModificarVenta($request, $response, $args)
    {
        $params = $request->getParsedBody();
        $numero_pedido = $params['numero_pedido'];
        $email = $params['email'];
        $nombre = $params['nombre'];
        $tipo = $params['tipo'];
        $talla = $params['talla'];
        $stock = $params['stock'];

        $venta = Venta::VentaPorNumeroPedido($numero_pedido);

        if ($venta) {
            Venta::modificarVenta($numero_pedido, $email, $nombre, $tipo, $talla, $stock);
            $payload = json_encode(array("mensaje" => "Venta modificada con éxito"));
        } else {
            $payload = json_encode(array("mensaje" => "Venta no encontrada"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ProductoEntreValores($request, $response, $args)
    {
        $min = $args['min'];
        $max = $args['max'];
    
        $productos = Venta::obtenerProductosEntreValores($min, $max);

        $payload = json_encode($productos);
    
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');

    }

    public function DescargarCSV($request, $response, $args) {
        // Obtener las ventas de la base de datos
        $ventas = Venta::obtenerTodasVentas();

        // Generar el contenido del CSV
        $csv = "ID,Email,Nombre,Tipo,Talla,Stock,Fecha,Numero_Pedido\n";
        foreach ($ventas as $venta) {
            $csv .= "{$venta->id},{$venta->email},{$venta->nombre},{$venta->tipo},{$venta->talla},{$venta->stock},{$venta->fecha},{$venta->numero_pedido}\n";
        }

        // Crear la respuesta con el archivo CSV
        $response->getBody()->write($csv);

        return $response
            ->withHeader('Content-Type', 'text/csv')
            ->withHeader('Content-Disposition', 'attachment; filename="ventas.csv"');
    }

}
