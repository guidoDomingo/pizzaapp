<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Pizza App') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Estilos adicionales para el Dashboard (sin Filament directives) -->
    <style>
        /* Filament Theme Variables */
        :root {
            --filament-primary: 59 130 246;
            --filament-danger: 239 68 68;
            --filament-gray: 107 114 128;
            --filament-success: 34 197 94;
            --filament-warning: 245 158 11;
        }
        
        /* Base Filament styles */
        body { 
            font-family: 'Inter', 'system-ui', sans-serif; 
            background-color: #f8fafc;
            color: #0f172a;
        }
        
        .bg-gray-50 { background-color: #f8fafc; }
        .bg-gray-100 { background-color: #f1f5f9; }
        .bg-gray-200 { background-color: #e2e8f0; }
        .bg-white { background-color: white; }
        .shadow { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
        .shadow-sm { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
        .shadow-md { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
        .shadow-lg { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }
        .shadow-xl { box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
        
        .px-1 { padding-left: 0.25rem; padding-right: 0.25rem; }
        .px-2 { padding-left: 0.5rem; padding-right: 0.5rem; }
        .px-3 { padding-left: 0.75rem; padding-right: 0.75rem; }
        .px-4 { padding-left: 1rem; padding-right: 1rem; }
        .px-6 { padding-left: 1.5rem; padding-right: 1.5rem; }
        .py-1 { padding-top: 0.25rem; padding-bottom: 0.25rem; }
        .py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
        .py-3 { padding-top: 0.75rem; padding-bottom: 0.75rem; }
        .py-4 { padding-top: 1rem; padding-bottom: 1rem; }
        .p-1 { padding: 0.25rem; }
        .p-2 { padding: 0.5rem; }
        .p-3 { padding: 0.75rem; }
        .p-4 { padding: 1rem; }
        .p-6 { padding: 1.5rem; }
        
        .rounded { border-radius: 0.375rem; }
        .rounded-md { border-radius: 0.375rem; }
        .rounded-lg { border-radius: 0.5rem; }
        .rounded-xl { border-radius: 0.75rem; }
        
        .text-xs { font-size: 0.75rem; line-height: 1rem; }
        .text-sm { font-size: 0.875rem; line-height: 1.25rem; }
        .text-base { font-size: 1rem; line-height: 1.5rem; }
        .text-lg { font-size: 1.125rem; line-height: 1.75rem; }
        .text-xl { font-size: 1.25rem; line-height: 1.75rem; }
        .text-2xl { font-size: 1.5rem; line-height: 2rem; }
        
        .font-medium { font-weight: 500; }
        .font-semibold { font-weight: 600; }
        .font-bold { font-weight: 700; }
        
        .grid { display: grid; }
        .grid-cols-1 { grid-template-columns: repeat(1, minmax(0, 1fr)); }
        .grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .grid-cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .gap-1 { gap: 0.25rem; }
        .gap-2 { gap: 0.5rem; }
        .gap-3 { gap: 0.75rem; }
        .gap-4 { gap: 1rem; }
        .gap-6 { gap: 1.5rem; }
        
        .space-y-1 > * + * { margin-top: 0.25rem; }
        .space-y-2 > * + * { margin-top: 0.5rem; }
        .space-y-3 > * + * { margin-top: 0.75rem; }
        .space-y-4 > * + * { margin-top: 1rem; }
        .space-x-2 > * + * { margin-left: 0.5rem; }
        
        /* Filament color scheme */
        .text-primary-600 { color: rgb(37 99 235); }
        .text-primary-700 { color: rgb(29 78 216); }
        .bg-primary-50 { background-color: rgb(239 246 255); }
        .bg-primary-500 { background-color: rgb(59 130 246); }
        .bg-primary-600 { background-color: rgb(37 99 235); }
        .border-primary-300 { border-color: rgb(147 197 253); }
        
        .text-success-600 { color: rgb(22 163 74); }
        .text-success-700 { color: rgb(21 128 61); }
        .bg-success-50 { background-color: rgb(240 253 244); }
        .bg-success-500 { color: rgb(34 197 94); }
        .bg-success-600 { background-color: rgb(22 163 74); }
        
        .text-gray-400 { color: rgb(156 163 175); }
        .text-gray-500 { color: rgb(107 114 128); }
        .text-gray-600 { color: rgb(75 85 99); }
        .text-gray-700 { color: rgb(55 65 81); }
        .text-gray-800 { color: rgb(31 41 55); }
        .text-gray-900 { color: rgb(17 24 39); }
        
        .text-white { color: white; }
        .text-green-600 { color: rgb(22 163 74); }
        .text-green-700 { color: rgb(21 128 61); }
        .text-blue-600 { color: rgb(37 99 235); }
        .text-red-600 { color: rgb(220 38 38); }
        
        /* Updated color scheme */
        .bg-blue-500 { background-color: rgb(59 130 246); }
        .bg-blue-600 { background-color: rgb(37 99 235); }
        .bg-green-500 { background-color: rgb(34 197 94); }
        .bg-green-600 { background-color: rgb(22 163 74); }
        .bg-red-500 { background-color: rgb(239 68 68); }
        .bg-red-600 { background-color: rgb(220 38 38); }
        .bg-purple-500 { background-color: rgb(168 85 247); }
        .bg-purple-600 { background-color: rgb(147 51 234); }
        
        .border { border-width: 1px; border-color: rgb(226 232 240); }
        .border-2 { border-width: 2px; }
        .border-t { border-top-width: 1px; border-color: rgb(226 232 240); }
        .border-b { border-bottom-width: 1px; border-color: rgb(226 232 240); }
        .border-l-4 { border-left-width: 4px; }
        .border-gray-200 { border-color: rgb(229 231 235); }
        .border-gray-300 { border-color: rgb(209 213 219); }
        
        .flex { display: flex; }
        .justify-between { justify-content: space-between; }
        .justify-center { justify-content: center; }
        .items-center { align-items: center; }
        .items-start { align-items: flex-start; }
        .w-full { width: 100%; }
        .min-h-screen { min-height: 100vh; }
        .max-w-7xl { max-width: 80rem; }
        .mx-auto { margin-left: auto; margin-right: auto; }
        .h-16 { height: 4rem; }
        
        .mb-1 { margin-bottom: 0.25rem; }
        .mb-2 { margin-bottom: 0.5rem; }
        .mb-3 { margin-bottom: 0.75rem; }
        .mb-4 { margin-bottom: 1rem; }
        .mb-6 { margin-bottom: 1.5rem; }
        .mt-1 { margin-top: 0.25rem; }
        .mt-2 { margin-top: 0.5rem; }
        .mt-3 { margin-top: 0.75rem; }
        .mr-2 { margin-right: 0.5rem; }
        .ml-2 { margin-left: 0.5rem; }
        
        /* Interactive states */
        .hover\:bg-primary-700:hover { background-color: rgb(29 78 216); }
        .hover\:bg-blue-600:hover { background-color: rgb(37 99 235); }
        .hover\:bg-green-600:hover { background-color: rgb(22 163 74); }
        .hover\:bg-red-600:hover { background-color: rgb(220 38 38); }
        .hover\:bg-purple-600:hover { background-color: rgb(147 51 234); }
        .hover\:bg-gray-50:hover { background-color: rgb(248 250 252); }
        .hover\:bg-gray-100:hover { background-color: rgb(241 245 249); }
        .hover\:shadow-sm:hover { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
        .hover\:shadow-md:hover { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
        .hover\:shadow-lg:hover { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }
        .hover\:text-gray-900:hover { color: rgb(17 24 39); }
        
        .transition { transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter; transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1); transition-duration: 150ms; }
        .transition-colors { transition-property: color, background-color, border-color, text-decoration-color, fill, stroke; transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1); transition-duration: 150ms; }
        .transition-all { transition-property: all; transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1); transition-duration: 150ms; }
        .transition-shadow { transition-property: box-shadow; transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1); transition-duration: 150ms; }
        .duration-200 { transition-duration: 200ms; }
        
        .cursor-pointer { cursor: pointer; }
        .cursor-not-allowed { cursor: not-allowed; }
        .opacity-50 { opacity: 0.5; }
        
        /* Responsive design */
        @media (min-width: 768px) {
            .md\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .md\:px-6 { padding-left: 1.5rem; padding-right: 1.5rem; }
        }
        
        @media (min-width: 1024px) {
            .lg\:col-span-2 { grid-column: span 2 / span 2; }
            .lg\:grid-cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            .lg\:px-8 { padding-left: 2rem; padding-right: 2rem; }
        }
        
        /* Filament-specific components */
        .fi-section { 
            background-color: white; 
            border-radius: 0.5rem; 
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); 
            border: 1px solid rgb(226 232 240);
        }
        
        .fi-badge { 
            display: inline-flex; 
            align-items: center; 
            padding: 0.25rem 0.75rem; 
            border-radius: 0.375rem; 
            font-size: 0.75rem; 
            font-weight: 500; 
        }
        
        .fi-badge-success { 
            background-color: rgb(220 252 231); 
            color: rgb(22 101 52); 
        }
        
        .fi-badge-warning { 
            background-color: rgb(254 243 199); 
            color: rgb(146 64 14); 
        }
        
        .fi-badge-primary { 
            background-color: rgb(219 234 254); 
            color: rgb(30 64 175); 
        }
        
        /* Button improvements */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.375rem;
            font-weight: 500;
            transition: all 0.15s ease;
            text-decoration: none;
            border: 1px solid transparent;
        }
        
        .btn-primary {
            background-color: rgb(59 130 246);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: rgb(37 99 235);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        /* Card improvements */
        .card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            border: 1px solid rgb(226 232 240);
            overflow: hidden;
        }
        
        .card:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100">
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-gray-800">🍕 Pizza Express</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/dashboard" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                        Dashboard
                    </a>
                    <a href="/admin" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                        Admin
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main>
        {{ $slot }}
    </main>

    <!-- Livewire Scripts - CDN version to avoid conflicts -->
    <script src="https://cdn.jsdelivr.net/gh/livewire/livewire@v3.x.x/dist/livewire.min.js" defer></script>
</body>
</html>
