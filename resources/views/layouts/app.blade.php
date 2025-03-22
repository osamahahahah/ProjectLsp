<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Room</title>
    @vite('resources/css/app.css') <!-- Jika menggunakan Vite -->
</head>
<body>
    <div class="min-h-screen">
        <!-- Navigasi dan header -->
        <nav class="bg-gray-800 p-4">
            <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
                <div class="relative flex items-center justify-between h-16">
                    <a href="{{ url('/') }}" class="text-white text-xl">Hotel Booking</a>
                </div>
            </div>
        </nav>

        <!-- Konten utama halaman -->
        <main>
            @yield('content') <!-- Konten dari halaman lain akan ditampilkan di sini -->
        </main>
    </div>
</body>
</html>
