<?php 
namespace App\Controllers;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel de Gestión Docente - ProfeWeb</title>

    <!-- Inclusión de Bootstrap 5 CSS para diseño responsive -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
          rel="stylesheet" 
          crossorigin="anonymous">
    
    <!-- Incluir Bootstrap Icons para iconos profesionales -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* ESTILOS FIJOS: AZUL (FIABILIDAD) y VERDE (EDUCACIÓN/CRECIMIENTO) */
        body {
            background-color: #f8f9fa; 
            font-family: sans-serif;
        }
        .main-content {
            padding-top: 30px;
        }
        /* Acento Verde en el borde de la tarjeta (Consistencia con Login/Registro) */
        .card-accent-green {
            border-left: 5px solid var(--bs-success); 
            border-radius: 0.75rem;
        }
        /* Estilo para las pestañas de navegación, usando el Azul corporativo */
        .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
            background-color: var(--bs-primary);
        }
        .nav-link {
            color: var(--bs-primary);
        }
    </style>
</head>
<body>

    <!-- BARRA DE NAVEGACIÓN - AZUL DOMINANTE (Fiabilidad) -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">ProfeWeb</a>
            <span class="navbar-text text-white me-auto">
                Panel de Gestión Privada
            </span>
            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item">
                    <!-- OE3: Portafolio Profesional -->
                    <a class="nav-link text-white" href="#"><i class="bi bi-person-circle"></i> Mi Perfil (Portafolio)</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="login.html"><i class="bi bi-box-arrow-right"></i> Cerrar Sesión</a>
                </li>
            </ul>
        </div>
    </nav>

    <main class="container main-content">
        <div class="card shadow-lg card-accent-green border-0 p-3">
            <div class="card-body">
                <h2 class="text-primary mb-4">Gestión de Contenidos Académicos (CRUD)</h2>

                <!-- NAVEGACIÓN DE PESTAÑAS (TABS) -->
                <ul class="nav nav-pills mb-3 nav-fill" id="pills-tab" role="tablist">
                    <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#pills-asignaturas" type="button"><i class="bi bi-book"></i> Asignaturas</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-temarios" type="button"><i class="bi bi-folder-fill"></i> Temarios</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-competencias" type="button"><i class="bi bi-award"></i> Competencias</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-criterios" type="button"><i class="bi bi-check2-square"></i> Criterios</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-actividades" type="button"><i class="bi bi-list-check"></i> Actividades</button></li>
                </ul>

                <!-- CONTENIDO DE LAS PESTAÑAS -->
                <div class="tab-content" id="pills-tabContent">
                    
                    <!-- PESTAÑA 1: ASIGNATURAS -->
                    <div class="tab-pane fade show active" id="pills-asignaturas" role="tabpanel">
                        <h4 class="text-primary">Listado de Asignaturas</h4>
                        
                        <!-- Botón Crear Nuevo - VERDE (Acción de Crecimiento/Educación) -->
                        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalCrearAsignatura">
                            <i class="bi bi-plus-circle"></i> Crear Nueva Asignatura
                        </button>

                        <!-- Tabla de Datos Dinámicos (tbody id="asignaturas-table-body") -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Visibilidad</th>
                                        <th>Acciones CRUD</th>
                                    </tr>
                                </thead>
                                <tbody id="asignaturas-table-body">
                                    <!-- FILAS DE EJEMPLO (Se cargarían dinámicamente desde el Controlador) -->
                                    <tr data-id="1" data-nombre="Informática II" data-publico="1">
                                        <td>1</td>
                                        <td>Informática II</td>
                                        <td><span class="badge bg-success">Público</span></td> 
                                        <td>
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalModificar" onclick="cargarDatosModal(1, 'Informática II', 'asignatura')">
                                                <i class="bi bi-pencil"></i> Modificar
                                            </button>
                                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Eliminar</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- PESTAÑA 2: TEMARIOS (UNIDADES DIDÁCTICAS) -->
                    <div class="tab-pane fade" id="pills-temarios" role="tabpanel">
                        <h4 class="text-primary">Gestión de Temarios (Unidades)</h4>
                        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalCrearTemario">
                            <i class="bi bi-plus-circle"></i> Crear Nueva Unidad
                        </button>
                        <p class="text-muted">Aquí se listarían las unidades didácticas con sus botones de acción.</p>
                        <div class="table-responsive"><table class="table table-striped table-hover align-middle">
                            <thead class="table-light"><tr><th>ID</th><th>Nombre</th><th>Asignatura</th><th>Acciones CRUD</th></tr></thead>
                            <tbody id="temarios-table-body"></tbody>
                        </table></div>
                    </div>

                    <!-- PESTAÑA 3: COMPETENCIAS -->
                    <div class="tab-pane fade" id="pills-competencias" role="tabpanel">
                        <h4 class="text-primary">Gestión de Competencias Clave</h4>
                        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalCrearCompetencia">
                            <i class="bi bi-plus-circle"></i> Crear Nueva Competencia
                        </button>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead class="table-light"><tr><th>Código</th><th>Descripción</th><th>Acciones CRUD</th></tr></thead>
                                <tbody id="competencias-table-body">
                                    <tr data-id="101" data-codigo="STEM"><td>STEM</td><td>Competencia en ciencia, tecnología, ingeniería y matemáticas.</td><td><button class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i> Modificar</button><button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Eliminar</button></td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- PESTAÑA 4: CRITERIOS DE EVALUACIÓN -->
                    <div class="tab-pane fade" id="pills-criterios" role="tabpanel">
                        <h4 class="text-primary">Gestión de Criterios de Evaluación</h4>
                        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalCrearCriterio">
                            <i class="bi bi-plus-circle"></i> Crear Nuevo Criterio
                        </button>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead class="table-light"><tr><th>Código</th><th>Descripción</th><th>Acciones CRUD</th></tr></thead>
                                <tbody id="criterios-table-body">
                                    <tr data-id="201" data-codigo="CE 1.1"><td>CE 1.1</td><td>Evalúa la aplicación de la arquitectura MVC y la separación de capas.</td><td><button class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i> Modificar</button><button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Eliminar</button></td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- PESTAÑA 5: ACTIVIDADES / EXÁMENES -->
                    <div class="tab-pane fade" id="pills-actividades" role="tabpanel">
                        <h4 class="text-primary">Gestión de Actividades y Exámenes</h4>
                        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalCrearActividad">
                            <i class="bi bi-plus-circle"></i> Crear Nueva Actividad
                        </button>
                        <p class="text-muted">Aquí se listarían las actividades evaluables relacionadas con unidades y criterios.</p>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <!-- ************************************ -->
    <!-- MODALES DE FORMULARIOS (VISTA) -->
    <!-- ************************************ -->

    <!-- MODAL GENÉRICO DE MODIFICACIÓN (UPDATE) - AZUL PRINCIPAL -->
    <div class="modal fade" id="modalModificar" tabindex="-1" aria-labelledby="modalModificarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content card-accent-green">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalModificarLabel"><i class="bi bi-pencil-square"></i> Modificar Elemento</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulario de ejemplo para modificación (Los datos se cargan vía JS) -->
                    <form id="formModificar" action="/update_content" method="POST">
                        <input type="hidden" id="edit-id" name="id">
                        <input type="hidden" id="edit-type" name="type">
                        <div class="mb-3">
                            <label for="edit-nombre" class="form-label">Nombre del Elemento</label>
                            <input type="text" class="form-control" id="edit-nombre" name="nombre" required>
                        </div>
                        <!-- ... Más campos para modificación ... -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="edit-publico" name="es_publico" value="1">
                            <label class="form-check-label text-success fw-bold" for="edit-publico">Hacer Público</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mt-2 fw-bold">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- MODAL DE CREACIÓN DE ASIGNATURA (CREATE) - VERDE DE ÉNFASIS -->
    <div class="modal fade" id="modalCrearAsignatura" tabindex="-1" aria-labelledby="modalCrearAsignaturaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content card-accent-green">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalCrearAsignaturaLabel"><i class="bi bi-plus-circle"></i> Crear Nueva Asignatura</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <!-- URL de acción que el Router PHP mapeará al AsignaturaController@store -->
                    <form id="formCrearAsignatura" action="/asignaturas/store" method="POST">
                        <div class="mb-3">
                            <label for="new-asignatura-nombre" class="form-label">Nombre de la Asignatura</label>
                            <input type="text" class="form-control" id="new-asignatura-nombre" name="nombre" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="new-asignatura-publico" name="es_publico" value="1">
                            <label class="form-check-label text-success fw-bold" for="new-asignatura-publico">
                                Publicar en el Sitio Público
                            </label>
                        </div>
                        <button type="submit" class="btn btn-success w-100 mt-2 fw-bold">Crear Asignatura</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL DE CREACIÓN DE COMPETENCIA (CREATE) - VERDE DE ÉNFASIS -->
    <div class="modal fade" id="modalCrearCompetencia" tabindex="-1" aria-labelledby="modalCrearCompetenciaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content card-accent-green">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalCrearCompetenciaLabel"><i class="bi bi-award"></i> Crear Nueva Competencia Clave</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <!-- URL de acción que el Router PHP mapeará al CompetenciaController@store -->
                    <form id="formCrearCompetencia" action="/competencias/store" method="POST">
                        <div class="mb-3">
                            <label for="new-comp-codigo" class="form-label">Código de Competencia (Ej: STEM)</label>
                            <input type="text" class="form-control" id="new-comp-codigo" name="codigo" required>
                        </div>
                        <div class="mb-3">
                            <label for="new-comp-desc" class="form-label">Descripción</label>
                            <textarea class="form-control" id="new-comp-desc" name="descripcion" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success w-100 mt-2 fw-bold">Crear Competencia</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- MODAL DE CREACIÓN DE CRITERIO DE EVALUACIÓN (CREATE) - VERDE DE ÉNFASIS -->
    <div class="modal fade" id="modalCrearCriterio" tabindex="-1" aria-labelledby="modalCrearCriterioLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content card-accent-green">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalCrearCriterioLabel"><i class="bi bi-check2-square"></i> Crear Nuevo Criterio de Evaluación</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <!-- URL de acción que el Router PHP mapeará al CriterioController@store -->
                    <form id="formCrearCriterio" action="/criterios/store" method="POST">
                        <div class="mb-3">
                            <label for="new-crit-codigo" class="form-label">Código (Ej: CE 1.1)</label>
                            <input type="text" class="form-control" id="new-crit-codigo" name="codigo" required>
                        </div>
                        <div class="mb-3">
                            <label for="new-crit-desc" class="form-label">Descripción</label>
                            <textarea class="form-control" id="new-crit-desc" name="descripcion" required></textarea>
                        </div>
                        
                        <!-- El criterio debe estar asociado a una asignatura/unidad, pero simplificamos a modo de ejemplo -->
                        <div class="mb-3">
                            <label for="new-crit-asig" class="form-label">Asignatura</label>
                            <select class="form-select" id="new-crit-asig" name="asignatura_id" required>
                                <option value="1">Informática II</option>
                                <!-- Opciones cargadas dinámicamente -->
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-success w-100 mt-2 fw-bold">Crear Criterio</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- PLACEHOLDERS para otros Modales de Creación (Temario y Actividades) -->
    <div class="modal fade" id="modalCrearTemario" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header bg-success text-white"><h5 class="modal-title">Crear Unidad Didáctica</h5></div><div class="modal-body"><p>Formulario para insertar datos en la tabla `unidades_didacticas`.</p><form id="formCrearTemario" action="/temarios/store" method="POST"><button type="submit" class="btn btn-success w-100">Crear Unidad</button></form></div></div></div></div>
    <div class="modal fade" id="modalCrearActividad" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header bg-success text-white"><h5 class="modal-title">Crear Actividad/Examen</h5></div><div class="modal-body"><p>Formulario para insertar datos en la tabla `actividades`.</p><form id="formCrearActividad" action="/actividades/store" method="POST"><button type="submit" class="btn btn-success w-100">Crear Actividad</button></form></div></div></div></div>


    <!-- 5. Inclusión del JS de Bootstrap 5 Bundle (Necesario para Modales y Tabs) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
            crossorigin="anonymous"></script>

    <!-- ************************************ -->
    <!-- LÓGICA DE ACTUALIZACIÓN EN TIEMPO REAL (AJAX/FETCH) -->
    <!-- ************************************ -->
    <script>
        
        // =========================================================
        // 1. FUNCIÓN GENÉRICA DE CREACIÓN (CRUD: CREATE)
        // Esta función utiliza la API Fetch para enviar datos JSON al Controlador PHP.
        // =========================================================

        async function handleCreateSubmit(event) {
            event.preventDefault(); // Evita la recarga de la página (¡Clave del AJAX!)
            
            const form = event.target;
            const endpoint = form.action; 
            const formData = new FormData(form);
            
            // 💡 Nota para PHP: El Controlador debe leer estos datos JSON usando file_get_contents('php://input')
            const payload = {};
            formData.forEach((value, key) => {
                payload[key] = value;
            });
            
            // Asegurar que 'es_publico' se envíe como 0 si no está chequeado (HTML solo envía si está chequeado)
            if (form.querySelector('[name="es_publico"]') && !payload.es_publico) {
                payload.es_publico = 0;
            }

            // Muestra una alerta de carga simple
            alert('Enviando datos de creación al servidor...'); 

            try {
                const response = await fetch(endpoint, {
                    method: 'POST', 
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json' 
                    },
                    body: JSON.stringify(payload)
                });

                // 🚨 CRUCIAL: Verificamos si la respuesta HTTP es exitosa (200-299)
                if (!response.ok) {
                    // Si el controlador devuelve un error HTTP (ej. 400 Bad Request)
                    const error = await response.json();
                    throw new Error(error.message || `Error HTTP: ${response.status}`);
                }

                const result = await response.json(); 

                if (result.success && result.data) {
                    // ÉXITO EN EL CONTROLADOR Y EL MODELO (MariaDB insertó el dato)
                    
                    // 2. Actualizar la Vista en tiempo real:
                    const type = endpoint.split('/')[3]; // Extraer el tipo de CRUD (asignaturas, competencias, etc.)
                    appendNewRow(result.data, type);

                    // CERRAR EL MODAL
                    const modalElement = form.closest('.modal');
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) {
                        modal.hide();
                    }
                    
                    alert(`✅ ¡Creación exitosa! El elemento ha sido añadido a la BBDD (ID: ${result.data.id || result.data.codigo}).`);

                    // Limpiar el formulario
                    form.reset();

                } else {
                    // El Controlador PHP devolvió { success: false, message: '...' }
                    alert(`❌ Error del servidor: ${result.message || 'Fallo desconocido.'}`);
                }

            } catch (error) {
                console.error('Error durante la operación:', error);
                alert(`❌ Fallo en la comunicación: ${error.message}`);
            }
        }

        // =========================================================
        // 2. FUNCIÓN DE RENDERIZADO (Actualización de la tabla)
        // Esta función construye la nueva fila TR y la añade al TBODY correcto.
        // =========================================================

        function appendNewRow(data, type) {
            let tbodyId;
            let rowHtml = '';
            
            // Determinar la tabla de destino
            switch (type) {
                case 'asignaturas':
                    tbodyId = 'asignaturas-table-body';
                    const visibilidad = data.es_publico == 1 ? '<span class="badge bg-success">Público</span>' : '<span class="badge bg-warning text-dark">Privado</span>';
                    rowHtml = `
                        <tr data-id="${data.id}" data-nombre="${data.nombre}" data-publico="${data.es_publico}">
                            <td>${data.id}</td>
                            <td>${data.nombre}</td>
                            <td>${visibilidad}</td>
                            <td>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalModificar" onclick="cargarDatosModal(${data.id}, '${data.nombre}', 'asignatura')">
                                    <i class="bi bi-pencil"></i> Modificar
                                </button>
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Eliminar</button>
                            </td>
                        </tr>
                    `;
                    break;
                case 'competencias':
                    tbodyId = 'competencias-table-body';
                    rowHtml = `
                        <tr data-id="${data.id}" data-codigo="${data.codigo}">
                            <td>${data.codigo}</td>
                            <td>${data.descripcion}</td>
                            <td>
                                <button class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i> Modificar</button>
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Eliminar</button>
                            </td>
                        </tr>
                    `;
                    break;
                case 'criterios':
                    tbodyId = 'criterios-table-body';
                    rowHtml = `
                        <tr data-id="${data.id}" data-codigo="${data.codigo}">
                            <td>${data.codigo}</td>
                            <td>${data.descripcion}</td>
                            <td>
                                <button class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i> Modificar</button>
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Eliminar</button>
                            </td>
                        </tr>
                    `;
                    break;
                default:
                    console.warn(`No se encontró lógica de renderizado para el tipo: ${type}`);
                    return;
            }

            if (tbodyId) {
                const tbody = document.getElementById(tbodyId);
                if (tbody) {
                    tbody.insertAdjacentHTML('beforeend', rowHtml);
                }
            }
        }
        
        // =========================================================
        // 3. ASIGNACIÓN DE EVENT LISTENERS
        // =========================================================

        document.addEventListener('DOMContentLoaded', () => {
            // Asignar el manejador de eventos a TODOS los formularios de creación
            const formIds = [
                'formCrearAsignatura',
                'formCrearCompetencia',
                'formCrearCriterio',
                'formCrearTemario',
                'formCrearActividad'
            ];

            formIds.forEach(id => {
                const form = document.getElementById(id);
                if (form) {
                    form.addEventListener('submit', handleCreateSubmit);
                }
            });
        });

        // Función simulada para cargar datos en el modal de Modificación (CRUD: READ para UPDATE)
        function cargarDatosModal(id, nombre, type) {
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-nombre').value = nombre;
            document.getElementById('edit-type').value = type;
            
            // En un entorno PHP MVC real, aquí harías una solicitud AJAX GET /asignaturas/show/{id}
        }

        // =========================================================
        // 1. LÓGICA DE LECTURA (READ - Se ejecuta al cargar la página)
        // =========================================================

        function renderTable(data) {
            const tbody = document.getElementById('asignaturas-table-body');
            if (!tbody) return;
            tbody.innerHTML = ''; // Limpiar contenido existente

            if (data && data.length > 0) {
                data.forEach(item => {
                    const isPublic = item.es_publico == 1;
                    const visibilidad = isPublic 
                        ? '<span class="badge bg-success">Público</span>' 
                        : '<span class="badge bg-warning text-dark">Privado</span>';
                    
                    const rowHtml = `
                        <tr id="row-${item.id}" data-id="${item.id}" data-nombre="${item.nombre_asignatura}" data-publico="${item.es_publico}">
                            <td>${item.id}</td>
                            <td>${item.nombre_asignatura}</td>
                            <td>${visibilidad}</td>
                            <td>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalModificar" 
                                    onclick="cargarDatosModal(${item.id}, '${item.nombre_asignatura}', ${item.es_publico})">
                                    <i class="bi bi-pencil"></i> Modificar
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="handleDelete(${item.id}, '${item.nombre_asignatura}')">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                    `;
                    tbody.insertAdjacentHTML('beforeend', rowHtml); // Renderizado en lote para optimización [29]
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">Aún no hay asignaturas registradas.</td></tr>';
            }
        }

        async function loadAsignaturas() {
            try {
                // Petición GET al controlador
                const response = await fetch('/api/asignaturas', {
                    method: 'GET',
                    headers: { 'Accept': 'application/json' }
                });

                if (!response.ok) throw new Error(`Error ${response.status}`);
                
                const result = await response.json();
                
                if (result.success) {
                    // Mostrar datos dinámicamente en la tabla
                    renderTable(result.data); 
                } else {
                    console.error('Fallo al obtener datos:', result.message);
                }
            } catch (error) {
                console.error('Error de conexión al cargar asignaturas:', error);
            }
        }

        // =========================================================
        // 2. LÓGICA DE BORRADO (DELETE)
        // =========================================================

        async function handleDelete(id, nombre) {
            if (!confirm(`¿Está seguro de eliminar la asignatura "${nombre}"? Esta acción es irreversible.`)) {
                return;
            }

            try {
                // Petición DELETE al controlador
                const response = await fetch(`/api/asignaturas/${id}`, {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' }
                });

                if (!response.ok) throw new Error(`Error ${response.status}`);
                
                const result = await response.json(); 

                if (result.success) {
                    // Éxito: Eliminar la fila del DOM sin recargar
                    const row = document.getElementById(`row-${id}`);
                    if (row) {
                        row.remove();
                        alert(`Asignatura "${nombre}" eliminada exitosamente.`);
                    }
                } else {
                    alert(`Error al eliminar: ${result.message}`);
                }
            } catch (error) {
                console.error('Fallo al eliminar:', error);
                alert('Fallo en la comunicación o servidor.');
            }
        }

        // =========================================================
        // 3. LÓGICA DE ACTUALIZACIÓN (UPDATE)
        // =========================================================

        // Modificar la función que prepara el modal
        function cargarDatosModal(id, nombre, esPublico) {
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-nombre').value = nombre;
            // Establecer el estado del checkbox
            document.getElementById('edit-publico').checked = esPublico == 1; 
            
            // Asignar el ID al formulario para saber qué recurso actualizar
            document.getElementById('formModificar').setAttribute('data-target-id', id);
        }

        // Manejar el envío del formulario de Modificar (PUT/PATCH)
        document.getElementById('formModificar').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const id = this.getAttribute('data-target-id');
            const endpoint = `/api/asignaturas/${id}`;
            
            const data = {
                nombre: document.getElementById('edit-nombre').value,
                // Leer el estado del checkbox
                es_publico: document.getElementById('edit-publico').checked ? 1 : 0 
            };

            try {
                const response = await fetch(endpoint, {
                    method: 'PUT', // Usamos PUT para reemplazar la asignatura
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                if (!response.ok) throw new Error(`Error ${response.status}`);
                
                const result = await response.json(); 
                
                if (result.success) {
                    // Actualizar la fila en la Vista
                    await loadAsignaturas(); // Opción simple: recargar toda la tabla
                    
                    // Cerrar el modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalModificar'));
                    modal.hide();
                    alert('Cambios guardados exitosamente.');

                } else {
                    alert(`Error al actualizar: ${result.message}`);
                }

            } catch (error) {
                console.error('Error al actualizar:', error);
                alert('Fallo en la conexión o servidor.');
            }
        });


        // Ejecutar la carga de datos al iniciar el documento
        document.addEventListener('DOMContentLoaded', () => {
            // Cargar los datos de las asignaturas al iniciar la página (Ver / READ)
            loadAsignaturas(); 
            // Los event listeners para CREATE (formCrearAsignatura, etc.) ya están definidos en la respuesta anterior.
        });

    </script>
</body>
</html>