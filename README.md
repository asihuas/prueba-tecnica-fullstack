# Prueba-tecnica-fullstack (Prueba Técnica - Parte 1)

Aplicación **Fullstack** para la gestión y validación masiva de equipos tecnológicos, desarrollada cumpliendo los requerimientos de la prueba técnica.

## Estructura del Proyecto

- `backend-api/`: API REST desarrollada en **Laravel** (Backend).
- `frontend-app/`: Cliente web SPA desarrollado en **Vue 3 + PrimeVue** (Frontend).

## Requisitos Previos

- PHP 8.1 o superior
- Composer
- Node.js y NPM
- **PostgreSQL** (Base de datos)

## ⚙️ Instrucciones de Instalación

### 1. Configuración del Backend (API)

Accede a la carpeta del backend:

```bash
cd backend-api
```

Instala las dependencias:

```bash
composer install
```

Configura el entorno:

1. Copia el archivo `.env.example` y renómbralo a `.env`.
2. Configura tus credenciales de PostgreSQL en el archivo `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=leasein_db
DB_USERNAME=postgres
DB_PASSWORD=TU_CONTRASEÑA
```

Genera la clave y ejecuta las migraciones:

```bash
php artisan key:generate
php artisan migrate --seed
```

> Nota: El comando `--seed` poblará la base de datos con datos de prueba (incluyendo códigos con distintos formatos) para facilitar la revisión de los filtros.

Levanta el servidor:

```bash
php artisan serve
```

---

### 2. Configuración del Frontend (Vue)

En otra terminal, accede a la carpeta del frontend:

```bash
cd frontend-app
```

Instala las dependencias:

```bash
npm install
```

Ejecuta el proyecto:

```bash
npm run dev
```

## 🚀 Funcionalidades

### 1. Validación Masiva

- Textarea que permite ingresar múltiples códigos.
- Conexión con endpoint `POST` para verificar existencias en base de datos.
- Alerta visual para códigos no encontrados.

### 2. Gestión de Inventario

- Tabla interactiva con PrimeVue.
- Filtros de búsqueda por columna (coincidencias parciales).
- Visualización de estado y fecha de entrega.