<!DOCTYPE html>
<html class="dark" lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Administrativo | MACUIN</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: #0e0e0e;
            color: white;
            font-family: 'Inter', sans-serif;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center relative overflow-hidden">

    <!-- Background Accents -->
    <div class="absolute -z-10 top-[-10%] right-[-10%] w-[600px] h-[600px] bg-yellow-500/10 rounded-full blur-[150px]"></div>
    <div class="absolute -z-10 bottom-[-10%] left-[-10%] w-[400px] h-[400px] bg-yellow-400/5 rounded-full blur-[120px]"></div>

    <main class="relative z-10 w-full max-w-md p-6">
        
        <div class="flex flex-col items-center mb-8">
            <span class="material-symbols-outlined text-yellow-400 text-[48px] mb-2 drop-shadow-[0_0_15px_rgba(255,193,7,0.5)]">admin_panel_settings</span>
            <h1 class="text-3xl font-extrabold tracking-widest text-white flex items-center gap-2">
                MACUIN <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-yellow-200">ADMIN</span>
            </h1>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-2">Acceso a Panel de Control</p>
        </div>

        <div class="glass-panel rounded-3xl p-8 shadow-2xl relative">
            <div class="absolute -top-4 -right-4 w-20 h-20 bg-yellow-400/10 rounded-full blur-2xl"></div>

            <form id="adminLoginForm" method="POST" action="/login" novalidate onsubmit="return validarAdminLogin(event)">
                @csrf
                
                <div class="mb-5">
                    <label class="flex items-center gap-2 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
                        <span class="material-symbols-outlined text-[16px]">badge</span> Rol Administrativo
                    </label>
                    <select name="rol" class="w-full bg-[#111] border border-gray-700 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-yellow-400 focus:ring-1 focus:ring-yellow-400 transition cursor-not-allowed opacity-80">
                        <option>Administrador Global</option>
                    </select>
                </div>

                <div class="mb-5">
                    <label class="flex items-center gap-2 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
                        <span class="material-symbols-outlined text-[16px]">mail</span> Correo de Empleado
                    </label>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 group-focus-within:text-yellow-400 transition">alternate_email</span>
                        <input type="email" name="email" id="email" placeholder="admin@macuin.com" class="w-full bg-[#111] border border-gray-700 text-white rounded-xl pl-12 pr-4 py-3 focus:outline-none focus:border-yellow-400 focus:ring-1 focus:ring-yellow-400 transition">
                    </div>
                </div>

                <div class="mb-8">
                    <label class="flex items-center gap-2 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
                        <span class="material-symbols-outlined text-[16px]">key</span> Contraseña Maestra
                    </label>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 group-focus-within:text-yellow-400 transition">lock</span>
                        <input type="password" name="password" id="password" placeholder="••••••••" class="w-full bg-[#111] border border-gray-700 text-white rounded-xl pl-12 pr-12 py-3 focus:outline-none focus:border-yellow-400 focus:ring-1 focus:ring-yellow-400 transition">
                        <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-white transition" onclick="togglePassword()">
                            <span id="eye-icon" class="material-symbols-outlined text-[18px]">visibility_off</span>
                        </button>
                    </div>
                </div>

                <button type="submit" id="btnSubmit" class="w-full bg-yellow-400 text-black py-3.5 rounded-xl font-bold text-base hover:bg-yellow-500 focus:outline-none transition shadow-[0_0_15px_rgba(255,193,7,0.3)] hover:shadow-[0_0_25px_rgba(255,193,7,0.5)] flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">login</span> ACCEDER AL SISTEMA
                </button>
            </form>
        </div>
        
        <p class="text-center font-mono text-[10px] text-gray-600 mt-6 tracking-widest uppercase">
            Macuin Systems v2.4.1<br>Plataforma Restringida
        </p>

    </main>

    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: '#1a1a1a',
            color: '#fff',
            iconColor: '#facc15'
        });

        // Controller Error feedback
        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: 'Acceso Denegado',
                text: '{{ session("error") }}',
                iconColor: '#ef4444'
            });
        @endif

        function togglePassword() {
            const pwd = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            if(pwd.type === 'password'){
                pwd.type = 'text';
                icon.textContent = 'visibility';
            } else {
                pwd.type = 'password';
                icon.textContent = 'visibility_off';
            }
        }

        function validarAdminLogin(e) {
            e.preventDefault();
            const email = document.getElementById('email');
            const pwd = document.getElementById('password');
            
            email.classList.remove('border-red-500');
            pwd.classList.remove('border-red-500');

            if(!email.value.trim() || !email.value.includes('@')) {
                email.classList.add('border-red-500');
                Toast.fire({icon: 'warning', title: 'Correo de empleado requerido.'});
                return false;
            }

            if(!pwd.value) {
                pwd.classList.add('border-red-500');
                Toast.fire({icon: 'warning', title: 'Contraseña requerida.'});
                return false;
            }

            const btn = document.getElementById('btnSubmit');
            btn.innerHTML = '<span class="material-symbols-outlined animate-spin">refresh</span> AUTENTICANDO...';
            btn.disabled = true;
            btn.classList.add('opacity-80', 'cursor-wait');

            document.getElementById('adminLoginForm').submit();
        }
    </script>
</body>
</html>