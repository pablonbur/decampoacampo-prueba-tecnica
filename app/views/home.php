<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test POST Request</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<div class="container-fluid text-bg-secondary p-3">
    <p class="h1 text-center">Productos</p>
    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-success btn-lg" id="btnAgregarProducto" data-bs-toggle="modal" data-bs-target="#agregarProductoModal">Agregar producto</button>
    </div>
    <table id="productosTabla" class="table table-dark table-hover table-borderless">
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Nombre</th>
            <th scope="col">Descripción</th>
            <th scope="col">Precio</th>
            <th scope="col">Precio en USD</th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody id="productosTablaTbody">

        </tbody>
    </table>
</div>

<div class="modal fade" id="agregarProductoModal" tabindex="-1" aria-labelledby="agregarProductoModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="agregarProductoModalLabel">Agregar producto</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="agregarProductoForm">
                    <div class="mb-3">
                        <label for="nombreProducto" class="col-form-label">Producto</label>
                        <input type="text" class="form-control" id="nombreProducto">
                    </div>
                    <div class="mb-3">
                        <label for="descripcionProducto" class="col-form-label">Descripción</label>
                        <textarea class="form-control" id="descripcionProducto"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="precioProducto" class="col-form-label">Precio en pesos</label>
                        <input type="number" class="form-control" id="precioProducto">
                    </div>
                    <button type="submit" class="btn btn-success">Agregar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editarProductoModal" tabindex="-1" aria-labelledby="editarProductoModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editarProductoModalLabel">Editar producto</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editarProductoForm">
                    <input type="hidden" id="productoIdEditado">
                    <div class="mb-3">
                        <label for="nombreProductoEditado" class="col-form-label">Producto</label>
                        <input type="text" class="form-control" id="nombreProductoEditado">
                    </div>
                    <div class="mb-3">
                        <label for="descripcionProductoEditado" class="col-form-label">Descripción</label>
                        <textarea class="form-control" id="descripcionProductoEditado"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="precioProductoEditado" class="col-form-label">Precio en pesos</label>
                        <input type="number" class="form-control" id="precioProductoEditado">
                    </div>
                    <button type="submit" class="btn btn-success">Editar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        fetch('http://localhost:9000/api/productos', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(response => {
                if (!response.ok) {
                    console.log(response);
                    // Lanza un error con el estado de la respuesta si no es OK
                    throw new Error(`HTTP ${response.status}`);
                }
                return response.json(); // Convierte la respuesta a JSON
            })
            .then(data => {

                console.log(data);

                const tableBody = document.getElementById('productosTablaTbody');
                data.data.forEach(producto => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <th scope="row">${producto.id}</th>
                        <td>${producto.nombre}</td>
                        <td class="text-wrap">${producto.descripcion}</td>
                        <td>${formatearDosDecimales(producto.precio)}</td>
                        <td>${formatearDosDecimales(producto.precio_usd)}</td>
                        <td>
                            <button class="btn btn-warning btnEditar" data-id="${producto.id}">Editar</button>
                        </td>
                        <td>
                            <button class="btn btn-danger btnEliminar" data-id="${producto.id}">Eliminar</button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            })
            .catch((error) => {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: error
                });
            });
    });

    document.getElementById('agregarProductoForm').onsubmit = function (event) {
        event.preventDefault();

        const data = {
            nombre:         document.getElementById('nombreProducto').value,
            descripcion:    document.getElementById('descripcionProducto').value,
            precio:         document.getElementById('precioProducto').value
        };

        fetch('http://localhost:9000/api/productos', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP: ${response.status}`);
                }
                return response.json();
            })
            .then(responseData => {
                console.log(responseData);
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Producto agregado correctamente.",
                    showConfirmButton: false,
                    timer: 1500
                });

                // Llama a getProducto y espera a que se complete
                return getProducto(responseData.data);
            })
            .then(productoAgregado => {
                // Agrega el nuevo producto a la tabla
                const tableBody = document.getElementById('productosTablaTbody');
                const row = document.createElement('tr');
                row.innerHTML = `
                    <th scope="row">${productoAgregado.data.id}</th>
                    <td>${productoAgregado.data.nombre}</td>
                    <td class="text-wrap">${productoAgregado.data.descripcion}</td>
                    <td>${formatearDosDecimales(productoAgregado.data.precio)}</td>
                    <td>${formatearDosDecimales(productoAgregado.data.precio_usd)}</td>
                    <td><button class="btn btn-warning btnEditar" data-id="${productoAgregado.data.id}">Editar</button></td>
                    <td><button class="btn btn-danger btnEliminar" data-id="${productoAgregado.data.id}">Eliminar</button></td>
                `;
                tableBody.appendChild(row);

                const modal = bootstrap.Modal.getInstance(document.getElementById('agregarProductoModal'));
                modal.hide();

                document.getElementById('agregarProductoForm').reset();
            })
            .catch(error => {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: error.message
                });
            });
    };

    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('btnEliminar')) {
            const productoId = event.target.getAttribute('data-id');
            eliminarProducto(productoId, event.target.closest('tr'));
        } else if (event.target.classList.contains('btnEditar')) {
            const productoId = event.target.getAttribute('data-id');
            rellenarModalProducto(productoId);
        }
    });

    function eliminarProducto(id, row) {
        fetch(`http://localhost:9000/api/productos/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    row.remove();
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Producto eliminado correctamente.",
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    throw new Error(data.message);
                }
            })
            .catch((error) => {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: error
                });
            });
    }

    function rellenarModalProducto(id) {
        getProducto(id)
            .then(producto => {
                if (producto.success) {
                    document.getElementById('productoIdEditado').value = producto.data.id;

                    document.getElementById('nombreProductoEditado').value = producto.data.nombre;
                    document.getElementById('descripcionProductoEditado').value = producto.data.descripcion;
                    document.getElementById('precioProductoEditado').value = producto.data.precio;

                    const modal = new bootstrap.Modal(document.getElementById('editarProductoModal'));
                    modal.show();
                } else {
                    throw new Error(producto.message);
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: error.message
                });
            });
    }


    document.getElementById('editarProductoForm').onsubmit = function (event) {
        event.preventDefault();

        const id =          document.getElementById('productoIdEditado').value;
        const data = {
            nombre:         document.getElementById('nombreProductoEditado').value,
            descripcion:    document.getElementById('descripcionProductoEditado').value,
            precio:         document.getElementById('precioProductoEditado').value
        };

        fetch(`http://localhost:9000/api/productos/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP: ${response.status}`);
                }
                return response.json();
            })
            .then(responseData => {
                if (responseData.success) {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Producto actualizado correctamente.",
                        showConfirmButton: false,
                        timer: 1500
                    });

                    // Actualiza la fila de la tabla
                    const row = document.querySelector(`button[data-id="${id}"]`).closest('tr');
                    row.innerHTML = `
                        <th scope="row">${id}</th>
                        <td>${data.nombre}</td>
                        <td class="text-wrap">${data.descripcion}</td>
                        <td>${formatearDosDecimales(data.precio)}</td>
                        <td>${formatearDosDecimales(data.precio_usd)}</td>
                        <td>
                            <button class="btn btn-warning btnEditar" data-id="${id}">Editar</button>
                        </td>
                        <td>
                            <button class="btn btn-danger btnEliminar" data-id="${id}">Eliminar</button>
                        </td>
                    `;

                    const modal = bootstrap.Modal.getInstance(document.getElementById('editarProductoModal'));
                    modal.hide();

                    document.getElementById('editarProductoForm').reset();
                } else {
                    throw new Error(responseData.message);
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: error.message
                });
            });
    };

    function getProducto(id) {
        return fetch(`http://localhost:9000/api/productos/${id}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP: ${response.status}`);
                }
                return response.json();
            })
            .catch(error => {
                return error;
            });
    }

    function formatearDosDecimales(number) {
        return parseFloat(number).toFixed(2);
    }
</script>
</body>
</html>
