# UMKM Freelance Marketplace

Platform digital untuk menghubungkan UMKM (Usaha Mikro, Kecil, dan Menengah) dengan freelancer dan pelanggan. Aplikasi ini menyediakan ekosistem lengkap untuk penjualan produk, penawaran layanan, dan manajemen transaksi.

## Fitur Utama

### 👥 Multi-Role User System
- **Admin**: Mengelola platform, promosi, dan konten
- **Mitra (Seller)**: Menjual produk dan layanan, mengelola katalog
- **Freelancer**: Menawarkan jasa dan portfolio kerja
- **Customer**: Membeli produk dan menggunakan layanan

### 🛍️ E-Commerce Features
- **Product Catalog**: Sistem kategori produk yang terstruktur
- **Shopping Cart**: Keranjang belanja yang fully functional
- **Checkout System**: Proses pembelian yang mudah dan aman
- **Order Management**: Tracking dan manajemen pesanan
- **Payment Methods**: Dukungan berbagai metode pembayaran

### 🎨 Portfolio & Services
- **Portfolio Management**: Freelancer dapat menampilkan portofolio kerja
- **Service Listings**: Penawaran layanan dengan detail lengkap
- **Transaction Tracking**: History transaksi dan status pembayaran

### 🎯 Promotion System
- **Product Highlights**: Promosi produk dengan level highlight
- **Admin Promotions**: Manajemen promosi dari sisi admin
- **Expiry Management**: Sistem expiry untuk promosi

### 📱 Modern Architecture
- **API-First Design**: REST API menggunakan Laravel Sanctum
- **Single Page Application**: Frontend dengan Vite + Tailwind CSS
- **Responsive Design**: Mobile-friendly interface
- **Real-time Updates**: Database-driven updates

## Tech Stack

### Backend
- **Framework**: Laravel 12.x
- **Database**: MySQL/MariaDB
- **Authentication**: Laravel Sanctum (Token-based)
- **Image Processing**: Intervention Image 3.11
- **Testing**: PHPUnit 11.5

### Frontend
- **Build Tool**: Vite 5.0
- **Styling**: Tailwind CSS 4.1
- **HTTP Client**: Axios 1.11
- **Runtime**: Node.js (ES Modules)

### Development Tools
- **Package Manager**: Composer & npm
- **Linting**: Laravel Pint
- **Process Management**: Concurrently
- **Containerization**: Laravel Sail

## System Requirements

- PHP 8.2 atau lebih tinggi
- Composer
- Node.js 16+ dengan npm
- MySQL 8.0+ atau MariaDB 10.3+
- Laravel Sail (Docker) - opsional

## Installation

### 1. Clone Repository
```bash
git clone <repository-url>
cd umkm-freelance
```

### 2. Setup Otomatis
```bash
composer run-script setup
```

Script ini akan:
- Install dependency PHP
- Generate `.env` file
- Generate application key
- Run database migrations
- Install dependency JavaScript
- Build assets

### 3. Manual Setup (Jika diperlukan)

#### Install Dependencies
```bash
composer install
npm install
```

#### Konfigurasi Environment
```bash
cp .env.example .env
php artisan key:generate
```

#### Database Setup
```bash
php artisan migrate
php artisan db:seed  # Optional
```

#### Build Assets
```bash
npm run build   # Production
npm run dev     # Development
```

## Development

### Menjalankan Development Server

Untuk menjalankan semua service sekaligus (server, queue, logs, vite):
```bash
composer run-script dev
```

Atau jalankan secara terpisah:
```bash
# Terminal 1: Laravel Development Server
php artisan serve

# Terminal 2: Queue Listener
php artisan queue:listen --tries=1

# Terminal 3: Pail Logs
php artisan pail --timeout=0

# Terminal 4: Vite Development Server
npm run dev
```

### Running Tests
```bash
composer run-script test
```

## Project Structure

### Backend (`app/`)
```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/              # API Controllers
│   │   └── ...               # Web Controllers
│   └── Middleware/           # Custom Middleware
├── Models/                   # Eloquent Models
│   ├── User.php              # User Model (multi-role)
│   ├── Product.php           # Product Model
│   ├── Category.php          # Category Model
│   ├── Cart.php              # Shopping Cart
│   ├── Transaction.php       # Order Transactions
│   ├── FreelancerProfile.php # Freelancer Profile
│   ├── Portfolio.php         # Freelancer Portfolio
│   └── ...
└── Providers/                # Service Providers
```

### Database (`database/`)
```
database/
├── migrations/               # Database Schema
├── factories/                # Model Factories
└── seeders/                  # Database Seeders
```

### Routes
- `routes/api.php` - API endpoints (JSON responses)
- `routes/web.php` - Web routes (HTML responses)
- `routes/console.php` - Console/CLI commands

### Frontend (`resources/`)
```
resources/
├── js/                       # JavaScript/Vue Components
├── css/                      # Tailwind CSS
└── views/                    # Blade Templates
```

## API Documentation

### Public Endpoints (No Authentication Required)

#### Authentication
- `POST /api/register` - Register new user
- `POST /api/login` - Login user
- `GET /api/admin-contact` - Get admin contact info

#### Products
- `GET /api/products` - List products (paginated, searchable)
  - Query params: `?page=1&search=keyword&category=id`
- `GET /api/products/categories` - List all categories
- `GET /api/products/{slug}` - Get product detail

### Protected Endpoints (Requires Authentication)

#### Cart Management
- `GET /api/cart` - Get user's cart
- `POST /api/cart/add` - Add item to cart
- `PUT /api/cart/update/{cartItemId}` - Update quantity
- `DELETE /api/cart/remove/{cartItemId}` - Remove item
- `DELETE /api/cart` - Clear entire cart
- `POST /api/checkout` - Create order from cart

#### Transactions
- `GET /api/transactions` - List user transactions
- `GET /api/transactions/{code}` - Get transaction detail
- `POST /api/transactions/{code}/upload-proof` - Upload payment proof

## Database Schema

### Key Tables
- `users` - User accounts dengan role-based access
- `products` - Product catalog
- `product_images` - Product image gallery
- `categories` - Product categories
- `carts` - Shopping carts
- `cart_items` - Cart items
- `transactions` - Orders/Transaksi
- `transaction_items` - Items dalam transaksi
- `mitra_profiles` - Seller profiles
- `freelancer_profiles` - Freelancer profiles
- `portfolios` - Freelancer portfolios
- `payment_methods` - Payment method options

## Features in Detail

### User Roles & Permissions

| Role | Capabilities |
|------|---|
| Customer | Browse products, add to cart, checkout, view order history |
| Mitra (Seller) | Create products, manage inventory, view sales, receive orders |
| Freelancer | Create portfolio, offer services, manage projects |
| Admin | Manage users, manage promotions, system configuration |

### Product Management
- Upload product dengan multiple images
- Kategori dan sub-kategori
- Pricing dengan real price dan selling price
- Stock management
- Product highlighting/promotion
- SEO-friendly slugs

### Order & Payment
- Cart-based shopping
- Multi-step checkout
- Payment verification
- Order status tracking
- Transaction history

### Promotion System
- Admin product highlights
- Highlight levels dan expiry dates
- Featured products display

## Environment Variables

Key environment variables di `.env`:
```
APP_NAME=UMKM-Freelance
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=umkm_freelance
DB_USERNAME=root
DB_PASSWORD=

MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
...
```

## Deployment

### Production Build
```bash
# Build frontend assets
npm run build

# Run migrations
php artisan migrate --force

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Using Laravel Sail
```bash
# Start containers
./vendor/bin/sail up -d

# Run migrations
./vendor/bin/sail artisan migrate

# Access application
# http://localhost
```

## Troubleshooting

### Storage Link Issue
```bash
php artisan storage:link
```

### Permission Issues
```bash
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

### Clear All Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

## Contributing

1. Create a feature branch (`git checkout -b feature/AmazingFeature`)
2. Commit changes (`git commit -m 'Add some AmazingFeature'`)
3. Push to branch (`git push origin feature/AmazingFeature`)
4. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

Untuk bantuan dan pertanyaan, silakan buat issue di repository ini atau hubungi tim development.

---

**Last Updated**: January 2026

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

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
