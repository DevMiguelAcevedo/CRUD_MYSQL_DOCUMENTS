# CRUD Gestión de Documentos

Aplicación web para la gestión de documentos con PHP, MySQL y patrón MVC.

## 📋 Características

- ✅ Login/Logout de usuario
- ✅ Tabla de documentos con búsqueda en tiempo real
- ✅ Crear, editar y eliminar documentos
- ✅ Generación automática de códigos únicos consecutivos
- ✅ Arquitectura MVC limpia
- ✅ Enrutador con URLs amigables
- ✅ Codificación UTF-8
- ✅ Configuración mediante .env

## 🔧 Requisitos

- PHP 7.0 o superior
- MySQL 5.7 o superior
- Servidor web (Apache, Nginx, etc.)

## 📥 Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/tu-usuario/crud-documentos.git
cd CRUD_MYSQL_DOCUMENTS
```

### 2. Copiar archivo de configuración

```bash
cp .env.example .env
```

Editar el archivo `.env` con tus credenciales de MySQL:

```env
DB_HOST=localhost
DB_USER=root
DB_PASS=tu_contraseña
DB_NAME=crud_documentos
DB_PORT=3306
APP_URL=http://localhost:8000
APP_DEBUG=true
```

### 3. Crear la base de datos

**Opción A: Usando línea de comandos**

```bash
mysql -u root -p < database/install.sql
```

**Opción B: Usando phpMyAdmin**

1. Abrir phpMyAdmin en tu navegador
2. Crear una nueva base de datos llamada `crud_documentos` (utf8mb4)
3. Importar el archivo `database/install.sql`

### 4. Ejecutar el servidor local

**PHP 7.0+**

```bash
cd public
php -S localhost:8000
```

Luego acceder a: `http://localhost:8000`

**Usando Apache o Nginx**

Configurar el servidor para apuntar a la carpeta `public` como raíz de documentos.

## 🔐 Credenciales de Acceso

Por defecto, se proporcionan las siguientes credenciales:

- **Usuario:** `admin`
- **Contraseña:** `admin123`

⚠️ **Nota:** Las credenciales están configuradas en la clase `AuthController`. Para cambiarlas, editar las constantes `USUARIO` y `PASSWORD` en el archivo `app/controllers/AuthController.php`.

## 📊 Estructura de la Base de Datos

### Tabla: PRO_PROCESO
Define los procesos disponibles en la organización.

```
PRO_ID (INT) - Identificador único
PRO_NOMBRE (VARCHAR) - Nombre del proceso (ej: Ingeniería)
PRO_PREFIJO (VARCHAR) - Prefijo para códigos (ej: ING)
```

**Datos precargados:**
- Ingeniería (ING)
- Desarrollo (DEV)
- Calidad (CAL)
- Operaciones (OPS)
- Administración (ADM)

### Tabla: TIP_TIPO_DOC
Define los tipos de documentos disponibles.

```
TIP_ID (INT) - Identificador único
TIP_NOMBRE (VARCHAR) - Nombre del tipo (ej: Instructivo)
TIP_PREFIJO (VARCHAR) - Prefijo para códigos (ej: INS)
```

**Datos precargados:**
- Instructivo (INS)
- Manual (MAN)
- Procedimiento (PRO)
- Especificación (ESP)
- Reporte (REP)

### Tabla: DOC_DOCUMENTO
Almacena los documentos registrados.

```
DOC_ID (INT) - Identificador único
DOC_NOMBRE (VARCHAR) - Nombre del documento
DOC_CODIGO (VARCHAR) - Código único generado automáticamente
DOC_CONTENIDO (LONGTEXT) - Contenido del documento
DOC_ID_TIPO (INT) - Referencia a TIP_TIPO_DOC
DOC_ID_PROCESO (INT) - Referencia a PRO_PROCESO
DOC_FECHA_CREACION - Fecha de creación automática
DOC_FECHA_ACTUALIZACION - Fecha de última actualización
```

## 💡 Ejemplos de Códigos Generados

El sistema genera códigos automáticos en el formato: **PREFIJO_TIPO-PREFIJO_PROCESO-CONSECUTIVO**

Ejemplos:
- `INS-ING-1` - Instructivo de Ingeniería, consecutivo 1
- `MAN-DEV-1` - Manual de Desarrollo, consecutivo 1
- `PRO-CAL-2` - Procedimiento de Calidad, consecutivo 2

Los consecutivos se generan de forma única por combinación de Tipo y Proceso. Si cambias el tipo o proceso durante la edición, el código se recalcula automáticamente.

## 🗂️ Estructura del Proyecto

```
CRUD_MYSQL_DOCUMENTS/
├── app/
│   ├── controllers/          # Controladores de la aplicación
│   │   ├── BaseController.php
│   │   ├── AuthController.php
│   │   └── DocumentController.php
│   ├── models/               # Modelos de datos
│   │   ├── BaseModel.php
│   │   ├── Documento.php
│   │   ├── Proceso.php
│   │   └── TipoDocumento.php
│   ├── views/                # Vistas (HTML)
│   │   ├── login.php
│   │   └── documentos/
│   │       ├── index.php
│   │       ├── crear.php
│   │       └── editar.php
│   └── Router.php            # Enrutador de aplicación
├── config/
│   └── Database.php          # Configuración de base de datos
├── database/
│   ├── schema.sql            # Scripts de creación de BD
│   └── install.sql           # Script de instalación
├── public/
│   └── index.php             # Punto de entrada de la aplicación
├── vendor/
│   └── autoload.php          # Cargador automático PSR-4
├── .env                      # Variables de entorno
├── .env.example              # Plantilla de .env
└── README.md                 # Este archivo
```

## 🛣️ Rutas Disponibles

### Rutas Públicas
| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/` | Mostrar formulario de login |
| POST | `/login` | Procesar login |

### Rutas Protegidas (requieren autenticación)
| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/documentos` | Listar todos los documentos |
| GET | `/documentos/crear` | Mostrar formulario de creación |
| POST | `/documentos/guardar` | Guardar nuevo documento |
| GET | `/documentos/{id}/editar` | Mostrar formulario de edición |
| POST | `/documentos/{id}/actualizar` | Actualizar documento |
| POST | `/documentos/{id}/eliminar` | Eliminar documento |
| GET | `/api/documentos/buscar` | Buscar documentos (AJAX) |
| GET | `/logout` | Cerrar sesión |

## 🏗️ Arquitectura

### MVC (Modelo-Vista-Controlador)

- **Modelos:** Manejan la lógica de datos y consultas a la base de datos
- **Vistas:** Generan el HTML para presentar los datos
- **Controladores:** Coordinan modelos y vistas, y manejan la lógica de negocio

### Características de Diseño

- ✅ **Patrón MVC:** Separación clara de responsabilidades
- ✅ **Inyección de Dependencias:** Uso de instancias de modelos
- ✅ **Responsabilidad Única (SRP):** Cada clase tiene un propósito único
- ✅ **URLs Amigables:** Rutas limpias y legibles
- ✅ **UTF-8:** Soporte completo para caracteres especiales
- ✅ **PSR-4:** Autoloading automático de clases
- ✅ **Prepared Statements:** Protección contra inyección SQL

## 🔒 Seguridad

- ✅ Prepared statements para prevenir inyección SQL
- ✅ Escapado de output para prevenir XSS
- ✅ Sesiones seguras
- ✅ Validación de entrada
- ✅ Validación de autorización (login required)
- ✅ UTF-8 en toda la aplicación

## 📝 Guía de Uso

### 1. Crear un documento
1. Hacer login con `admin` / `admin123`
2. Clickear en "Crear nuevo documento"
3. Completar el formulario:
   - Nombre del documento
   - Tipo (Instructivo, Manual, etc.)
   - Proceso (Ingeniería, Desarrollo, etc.)
   - Contenido
4. Clickear en "Crear documento"
5. El código se genera automáticamente

### 2. Buscar documentos
1. En la página principal, escribir en la caja de búsqueda
2. Los resultados aparecen en tiempo real
3. Clickear en un resultado para editarlo

### 3. Editar un documento
1. En el listado, clickear en el botón "Editar"
2. Modificar los datos necesarios
3. Clickear en "Actualizar documento"

### 4. Eliminar un documento
1. En el listado, clickear en el botón "Eliminar"
2. Confirmar la eliminación

## 🆘 Solución de Problemas

### Error de conexión a MySQL
- Verificar credenciales en `.env`
- Asegurar que MySQL esté ejecutándose
- Verificar puerto de MySQL (por defecto 3306)

### Error 404 - Página no encontrada
- Verificar que el servidor PHP esté ejecutándose
- Revisar la ruta en la barra de direcciones
- Asegurar que las rutas en `public/index.php` son correctas

### Error de base de datos no encontrada
- Ejecutar el script de instalación: `database/install.sql`
- Verificar que la base de datos se creó correctamente

### El login no funciona
- Verificar usuario y contraseña (admin/admin123)
- Limpiar cookies/cache del navegador
- Verificar que las sesiones estén habilitadas

## 📚 Tecnologías Utilizadas

- **PHP 8.4.12** - Lenguaje de servidor
- **MySQL** - Base de datos
- **HTML5** - Markup
- **CSS3** - Estilos
- **JavaScript** - Interactividad (búsqueda en tiempo real)

## 📄 Licencia

Este proyecto es de código abierto.

## 👤 Autor: Miguel Angel Acevedo Florez

Desarrollado como prueba técnica

## 📞 Contacto

acebedo2524@gmail.com

---

**Última actualización:** 2026-03-24