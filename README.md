# 🍔 SISTEMA POS TOCHIS

## 📋 Descripción General

Sistema de Punto de Venta (POS) desarrollado en Laravel para el restaurante TOCHIS. Incluye gestión completa de Platillos, ventas, combos, promociones y reportes con una interfaz moderna estilo FoodMeal.

---

## 🏗️ **ARQUITECTURA GENERAL**

### **Stack Tecnológico:**
- **Laravel 9.x** - Framework PHP backend
- **Tailwind CSS** - Framework de estilos CSS
- **JavaScript Vanilla** - Frontend interactivo
- **MySQL** - Sistema de base de datos
- **Font Awesome 6.4.0** - Iconografía
- **Google Fonts (Inter)** - Tipografía moderna

### **Estructura del Proyecto:**
```
laravel-pos/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Controladores para administradores
│   │   └── Cashier/        # Controladores para cajeros
│   ├── Models/             # Modelos Eloquent
│   └── Helpers/            # Funciones auxiliares
├── database/
│   ├── migrations/         # Migraciones de BD
│   └── seeders/           # Datos de prueba
├── resources/
│   ├── views/
│   │   ├── admin/         # Vistas de administrador
│   │   ├── cashier/       # Vistas de cajero
│   │   └── layouts/       # Plantillas base
│   └── css/               # Estilos adicionales
└── routes/
    └── web.php            # Definición de rutas
```

---

## 🎨 **BRANDING Y DISEÑO**

### **Paleta de Colores TOCHIS:**
```css
:root {
    --tochis-orange: #f97316;        /* Color principal */
    --tochis-orange-dark: #ea580c;   /* Color oscuro */
    --tochis-orange-light: #fb923c;  /* Color claro */
    --tochis-orange-lightest: #fed7aa; /* Color muy claro */
    --tochis-black: #1a1a1a;        /* Negro corporativo */
    --tochis-gray: #f8fafc;         /* Gris claro */
    --tochis-gray-dark: #64748b;    /* Gris oscuro */
}
```

### **Características Visuales:**
- ✨ **Gradientes naranjas** para elementos principales
- 🎯 **Cards con bordes redondeados** (16px radius)
- 🎭 **Efectos hover** con transformaciones 3D
- ⚡ **Animaciones suaves** (0.3s cubic-bezier)
- 🌟 **Sombras dinámicas** que responden a interacciones
- 📱 **Diseño completamente responsive**

---

## 🗃️ **ESTRUCTURA DE BASE DE DATOS**

### **Tablas Principales:**

#### **1. `users` - Usuarios del Sistema**
```sql
- id, name, email, password
- role (admin/cashier)
- is_active, created_at, updated_at
```

#### **2. `categories` - Categorías de Platillos**
```sql
- id, name, description, color
- is_active, is_customizable
- created_at, updated_at
```

#### **3. `products` - Platillos del Menú**
```sql
- id, name, code, description, category_id
- price, cost, stock, min_stock
- image, is_active, is_food, preparation_time
- created_at, updated_at
```

#### **4. `sales` - Ventas Realizadas**
```sql
- id, sale_number, user_id
- subtotal, tax, discount, total
- paid_amount, change_amount
- payment_method, status, notes
- created_at, updated_at
```

#### **5. `sale_details` - Detalles de Venta**
```sql
- id, sale_id, product_id
- quantity, unit_price, total_price
- options, observations
- created_at, updated_at
```

#### **6. `combos` - Combos Promocionales**
```sql
- id, name, description
- price, original_price, discount_amount
- is_active, auto_suggest, min_items
- image, created_at, updated_at
```

#### **7. `promotions` - Promociones Especiales**
```sql
- id, name, description, type
- discount_value, apply_to, applicable_items
- minimum_amount, max_uses, uses_count
- start_date, end_date, is_active
- created_by, created_at, updated_at
```

---

## 🚀 **FUNCIONALIDADES IMPLEMENTADAS**

### **🏪 Para Administradores:**

#### **Gestión de Categorías:**
- ✅ CRUD completo de categorías
- ✅ Configuración de colores personalizados
- ✅ Activación/desactivación
- ✅ Opciones de personalización

#### **Gestión de Platillos:**
- ✅ CRUD completo con imágenes
- ✅ Control de inventario
- ✅ Clasificación comida/Platillos
- ✅ Tiempo de preparación
- ✅ Código de barras

#### **Gestión de Combos:**
- ✅ Creación de combos con múltiples Platillos
- ✅ Cálculo automático de descuentos
- ✅ Sugerencias automáticas
- ✅ Configuración de elementos mínimos

#### **Gestión de Promociones:**
- ✅ Promociones por porcentaje o monto fijo
- ✅ Aplicables a Platillos, categorías o todo
- ✅ Fechas de vigencia
- ✅ Límites de uso

#### **Reportes:**
- ✅ Reportes de ventas por período
- ✅ Análisis de Platillos más vendidos
- ✅ Reportes de cortes de caja
- ✅ Exportación de datos

### **🛒 Para Cajeros:**

#### **Punto de Venta:**
- ✅ Interfaz intuitiva estilo tablet
- ✅ Filtros por categoría
- ✅ Búsqueda en tiempo real
- ✅ Carrito de compras interactivo

#### **Personalización de Platillos:**
- ✅ Modal de personalización para comidas
- ✅ Selección de opciones adicionales
- ✅ Observaciones especiales
- ✅ Precio dinámico según opciones

#### **Sistema de Combos:**
- ✅ Detección automática de combos disponibles
- ✅ Sugerencias inteligentes basadas en carrito
- ✅ Aplicación de descuentos automática
- ✅ Visualización de ahorros

#### **Procesamiento de Ventas:**
- ✅ Múltiples métodos de pago
- ✅ Cálculo automático de impuestos
- ✅ Aplicación de promociones
- ✅ Generación de tickets

#### **Gestión de Caja:**
- ✅ Apertura y cierre de caja
- ✅ Registro de gastos e ingresos
- ✅ Conteo de efectivo
- ✅ Reportes de turno

---

## 🔄 **FLUJO DE TRABAJO**

### **🔐 Autenticación y Roles:**
```
Login → Verificación de credenciales → Redirección por rol
```

### **👨‍💼 Flujo del Administrador:**
```
Dashboard → Gestión de Datos → Configuración → Reportes
```

### **👨‍💼 Flujo del Cajero:**
```
Apertura de Caja → Nueva Venta → Procesamiento → Cierre de Caja
```

### **🛍️ Flujo de Venta:**
```
Selección de Platillos → Personalización (opcional) → 
Aplicación de Combos/Promociones → Procesamiento de Pago → 
Confirmación
```

---

## 🔌 **API Y ENDPOINTS**

### **Rutas Principales:**

#### **Autenticación:**
```php
GET  /login                    # Mostrar formulario de login
POST /login                    # Procesar login
POST /logout                   # Cerrar sesión
```

#### **Administrador:**
```php
GET  /admin/dashboard          # Dashboard principal
GET  /admin/categories         # Lista de categorías
POST /admin/categories         # Crear categoría
GET  /admin/products           # Lista de Platillos
POST /admin/products           # Crear Platillo
GET  /admin/combos             # Lista de combos
POST /admin/combos             # Crear combo
GET  /admin/promotions         # Lista de promociones
POST /admin/promotions         # Crear promoción
```

#### **Cajero:**
```php
GET  /cashier/dashboard        # Dashboard del cajero
GET  /cashier/sale             # Interfaz POS
POST /cashier/sale             # Procesar venta
GET  /cashier/sale/history     # Historial de ventas
```

#### **API Endpoints:**
```php
GET  /api/products/{id}/options        # Opciones de Platillo
POST /api/combos/suggest               # Sugerir combos
POST /api/combos/apply                 # Aplicar combo
GET  /api/promotions                   # Promociones disponibles
```

---

## 💻 **COMPONENTES FRONTEND**

### **🎯 Interfaz POS (Punto de Venta):**

#### **Layout Principal:**
```
┌─────────────────────────────────────────────────┐
│ [Header con usuario y caja]                    │
├─────────────┬─────────────────┬─────────────────┤
│ Categorías  │ Grid Platillos  │ Carrito         │
│ - Filtros   │ - Cards         │ - Items         │
│ - Búsqueda  │ - Precios       │ - Totales       │
│ - Conteos   │ - Stock         │ - Checkout      │
└─────────────┴─────────────────┴─────────────────┘
```

#### **Componentes JavaScript:**

**Variables Globales:**
```javascript
let cart = [];                 // Carrito de compras
let subtotal = 0;             // Subtotal actual
let tax = 0;                  // Impuestos
let total = 0;                // Total final
let availablePromotions = []; // Promociones disponibles
let suggestedCombos = [];     // Combos sugeridos
```

**Funciones Principales:**
```javascript
handleProductClick()      // Maneja clics en Platillos
addToCart()              // Agrega items al carrito
updateCartDisplay()      // Actualiza interfaz del carrito
filterByCategory()       // Filtra Platillos por categoría
openCustomizationModal() // Abre modal de personalización
processSale()           // Procesa la venta
checkForCombos()        // Verifica combos disponibles
```

---

## 🔧 **CONFIGURACIÓN Y INSTALACIÓN**

### **Requisitos del Sistema:**
- **PHP 8.1+**
- **MySQL 8.0+**
- **Composer 2.x**
- **Node.js 16+** (opcional, para compilación de assets)

### **Instalación:**

```bash
# 1. Clonar repositorio
git clone https://github.com/Fabian03October/Tochis.git
cd Tochis

# 2. Instalar dependencias PHP
composer install

# 3. Configurar entorno
cp .env.example .env
php artisan key:generate

# 4. Configurar base de datos en .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tochis_pos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password

# 5. Ejecutar migraciones
php artisan migrate

# 6. Ejecutar seeders (datos de prueba)
php artisan db:seed

# 7. Iniciar servidor
php artisan serve
```

### **Configuración Inicial:**

#### **Usuario Administrador por Defecto:**
```
Email: admin@tochis.com
Password: admin123
```

#### **Usuario Cajero por Defecto:**
```
Email: cajero@tochis.com
Password: cajero123
```

---

## 🧪 **TESTING Y VALIDACIÓN**

### **Datos de Prueba Incluidos:**

#### **Categorías:**
- 🍔 **Hamburguesas** (9 Platillos)
- 🍕 **Pizza** (8 Platillos)
- 🍗 **Pollo** (6 Platillos)
- 🥤 **Bebidas** (4 Platillos)
- 🍰 **Postres** (3 Platillos)
- 🍟 **Acompañamientos** (3 Platillos)

#### **Combos de Ejemplo:**
- **Combo Familiar** - Hamburguesa + Papa + Bebida
- **Combo Pareja** - 2 Pizzas + 2 Bebidas
- **Combo Individual** - Pollo + Acompañamiento + Postre

#### **Promociones de Ejemplo:**
- **Descuento 15%** en hamburguesas (fines de semana)
- **2x1** en bebidas (hora feliz)
- **Descuento $20** en pedidos mayores a $200

---

## 🚀 **ROADMAP Y MEJORAS FUTURAS**

### **Versión 2.0:**
- [ ] **App móvil** para pedidos
- [ ] **Sistema de delivery** integrado
- [ ] **Integración con APIs** de pago
- [ ] **Sistema de fidelización** de clientes
- [ ] **Analytics avanzado** con dashboards

### **Versión 1.5:**
- [ ] **Notificaciones push** para promociones
- [ ] **Sistema de reservas** de mesa
- [ ] **Integración con redes sociales**
- [ ] **Códigos QR** para menús digitales
- [ ] **Sistema de comentarios** y calificaciones

### **Optimizaciones Técnicas:**
- [ ] **Cache Redis** para mejor performance
- [ ] **Queue system** para procesos pesados
- [ ] **CDN** para imágenes
- [ ] **API REST** completa
- [ ] **Tests automatizados** unitarios e integración

---

## 🐛 **RESOLUCIÓN DE PROBLEMAS**

### **Problemas Comunes:**

#### **Error de conexión a BD:**
```bash
# Verificar configuración
php artisan config:cache
php artisan migrate:status
```

#### **Permisos de archivos:**
```bash
# En Linux/Mac
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# En Windows
# Otorgar permisos completos a las carpetas storage y bootstrap/cache
```

#### **Error de CSRF Token:**
```javascript
// Verificar que existe el meta tag
<meta name="csrf-token" content="{{ csrf_token() }}">

// En JavaScript
const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
```

---

## 📞 **SOPORTE Y CONTACTO**

### **Desarrollador:**
- **Nombre:** Fabian de Jesus
- **GitHub:** [@Fabian03October](https://github.com/Fabian03October)
- **Proyecto:** [Tochis POS](https://github.com/Fabian03October/Tochis)

### **Documentación Técnica:**
- **Laravel Docs:** [laravel.com/docs](https://laravel.com/docs)
- **Tailwind CSS:** [tailwindcss.com/docs](https://tailwindcss.com/docs)
- **Font Awesome:** [fontawesome.com/docs](https://fontawesome.com/docs)

---

## 📄 **LICENCIA**

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

---

## 🙏 **AGRADECIMIENTOS**

- **Laravel Community** por el excelente framework
- **Tailwind CSS Team** por el sistema de diseño
- **Font Awesome** por los iconos
- **Equipo TOCHIS** por la confianza en el proyecto

---

**💡 ¿Necesitas ayuda?** Abre un issue en GitHub o consulta la documentación técnica.

**🚀 ¿Quieres contribuir?** Fork el proyecto y envía un pull request.

---

*Última actualización: Septiembre 2025 - Versión 1.0*
1. **Seleccionar Platillos** desde el menú por categorías
2. **Personalizar Platillos** (observaciones/especialidades)
3. **Aplicar promociones** automáticamente
4. **Seleccionar método de pago**
5. **Procesar venta** y generar cambio

### Gestión de Inventario
- Control de stock en tiempo real
- Alertas de stock bajo
- Actualización automática post-venta

## 🤝 **Contribución**

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit tus cambios (`git commit -am 'Agrega nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Abre un Pull Request

## 📄 **Licencia**

Este proyecto está bajo la Licencia MIT - ver el archivo [LICENSE](LICENSE) para detalles.

## 👨‍💻 **Desarrollador**

**Fabian03October**
- GitHub: [@Fabian03October](https://github.com/Fabian03October)

---

### 🍟 **¡Gracias por usar Tochis POS!**

*Sistema desarrollado para optimizar la gestión de restaurantes de comida rápida con tecnología moderna y funcionalidades avanzadas.*

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
