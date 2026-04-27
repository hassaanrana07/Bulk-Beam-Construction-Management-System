# Brick & Beam - Enterprise Construction CMS

## Setup Instructions

### Prerequisites
1. PHP 8.2+ with the following extensions enabled:
   - pdo_sqlite (or pdo_mysql for MySQL)
   - gd or imagick
   - fileinfo
   - openssl
   - mbstring
   - tokenizer
   - xml
   - ctype
   - json
   - bcmath

2. Composer
3. Node.js & NPM
4. MySQL (optional, SQLite is configured by default)

### Installation Steps

1. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

2. **Environment Configuration**
   - Copy `.env.example` to `.env` (already done)
   - Update database credentials if using MySQL
   - For SQLite: Ensure `pdo_sqlite` extension is enabled in php.ini

3. **Enable PDO SQLite (if not enabled)**
   - Open your `php.ini` file
   - Find and uncomment (remove `;` from):
     ```
     extension=pdo_sqlite
     extension=sqlite3
     ```
   - Restart your web server/PHP

4. **Run Migrations**
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Create Storage Link**
   ```bash
   php artisan storage:link
   ```

6. **Build Frontend Assets**
   ```bash
   npm run build
   ```

7. **Start Development Server**
   ```bash
   # Terminal 1: Laravel
   php artisan serve

   # Terminal 2: Vite (for hot reload)
   npm run dev
   ```

8. **Access the Application**
   - Public Site: http://localhost:8000
   - Admin Panel: http://localhost:8000/admin
   - Default Admin Credentials:
     - Email: admin@brickbeam.com
     - Password: password

### Database Options

#### Option 1: SQLite (Default - Easier Setup)
Already configured in `.env`. Just ensure PDO SQLite extension is enabled.

#### Option 2: MySQL (Production Recommended)
Update `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=brick_beam
DB_USERNAME=root
DB_PASSWORD=your_password
```

Create the database:
```sql
CREATE DATABASE brick_beam CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Features Implemented

#### Public Website
- ✅ Home (9 sections)
- ✅ About (8 sections)
- ✅ Services (listing + detail pages)
- ✅ Portfolio/Case Studies (filterable grid + detail pages)
- ✅ Cost Estimator (dynamic calculator with PDF export)
- ✅ Blog (categories, search, pagination)
- ✅ Careers (job listings + application system)
- ✅ Contact (multi-location, map, form)
- ✅ Legal Pages (Privacy, Terms, Cookies)

#### Admin Panel
- ✅ Role-Based Access Control (Super Admin, Admin, Manager, Editor, Support)
- ✅ Dashboard with analytics and charts
- ✅ Lead Management (UTM tracking, scoring, workflow)
- ✅ Media Library (folders, tags, WebP conversion)
- ✅ CMS Modules (Pages, Services, Portfolio, Blog, etc.)
- ✅ Settings Module
- ✅ Audit Logs

#### Advanced Features
- ✅ Theme System (Light/Dark with admin control)
- ✅ SEO Automation (sitemap, meta, structured data)
- ✅ Lead Scoring System
- ✅ Cost Estimator with DB-driven rules
- ✅ Email Notifications
- ✅ Security (rate limiting, honeypot, reCAPTCHA ready)
- ✅ Performance Optimization (caching, lazy loading)

### Theme Colors
- **Primary**: Black (#000000)
- **Accent**: Orange (#ff6b00)
- **Background**: White (#ffffff)
- **Grays**: Professional construction tones

### Next Steps After Setup
1. Configure email settings in `.env` for notifications
2. Add reCAPTCHA keys for form protection
3. Upload company logo and images via Media Library
4. Configure global settings via Admin Panel
5. Create initial content (services, portfolio, blog posts)
6. Set up queue workers for background jobs
7. Configure backup schedule

### Troubleshooting

**Migration Errors**
- Ensure database connection is correct
- Check PDO extension is enabled
- Verify database exists (for MySQL)

**Asset Build Errors**
- Clear node_modules: `rm -rf node_modules && npm install`
- Clear cache: `npm cache clean --force`

**Permission Errors**
- Ensure storage and bootstrap/cache are writable:
  ```bash
  chmod -R 775 storage bootstrap/cache
  ```

### Production Deployment
1. Set `APP_ENV=production` and `APP_DEBUG=false`
2. Run `php artisan config:cache`
3. Run `php artisan route:cache`
4. Run `php artisan view:cache`
5. Set up queue workers
6. Configure automated backups
7. Set up SSL certificate
8. Configure CDN for media files

## Architecture

### Backend
- Laravel 12
- Spatie Permission (RBAC)
- Spatie Activity Log (Audit)
- Intervention Image (Image Processing)
- Inertia.js (SPA Bridge)

### Frontend
- Vue 3
- Pinia (State Management)
- Vite (Build Tool)
- Ziggy (Route Helper)

### Database
- SQLite (Development)
- MySQL (Production Recommended)

## Support
For issues or questions, refer to the comprehensive code documentation within each module.
