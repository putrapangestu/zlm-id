# 🛍️ Laptop Sales Website - Complete Routing & Pages Documentation

## Project Overview

This is a professional laptop sales website built with Laravel and Tailwind CSS, following international UI/UX standards with clean and modular code.

**Brand Colors:**
- Primary: `#DF5E1D` (Orange)
- Dark: `#363230` (Dark Gray)
- Light Gray: `#f5f5f5`

---

## 📋 Database Structure

### Laptops Table
```sql
CREATE TABLE laptops (
    id - Primary Key
    name - Laptop model name
    brand - Manufacturer (e.g., ASUS, Dell)
    description - Full description
    price - Decimal price
    processor - CPU details
    ram - Memory specifications
    storage - Storage type and capacity
    graphics - GPU details
    display - Screen specifications
    weight - Product weight in kg
    battery_life - Battery duration info
    image_url - Product image URL
    category - Enum: gaming, business, student, ultrabook
    stock - Available quantity
    is_featured - Boolean for featured products
    timestamps - Created & updated timestamps
)
```

---

## 🛣️ Routes & URL Structure

### Public Landing Pages

| Route | URL | Controller Method | Description |
|-------|-----|------------------|-------------|
| `landing.home` | `/` | `LaptopController@index` | Home page with featured products |
| `landing.search` | `/search` | `LaptopController@search` | Product search with filters |
| `landing.compare` | `/compare` | `LaptopController@compare` | Compare laptops side-by-side |
| `landing.detail` | `/laptop/{id}` | `LaptopController@show` | Individual laptop details |

---

## 📄 Pages Breakdown

### 1. **Home Page** (`landing.home`)
**URL:** `/`
**Template:** `resources/views/landing/home.blade.php`

**Sections:**
- ✨ **Hero Section** - Main banner with CTA buttons
- ⭐ **Featured Laptops** - Grid display of 6 featured products
- 📂 **Shop by Category** - 4 category cards (Gaming, Business, Student, Ultrabook)
- ✓ **Why Choose Us** - 4 benefit cards with icons
- 💬 **Testimonials** - 3 customer reviews with 5-star ratings
- 📰 **Articles & Tips** - 3 blog article cards
- 📧 **Newsletter Section** - Email subscription form

**Features:**
- Responsive grid layouts
- Hover effects and transitions
- Category filtering
- Quick action links

---

### 2. **Search/Shop Page** (`landing.search`)
**URL:** `/search`
**Template:** `resources/views/landing/search.blade.php`

**Sidebar Filters:**
- 🔍 Search by name, processor, specifications
- 📂 Category filter (radio buttons)
- 🏭 Brand dropdown filter
- 💰 Price range input (min/max)
- 🔄 Apply/Reset buttons

**Main Content:**
- Product grid (responsive: 1→2→3 columns)
- Results counter
- Sorting dropdown
- Laptop cards with:
  - Product image with hover zoom
  - Category badge
  - Brand & name
  - Key specs (CPU, RAM, Storage)
  - Price display
  - Detail button link
- Stock status indicator
- Pagination links

**Database Queries:**
- Filter by category
- Price range filtering
- Brand filtering
- Full-text search capabilities
- Pagination (12 items per page)

---

### 3. **Compare Page** (`landing.compare`)
**URL:** `/compare`
**Template:** `resources/views/landing/compare.blade.php`

**Features:**
- Side-by-side product comparison table
- Specifications row highlighting:
  - Processor
  - Memory (RAM)
  - Storage
  - Graphics
  - Display
  - Battery Life
  - Weight
  - Category
- Product info column with:
  - Product image
  - Brand & model name
  - Price (large font)
  - View Details link
  - Add to Cart button
  - Remove button (X)
- Empty state with CTA to browse
- Support for up to 4 laptops
- "Add Another Laptop" link (when < 4 selected)

**Functionality:**
- Query parameter based: `?laptops[]=1&laptops[]=2&laptops[]=3`
- Responsive table scroll for mobile
- Interactive row highlighting on hover

---

### 4. **Detail Page** (`landing.detail`)
**URL:** `/laptop/{id}`
**Template:** `resources/views/landing/detail.blade.php`

**Main Product Section:**
- Breadcrumb navigation
- Category badge
- Product name & brand
- Stock indicator
- Product image/gallery area
- Price display (large, primary color)
- Add to Cart button (disabled if out of stock)
- Wishlist button
- Product description
- Social sharing buttons

**Technical Specifications:**
- 3-column grid layout
- Left-border accent (primary color)
- Specifications displayed:
  - Processor
  - Memory (RAM)
  - Storage
  - Graphics
  - Display
  - Battery Life
  - Weight (if available)

**Similar Products Section:**
- Grid of 4 related laptops (same category)
- Quick preview cards with:
  - Product image
  - Category badge
  - Brand & name
  - Key specs
  - Price
  - Link to detail page

---

## 🎨 Components

### Navigation Component (`components/landing-nav.blade.php`)
- Logo/Brand link (returns to home)
- Navigation links (Home, Shop, Compare)
- Cart icon with item count badge
- Mobile hamburger menu button
- Dark background with orange hover effects

### Footer Component (`components/landing-footer.blade.php`)
- 4-column grid:
  1. About section
  2. Quick Links (Home, Products, About, Contact)
  3. Support Links (Help, Track, Returns, FAQ)
  4. Contact Info (Email, Phone, Address)
- Additional links row (Privacy, Terms)
- Copyright notice

---

## 🎯 Sample Data

**12 Laptops Seeded:**

### Gaming (3)
- ROG Zephyrus G16 ($3,499.99) - Featured
- Legion Pro 9 ($3,299.99) - Featured
- MSI Raider GE78 ($2,899.99)

### Business (3)
- ThinkPad X1 Extreme ($2,299.99) - Featured
- MacBook Pro 16" ($2,499.99) - Featured
- Dell XPS 17 ($2,199.99)

### Student (3)
- IdeaPad 3 ($599.99) - Featured
- VivoBook 15 ($649.99)
- Inspiron 15 ($549.99)

### Ultrabook (3)
- MacBook Air M2 ($1,299.99) - Featured
- ZenBook 14 ($1,099.99)
- XPS 13 Plus ($1,199.99)

---

## 💾 Model & Relationships

### Laptop Model (`app/Models/Laptop.php`)

**Fillable Properties:**
- name, brand, description, price
- processor, ram, storage, graphics, display
- weight, battery_life, image_url
- category, stock, is_featured

**Query Scopes:**
```php
// Get featured laptops
Laptop::featured()->get();

// Get by category
Laptop::byCategory('gaming')->get();

// Get in price range
Laptop::inPriceRange(500, 2000)->get();
```

---

## 🔧 Controller Methods

### LaptopController (`app/Http/Controllers/LaptopController.php`)

#### `index()`
- Returns featured laptops (6 items)
- Passes categories list
- View: `landing.home`

#### `search(Request $request)`
- Filters by: category, price range, brand, search term
- Paginates results (12 per page)
- Gets all distinct brands
- Gets max price for filter range
- View: `landing.search`

#### `compare(Request $request)`
- Gets laptop IDs from query parameters
- Retrieves full laptop objects
- View: `landing.compare`

#### `show($id)`
- Gets specific laptop by ID
- Gets 4 similar laptops (same category)
- View: `landing.detail`

---

## 📱 Responsive Design

**Tailwind Breakpoints:**
- `sm`: 640px (mobile landscape)
- `md`: 768px (tablet)
- `lg`: 1024px (desktop)
- `xl`: 1280px (large desktop)
- `2xl`: 1536px (extra large)

**Mobile-First Approach:**
- Navigation collapses to hamburger menu on mobile
- Product grids: 1 column (mobile) → 2 columns (tablet) → 3 columns (desktop)
- Search sidebar appears below products on mobile (reorder with `lg:`)
- Tables scroll horizontally on mobile

---

## 🎨 Color Usage

**Primary Color: #DF5E1D**
- Category badges
- Buttons (CTA)
- Price displays
- Links hover states
- Borders and accents

**Dark Color: #363230**
- Navigation background
- Text/headings
- Dark theme elements

**Light Gray: #f5f5f5**
- Section backgrounds
- Product grid background
- Filter backgrounds

---

## ✨ Notable Features

1. **Advanced Filtering**
   - Multi-criterion filtering (category, price, brand)
   - Full-text search
   - Persistent filter state in URL

2. **Responsive Tables**
   - Horizontal scroll on mobile
   - Sticky headers
   - Alternating row colors on hover

3. **Image Optimization**
   - Fallback icons for missing images
   - Hover zoom effects
   - Lazy loading friendly

4. **SEO Ready**
   - Semantic HTML
   - Proper meta tags
   - Breadcrumb navigation
   - Descriptive alt text

5. **Accessibility**
   - ARIA labels
   - Keyboard navigation
   - Color contrast compliance
   - Form labels properly associated

---

## 🚀 Next Steps for Development

1. **Admin Dashboard** - Manage products, orders, users
2. **User Authentication** - Login, registration, profiles
3. **Shopping Cart** - Add/remove items, checkout
4. **Order Management** - Purchase history, tracking
5. **Payment Integration** - Stripe, PayPal integration
6. **Review System** - Customer reviews and ratings
7. **Wishlist Feature** - Save favorite products
8. **Inventory Management** - Stock tracking

---

## 🛠️ Development Commands

```bash
# Start dev server
npm run dev

# Build for production
npm run build

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Fresh migration + seed
php artisan migrate:fresh --seed

# View routes
php artisan route:list
```

---

## 📚 File Structure

```
resources/
├── views/
│   ├── layouts/
│   │   ├── landing.blade.php        # Main landing layout
│   │   └── app.blade.php            # Admin layout
│   ├── landing/
│   │   ├── home.blade.php           # Home page
│   │   ├── search.blade.php         # Search/shop page
│   │   ├── compare.blade.php        # Compare page
│   │   └── detail.blade.php         # Product detail page
│   └── components/
│       ├── landing-nav.blade.php    # Navigation bar
│       └── landing-footer.blade.php # Footer
app/
├── Models/
│   └── Laptop.php                   # Laptop model
└── Http/
    └── Controllers/
        └── LaptopController.php     # Landing page controller
database/
├── migrations/
│   └── 2026_04_20_033626_create_laptops_table.php
└── seeders/
    └── LaptopSeeder.php             # Sample data seeder
routes/
└── web.php                          # Landing page routes
```

---

**Status: ✅ COMPLETE** - All pages and routing implemented with sample data!
