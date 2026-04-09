# ProfeWeb - Plataforma Educativa Abierta

Plataforma web educativa desarrollada en PHP puro (sin frameworks) con arquitectura limpia, URLs amigables, sistema de login/registro, dashboard privado para profesores y perfil público de asignaturas.

Ideal para profesores que quieran organizar y compartir sus programaciones didácticas.

URL de acceso: http://localhost (o http://profeweb.local si usas VirtualHost)

---

### Estructura del Proyecto
Profeweb/
├── public/                  ← Única carpeta accesible desde el navegador
│   ├── index.php            ← Punto de entrada único
│   ├── .htaccess            ← URLs limpias
│   └── assets/              ← CSS, JS, imágenes (opcional)
│
├── app/
│   ├── Config/
│   │   └── config.php       ← Constantes (DB, URL base, etc.)
│   ├── Core/
│   │   ├── Database.php     ← Singleton PDO
│   │   ├── Controller.php   ← Clase base con view(), dashboardView(), redirect()
│   │   └── Router.php       ← Router con soporte para rutas dinámicas {id}
│   └── Controllers/
│       ├── HomeController.php
│       ├── AuthController.php
│       ├── DashboardController.php
│       ├── AsignaturaController.php
│       └── ProfesorController.php
│
├── views/
│   ├── layout/
│   │   └── main.php         ← Layout principal (navbar + footer)
│   ├── dashboard/
│   │   ├── layout.php       ← Layout del panel privado (menú lateral)
│   │   ├── index.php        ← Bienvenida del dashboard
│   │   └── asignaturas/
│   │       ├── index.php
│   │       ├── create.php
│   │       └── edit.php
│   ├── home/
│   │   └── index.php        ← Página principal pública (listado de profesores)
│   ├── auth/
│   │   ├── login.php
│   │   └── register.php
│   └── profesor/
│       └── show.php         ← Perfil público de un profesor
│
└── README.md


### Rutas Disponibles

| Método | URL                          | Controlador@Método                     | Descripción                                             | ¿Requiere login? |
|--------|------------------------------|----------------------------------------|---------------------------------------------------------|------------------|
| GET    | `/`                          | `HomeController@index`                 | Página principal (listado de profesores)                | No               |
| GET    | `/login`                     | `AuthController@showLogin`             | Formulario de login                                     | No               |
| POST   | `/login`                     | `AuthController@login`                 | Procesar login                                          | No               |
| GET    | `/register`                  | `AuthController@showRegister`          | Formulario de registro                                  | No               |
| POST   | `/register`                  | `AuthController@register`              | Procesar registro                                       | No               |
| GET    | `/logout`                    | `AuthController@logout`                | Cerrar sesión                                           | Sí               |
| GET    | `/dashboard`                 | `DashboardController@index`            | Panel privado del profesor                              | Sí               |
| GET    | `/asignaturas`               | `AsignaturaController@index`           | Listado de mis asignaturas                              | Sí               |
| GET    | `/asignatura/crear`          | `AsignaturaController@create`          | Formulario crear asignatura                             | Sí               |
| POST   | `/asignatura/crear`          | `AsignaturaController@store`           | Guardar nueva asignatura                                | Sí               |
| GET    | `/asignatura/{id}/editar`    | `AsignaturaController@edit`            | Formulario editar asignatura                            | Sí               |
| POST   | `/asignatura/{id}/editar`    | `AsignaturaController@update`          | Actualizar asignatura                                   | Sí               |
| POST   | `/asignatura/{id}/eliminar`  | `AsignaturaController@delete`          | Eliminar asignatura                                     | Sí               |
| GET    | `/profesor/{id}`             | `ProfesorController@show`              | Perfil público del profesor (solo asignaturas públicas) | No               |

---

### Características Implementadas

- Login / Registro con hash de contraseñas (`password_hash`)
- Sistema de sesiones seguro
- URLs limpias con parámetros dinámicos (`{id}`)
- Dashboard privado con menú lateral
- Gestión completa de asignaturas (CRUD)
- Visibilidad pública/privada por asignatura
- Perfil público de profesor con asignaturas visibles
- Arquitectura limpia y escalable (PSR-4 básico)
- Diseño responsive con Bootstrap 5 + Font Awesome

---

### Próximos Pasos (ya listos para implementar)

- Unidades didácticas dentro de cada asignatura
- Competencias, criterios de evaluación y actividades
- Vista detallada pública de asignatura
- Drag & drop para ordenar unidades

---