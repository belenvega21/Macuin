<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | MACUIN</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />

    <style>
        body { background-color: #080808; color: #f8fafc; font-family: 'Inter', sans-serif; }
        .pulpos-card {
            background-color: #111111;
            border-radius: 2.5rem;
            border: 1px solid rgba(255, 255, 255, 0.04);
            box-shadow: 0 30px 60px rgba(0,0,0,0.3);
        }
        .macuin-input {
            background-color: #0e0e0e;
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 1.5rem;
            color: white;
            transition: all 0.3s ease;
        }
        .macuin-input:focus { border-color: #3b82f6; box-shadow: 0 0 0 2px rgba(59,130,246,0.2); outline: none; }
    </style>
</head>
<body class="flex flex-col min-h-screen relative items-center justify-center p-6 selection:bg-blue-500 selection:text-white">

    @auth
        <script>window.location.href = "{{ url('/dashboard') }}";</script>
    @endauth

    <!-- Background Decoration matching Admin Blue Theme -->
    <div class="fixed -z-10 top-[-20%] left-[-10%] w-[600px] h-[600px] bg-blue-500/10 rounded-full blur-[150px] pointer-events-none"></div>

    <div class="w-full max-w-md">
        <div class="text-center mb-10">
            <div class="inline-block mb-6 relative">
                <div class="w-20 h-20 bg-yellow-400/10 rounded-[2rem] mx-auto flex items-center justify-center shadow-[0_0_30px_rgba(255,193,7,0.3)] mb-6 overflow-hidden p-2">
                    <img src="/images/nuevologo.png" class="w-full h-full object-contain" alt="MACUIN">
                </div>
            </div>
            
            <h1 class="text-4xl font-black tracking-tight mb-2">MACUIN</h1>
            <p class="text-gray-400 font-medium">Panel de gestión para empleados</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="pulpos-card p-8 sm:p-10 mb-8" novalidate>
            @csrf

            <!-- Rol -->
            <div class="mb-5 relative group">
                <span class="material-symbols-rounded absolute left-5 top-1/2 -translate-y-1/2 text-gray-500 group-focus-within:text-blue-500 transition">badge</span>
                <select name="rol" class="macuin-input w-full py-4 pl-14 pr-6 appearance-none cursor-pointer">
                    <option value="admin">Administrador General</option>
                    <option value="ventas">Personal de Ventas</option>
                    <option value="almacen">Encargado de Almacén</option>
                </select>
                <span class="material-symbols-rounded absolute right-5 top-1/2 -translate-y-1/2 text-gray-600 pointer-events-none">expand_more</span>
            </div>

            <!-- Email -->
            <div class="mb-5 relative group">
                <span class="material-symbols-rounded absolute left-5 top-1/2 -translate-y-1/2 text-gray-500 group-focus-within:text-blue-500 transition">email</span>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="tu.nombre@macuin.com" class="macuin-input w-full py-4 pl-14 pr-6" required>
                @error('email')
                <div class="text-red-500 text-[11px] font-bold mt-2 ml-4 flex items-center gap-1">
                    <span class="material-symbols-rounded text-[14px]">error</span> {{ $message }}
                </div>
                @enderror
            </div>

            <!-- Contraseña -->
            <div class="mb-8 relative group">
                <span class="material-symbols-rounded absolute left-5 top-1/2 -translate-y-1/2 text-gray-500 group-focus-within:text-blue-500 transition">lock</span>
                <input type="password" name="password" placeholder="Tu contraseña segura" class="macuin-input w-full py-4 pl-14 pr-6" required>
                @error('password')
                <div class="text-red-500 text-[11px] font-bold mt-2 ml-4 flex items-center gap-1">
                    <span class="material-symbols-rounded text-[14px]">error</span> {{ $message }}
                </div>
                @enderror
            </div>

            <!-- Botón Login -->
            <button type="submit" class="w-full bg-blue-600 text-white font-black py-4 rounded-full hover:bg-blue-500 hover:scale-[1.02] transition duration-300 shadow-[0_0_20px_rgba(59,130,246,0.4)] flex items-center justify-center gap-2">
                Ingresar al Panel <span class="material-symbols-rounded">arrow_forward</span>
            </button>
        </form>

        <p class="text-center text-gray-600 text-xs font-medium">Solo personal autorizado. <br>© 2026 MACUIN STORE.</p>
    </div>

</body>
</html>