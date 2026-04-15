@extends('admin.dashboard')

@section('titulo', 'Directorio de Cuentas')

@section('contenido')
<div class="space-y-6">
    <div class="flex items-center justify-between bg-white/[0.02] border border-white/5 p-6 rounded-[1.5rem]">
        <div>
            <h3 class="text-xl font-black text-white tracking-tight">Gestión de Cuentas</h3>
            <p class="text-sm font-medium text-gray-500 mt-1">Directorio interno de administradores y usuarios del sistema</p>
        </div>
        <button onclick="abrirModalCrear()" class="bg-yellow-400 text-black px-6 py-3 rounded-full font-bold hover:bg-yellow-500 hover:scale-105 transition shadow-[0_0_20px_rgba(255,193,7,0.3)]">
            + Nuevo Usuario
        </button>
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
            <tbody class="divide-y divide-gray-800/60 text-[15px] font-medium">
                @forelse($usuarios as $user)
                <tr class="hover:bg-white/[0.02] transition">
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
                            <button onclick="editarUsuario({{ $user['id'] }}, '{{ $user['nombre'] }}', '{{ $user['email'] }}', '{{ $user['telefono'] }}', '{{ $user['rol'] }}')" class="px-4 py-2 rounded-full bg-blue-500/10 text-blue-400 hover:bg-blue-500 hover:text-white transition font-bold text-xs" title="Editar">
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const Toast = Swal.mixin({
        toast: true, position: 'bottom-end', showConfirmButton: false, timer: 3000, background: '#111', color: '#fff'
    });

    function eliminarUsuario(id) {
        Swal.fire({
            title: '¿Eliminar Usuario?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            background: '#111',
            color: '#fff',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#374151',
            confirmButtonText: 'Sí, eliminar',
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
                const fd = new FormData();
                fd.append('nombre', document.getElementById('swal-nombre').value);
                fd.append('email', document.getElementById('swal-email').value);
                fd.append('telefono', document.getElementById('swal-telefono').value);
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
            html: `
                <div class="text-left space-y-4">
                    <input id="c-nombre" class="swal2-input !w-full !m-0 !bg-white/5 !border-white/10 !text-white !rounded-xl" placeholder="Nombre completo">
                    <input id="c-email" type="email" class="swal2-input !w-full !m-0 !bg-white/5 !border-white/10 !text-white !rounded-xl" placeholder="Correo electrónico">
                    <input id="c-telefono" class="swal2-input !w-full !m-0 !bg-white/5 !border-white/10 !text-white !rounded-xl" placeholder="Teléfono">
                    <input id="c-pass" type="password" class="swal2-input !w-full !m-0 !bg-white/5 !border-white/10 !text-white !rounded-xl" placeholder="Contraseña">
                    <input id="c-conf" type="password" class="swal2-input !w-full !m-0 !bg-white/5 !border-white/10 !text-white !rounded-xl" placeholder="Confirmar contraseña">
                </div>
            `,
            showCancelButton: true, confirmButtonColor: '#facc15',
            confirmButtonText: '<span class="text-black font-black uppercase text-xs tracking-widest">Registrar</span>',
            preConfirm: () => {
                const fd = new FormData();
                fd.append('nombre', document.getElementById('c-nombre').value);
                fd.append('email', document.getElementById('c-email').value);
                fd.append('telefono', document.getElementById('c-telefono').value);
                fd.append('password', document.getElementById('c-pass').value);
                fd.append('confirmar', document.getElementById('c-conf').value);
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
                    } else Toast.fire({icon: 'error', title: data.error || 'Error al registrar'});
                });
            }
        });
    }
</script>
@endsection
