<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Pizza App') }} - Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Livewire v2 Styles -->
    @livewireStyles
        /* Filament-inspired styles */
        :root {
            --primary: 59 130 246;
            --success: 34 197 94;
            --danger: 239 68 68;
            --warning: 245 158 11;
            --gray: 107 114 128;
        }
        
        body { 
            font-family: 'Inter', 'system-ui', sans-serif; 
            background-color: #f8fafc;
            color: #0f172a;
        }
        
        .bg-gray-50 { background-color: #f8fafc; }
        .bg-gray-100 { background-color: #f1f5f9; }
        .bg-white { background-color: white; }
        .shadow-md { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
        .px-4 { padding-left: 1rem; padding-right: 1rem; }
        .py-3 { padding-top: 0.75rem; padding-bottom: 0.75rem; }
        .rounded-lg { border-radius: 0.5rem; }
        .text-xl { font-size: 1.25rem; line-height: 1.75rem; }
        .font-bold { font-weight: 700; }
        .grid { display: grid; }
        .grid-cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .gap-2 { gap: 0.5rem; }
        .bg-blue-500 { background-color: rgb(59 130 246); }
        .text-white { color: white; }
        .border { border: 1px solid #e2e8f0; }
        .p-3 { padding: 0.75rem; }
        .hover\:bg-blue-600:hover { background-color: rgb(37 99 235); }
        .transition-colors { transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out; }
        .font-medium { font-weight: 500; }
        .text-sm { font-size: 0.875rem; }
        .mb-2 { margin-bottom: 0.5rem; }
        .mb-4 { margin-bottom: 1rem; }
        .mb-6 { margin-bottom: 1.5rem; }
        .space-y-2 > * + * { margin-top: 0.5rem; }
        .flex { display: flex; }
        .justify-between { justify-content: space-between; }
        .items-center { align-items: center; }
        .bg-green-500 { background-color: rgb(34 197 94); }
        .hover\:bg-green-600:hover { background-color: rgb(22 163 74); }
        .w-full { width: 100%; }
        .py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
        .min-h-screen { min-height: 100vh; }
        .max-w-7xl { max-width: 80rem; }
        .mx-auto { margin-left: auto; margin-right: auto; }
        .h-16 { height: 4rem; }
        .text-gray-700 { color: #374151; }
        .hover\:text-gray-900:hover { color: #111827; }
        .lg\:col-span-2 { grid-column: span 2 / span 2; }
        .cursor-pointer { cursor: pointer; }
        .bg-red-500 { background-color: rgb(239 68 68); }
        .text-green-600 { color: rgb(22 163 74); }
        .font-semibold { font-weight: 600; }
        .text-gray-800 { color: #1f2937; }
        .border-2 { border-width: 2px; }
        .transition-all { transition: all 0.15s ease-in-out; }
        .duration-200 { transition-duration: 200ms; }
        .transform { transform: translateZ(0); }
        .scale-105 { transform: scale(1.05); }
        .bg-green-50 { background-color: #f0fdf4; }
        .border-green-300 { border-color: #86efac; }
        .text-green-800 { color: #166534; }
        .hover\:bg-green-100:hover { background-color: #dcfce7; }
        .hover\:shadow-md:hover { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .bg-red-50 { background-color: #fef2f2; }
        .border-red-300 { border-color: #fca5a5; }
        .text-red-800 { color: #991b1b; }
        .opacity-50 { opacity: 0.5; }
        .cursor-not-allowed { cursor: not-allowed; }
        .shadow-lg { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
        .border-blue-500 { border-color: rgb(59 130 246); }
        .text-xs { font-size: 0.75rem; }
        .mt-1 { margin-top: 0.25rem; }
        .border-b { border-bottom: 1px solid #e2e8f0; }
        .pb-1 { padding-bottom: 0.25rem; }
        .hover\:shadow-sm:hover { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
        .transition-shadow { transition: box-shadow 0.15s ease-in-out; }
        .flex-1 { flex: 1; }
        .rounded { border-radius: 0.25rem; }
        .hover\:bg-red-600:hover { background-color: rgb(220 38 38); }
        .border-t { border-top: 1px solid #e2e8f0; }
        .pt-3 { padding-top: 0.75rem; }
        .px-3 { padding-left: 0.75rem; padding-right: 0.75rem; }
        .py-1 { padding-top: 0.25rem; padding-bottom: 0.25rem; }
        .space-x-2 > * + * { margin-left: 0.5rem; }
        .bg-gradient-to-br { background-image: linear-gradient(to bottom right, var(--tw-gradient-stops)); }
        .from-gray-50 { --tw-gradient-from: #f9fafb; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(249, 250, 251, 0)); }
        .to-gray-100 { --tw-gradient-to: #f3f4f6; }
        .border-l-4 { border-left-width: 4px; }
        .border-green-400 { border-color: #4ade80; }
        .border-red-400 { border-color: #f87171; }
        .px-6 { padding-left: 1.5rem; padding-right: 1.5rem; }
        .py-4 { padding-top: 1rem; padding-bottom: 1rem; }
        .mx-6 { margin-left: 1.5rem; margin-right: 1.5rem; }
        .mt-6 { margin-top: 1.5rem; }
        .rounded-r-lg { border-top-right-radius: 0.5rem; border-bottom-right-radius: 0.5rem; }
        .mr-2 { margin-right: 0.5rem; }
        .p-6 { padding: 1.5rem; }
        .text-2xl { font-size: 1.5rem; line-height: 2rem; }
        .lg\:grid-cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .gap-6 { gap: 1.5rem; }
        .md\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .gap-4 { gap: 1rem; }
        .border-yellow-300 { border-color: #fde047; }
        .bg-yellow-50 { background-color: #fefce8; }
        .border-blue-300 { border-color: #93c5fd; }
        .bg-blue-50 { background-color: #eff6ff; }
        .items-start { align-items: flex-start; }
        .mb-3 { margin-bottom: 0.75rem; }
        .text-lg { font-size: 1.125rem; line-height: 1.75rem; }
        .text-gray-600 { color: #4b5563; }
        .text-gray-500 { color: #6b7280; }
        .px-2 { padding-left: 0.5rem; padding-right: 0.5rem; }
        .bg-yellow-200 { background-color: #fef08a; }
        .text-yellow-800 { color: #854d0e; }
        .bg-blue-200 { background-color: #bfdbfe; }
        .text-blue-800 { color: #1e40af; }
        .bg-green-200 { background-color: #bbf7d0; }
        .bg-purple-500 { background-color: rgb(168 85 247); }
        .hover\:bg-purple-600:hover { background-color: rgb(147 51 234); }
        
        @media (min-width: 768px) {
            .md\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        
        @media (min-width: 1024px) {
            .lg\:col-span-2 { grid-column: span 2 / span 2; }
            .lg\:grid-cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        }
    </style>
    
    <!-- Livewire v2 Styles -->
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-100">
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-gray-800">🍕 Pizza Express</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/dashboard" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                    <a href="/admin" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Admin</a>
                </div>
            </div>
        </div>
    </nav>

    <main>
        {{ $slot }}
    </main>

    <!-- Livewire v2 Scripts -->
    @livewireScripts
</body>
</html>
