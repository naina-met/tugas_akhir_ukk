<!DOCTYPE html>
<html lang="id">
<head>
    <title>Login User - SARPRAS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-600 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md">
        <h2 class="text-2xl font-bold text-center mb-6 text-slate-800">Login Peminjam</h2>
        
        @if(session('success'))
            <div class="mb-4 text-green-600 bg-green-100 p-3 rounded text-center">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="mb-4 text-red-600 bg-red-100 p-3 rounded text-center">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('user.login.submit') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                <input type="email" name="email" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700">Masuk</button>
        </form>
        <p class="text-center mt-4 text-sm text-slate-500">
            Belum punya akun? <a href="{{ route('user.register') }}" class="text-blue-600 font-bold">Daftar</a>
        </p>
    </div>
</body>
</html>