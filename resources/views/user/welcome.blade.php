<!DOCTYPE html>
<html lang="id">
<head>
    <title>Portal Peminjaman - SARPRAS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 flex flex-col min-h-screen">
    <nav class="p-6 flex justify-between items-center bg-white shadow">
        <h1 class="text-2xl font-bold text-blue-600">Portal Siswa & Guru</h1>
        <div>
            @auth
               <a href="{{ route('dashboard') }}" class="text-blue-600 font-bold">Ke Dashboard</a>
            @else
                <a href="{{ route('user.login') }}" class="text-gray-600 hover:text-blue-600 mr-4">Login</a>
                <a href="{{ route('user.register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Daftar</a>
            @endauth
        </div>
    </nav>
    <main class="flex-grow flex items-center justify-center text-center p-6">
        <div>
            <h2 class="text-4xl font-extrabold text-slate-800 mb-4">Pinjam Barang Lebih Mudah</h2>
            <p class="text-slate-500 mb-8">Silakan login atau daftar untuk mulai meminjam fasilitas sekolah.</p>
            <a href="{{ route('user.login') }}" class="bg-blue-600 text-white px-8 py-3 rounded-full font-bold shadow-lg hover:bg-blue-700">Mulai Meminjam</a>
        </div>
    </main>
</body>
</html>