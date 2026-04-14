<!DOCTYPE html>
<html class="dark" lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Clientes | MACUIN</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;700&family=Noto+Sans:wght@400;500;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
darkMode:"class",
theme:{extend:{
colors:{primary:"#BE0000",dashboard:"#1A1A1A",yellow:"#FFD700"},
fontFamily:{display:["Space Grotesk","sans-serif"],body:["Noto Sans","sans-serif"]}
}}
}
</script>
<style>
.glass-panel{background:rgba(20,20,20,0.85);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.08);}
</style>
</head>

<body class="bg-dashboard text-white font-body min-h-screen">
<div class="flex h-screen">

<!-- SIDEBAR -->
<aside class="w-64 bg-black border-r border-white/10 flex flex-col">
<div class="p-6 border-b border-white/10">
<h1 class="font-display font-bold text-lg">MACUIN</h1>
<p class="text-xs text-gray-400 uppercase">Autopartes</p>
</div>
<nav class="flex-1 p-4 space-y-2">
<a href="/admin" class="flex items-center gap-3 p-3 text-gray-400 hover:bg-white/5 rounded transition">
<span class="material-symbols-outlined">dashboard</span> Dashboard
</a>
<a href="/inventario" class="flex items-center gap-3 p-3 text-gray-400 hover:bg-white/5 rounded transition">
<span class="material-symbols-outlined">inventory_2</span> Inventario
</a>
<a href="/pedidos" class="flex items-center gap-3 p-3 text-gray-400 hover:bg-white/5 rounded transition">
<span class="material-symbols-outlined">shopping_cart</span> Pedidos
</a>
<a href="/clientes" class="flex items-center gap-3 p-3 bg-primary/10 border border-primary/40 rounded text-white">
<span class="material-symbols-outlined">group</span> Clientes
</a>
<a href="/reportes" class="flex items-center gap-3 p-3 text-gray-400 hover:bg-white/5 rounded transition">
<span class="material-symbols-outlined">assessment</span> Reportes
</a>
</nav>
<div class="p-4 border-t border-white/10">
<a href="/logout" class="flex items-center gap-3 p-3 text-red-400 hover:bg-red-900/10 rounded transition">
<span class="material-symbols-outlined">logout</span> Cerrar sesión
</a>
</div>
</aside>

<!-- CONTENIDO -->
<main class="flex-1 p-8 overflow-y-auto">

<h2 class="text-2xl font-display font-bold uppercase mb-6">Directorio / Clientes</h2>

<div class="grid grid-cols-12 gap-6 h-[80vh]">

<!-- LISTA CLIENTES -->
<div class="col-span-4 glass-panel rounded-xl p-4 overflow-y-auto">
<h3 class="text-lg font-bold mb-4">Clientes Registrados</h3>

<input id="searchCliente" placeholder="Buscar cliente..." onkeyup="buscarCliente()"
class="w-full mb-4 bg-black/40 border border-white/10 rounded p-2 text-sm">

<div id="listaClientes" class="space-y-3">
@forelse($clientes as $i => $cliente)
<div class="cliente-item p-3 rounded cursor-pointer transition hover:bg-white/10 {{ $i === 0 ? 'bg-primary/20 border border-primary' : 'bg-white/5' }}"
     data-id="{{ $cliente['id'] }}"
     data-nombre="{{ $cliente['nombre'] }}"
     data-email="{{ $cliente['email'] }}"
     data-telefono="{{ $cliente['telefono'] ?? 'No disponible' }}"
     data-rol="{{ $cliente['rol'] ?? 'cliente' }}"
     data-search="{{ strtolower($cliente['nombre'] . ' ' . $cliente['email']) }}"
     onclick="seleccionarCliente(this)">
<p class="font-bold">{{ $cliente['nombre'] }}</p>
<p class="text-xs text-gray-400">ID: CLI-{{ str_pad($cliente['id'], 4, '0', STR_PAD_LEFT) }}</p>
</div>
@empty
<p class="text-gray-500 text-sm">No hay clientes registrados.</p>
@endforelse
</div>
</div>

<!-- DETALLE CLIENTE -->
<div class="col-span-8 glass-panel rounded-xl p-6" id="detallePanel">

<div class="flex justify-between mb-6">
<div>
<h3 class="text-xl font-bold" id="detNombre">{{ $clientes[0]['nombre'] ?? 'Sin clientes' }}</h3>
<p class="text-sm text-gray-400" id="detTelefono">{{ $clientes[0]['telefono'] ?? '' }}</p>
<p class="text-sm text-gray-400" id="detEmail">{{ $clientes[0]['email'] ?? '' }}</p>
</div>
<span class="bg-green-500/20 text-green-400 px-3 py-1 text-xs rounded h-fit" id="detRol">
{{ ucfirst($clientes[0]['rol'] ?? 'cliente') }}
</span>
</div>

<div class="grid grid-cols-2 gap-4 mb-6">
<div class="bg-white/5 p-4 rounded">
<p class="text-xs text-gray-400">Total pedidos</p>
<p class="text-xl font-bold text-yellow" id="detTotalPedidos">0</p>
</div>
<div class="bg-white/5 p-4 rounded">
<p class="text-xs text-gray-400">Gasto total estimado</p>
<p class="text-xl font-bold text-green-400" id="detGastoTotal">$0.00</p>
</div>
</div>

<h4 class="font-bold mb-3">Historial de Pedidos</h4>

<table class="w-full text-sm">
<thead class="text-gray-400 text-xs">
<tr><th class="text-left py-2">Pedido</th><th>Fecha</th><th>Monto</th><th>Estatus</th></tr>
</thead>
<tbody id="detHistorial" class="divide-y divide-white/5">
<tr><td colspan="4" class="py-4 text-center text-gray-500">Selecciona un cliente</td></tr>
</tbody>
</table>

</div>
</div>
</main>
</div>

<script>
const pedidosData = @json($pedidos);
const productosData = @json($productos);
const clientesData = @json($clientes);
const prodMap = {};
productosData.forEach(p => prodMap[p.id] = p);

function seleccionarCliente(el) {
    // UI active state
    document.querySelectorAll('.cliente-item').forEach(c => {
        c.classList.remove('bg-primary/20','border','border-primary');
        c.classList.add('bg-white/5');
    });
    el.classList.remove('bg-white/5');
    el.classList.add('bg-primary/20','border','border-primary');

    const id = parseInt(el.dataset.id);
    document.getElementById('detNombre').textContent = el.dataset.nombre;
    document.getElementById('detEmail').textContent = el.dataset.email;
    document.getElementById('detTelefono').textContent = el.dataset.telefono;
    document.getElementById('detRol').textContent = el.dataset.rol.charAt(0).toUpperCase() + el.dataset.rol.slice(1);

    // Filtrar pedidos de este cliente
    const clientePedidos = pedidosData.filter(p => p.usuario_id === id);
    document.getElementById('detTotalPedidos').textContent = clientePedidos.length;

    let gastoTotal = 0;
    let html = '';
    if (clientePedidos.length === 0) {
        html = '<tr><td colspan="4" class="py-4 text-center text-gray-500">Sin pedidos</td></tr>';
    } else {
        clientePedidos.forEach(ped => {
            let total = 0;
            (ped.productos || []).forEach(det => {
                const pid = det.autoparte_id || det.id;
                const prod = prodMap[pid];
                if (prod) total += prod.precio * (det.cantidad || 1);
            });
            gastoTotal += total;

            const colorMap = {recibido:'text-blue-400',en_proceso:'text-yellow-400',enviado:'text-purple-400',entregado:'text-green-400',cancelado:'text-red-400'};
            const color = colorMap[ped.estado] || 'text-gray-400';

            html += `<tr>
                <td class="py-2">#ORD-${String(ped.id).padStart(4,'0')}</td>
                <td>${ped.fecha ? ped.fecha.substring(0,10) : 'N/A'}</td>
                <td>$${total.toFixed(2)}</td>
                <td class="${color}">${(ped.estado||'recibido').replace('_',' ')}</td>
            </tr>`;
        });
    }
    document.getElementById('detHistorial').innerHTML = html;
    document.getElementById('detGastoTotal').textContent = '$' + gastoTotal.toFixed(2);
}

function buscarCliente() {
    const q = document.getElementById('searchCliente').value.toLowerCase();
    document.querySelectorAll('.cliente-item').forEach(el => {
        el.style.display = el.dataset.search.includes(q) ? '' : 'none';
    });
}

// Auto-select first client
document.addEventListener('DOMContentLoaded', () => {
    const first = document.querySelector('.cliente-item');
    if (first) seleccionarCliente(first);
});
</script>
</body>
</html>