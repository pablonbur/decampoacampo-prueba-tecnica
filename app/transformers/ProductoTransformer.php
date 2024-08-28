<?php

class ProductoTransformer
{
    private float $usdRate;

    public function __construct()
    {
        $this->usdRate = (float)getenv('PRECIO_USD');
    }
    // Transform nos daría la opción de modificar otros campos también, pero en este caso, tocaré el precio_usd solamente.
    public function transform(array $producto): array
    {
        return [
            'id' => $producto['id'],
            'nombre' => $producto['nombre'],
            'descripcion' => $producto['descripcion'],
            'precio' => $producto['precio'],
            'precio_usd' => $this->convertirPesosADolares($producto['precio']),
            'created_at' => $producto['created_at'],
            'updated_at' => $producto['updated_at']
        ];
    }

    private function convertirPesosADolares(string $precioEnPesos): float
    {
        return (float)$precioEnPesos / $this->usdRate;
    }
}
