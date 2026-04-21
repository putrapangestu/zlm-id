# 🎨 Tailwind CSS Setup Guide

Project ini sudah dikonfigurasi dengan **Tailwind CSS v4.0.0** untuk styling modern dan responsif.

## ✅ Setup Completion Status

### Installed & Configured
- ✅ **Tailwind CSS v4.0.0** - CSS framework
- ✅ **@tailwindcss/vite** - Vite plugin untuk Tailwind
- ✅ **Laravel Vite Plugin** - Integrasi dengan Laravel
- ✅ **Vite** - Build tool dan dev server

### Configuration Files
- ✅ **vite.config.js** - Configured dengan Tailwind plugin
- ✅ **resources/css/app.css** - Main stylesheet dengan @import tailwindcss
- ✅ **package.json** - Dependencies sudah tercakup

## 🚀 How to Use

### 1. Install Dependencies (First Time Only)
```bash
npm install
```

### 2. Start Development Server
```bash
npm run dev
```
Server akan berjalan di `http://localhost:5173`

### 3. Build for Production
```bash
npm run build
```

## 📁 Project Structure

```
resources/
├── css/
│   └── app.css          # Main Tailwind stylesheet
├── js/
│   └── app.js           # JavaScript entry point
└── views/
    ├── layouts/
    │   └── app.blade.php    # Main layout template
    ├── components/
    │   ├── navigation.blade.php
    │   └── footer.blade.php
    ├── dashboard.blade.php   # Example page
    └── welcome.blade.php     # Home page
```

## 🎯 Tailwind Features Included

### Utility Classes
- **Spacing** - margin, padding dengan skala consistent
- **Colors** - Comprehensive color palette
- **Typography** - Font sizing, weight, styling
- **Responsive** - Mobile-first breakpoints (sm, md, lg, xl, 2xl)
- **Dark Mode** - Dark mode support dengan `dark:` prefix
- **Effects** - Shadow, opacity, transitions, transforms

### Breakpoints
- `sm`: 640px
- `md`: 768px
- `lg`: 1024px
- `xl`: 1280px
- `2xl`: 1536px

## 📝 Quick Examples

### Responsive Grid
```html
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Grid items -->
</div>
```

### Button Styling
```html
<button class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
    Click Me
</button>
```

### Card Component
```html
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Title</h3>
    <p class="text-gray-600 dark:text-gray-400">Content here</p>
</div>
```

### Dark Mode
```html
<div class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
    Content yang support dark mode
</div>
```

## 🔧 Customization

Edit `resources/css/app.css` untuk customize tema:

```css
@theme {
    --font-family-custom: 'Your Font', sans-serif;
    --color-primary: #your-color;
}
```

## 📚 Resources

- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [Tailwind CSS v4 Guide](https://tailwindcss.com/blog/tailwindcss-v4)
- [Laravel Blade Components](https://laravel.com/docs/blade#components)

## 🎨 Created Components

### Layout Files
- `resources/views/layouts/app.blade.php` - Main layout dengan navigation

### Component Examples
- `resources/components/navigation.blade.php` - Navigation bar
- `resources/components/footer.blade.php` - Footer section

### Page Examples
- `resources/views/dashboard.blade.php` - Dashboard dengan Tailwind styling

## ✨ Next Steps

1. Explore Tailwind CSS classes di [tailwindcss.com](https://tailwindcss.com)
2. Customize theme colors dan fonts sesuai brand
3. Build responsive components menggunakan Tailwind utilities
4. Use dark mode variant untuk better UX

---

**Happy Styling! 🎨**
