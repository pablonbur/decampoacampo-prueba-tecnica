<?php

require_once 'BaseTestCase.php';

class ProductoTest extends BaseTestCase
{
    public function testCrearProducto()
    {
        $data = [
            'nombre' => 'Producto de Prueba',
            'descripcion' => 'Descripción de prueba',
            'precio' => 123.45,
        ];

        $response = $this->postJson('/productos', $data);

        // Verifico que dé 200
        $response->assertStatus(200);

        $responseData = json_decode($response->getContent(), true);

        // Verifico que esté la estructura del ResponseHelper

        $this->assertArrayHasKey('success', $responseData);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('data', $responseData);

        // Verifico que data sea un int, ya que se devuelve el ID del producto creado.
        $this->assertTrue($responseData['success']);
        $this->assertIsInt($responseData['data']);
    }

    public function testObtenerProductos()
    {
        $response = $this->getJson('/productos');

        // Verifico que dé 200
        $response->assertStatus(200);

        $responseData = json_decode($response->getContent(), true);

        // Verifico que esté la estructura del ResponseHelper

        $this->assertArrayHasKey('success', $responseData);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('data', $responseData);

        // Verifico que data sea un array.
        $this->assertIsArray($responseData['data']);

        // Si es un array, podría ser false y array vacío, sino, true y con datos.
        $this->assertTrue($responseData['success']);
        $this->assertNotEmpty($responseData['data']);

        // Ahora verifico si los ítems en data están, y si tienen su tipo de dato acorde.
        foreach ($responseData['data'] as $producto) {
            $this->assertArrayHasKey('id', $producto);
            $this->assertArrayHasKey('nombre', $producto);
            $this->assertArrayHasKey('descripcion', $producto);
            $this->assertArrayHasKey('precio', $producto);
            $this->assertArrayHasKey('precio_usd', $producto);

            $this->assertIsInt($producto['id']);
            $this->assertIsString($producto['nombre']);
            $this->assertIsString($producto['descripcion']);
        }
    }

}
