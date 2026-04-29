
---
name: Sistema Birra Market - Descripción General
description: Explicación completa del funcionamiento del ecommerce de cervezas artesanas construido en Laravel 11
type: reference
originSessionId: d555e737-903f-473f-936f-1ba8cb95bb05
---
# 🍺 Birra Market - Sistema de Ecommerce de Cervezas Artesanas


## Visión General


Birra Market es una **plataforma de ecommerce moderna** diseñada para la venta de cervezas artesanas. El sistema permite a usuarios anónimos explorar un catálogo de productos, y a usuarios registrados realizar compras con pagos integrados a través de Stripe.


**Stack Tecnológico:**
- **Backend:** PHP 8.2 + Laravel 11.31
- **Frontend:** Blade Templates + Tailwind CSS + Vite
- **Base de Datos:** MySQL/SQLite
- **Pagos:** Stripe API v20.1
- **Email:** Mailtrap SMTP
- **Autenticación:** Laravel Fortify


---


## 📊 Arquitectura de la Base de Datos


### Tablas Principales


```
Usuarios y Autenticación
├── users (id, name, email, password, email_verified_at)
├── roles (id, nombre UNIQUE)
└── role_user (rol_id FK, usuario_id FK)


Productos y Catálogo
├── products (id, nombre, descripcion, price_cents, stock, activo, images JSON)
├── categories (id, nombre, descripcion)
├── category_product (categoria_id FK, producto_id FK)
├── product_images (id, producto_id FK, path, orden)
└── favorites (usuario_id FK, producto_id FK) [N:M]


Compra y Carrito
├── carts (id, usuario_id FK, session_id)
├── cart_lines (id, cart_id FK, producto_id FK, cantidad, price_snapshot_cents)
├── addresses (id, usuario_id FK, dirección, ciudad, código_postal)
└── discounts (id, codigo UNIQUE, porcentaje, fecha_caducidad)


Pedidos y Pagos
├── orders (id, usuario_id FK, estado ENUM, precio_total_cents, address_id FK)
├── order_lines (id, pedido_id FK, producto_id FK, cantidad, precio_unitario_cents)
├── order_statuses (id, nombre: pending|paid|shipped|delivered|cancelled)
├── payments (id, pedido_id FK, metodo, amount_cents, status, provider_ref)
└── invoices (id, pedido_id FK, datos_fiscales, impuestos_cents)
```


**Relaciones Clave:**
- Un Usuario puede tener múltiples Órdenes (1:N)
- Un Producto puede estar en múltiples Categorías (N:M)
- Un Carrito contiene múltiples CartLines (1:N)
- Una Orden contiene múltiples OrderLines (1:N)


---


## 🔄 Flujo de Compra (Happy Path)


### 1. Exploración de Catálogo (Público)
```
GET /productos
├─ Se cargan 9 productos paginados
├─ Se muestran: imagen, nombre, precio, botón "Añadir al carrito"
└─ Usuario puede clickear en producto para ver detalles (GET /productos/{id})
```


### 2. Añadir al Carrito (Usuario Autenticado)
```
POST /cart/add {producto_id, cantidad}
├─ Validación: Producto existe y tiene stock
├─ Obtiene o crea Cart del usuario autenticado
├─ Busca/crea CartLine con precio_snapshot_cents (precio capturado en ese momento)
├─ Si CartLine ya existe, incrementa cantidad
└─ Redirige a GET /cart para ver carrito actualizado
```


### 3. Visualizar Carrito
```
GET /cart
├─ Muestra todas las CartLines del usuario
├─ Para cada línea: imagen, nombre, precio unitario, cantidad, subtotal
├─ Botones para aumentar/disminuir cantidad o eliminar línea
├─ Total calculado: SUM(price_snapshot_cents * cantidad)
└─ Botón "Proceder a Pagar" → POST /checkout/create
```


### 4. Crear Orden (Transacción Atómica)
```
POST /checkout/create
├─ Validación: Carrito NO vacío
├─ TRANSACCIÓN DE BASE DE DATOS:
│   ├─ Verifica stock NUEVAMENTE (previene race conditions)
│   ├─ Crea Order con estado = 'pending'
│   ├─ Para cada CartLine:
│   │   ├─ Crea OrderLine con mismo precio_snapshot
│   │   ├─ Decrementa stock atómicamente
│   │   └─ Suma al total
│   ├─ Actualiza Order.precio_total_cents
│   ├─ Elimina CartLines y Cart
│   ├─ Crea Payment con status = 'pending'
│   └─ COMMIT
└─ Procede a pago
```


### 5. Pago con Stripe (Flujo Online)
```
CheckoutController::pay()
├─ Crea Stripe Checkout Session
│   └─ Line items: producto.nombre, precio en cents, cantidad, imagen
├─ Guarda Order ID en client_reference_id
├─ Redirige a session.url (hosted checkout de Stripe)
└─ Usuario ingresa tarjeta (test: 4242 4242 4242 4242)
    │
    ├─ ✅ ÉXITO: Stripe redirige a /checkout/success?session_id=X
    │   └─ CheckoutController::success():
    │       ├─ Verifica session con Stripe API
    │       ├─ Marca Order.estado = 'paid'
    │       ├─ Actualiza Payment.status = 'succeeded'
    │       └─ Render: checkout/success.blade.php
    │
    └─ ❌ CANCELADO: Stripe redirige a /checkout/cancel
        └─ Orden permanece 'pending' (puede reintentarse)
```


### 6. Webhook de Stripe (Confirmación Backend)
```
POST /stripe/webhook
├─ Valida firma webhook con STRIPE_WEBHOOK_SECRET
├─ Escucha evento: 'checkout.session.completed'
├─ Extrae Order ID de client_reference_id
├─ TRANSACCIÓN DE BASE DE DATOS:
│   ├─ Inserta Payment con datos de Stripe
│   ├─ Actualiza Order.estado = 'paid'
│   ├─ Dispara evento 'Mail\OrderConfirmed' (queued)
│   └─ COMMIT
└─ Retorna 200 OK
    └─ Queue worker (php artisan queue:work) procesa email asincronamente
```


### 7. Confirmación por Email
```
Mail\OrderConfirmed
├─ Template: resources/emails/orders/confirmed.blade.php
├─ Incluye: resumen de líneas, total, dirección de entrega
├─ Enviado vía: Mailtrap SMTP (MAIL_HOST=smtp.mailtrap.io)
└─ Usuario recibe: "Confirmación de pedido #12345"
```


---


## 👥 Gestión de Usuarios y Roles


### Autenticación (Laravel Fortify)
- **Registro:** POST /register (email único, password hasheado con bcrypt)
- **Login:** POST /login (email + password)
- **Logout:** POST /logout (invalida sesión)
- **Recuperar Contraseña:** GET /forgot-password + POST /forgot-password
- **Sesión:** Cookie HTTP-only `XSRF-TOKEN` + Laravel session


### Roles y Permisos
```
Usuarios → roles (N:M vía role_user)


Roles disponibles:
├─ admin (acceso a /admin/*, middleware EnsureUserIsAdmin)
└─ user (rol por defecto, acceso a tienda)


Middleware:
├─ auth (requiere usuario autenticado)
├─ guest (solo usuarios NO autenticados)
└─ admin (requiere auth AND rol admin)
```


---


## 🛠️ Panel de Administración


### Rutas Admin (Todas bajo `/admin/`, protegidas con middleware admin)


```
Dashboard
GET /admin/                         → Muestra estadísticas generales


Productos (CRUD Completo)
GET /admin/products                 → Listar todos productos (paginado)
GET /admin/products/create          → Formulario crear producto
POST /admin/products                → Guardar nuevo producto
GET /admin/products/{id}/edit       → Formulario editar producto
PUT /admin/products/{id}            → Guardar cambios
DELETE /admin/products/{id}         → Eliminar producto (soft delete)


Administradores (CRUD)
GET /admin/admins                   → Listar administradores
POST /admin/admins                  → Crear nuevo admin


Categorías (CRUD)
GET /admin/categories               → Listar categorías
POST /admin/categories              → Crear categoría
PUT /admin/categories/{id}          → Actualizar categoría
DELETE /admin/categories/{id}       → Eliminar categoría
```


### Controlador Admin ProductController
```
Validación de entrada:
├─ nombre: required|string|max:255
├─ descripcion: required|string
├─ precio: required|numeric|min:0.01  (convertido a cents)
├─ stock: required|integer|min:0
├─ images: array|max:5 (archivos subidos)
└─ categorias: array|exists:categories,id


Procesamiento:
├─ Convierte precio EUR → price_cents (float * 100)
├─ Guarda imágenes en storage/app/public/products/
├─ Sincroniza categorías (attach/sync)
└─ Auditoría: creado por/en, actualizado por/en
```




## 📈 Características Implementadas


✅ Catálogo de productos paginado  
✅ Filtro por categorías  
✅ Carrito de compra persistente (BD)  
✅ Sistema de favoritos  
✅ Autenticación y registro (Fortify)  
✅ Órdenes con confirmación  
✅ Integración Stripe (pagos online)  
✅ Webhooks Stripe (confirmación backend)  
✅ Emails transaccionales (Mailtrap)  
✅ Panel de administración (CRUD productos)  
✅ Gestión de roles/permisos  
✅ Queue de trabajos asincrónicos  


---


## 📝 Convenciones del Proyecto


- **Idioma BD:** Spanish (nombres de campos en español)
- **Rutas:** Spanish (ej: `/productos`, `/carrito`, `/pagar`)
- **Nombres Variables:** snake_case en PHP y BD
- **Precios:** Almacenados en cents (int) para evitar problemas de precisión
- **Timestamps:** created_at, updated_at automáticos en Eloquent
- **Soft Deletes:** Productos pueden estar inactivos (no hard delete)
- **Assets:** Vite para bundling, Tailwind para CSS


---


## 🔗 Entrypoints Principales


| Archivo | Propósito |
|---------|----------|
| `public/index.php` | Punto de entrada HTTP |
| `routes/web.php` | Definición de todas las rutas |
| `app/Http/Controllers/*` | Lógica de negocio |
| `app/Models/*` | Esquema BD y relaciones |
| `resources/views/*` | Templates HTML (Blade) |
| `database/migrations/*` | Esquema BD |
| `config/services.php` | Configuración de APIs externas |
| `.env` | Variables de entorno |





