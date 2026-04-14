<!DOCTYPE html>
<html class="dark" lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inventario | MACUIN</title>
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
<h1 class="font-display text-lg font-bold">MACUIN</h1>
<p class="text-xs text-gray-400 uppercase">Autopartes</p>
</div>
<nav class="flex-1 p-4 space-y-2">
<a href="/admin" class="flex items-center gap-3 p-3 text-gray-400 hover:bg-white/5 rounded transition">
<span class="material-symbols-outlined">dashboard</span> Dashboard
</a>
<a href="/inventario" class="flex items-center gap-3 p-3 bg-primary/10 border border-primary/40 rounded text-white">
<span class="material-symbols-outlined">inventory_2</span> Inventario
</a>
<a href="/pedidos" class="flex items-center gap-3 p-3 text-gray-400 hover:bg-white/5 rounded transition">
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

<h2 class="text-2xl font-display font-bold uppercase mb-6">Inventario / Catálogo de Autopartes</h2>

@if(session('success'))
<div class="bg-green-500/20 border border-green-500/40 text-green-400 px-4 py-2 rounded mb-4">
{{ session('success') }}
</div>
@endif

<!-- BUSCADOR -->
<div class="flex items-center bg-black/40 border border-white/10 rounded px-3 py-2 mb-6">
<span class="material-symbols-outlined text-gray-400 mr-2">search</span>
<input id="searchProd" type="text" placeholder="Buscar producto por nombre, marca, categoría..." 
class="bg-transparent outline-none text-sm w-full" onkeyup="filtrarInventario()">
</div>

<!-- TABLA -->
<div class="bg-[#121212] border border-white/10 rounded-lg overflow-hidden">
<table class="w-full text-left">
<thead class="bg-white/5 text-xs uppercase text-gray-400">
<tr>
<th class="p-4">SKU</th>
<th class="p-4">Imagen</th>
<th class="p-4">Nombre</th>
<th class="p-4">Marca</th>
<th class="p-4">Categoría</th>
<th class="p-4">Precio</th>
<th class="p-4">Stock</th>
<th class="p-4 text-center">Acciones</th>
</tr>
</thead>
<tbody class="divide-y divide-white/10" id="inventarioBody">

@forelse($productos as $producto)
<tr class="inv-row" data-search="{{ strtolower($producto['nombre'] . ' ' . $producto['marca'] . ' ' . $producto['categoria']) }}">
<td class="p-4 font-mono text-gray-400">{{ $producto['sku'] ?? 'N/A' }}</td>
<td class="p-4">
    <img src="{{ $producto['imagen'] ?? 'https://via.placeholder.com/80x80?text=N/A' }}" 
         alt="{{ $producto['nombre'] }}"
         class="w-12 h-12 object-contain rounded bg-white p-1"
         onerror="this.src='https://via.placeholder.com/80x80?text=N/A'">
</td>
<td class="p-4 font-bold">{{ $producto['nombre'] }}</td>
<td class="p-4">{{ $producto['marca'] }}</td>
<td class="p-4">
    <span class="px-2 py-1 text-xs rounded-full bg-white/10">{{ $producto['categoria'] }}</span>
</td>
<td class="p-4 text-green-400 font-bold">${{ number_format($producto['precio'], 2) }}</td>
<td class="p-4">
    @if($producto['stock'] > 10)
        <span class="text-green-500">{{ $producto['stock'] }} pz</span>
    @elseif($producto['stock'] > 0)
        <span class="text-yellow-400">{{ $producto['stock'] }} pz ⚠️</span>
    @else
        <span class="text-red-500 font-bold">Agotado</span>
    @endif
</td>
<td class="p-4 flex gap-3 justify-center">
    <a href="/inventario/editar/{{ $producto['id'] }}" class="text-blue-400 hover:text-blue-300 transition" title="Editar">
        <span class="material-symbols-outlined">edit</span>
    </a>
    <form action="/inventario/eliminar/{{ $producto['id'] }}" method="POST" class="inline delete-form">
        @csrf
        @method('DELETE')
        <button type="button" class="text-red-400 hover:text-red-300 transition btn-delete" title="Eliminar">
            <span class="material-symbols-outlined">delete</span>
        </button>
    </form>
</td>
</tr>
@empty
<tr>
    <td colspan="8" class="p-6 text-center text-gray-500">No hay autopartes en el inventario.</td>
</tr>
@endforelse

</tbody>
</table>
</div>

<!-- BOTON AGREGAR -->
<a href="/inventario/crear"
class="fixed bottom-10 right-10 bg-primary w-16 h-16 rounded-full flex items-center justify-center shadow-lg hover:bg-red-700 transition hover:scale-110">
<span class="material-symbols-outlined text-3xl">add</span>
</a>

</main>
</div>

<script>
function filtrarInventario() {
    const q = document.getElementById('searchProd').value.toLowerCase();
    document.querySelectorAll('.inv-row').forEach(row => {
        row.style.display = row.dataset.search.includes(q) ? '' : 'none';
    });
}

// SweetAlert2 Confirmation for Delete
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function() {
        const form = this.closest('form');
        Swal.fire({
            title: '¿Eliminar Autoparte?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#BE0000',
            cancelButtonColor: '#333333',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            background: '#1A1A1A',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
</body>
</html>