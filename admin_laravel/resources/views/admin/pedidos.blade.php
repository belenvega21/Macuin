<!DOCTYPE html>
<html class="dark" lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pedidos | MACUIN</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;700&family=Noto+Sans:wght@400;500;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
darkMode:"class",
theme:{extend:{
colors:{primary:"#BE0000",dashboard:"#1A1A1A"},
fontFamily:{display:["Space Grotesk","sans-serif"],body:["Noto Sans","sans-serif"]}
}}
}
</script>
<style>
.glass-panel{background:rgba(30,30,30,0.6);backdrop-filter:blur(12px);border:1px solid rgba(190,0,0,0.2);}
.glass-panel-heavy{background:rgba(20,20,20,0.85);backdrop-filter:blur(20px);border:1px solid rgba(190,0,0,0.3);}
/* Tema personalizado para SweetAlert2 */
.swal2-popup { background: #1A1A1A !important; border: 1px solid rgba(190,0,0,0.3) !important; color: white !important; border-radius: 1rem !important; }
.swal2-title { color: #fff !important; font-family: 'Space Grotesk', sans-serif !important; }
</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
<a href="/pedidos" class="flex items-center gap-3 p-3 bg-primary/10 border border-primary/40 rounded text-white">
<span class="material-symbols-outlined">shopping_cart</span> Pedidos
</a>
<a href="/clientes" class="flex items-center gap-3 p-3 text-gray-400 hover:bg-white/5 rounded transition">
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
<main class="flex-1 p-10 overflow-y-auto">

<h2 class="text-2xl font-display font-bold uppercase mb-8">Monitor / Tráfico de Pedidos</h2>

@if(session('success'))
<div class="bg-green-500/20 border border-green-500/40 text-green-400 px-4 py-2 rounded mb-4">
{{ session('success') }}
</div>
@endif

<!-- BUSCADOR + FILTROS -->
<div class="glass-panel p-4 rounded-xl mb-6 flex flex-col md:flex-row gap-4">
<div class="flex items-center bg-black/40 border border-white/10 rounded-lg px-3 py-2 w-full">
<span class="material-symbols-outlined text-gray-400 mr-2">search</span>
<input id="searchInput" class="bg-transparent outline-none text-sm w-full" placeholder="Buscar pedido, cliente..." onkeyup="filtrarPedidos()">
</div>
<div class="flex gap-2 flex-shrink-0">
<button onclick="filtrarEstado('')" class="filter-btn px-4 py-2 bg-primary/20 border border-primary/40 rounded text-sm" data-active="true">Todos</button>
<button onclick="filtrarEstado('recibido')" class="filter-btn px-4 py-2 bg-black/40 border border-white/10 rounded text-sm">Recibido</button>
<button onclick="filtrarEstado('en_proceso')" class="filter-btn px-4 py-2 bg-black/40 border border-white/10 rounded text-sm">En Proceso</button>
<button onclick="filtrarEstado('enviado')" class="filter-btn px-4 py-2 bg-black/40 border border-white/10 rounded text-sm">Enviado</button>
<button onclick="filtrarEstado('entregado')" class="filter-btn px-4 py-2 bg-black/40 border border-white/10 rounded text-sm">Entregado</button>
</div>
</div>

<!-- TABLA -->
<div class="glass-panel rounded-xl overflow-hidden">
<table class="w-full text-left text-sm">
<thead class="bg-white/5 text-gray-400 uppercase text-xs">
<tr>
<th class="p-4">ID Pedido</th>
<th>Cliente</th>
<th>Fecha</th>
<th class="text-right">Total</th>
<th class="text-center">Estado</th>
<th class="text-center">Acciones</th>
</tr>
</thead>
<tbody id="pedidosBody" class="divide-y divide-white/5">

@php
$productoMap = [];
foreach($productos as $p) { $productoMap[$p['id']] = $p; }
$usuarioMap = [];
foreach($usuarios as $u) { $usuarioMap[$u['id']] = $u; }
@endphp

@forelse($pedidos as $pedido)
@php
    $cliente = $usuarioMap[$pedido['usuario_id']] ?? null;
    $nombreCliente = $cliente ? $cliente['nombre'] : 'Cliente #'.$pedido['usuario_id'];
    
    $totalPedido = 0;
    foreach($pedido['productos'] as $det) {
        $pid = $det['autoparte_id'] ?? $det['id'] ?? 0;
        $prod = $productoMap[$pid] ?? null;
        if($prod) $totalPedido += $prod['precio'] * ($det['cantidad'] ?? 1);
    }
    
    $estado = $pedido['estado'] ?? 'recibido';
    $colorClass = match($estado) {
        'recibido' => 'bg-blue-500/20 text-blue-400',
        'en_proceso' => 'bg-yellow-500/20 text-yellow-400',
        'enviado' => 'bg-purple-500/20 text-purple-400',
        'entregado' => 'bg-green-500/20 text-green-400',
        'cancelado' => 'bg-red-500/20 text-red-400',
        default => 'bg-gray-500/20 text-gray-400'
    };
    $siguienteEstado = match($estado) {
        'recibido' => 'en_proceso',
        'en_proceso' => 'enviado',
        'enviado' => 'entregado',
        default => ''
    };
@endphp
<tr class="pedido-row" data-estado="{{ $estado }}" data-search="{{ strtolower($nombreCliente . ' ORD-' . str_pad($pedido['id'], 4, '0', STR_PAD_LEFT)) }}">
<td class="p-4 font-mono text-gray-400">#ORD-{{ str_pad($pedido['id'], 4, '0', STR_PAD_LEFT) }}</td>
<td>{{ $nombreCliente }}</td>
<td>{{ isset($pedido['fecha']) ? \Carbon\Carbon::parse($pedido['fecha'])->format('M d, Y') : 'N/A' }}</td>
<td class="text-right font-bold">${{ number_format($totalPedido, 2) }}</td>
<td class="text-center">
    <span class="px-2 py-1 text-xs rounded-full {{ $colorClass }}">
        {{ ucfirst(str_replace('_', ' ', $estado)) }}
    </span>
</td>
<td class="text-center flex justify-center gap-3 py-3">
    <!-- Ver detalle -->
    <button onclick="verDetalle({{ $pedido['id'] }})" class="text-gray-400 hover:text-white transition" title="Ver detalle">
        <span class="material-symbols-outlined">visibility</span>
    </button>
    <!-- Cambiar estado -->
    @if($siguienteEstado)
    <form action="/pedidos/estado/{{ $pedido['id'] }}" method="POST" class="inline form-estado">
        @csrf
        <input type="hidden" name="estado" value="{{ $siguienteEstado }}">
        <button type="button" class="text-red-400 hover:text-red-300 transition btn-estado" title="Avanzar a: {{ ucfirst(str_replace('_',' ',$siguienteEstado)) }}" data-estado="{{ ucfirst(str_replace('_',' ',$siguienteEstado)) }}">
            <span class="material-symbols-outlined">local_shipping</span>
        </button>
    </form>
    @else
    <span class="text-green-400" title="Pedido completado">
        <span class="material-symbols-outlined">check_circle</span>
    </span>
    @endif
</td>
</tr>
@empty
<tr>
    <td colspan="6" class="p-6 text-center text-gray-500">No hay pedidos registrados.</td>
</tr>
@endforelse

</tbody>
</table>

<!-- PAGINACION -->
<div id="paginacion" class="flex justify-between items-center p-4 text-xs text-gray-400">
<span id="paginacionInfo"></span>
<div id="paginacionBtns" class="flex gap-2"></div>
</div>
</div>

<!-- MODAL DETALLE -->
<div id="detalleModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden z-50 flex items-center justify-center">
<div class="bg-[#1a1a1a] border border-white/10 rounded-2xl p-8 max-w-lg w-full mx-4">
<div class="flex justify-between items-center mb-4">
<h3 class="text-lg font-bold" id="modalTitle">Detalle del Pedido</h3>
<button onclick="cerrarModal()" class="text-gray-400 hover:text-white"><span class="material-symbols-outlined">close</span></button>
</div>
<div id="modalContent" class="text-sm text-gray-300 space-y-2"></div>
</div>
</div>

</main>
</div>

<script>
const ITEMS_PER_PAGE = 8;
let currentPage = 1;
let currentFilter = '';

function filtrarEstado(estado) {
    currentFilter = estado;
    currentPage = 1;
    document.querySelectorAll('.filter-btn').forEach(b => {
        b.classList.remove('bg-primary/20','border-primary/40');
        b.classList.add('bg-black/40','border-white/10');
    });
    event.target.classList.remove('bg-black/40','border-white/10');
    event.target.classList.add('bg-primary/20','border-primary/40');
    aplicarFiltros();
}

function filtrarPedidos() {
    currentPage = 1;
    aplicarFiltros();
}

// SweetAlert2 Confirmation for Status Change
document.querySelectorAll('.btn-estado').forEach(btn => {
    btn.addEventListener('click', function() {
        const form = this.closest('form');
        const estadoLabel = this.getAttribute('data-estado');
        const inputEstado = form.querySelector('input[name="estado"]').value;
        
        if(inputEstado === 'enviado') {
            Swal.fire({
                title: 'Información de Envío',
                html: `
                    <input id="swal-paqueteria" class="swal2-input" placeholder="Paquetería (ej. FedEx, DHL)">
                    <input id="swal-seguimiento" class="swal2-input" placeholder="Número de Seguimiento">
                `,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#BE0000',
                cancelButtonColor: '#333333',
                confirmButtonText: 'Registrar Envío',
                cancelButtonText: 'Cancelar',
                background: '#1A1A1A',
                color: '#fff',
                preConfirm: () => {
                    return [
                        document.getElementById('swal-paqueteria').value,
                        document.getElementById('swal-seguimiento').value
                    ]
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    if(!result.value[0] || !result.value[1]){
                        Swal.fire({icon:'error', title:'Error', text:'Ambos campos son obligatorios', background: '#1A1A1A', color: '#fff'});
                        return;
                    }
                    // Insert inputs to form
                    let inpPaq = document.createElement('input');
                    inpPaq.type = 'hidden';
                    inpPaq.name = 'paqueteria';
                    inpPaq.value = result.value[0];
                    let inpSeg = document.createElement('input');
                    inpSeg.type = 'hidden';
                    inpSeg.name = 'num_seguimiento';
                    inpSeg.value = result.value[1];
                    form.appendChild(inpPaq);
                    form.appendChild(inpSeg);
                    form.submit();
                }
            });
        } else {
            Swal.fire({
                title: `¿Cambiar pedido a: ${estadoLabel}?`,
                text: "El cliente podrá ver este nuevo estado",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#BE0000',
                cancelButtonColor: '#333333',
                confirmButtonText: 'Sí, cambiar',
                cancelButtonText: 'Cancelar',
                background: '#1A1A1A',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    });
});

function aplicarFiltros() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('.pedido-row');
    let visibleRows = [];

    rows.forEach(row => {
        const matchEstado = !currentFilter || row.dataset.estado === currentFilter;
        const matchSearch = !search || row.dataset.search.includes(search);
        if (matchEstado && matchSearch) {
            visibleRows.push(row);
        }
        row.style.display = 'none';
    });

    // Paginar
    const totalPages = Math.ceil(visibleRows.length / ITEMS_PER_PAGE);
    const start = (currentPage - 1) * ITEMS_PER_PAGE;
    const end = start + ITEMS_PER_PAGE;

    visibleRows.forEach((row, i) => {
        if (i >= start && i < end) row.style.display = '';
    });

    // Info
    document.getElementById('paginacionInfo').textContent =
        `Mostrando ${Math.min(start+1, visibleRows.length)}-${Math.min(end, visibleRows.length)} de ${visibleRows.length} pedidos`;

    // Botones
    const btnsDiv = document.getElementById('paginacionBtns');
    btnsDiv.innerHTML = '';
    for (let i = 1; i <= totalPages; i++) {
        const btn = document.createElement('button');
        btn.textContent = i;
        btn.className = i === currentPage
            ? 'px-3 py-1 bg-primary rounded text-white'
            : 'px-3 py-1 bg-black/40 border border-white/10 rounded hover:bg-white/10';
        btn.onclick = () => { currentPage = i; aplicarFiltros(); };
        btnsDiv.appendChild(btn);
    }
}

// Pedidos data para modal
const pedidosData = @json($pedidos);
const productosData = @json($productos);
const usuariosData = @json($usuarios);

function verDetalle(id) {
    const pedido = pedidosData.find(p => p.id === id);
    if (!pedido) return;
    const usuario = usuariosData.find(u => u.id === pedido.usuario_id);
    const prodMap = {};
    productosData.forEach(p => prodMap[p.id] = p);

    let html = `<p><strong>Pedido:</strong> #ORD-${String(id).padStart(4,'0')}</p>`;
    html += `<p><strong>Cliente:</strong> ${usuario ? usuario.nombre : 'ID: '+pedido.usuario_id}</p>`;
    html += `<p><strong>Estado:</strong> ${pedido.estado || 'recibido'}</p>`;
    html += `<hr class="border-white/10 my-3">`;
    html += `<p class="font-bold mb-2">Productos:</p>`;
    let total = 0;
    (pedido.productos || []).forEach(det => {
        const pid = det.autoparte_id || det.id;
        const prod = prodMap[pid];
        if (prod) {
            const sub = prod.precio * (det.cantidad || 1);
            total += sub;
            html += `<div class="flex justify-between py-1"><span>${det.cantidad || 1}x ${prod.nombre}</span><span>$${sub.toFixed(2)}</span></div>`;
        }
    });
    html += `<hr class="border-white/10 my-3">`;
    html += `<div class="flex justify-between font-bold text-white"><span>Total</span><span>$${total.toFixed(2)}</span></div>`;

    document.getElementById('modalTitle').textContent = `Detalle Pedido #ORD-${String(id).padStart(4,'0')}`;
    document.getElementById('modalContent').innerHTML = html;
    document.getElementById('detalleModal').classList.remove('hidden');
}

function cerrarModal() {
    document.getElementById('detalleModal').classList.add('hidden');
}

// Init
document.addEventListener('DOMContentLoaded', aplicarFiltros);
</script>
</body>
</html>