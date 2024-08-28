<?php

require_once __DIR__ . '/../helpers/ResponseHelper.php';
require_once __DIR__ . '/../helpers/ValidationHelper.php';
require_once __DIR__ . '/../transformers/ProductoTransformer.php';


class ProductoController {
    private Producto $productoModel;
    private ProductoTransformer $transformer;

    public function __construct() {
        $this->productoModel = new Producto();
        $this->transformer = new ProductoTransformer();
    }

    public function productos() {
        $productos = $this->productoModel->getAll();
        $productosTransformados = array_map([$this->transformer, 'transform'], $productos);
        ResponseHelper::handleResponse($productosTransformados);
    }

    public function producto($id) {
        $producto = $this->productoModel->getById($id);
        $productoTransformado = $this->transformer->transform($producto);
        ResponseHelper::handleResponse($productoTransformado);
    }

    public function crearProducto() {
        $post = json_decode(file_get_contents('php://input'), true);

        $requiredFields = ['nombre', 'descripcion', 'precio'];
        ValidationHelper::validate($post, $requiredFields);

        $data = $this->productoModel->create($post);
        ResponseHelper::handleResponse($data);
    }

    public function editarProducto($id) {
        $post = json_decode(file_get_contents("php://input"), true);

        $requiredFields = ['nombre', 'descripcion', 'precio'];
        ValidationHelper::validate($post, $requiredFields);


        $data = $this->productoModel->update($id, $post);
        ResponseHelper::handleResponse($data);
    }

    public function eliminarProducto($id) {
        $data = $this->productoModel->delete($id);
        ResponseHelper::handleResponse($data);
    }

}
