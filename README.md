# 🍔 Tochis - Sistema POS para Restaurante

Sistema de Punto de Venta (POS) desarrollado en Laravel para restaurantes de comida rápida.

## 🚀 Características

### 📱 **Funcionalidades Principales**
- **Ventas Completas:** Procesamiento de órdenes con personalización de productos
- **Gestión de Inventario:** Control de stock y productos por categorías
- **Sistema de Promociones:** Descuentos automáticos por categorías/productos
- **Múltiples Métodos de Pago:** Efectivo, tarjeta, transferencia
- **Gestión de Caja:** Apertura, cierre y cortes de caja
- **Multi-Usuario:** Sistema de roles (Admin/Cajero)
- **Multi-Dispositivo:** Sesiones simultáneas en múltiples terminales

### 🎯 **Personalización de Productos**
- Observaciones especiales (sin ingredientes)
- Especialidades adicionales (con precio)
- Control de tiempo de preparación
- Gestión de stock por producto

### 📊 **Reportes y Analytics**
- Historial de ventas
- Reportes por fechas
- Control de inventario
- Gestión de usuarios

## 🛠️ **Tecnologías Utilizadas**

- **Backend:** Laravel 10
- **Base de Datos:** MySQL
- **Frontend:** Blade Templates + Tailwind CSS
- **JavaScript:** Vanilla JS + jQuery
- **Autenticación:** Laravel Auth
- **API:** RESTful endpoints

## 📋 **Requisitos del Sistema**

- PHP >= 8.1
- Composer
- MySQL >= 8.0
- Node.js (para assets)
- Git

## 🚀 **Instalación**

### 1. Clonar el repositorio
```bash
git clone https://github.com/Fabian03October/Tochis.git
cd Tochis
```

### 2. Instalar dependencias
```bash
composer install
npm install
```

### 3. Configurar entorno
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configurar base de datos
Edita el archivo `.env` con tus credenciales de base de datos:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tu_base_de_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### 5. Ejecutar migraciones y seeders
```bash
php artisan migrate
php artisan db:seed
```

### 6. Iniciar servidor
```bash
php artisan serve
```

El sistema estará disponible en `http://localhost:8000`

## 👥 **Usuarios por Defecto**

### Administrador
- **Email:** admin@tochis.com
- **Contraseña:** admin123

### Cajero
- **Email:** cashier@tochis.com  
- **Contraseña:** cashier123

## 🏗️ **Estructura del Proyecto**

```
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Controladores de administración
│   │   └── Cashier/        # Controladores de cajero
│   ├── Models/             # Modelos Eloquent
│   └── Console/Commands/   # Comandos Artisan personalizados
├── database/
│   ├── migrations/         # Migraciones de base de datos
│   └── seeders/           # Datos iniciales
├── resources/
│   └── views/
│       ├── admin/         # Vistas de administración
│       └── cashier/       # Vistas de cajero
└── routes/
    └── web.php            # Rutas de la aplicación
```

## 🔧 **Configuración Multi-Dispositivo**

Para habilitar múltiples terminales:

1. **Configurar sesiones en base de datos:**
```bash
php artisan session:table
php artisan migrate
```

2. **Iniciar servidor accesible en red:**
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

3. **Acceder desde múltiples dispositivos:**
- Terminal 1: `http://ip-servidor:8000`
- Terminal 2: `http://localhost:8000`
- Dispositivo móvil: `http://ip-servidor:8000`

## 🎯 **Sistema de Promociones**

### Crear Promociones
1. Acceder como Admin
2. Ir a "Promociones" → "Crear Nueva"
3. Configurar:
   - Tipo de descuento (porcentaje/monto fijo)
   - Aplicar a (todas las ventas/categoría/producto específico)
   - Monto mínimo de compra
   - Fechas de vigencia

### Tipos de Promociones
- **Por Categoría:** Descuento a productos de categorías específicas
- **Por Producto:** Descuento a productos individuales
- **General:** Descuento a toda la venta

## 📱 **Uso del POS**

### Proceso de Venta
1. **Seleccionar productos** desde el menú por categorías
2. **Personalizar productos** (observaciones/especialidades)
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
