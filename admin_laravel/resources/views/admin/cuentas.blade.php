@extends('admin.dashboard')

@section('titulo', 'Directorio de Cuentas')

@section('contenido')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white/[0.02] border border-white/5 p-6 rounded-[1.5rem]">
        <div>
            <h3 class="text-xl font-black text-white tracking-tight">Gestión de Cuentas</h3>
            <p class="text-sm font-medium text-gray-500 mt-1">Directorio interno de administradores y usuarios del sistema</p>
        </div>
        <div class="flex items-center gap-3">
            <!-- Search Bar -->
            <div class="flex items-center gap-2 bg-black/30 border border-white/10 rounded-full px-4 py-2.5 focus-within:border-yellow-400/50 transition">
                <span class="material-symbols-rounded text-gray-500 text-[18px]">search</span>
                <input type="text" id="search-usuarios" placeholder="Buscar usuario..." class="bg-transparent text-sm text-white focus:outline-none w-32 lg:w-44 font-medium" oninput="filtrarUsuarios()">
            </div>
            <!-- Filter by role -->
            <div class="relative">
                <button onclick="document.getElementById('role-filter-dd').classList.toggle('hidden')" class="flex items-center gap-2 bg-black/30 border border-white/10 rounded-full px-4 py-2.5 hover:border-yellow-400/30 transition text-sm font-bold text-gray-300">
                    <span class="material-symbols-rounded text-[18px] text-gray-500">filter_list</span>
                    <span id="role-filter-label">Todos</span>
                    <span class="material-symbols-rounded text-[14px] text-gray-500">expand_more</span>
                </button>
                <div id="role-filter-dd" class="hidden absolute top-full right-0 mt-2 w-40 bg-[#151515] border border-white/10 rounded-2xl shadow-2xl z-50 overflow-hidden">
                    <button onclick="setRoleFilter('todos')" class="w-full px-4 py-3 text-left text-sm font-bold text-gray-300 hover:bg-white/5 hover:text-white transition">Todos</button>
                    <button onclick="setRoleFilter('admin')" class="w-full px-4 py-3 text-left text-sm font-bold text-gray-300 hover:bg-white/5 hover:text-white transition flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-purple-400"></span> Admins
                    </button>
                    <button onclick="setRoleFilter('cliente')" class="w-full px-4 py-3 text-left text-sm font-bold text-gray-300 hover:bg-white/5 hover:text-white transition flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span> Clientes
                    </button>
                </div>
            </div>
            <button onclick="abrirModalCrear()" class="bg-yellow-400 text-black px-6 py-3 rounded-full font-bold hover:bg-yellow-500 hover:scale-105 transition shadow-[0_0_20px_rgba(255,193,7,0.3)] flex items-center gap-2">
                <span class="material-symbols-rounded text-[20px]">person_add</span>
                <span class="hidden sm:inline">Nuevo Usuario</span>
            </button>
        </div>
    </div>

    <!-- Stats Mini Cards -->
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white/[0.02] border border-white/5 rounded-2xl p-4 flex items-center gap-4">
            <div class="w-10 h-10 bg-blue-500/10 rounded-xl flex items-center justify-center text-blue-400">
                <span class="material-symbols-rounded text-[20px]">group</span>
            </div>
            <div>
                <p class="text-[10px] text-gray-500 uppercase font-black tracking-widest">Total</p>
                <p class="text-xl font-black text-white" id="stat-total">{{ count($usuarios) }}</p>
            </div>
        </div>
        <div class="bg-white/[0.02] border border-white/5 rounded-2xl p-4 flex items-center gap-4">
            <div class="w-10 h-10 bg-purple-500/10 rounded-xl flex items-center justify-center text-purple-400">
                <span class="material-symbols-rounded text-[20px]">shield_person</span>
            </div>
            <div>
                <p class="text-[10px] text-gray-500 uppercase font-black tracking-widest">Admins</p>
                <p class="text-xl font-black text-white" id="stat-admins">{{ collect($usuarios)->where('rol', 'admin')->count() }}</p>
            </div>
        </div>
        <div class="bg-white/[0.02] border border-white/5 rounded-2xl p-4 flex items-center gap-4">
            <div class="w-10 h-10 bg-emerald-500/10 rounded-xl flex items-center justify-center text-emerald-400">
                <span class="material-symbols-rounded text-[20px]">person</span>
            </div>
            <div>
                <p class="text-[10px] text-gray-500 uppercase font-black tracking-widest">Clientes</p>
                <p class="text-xl font-black text-white" id="stat-clients">{{ collect($usuarios)->where('rol', '!=', 'admin')->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="flex-1 overflow-x-auto rounded-[1.5rem] bg-white/[0.02] border border-white/5">
        <table class="w-full text-left whitespace-nowrap">
            <thead>
                <tr class="text-xs text-gray-400 uppercase tracking-widest border-b border-gray-800 bg-black/20">
                    <th class="px-6 py-5 font-bold">Usuario</th>
                    <th class="px-6 py-5 font-bold">Rol</th>
                    <th class="px-6 py-5 font-bold">Contacto</th>
                    <th class="px-6 py-5 font-bold">Título</th>
                    <th class="px-6 py-5 font-bold text-right">Acciones</th>
                </tr>
            </thead>
            <tbody id="users-table-body" class="divide-y divide-gray-800/60 text-[15px] font-medium">
                @forelse($usuarios as $user)
                <tr class="hover:bg-white/[0.02] transition user-row" data-nombre="{{ strtolower($user['nombre']) }}" data-email="{{ strtolower($user['email']) }}" data-rol="{{ $user['rol'] }}">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center border-2 {{ $user['rol'] == 'admin' ? 'border-purple-400/50' : 'border-yellow-400/50' }}">
                                <span class="material-symbols-rounded text-gray-500">{{ $user['rol'] == 'admin' ? 'admin_panel_settings' : 'person' }}</span>
                            </div>
                            <div>
                                <p class="font-bold text-white leading-tight">{{ $user['nombre'] }}</p>
                                <p class="text-[12px] text-gray-500">{{ $user['email'] }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($user['rol'] == 'admin')
                        <span class="px-3 py-1 bg-purple-500/10 text-purple-400 border border-purple-500/20 rounded-full text-xs font-bold inline-flex items-center gap-1.5">
                            <span class="material-symbols-rounded text-[14px]">shield_person</span> Admin
                        </span>
                        @else
                        <span class="px-3 py-1 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-full text-xs font-bold inline-flex items-center gap-1.5">
                            <span class="material-symbols-rounded text-[14px]">person</span> Usuario
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-400 text-sm">
                        {{ $user['telefono'] ?? 'Sin teléfono' }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-white/5 border border-white/5 rounded-full text-xs font-bold text-gray-300">
                            {{ $user['rol'] == 'admin' ? 'Administrador Sistema' : 'Usuario Regular' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <button onclick="cambiarRolRapido({{ $user['id'] }}, '{{ $user['nombre'] }}', '{{ $user['rol'] }}')" class="px-4 py-2 rounded-full {{ $user['rol'] == 'admin' ? 'bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500 hover:text-white' : 'bg-purple-500/10 text-purple-400 hover:bg-purple-500 hover:text-white' }} transition font-bold text-xs" title="{{ $user['rol'] == 'admin' ? 'Cambiar a Cliente' : 'Cambiar a Admin' }}">
                                {{ $user['rol'] == 'admin' ? '→ Cliente' : '→ Admin' }}
                            </button>
                            <button onclick="editarUsuario({{ $user['id'] }}, '{{ addslashes($user['nombre']) }}', '{{ addslashes($user['email']) }}', '{{ addslashes($user['telefono'] ?? '') }}', '{{ $user['rol'] }}')" class="px-4 py-2 rounded-full bg-blue-500/10 text-blue-400 hover:bg-blue-500 hover:text-white transition font-bold text-xs" title="Editar">
                                Editar
                            </button>
                            <button onclick="eliminarUsuario({{ $user['id'] }})" class="px-4 py-2 rounded-full bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition font-bold text-xs" title="Eliminar">
                                Eliminar
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-10 text-center text-gray-500 font-medium tracking-wide">
                        No hay usuarios registrados
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    let currentRoleFilter = 'todos';

    // Close dropdowns on outside click
    document.addEventListener('click', (e) => {
        const dd = document.getElementById('role-filter-dd');
        if (dd && !dd.parentElement.contains(e.target)) dd.classList.add('hidden');
    });

    // Client-side search
    function filtrarUsuarios() {
        const query = document.getElementById('search-usuarios').value.toLowerCase().trim();
        document.querySelectorAll('.user-row').forEach(row => {
            const nombre = row.dataset.nombre || '';
            const email = row.dataset.email || '';
            const matchSearch = !query || nombre.includes(query) || email.includes(query);
            const matchRole = currentRoleFilter === 'todos' || row.dataset.rol === currentRoleFilter;
            row.style.display = (matchSearch && matchRole) ? '' : 'none';
        });
    }

    function setRoleFilter(role) {
        currentRoleFilter = role;
        const labels = { 'todos': 'Todos', 'admin': 'Admins', 'cliente': 'Clientes' };
        document.getElementById('role-filter-label').textContent = labels[role];
        document.getElementById('role-filter-dd').classList.add('hidden');
        filtrarUsuarios();
    }

    // Quick Role Change (single-click with confirmation)
    async function cambiarRolRapido(userId, nombre, rolActual) {
        const nuevoRol = rolActual === 'admin' ? 'cliente' : 'admin';
        const rolLabel = nuevoRol === 'admin' ? 'Administrador' : 'Cliente';
        const iconColor = nuevoRol === 'admin' ? 'text-purple-400' : 'text-emerald-400';
        const icon = nuevoRol === 'admin' ? 'shield_person' : 'person';

        const result = await Swal.fire({
            html: `
                <div class="text-center">
                    <div class="w-20 h-20 rounded-full ${nuevoRol === 'admin' ? 'bg-purple-500/10 border-purple-500/20' : 'bg-emerald-500/10 border-emerald-500/20'} flex items-center justify-center mx-auto mb-6 border">
                        <span class="material-symbols-rounded text-4xl ${iconColor}">${icon}</span>
                    </div>
                    <h3 class="text-xl font-black text-white mb-2">Cambiar Rol</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">¿Convertir a <span class="font-bold text-white">${nombre}</span> en <span class="font-bold ${iconColor}">${rolLabel}</span>?</p>
                </div>
            `,
            background: '#111', color: '#fff', width: 380,
            customClass: { popup: 'rounded-[2rem] border border-white/10' },
            showCancelButton: true,
            confirmButtonColor: '#facc15',
            cancelButtonColor: '#374151',
            confirmButtonText: '<span class="text-black font-bold">Sí, cambiar</span>',
            cancelButtonText: 'Cancelar'
        });

        if (!result.isConfirmed) return;

        try {
            // First fetch current user data
            const uRes = await fetch(API + '/usuarios/' + userId, {
                headers: JWT_TOKEN ? { 'Authorization': 'Bearer ' + JWT_TOKEN } : {}
            });
            const userInfo = await uRes.json();

            const formData = new URLSearchParams();
            formData.append('nombre', userInfo.nombre);
            formData.append('email', userInfo.email);
            formData.append('telefono', userInfo.telefono || 'N/A');
            formData.append('rol', nuevoRol);

            const res = await fetch(`${API}/usuarios/${userId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    ...(JWT_TOKEN ? { 'Authorization': 'Bearer ' + JWT_TOKEN } : {})
                },
                body: formData.toString()
            });

            if (res.ok) {
                Toast.fire({ icon: 'success', title: `${nombre} ahora es ${rolLabel}` });
                setTimeout(() => window.location.reload(), 800);
            } else {
                throw new Error('API error');
            }
        } catch (e) {
            Swal.fire({
                background: '#111', color: '#fff',
                customClass: { popup: 'rounded-[2rem] border border-white/10' },
                icon: 'error', title: 'Error', text: 'No se pudo actualizar el rol.'
            });
        }
    }

    function eliminarUsuario(id) {
        Swal.fire({
            html: `
                <div class="text-center">
                    <div class="w-20 h-20 rounded-full bg-red-500/10 border border-red-500/20 flex items-center justify-center mx-auto mb-6">
                        <span class="material-symbols-rounded text-4xl text-red-500">person_remove</span>
                    </div>
                    <h3 class="text-xl font-black text-white mb-2">¿Eliminar Usuario?</h3>
                    <p class="text-gray-400 text-sm">Esta acción no se puede deshacer. Se eliminarán todos los datos asociados.</p>
                </div>
            `,
            background: '#111', color: '#fff', width: 380,
            customClass: { popup: 'rounded-[2rem] border border-white/10' },
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#374151',
            confirmButtonText: '<span class="text-white font-bold">Sí, eliminar</span>',
            cancelButtonText: 'Cancelar'
        }).then(async (result) => {
            if (result.isConfirmed) {
                const res = await fetch(API + "/usuarios/" + id, {
                    method: 'DELETE',
                    headers: { 'Authorization': 'Bearer ' + JWT_TOKEN }
                });
                if (res.ok) {
                    Toast.fire({icon: 'success', title: 'Usuario eliminado'});
                    setTimeout(() => window.location.reload(), 1000);
                } else Toast.fire({icon: 'error', title: 'No se pudo eliminar'});
            }
        });
    }

    function editarUsuario(id, nombre, email, telefono, rol) {
        Swal.fire({
            title: 'Configuración de Cuenta',
            background: '#111', color: '#fff',
            customClass: { popup: 'rounded-[2rem] border border-white/10' },
            html: `
                <div class="text-left space-y-4">
                    <div><label class="text-[10px] text-gray-500 uppercase font-black tracking-widest block mb-2">Nombre Completo</label>
                    <input id="swal-nombre" class="swal2-input !w-full !m-0 !bg-white/5 !border-white/10 !text-white !rounded-xl" value="${nombre}"></div>
                    <div><label class="text-[10px] text-gray-500 uppercase font-black tracking-widest block mb-2">Correo Electrónico</label>
                    <input id="swal-email" class="swal2-input !w-full !m-0 !bg-white/5 !border-white/10 !text-white !rounded-xl" value="${email}"></div>
                    <div><label class="text-[10px] text-gray-500 uppercase font-black tracking-widest block mb-1">Teléfono</label>
                    <input id="swal-telefono" class="swal2-input !w-full !m-0 !bg-white/5 !border-white/10 !text-white !rounded-xl" value="${telefono === 'null' || !telefono ? '' : telefono}"></div>
                    <div><label class="text-[10px] text-gray-500 uppercase font-black tracking-widest block mb-3">Privilegios</label>
                    <input type="hidden" id="swal-rol" value="${rol}">
                    <div class="grid grid-cols-2 gap-3">
                        <div onclick="document.getElementById('swal-rol').value='admin'; document.querySelectorAll('.rol-card').forEach(c=>c.classList.remove('border-yellow-400','bg-yellow-400/10')); this.classList.add('border-yellow-400','bg-yellow-400/10');" 
                             class="rol-card p-4 rounded-2xl border ${rol==='admin'?'border-yellow-400 bg-yellow-400/10':'border-white/5 bg-white/5'} cursor-pointer hover:border-yellow-400/50 transition flex flex-col items-center gap-2">
                            <span class="material-symbols-rounded text-2xl ${rol==='admin'?'text-yellow-400':'text-gray-500'}">shield_person</span>
                            <p class="text-[11px] font-black uppercase text-white">Admin</p>
                        </div>
                        <div onclick="document.getElementById('swal-rol').value='cliente'; document.querySelectorAll('.rol-card').forEach(c=>c.classList.remove('border-yellow-400','bg-yellow-400/10')); this.classList.add('border-yellow-400','bg-yellow-400/10');" 
                             class="rol-card p-4 rounded-2xl border ${rol!=='admin'?'border-yellow-400 bg-yellow-400/10':'border-white/5 bg-white/5'} cursor-pointer hover:border-yellow-400/50 transition flex flex-col items-center gap-2">
                            <span class="material-symbols-rounded text-2xl ${rol!=='admin'?'text-yellow-400':'text-gray-500'}">person</span>
                            <p class="text-[11px] font-black uppercase text-white">Cliente</p>
                        </div>
                    </div>
                    </div>
                </div>
            `,
            showCancelButton: true, confirmButtonColor: '#facc15', cancelButtonColor: '#374151',
            confirmButtonText: '<span class="text-black font-black uppercase text-xs tracking-widest">Guardar Cambios</span>',
            preConfirm: () => {
                const nombre = document.getElementById('swal-nombre').value.trim();
                const email = document.getElementById('swal-email').value.trim();
                const telefono = document.getElementById('swal-telefono').value.trim();
                
                // Validation
                if (!nombre || nombre.length < 2) {
                    Swal.showValidationMessage('El nombre debe tener al menos 2 caracteres');
                    return false;
                }
                if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    Swal.showValidationMessage('Ingresa un correo electrónico válido');
                    return false;
                }

                const fd = new FormData();
                fd.append('nombre', nombre);
                fd.append('email', email);
                fd.append('telefono', telefono || 'N/A');
                fd.append('rol', document.getElementById('swal-rol').value);
                return fd;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(API + "/usuarios/" + id, {
                    method: 'PUT',
                    headers: { 'Authorization': 'Bearer ' + JWT_TOKEN },
                    body: result.value
                }).then(res => {
                    if (res.ok) {
                        Toast.fire({icon: 'success', title: 'Usuario actualizado'});
                        setTimeout(() => window.location.reload(), 1000);
                    } else Toast.fire({icon: 'error', title: 'Error al actualizar'});
                });
            }
        });
    }

    function abrirModalCrear() {
        Swal.fire({
            title: 'Nueva Cuenta',
            background: '#111', color: '#fff',
            customClass: { popup: 'rounded-[2rem] border border-white/10' },
            html: `
                <div class="text-left space-y-4">
                    <div>
                        <label class="text-[10px] text-gray-500 uppercase font-black tracking-widest block mb-2">Nombre completo <span class="text-red-500">*</span></label>
                        <input id="c-nombre" class="swal2-input !w-full !m-0 !bg-white/5 !border-white/10 !text-white !rounded-xl" placeholder="Ej: Juan Pérez">
                    </div>
                    <div>
                        <label class="text-[10px] text-gray-500 uppercase font-black tracking-widest block mb-2">Correo electrónico <span class="text-red-500">*</span></label>
                        <input id="c-email" type="email" class="swal2-input !w-full !m-0 !bg-white/5 !border-white/10 !text-white !rounded-xl" placeholder="correo@ejemplo.com">
                    </div>
                    <div>
                        <label class="text-[10px] text-gray-500 uppercase font-black tracking-widest block mb-2">Teléfono</label>
                        <input id="c-telefono" class="swal2-input !w-full !m-0 !bg-white/5 !border-white/10 !text-white !rounded-xl" placeholder="10 dígitos">
                    </div>
                    <div>
                        <label class="text-[10px] text-gray-500 uppercase font-black tracking-widest block mb-2">Contraseña <span class="text-red-500">*</span></label>
                        <input id="c-pass" type="password" class="swal2-input !w-full !m-0 !bg-white/5 !border-white/10 !text-white !rounded-xl" placeholder="Mínimo 6 caracteres">
                    </div>
                    <div>
                        <label class="text-[10px] text-gray-500 uppercase font-black tracking-widest block mb-2">Confirmar contraseña <span class="text-red-500">*</span></label>
                        <input id="c-conf" type="password" class="swal2-input !w-full !m-0 !bg-white/5 !border-white/10 !text-white !rounded-xl" placeholder="Repetir contraseña">
                    </div>
                    <p class="text-[10px] text-gray-600 mt-2">Los campos marcados con <span class="text-red-500">*</span> son obligatorios</p>
                </div>
            `,
            showCancelButton: true, 
            confirmButtonColor: '#facc15',
            cancelButtonColor: '#374151',
            confirmButtonText: '<span class="text-black font-black uppercase text-xs tracking-widest">Registrar</span>',
            cancelButtonText: '<span class="text-gray-400 font-bold">Cancel</span>',
            preConfirm: () => {
                const nombre = document.getElementById('c-nombre').value.trim();
                const email = document.getElementById('c-email').value.trim();
                const telefono = document.getElementById('c-telefono').value.trim();
                const password = document.getElementById('c-pass').value;
                const confirmar = document.getElementById('c-conf').value;

                // ========== VALIDATIONS ==========
                if (!nombre || nombre.length < 2) {
                    Swal.showValidationMessage('El nombre debe tener al menos 2 caracteres');
                    return false;
                }

                if (!email) {
                    Swal.showValidationMessage('El correo electrónico es obligatorio');
                    return false;
                }

                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    Swal.showValidationMessage('Ingresa un correo electrónico válido');
                    return false;
                }

                if (telefono && !/^\d{10}$/.test(telefono.replace(/\D/g, ''))) {
                    Swal.showValidationMessage('El teléfono debe tener 10 dígitos');
                    return false;
                }

                if (!password) {
                    Swal.showValidationMessage('La contraseña es obligatoria');
                    return false;
                }

                if (password.length < 6) {
                    Swal.showValidationMessage('La contraseña debe tener al menos 6 caracteres');
                    return false;
                }

                if (password !== confirmar) {
                    Swal.showValidationMessage('Las contraseñas no coinciden');
                    return false;
                }

                const fd = new FormData();
                fd.append('nombre', nombre);
                fd.append('email', email);
                fd.append('telefono', telefono || 'N/A');
                fd.append('password', password);
                fd.append('confirmar', confirmar);
                return fd;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(API + "/usuarios/registro", {
                    method: 'POST',
                    headers: { 'Authorization': 'Bearer ' + JWT_TOKEN },
                    body: result.value
                }).then(res => res.json()).then(data => {
                    if (data.msg) {
                        Toast.fire({icon: 'success', title: data.msg});
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        Swal.fire({
                            background: '#111', color: '#fff',
                            customClass: { popup: 'rounded-[2rem] border border-white/10' },
                            icon: 'error',
                            title: 'Error al registrar',
                            text: data.error || 'Verifica los datos e intenta de nuevo.'
                        });
                    }
                }).catch(() => {
                    Toast.fire({icon: 'error', title: 'Error de conexión con el servidor'});
                });
            }
        });
    }
</script>
@endsection
