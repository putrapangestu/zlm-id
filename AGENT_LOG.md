# AGENT_LOG.md

## 2026-05-22

### Decisions
- **Currency**: Changed all USD ($) to Rupiah (Rp) with format `Rp X.XXX.XXX`
- **Editor**: Chose Trix Editor (by Basecamp) — ringan, tanpa API key, output HTML bersih
- **Default Image**: Using `placehold.co/XXXX/363230/DF5E1D?text=ZLM` dengan brand color
- **Nav Role**: Changed from Laravel Gate `@can('admin')` to Spatie `@role('admin')`
- **Kelebihan/Kekurangan**: Disimpan sebagai HTML dari Trix, dirender dengan `{!! !!}`

## 2026-05-25

### Decisions
- **Auth Pages Redesign**: Register, Forgot Password, and Reset Password pages redesigned to match Login page design (two-panel layout with dark gradient branding + Iconify icons + consistent form styling)
- **Files Modified**: `resources/views/auth/register.blade.php`, `resources/views/auth/forgot-password.blade.php`, `resources/views/auth/reset-password.blade.php`
- **Admin Route**: Show route added explicitly before resource to avoid conflict

## 2026-06-19

### Builder-4: NOTIF-1 + LAPORAN Modules

#### NOTIF-1: Email Notifications
**Files Created:**
- `app/Mail/OrderConfirmationMail.php` — Mail class for order confirmation
- `app/Mail/OrderShippedMail.php` — Mail class for order shipped notification
- `app/Mail/OrderDeliveredMail.php` — Mail class for order delivered notification
- `resources/views/emails/order-confirmation.blade.php` — Email template (confirmed order + payment link)
- `resources/views/emails/order-shipped.blade.php` — Email template (shipped + tracking info)
- `resources/views/emails/order-delivered.blade.php` — Email template (delivered + review link)

**Files Modified:**
- `app/Http/Controllers/OrderController.php` — Added `Mail::to()->queue(new OrderConfirmationMail())` after order creation
- `app/Http/Controllers/Admin/OrderStatusController.php` — Added mail triggers for shipped/delivered status changes

**Design Notes:**
- All emails use inline CSS (email client compatibility)
- Consistent ZLM.ID branding (primary #DF5E1D, text #363230)
- Uses `Mail::queue()` for non-blocking email delivery
- `addTrackingEvent()` calls guarded with `method_exists()` for backward compatibility

#### LAPORAN: Reports Module
**Files Created:**
- `app/Http/Controllers/Admin/ReportController.php` — 3 report methods: purchases, profitLoss, productStats
- `resources/views/admin/reports/purchases.blade.php` — Purchase report with filters + summary cards + table
- `resources/views/admin/reports/profit-loss.blade.php` — P&L report with revenue, HPP, gross/net profit
- `resources/views/admin/reports/product-stats.blade.php` — Stock summary + top selling + top rated products

**Files Modified:**
- `routes/web.php` — Added 3 report routes under admin prefix
- `resources/views/layouts/admin.blade.php` — Added "Laporan" sidebar section with 3 submenus

**Route Names:**
- `admin.reports.purchases` → GET /admin/reports/purchases
- `admin.reports.profit-loss` → GET /admin/reports/profit-loss
- `admin.reports.product-stats` → GET /admin/reports/product-stats

## 2026-06-19 (Builder-2)

### TESTI-1: Testimoni CRUD

**Files Created:**
- `database/migrations/2026_06_19_100001_create_testimonials_table.php` — Migration for testimonials table (UUID, name, position, content, rating, photo, is_active)
- `app/Models/Testimonial.php` — Eloquent model with UUID primary key, $fillable, $casts
- `app/Http/Controllers/Admin/TestimonialController.php` — CRUD controller with validation, photo upload
- `resources/views/admin/testimonials/index.blade.php` — Table view with pagination, search, status badges
- `resources/views/admin/testimonials/create.blade.php` — Create form with validation
- `resources/views/admin/testimonials/edit.blade.php` — Edit form with pre-filled data
- `resources/views/landing/testimonials.blade.php` — Public testimonials page

**Files Modified:**
- `routes/web.php` — Added public route `/testimonials` and admin resource route
- `app/Http/Controllers/LaptopController.php` — Added testimonials to index method
- `resources/views/landing/home.blade.php` — Replaced static testimonials with dynamic loop
- `resources/views/layouts/admin.blade.php` — Added Testimonials link to sidebar

### PROFIL-1: Store Info Settings

**Files Modified:**
- `database/seeders/SettingsSeeder.php` — Added store_description, store_address, store_phone, store_email, store_google_maps, store_whatsapp, social_instagram, social_facebook, social_tiktok, social_youtube, store_logo, store_opening_hours
- `app/Http/Controllers/Admin/SettingController.php` — Rewritten with tabs logic (general, social, location)
- `resources/views/admin/settings/index.blade.php` — Rewritten with Alpine.js tabs, full forms for all settings

**Route Names:**
- `admin.testimonials.index` → GET /admin/testimonials
- `admin.testimonials.create` → GET /admin/testimonials/create
- `admin.testimonials.store` → POST /admin/testimonials
- `admin.testimonials.edit` → GET /admin/testimonials/{id}/edit
- `admin.testimonials.update` → PUT /admin/testimonials/{id}
- `admin.testimonials.destroy` → DELETE /admin/testimonials/{id}
- `landing.testimonials` → GET /testimonials

## 2026-06-19 (Builder-3)

### USER-1: Admin User CRUD

**Files Modified:**
- `app/Http/Controllers/Admin/UserController.php` — Full CRUD: index (search + role filter), create, store, show (with order history + total spent), edit, update, destroy (self-delete guard)
- `routes/web.php` — Added full CRUD routes for admin users (7 routes)
- `resources/views/admin/users/index.blade.php` — Rewritten with real data: search bar, role filter, paginated table, delete confirmation
- `resources/views/admin/users/create.blade.php` — New: form with name, email, password, confirm, role select
- `resources/views/admin/users/edit.blade.php` — New: form with optional password, pre-filled data
- `resources/views/admin/users/show.blade.php` — New: user info card + order history table + total spent

**Route Names:**
- `admin.users.index` → GET /admin/users
- `admin.users.create` → GET /admin/users/create
- `admin.users.store` → POST /admin/users
- `admin.users.show` → GET /admin/users/{user}
- `admin.users.edit` → GET /admin/users/{user}/edit
- `admin.users.update` → PUT /admin/users/{user}
- `admin.users.destroy` → DELETE /admin/users/{user}

### TRACK-1: User Tracking

**Files Created:**
- `database/migrations/2026_06_19_200001_add_tracking_fields_to_orders_table.php` — Migration adding tracking_number, tracking_history, shipped_at, estimated_delivery to orders
- `app/Http/Controllers/TrackingController.php` — Public tracking: index (search form), trackByNumber (lookup by order_number or tracking_number), show (auth-verified)
- `resources/views/landing/tracking.blade.php` — Public tracking page with search form + visual timeline (4 steps: Pesanan Dibuat → Diproses → Dikirim → Diterima)
- `resources/views/admin/orders/tracking.blade.php` — Admin tracking update form: status select, tracking number input, tracking history timeline

**Files Modified:**
- `app/Models Order.php` — Added fillable (tracking_number, tracking_history, shipped_at, estimated_delivery), casts (array, datetime, date), helper methods (addTrackingEvent, getLatestTracking)
- `app/Http/Controllers/Admin/OrderStatusController.php` — Enhanced update() to auto-add tracking events on status changes; added tracking() method
- `routes/web.php` — Added public routes (GET /tracking, POST /tracking), auth route (GET /tracking/{order}), admin route (GET /admin/orders/{order}/tracking)
- `resources/views/orders/history.blade.php` — Added "Lacak" tracking link button on each order card

**Route Names:**
- `tracking.index` → GET /tracking
- `tracking.by-number` → POST /tracking
- `tracking.show` → GET /tracking/{order}
- `admin.orders.tracking` → GET /admin/orders/{order}/tracking

## 2026-06-19 (Builder-1)

### AUTH-1: Login dengan Google

**Files Created:**
- `database/migrations/2026_06_19_000001_add_google_id_to_users_table.php` — Migration adding google_id (nullable, unique) and avatar columns to users table
- `app/Http/Controllers/Auth/GoogleController.php` — OAuth controller: redirect() to Google, callback() to handle login/register with google_id linking

**Files Modified:**
- `app/Models/User.php` — Added `google_id` and `avatar` to Fillable attribute
- `config/services.php` — Added `google` config block (client_id, client_secret, redirect from env)
- `routes/auth.php` — Added 2 routes under guest middleware: `auth/google` and `auth/google/callback`
- `resources/views/auth/login.blade.php` — Added divider "atau" + Google login button with SVG logo
- `resources/views/auth/register.blade.php` — Added divider "atau" + Google login button with SVG logo
- `.env` — Added `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_REDIRECT_URL`

**Dependencies:**
- `laravel/socialite` v5.28.0 installed via composer

**Route Names:**
- `auth.google` → GET /auth/google
- (no name) → GET /auth/google/callback

**Flow:**
1. User clicks "Lanjutkan dengan Google" → redirects to Google OAuth
2. On callback: find user by google_id or email → login or create new user
3. New users get `user` role assigned automatically and `email_verified_at` set

### SEARCH-1: Smart Search

**Files Created:**
- `app/Http/Controllers/SmartSearchController.php` — Search engine: index() shows form, search() scores laptops by budget/priority/usage/brand
- `resources/views/landing/smart-search.blade.php` — Full page: search form (budget input, priority radio, usage select, brand select) + results grid with score badges

**Files Modified:**
- `routes/web.php` — Added GET `/smart-search` and POST `/smart-search` in public routes
- `resources/views/landing/home.blade.php` — Added "Smart Search" button in hero section after "Explore Catalog"

**Scoring Algorithm:**
- Budget score: (price / budget_max) × 100
- CPU score: mapped by processor class (i9=100, i7=80, i5=60, i3=40, Apple M=85)
- RAM score: 32GB+=100, 16GB=80, 8GB=60, 4GB=30
- Storage score: 1TB+=100, 512GB=80, 256GB=60
- GPU score: high-end dedicated=100, mid-range=80, entry dedicated=60, integrated=40
- Default weights: budget=35%, cpu=25%, ram=15%, storage=10%, gpu=15%
- Priority boost: selected priority +5%, others -1%

**Route Names:**
- `landing.smart-search` → GET /smart-search
- `landing.smart-search.post` → POST /smart-search

**Design:**
- Score badges: green ≥80%, yellow ≥60%, red <60%
- Score breakdown bars per card (Budget, CPU, RAM, SSD, GPU)
- Budget input with Rupiah formatting (Alpine.js-free, vanilla JS)
- Empty state with reset/search alternatives
