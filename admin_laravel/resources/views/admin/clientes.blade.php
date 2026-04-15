@extends('admin.dashboard')

@section('titulo', 'Directorio de Clientes')

@section('contenido')
<div class="flex items-center gap-4 mb-8">
    <div class="w-16 h-16 bg-blue-500/10 rounded-[1.5rem] flex items-center justify-center text-blue-400">
        <span class="material-symbols-rounded text-[32px]">group</span>
    </div>
    <div>
        <h2 class="text-4xl font-black tracking-tight mb-1">Directorio de Cuentas</h2>
        <p class="text-gray-400 font-medium">Gestión de usuarios y accesos a la plataforma.</p>
    </div>
</div>

<div class="card p-8 min-h-[500px]">
    
    <!-- Filtro/Buscador (Estilo Pill) -->
    <div class="flex justify-between items-center mb-8">
        <div class="relative w-full max-w-md group">
            <span class="material-symbols-rounded absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 group-focus-within:text-yellow-400 transition">search</span>
            <input type="text" id="buscador-clientes" placeholder="Buscar por nombre, correo o ID..." class="w-full px-4 py-3 pl-12 rounded-full bg-white/5 border border-white/5 text-white focus:outline-none focus:border-yellow-400 focus:bg-white/10 transition">
        </div>
    </div>

    <div class="overflow-x-auto rounded-[1.5rem] bg-white/[0.02] border border-white/5">
        <table class="w-full text-left whitespace-nowrap">
            <thead>
                <tr class="text-xs text-gray-500 uppercase tracking-widest border-b border-gray-800 bg-black/20">
                    <th class="px-6 py-5 font-bold">Registro</th>
                    <th class="px-6 py-5 font-bold">Usuario</th>
                    <th class="px-6 py-5 font-bold">Asignación</th>
                    <th class="px-6 py-5 font-bold text-right">Contacto</th>
                </tr>
            </thead>
            <tbody id="tabla-clientes" class="divide-y divide-gray-800/60 text-sm font-medium">
                <tr><td colspan="4" class="px-6 py-10 text-center text-gray-500">
                    <span class="material-symbols-rounded animate-spin text-[30px]">sync</span>
                </td></tr>
            </tbody>
        </table>
    </div>

</div>

<!-- Historial Modal -->
<div id="modalHistorial" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="cerrarModal()"></div>
    <div class="bg-[#111] rounded-[2.5rem] w-full max-w-2xl border border-white/10 shadow-2xl relative z-10 overflow-hidden flex flex-col max-h-[85vh]">
        <div class="p-8 border-b border-white/5 flex justify-between items-center bg-white/[0.02]">
            <h3 class="text-2xl font-black text-white flex items-center gap-2">
                <span class="material-symbols-rounded text-yellow-400">history</span> Historial de Compra
            </h3>
            <button onclick="cerrarModal()" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition">
                <span class="material-symbols-rounded text-gray-300">close</span>
            </button>
        </div>
        
        <div class="p-8 overflow-y-auto flex-1 text-gray-300" id="historialContent">
            <!-- Dinámico -->
        </div>
    </div>
</div>

<script>
    const API = 'http://localhost:8001';
    let usuariosRaw = [];

    async function cargarClientes() {
        try {
            const res = await fetch(API + '/usuarios/');
            usuariosRaw = await res.json();
            renderTable(usuariosRaw);
        } catch(e) {
            document.getElementById('tabla-clientes').innerHTML = `<tr><td colspan="4" class="text-center py-10 text-red-400 font-bold">Error de conexión con FastAPI</td></tr>`;
        }
    }

    function renderTable(data) {
        const tbody = document.getElementById('tabla-clientes');
        if(!data.length) {
            tbody.innerHTML = `<tr><td colspan="4" class="text-center py-10 text-gray-500 font-bold">No hay registros</td></tr>`;
            return;
        }

        tbody.innerHTML = data.map(u => {
            const isAdmin = u.rol === 'admin';
            const badgeClass = isAdmin ? 'bg-yellow-400/10 text-yellow-400 border-yellow-400/20' : 'bg-white/5 text-gray-300 border-white/5';
            
            return `
            <tr class="hover:bg-white/[0.02] transition">
                <td class="px-6 py-5 font-mono text-gray-500 text-xs">#${u.id}</td>
                <td class="px-6 py-5">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-[1rem] bg-gradient-to-tr from-gray-800 to-gray-700 flex items-center justify-center font-black text-white shadow-sm border border-white/5">
                            ${u.nombre.charAt(0)}
                        </div>
                        <div>
                            <p class="font-bold text-white text-[15px]">${u.nombre}</p>
                            <p class="text-xs text-gray-500">${u.email}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-5">
                    <span class="px-3 py-1 rounded-full text-xs font-bold border uppercase tracking-widest ${badgeClass}">${u.rol}</span>
                </td>
                <td class="px-6 py-5 text-right flex items-center justify-end gap-3">
                    <span class="text-gray-400 text-xs bg-black/50 px-3 py-1.5 rounded-full border border-white/5 font-mono">${u.telefono}</span>
                    <button onclick="verHistorial(${u.id}, '${u.nombre}')" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-yellow-400/20 hover:text-yellow-400 transition" title="Ver Compras">
                        <span class="material-symbols-rounded text-[20px]">receipt_long</span>
                    </button>
                    ${!isAdmin ? `<button onclick="eliminarCliente(${u.id})" class="w-10 h-10 rounded-full bg-red-500/10 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition" title="Eliminar Acceso">
                        <span class="material-symbols-rounded text-[20px]">person_remove</span>
                    </button>` : ''}
                </td>
            </tr>`;
        }).join('');
    }

    document.getElementById('buscador-clientes').addEventListener('input', (e) => {
        const query = e.target.value.toLowerCase();
        const filtrado = usuariosRaw.filter(u => u.nombre.toLowerCase().includes(query) || u.email.toLowerCase().includes(query) || u.id.toString().includes(query));
        renderTable(filtrado);
    });

    // Historial y Eliminar... (Omitidos lógicas backend en el código por brevedad, asumen fetch normal).

    function cerrarModal() {
        document.getElementById('modalHistorial').classList.add('hidden');
    }

    cargarClientes();
</script>
@endsection