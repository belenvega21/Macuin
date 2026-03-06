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

theme:{
extend:{

colors:{
primary:"#BE0000",
dashboard:"#1A1A1A",
yellow:"#FFD700"
},

fontFamily:{
display:["Space Grotesk","sans-serif"],
body:["Noto Sans","sans-serif"]
}

}
}

}

</script>

<style>

.glass-panel{
background:rgba(20,20,20,0.8);
backdrop-filter:blur(15px);
border:1px solid rgba(255,255,255,0.1);
}

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

<a href="/admin" class="flex items-center gap-3 p-3 text-gray-400 hover:bg-white/5 rounded">

<span class="material-symbols-outlined">dashboard</span>

Dashboard

</a>

<a href="/inventario" class="flex items-center gap-3 p-3 text-gray-400 hover:bg-white/5 rounded">

<span class="material-symbols-outlined">inventory_2</span>

Inventario

</a>

<a href="/pedidos" class="flex items-center gap-3 p-3 bg-primary/10 border border-primary/40 rounded">

<span class="material-symbols-outlined">shopping_cart</span>

Pedidos

</a>

</nav>


<div class="p-4 border-t border-white/10">

<a class="flex items-center gap-3 text-red-400">

<span class="material-symbols-outlined">logout</span>

Salir

</a>

</div>

</aside>



<!-- CONTENIDO -->

<main class="flex-1 p-10 overflow-y-auto">


<h2 class="text-2xl font-display font-bold uppercase mb-8">

Monitor / Tráfico de Pedidos

</h2>



<!-- BUSCADOR -->

<div class="glass-panel p-4 rounded-xl mb-6 flex gap-4">

<div class="flex items-center bg-black/40 border border-white/10 rounded-lg px-3 py-2 w-full">

<span class="material-symbols-outlined text-gray-400 mr-2">

search

</span>

<input
class="bg-transparent outline-none text-sm w-full"
placeholder="Buscar pedido, cliente...">

</div>


<div class="flex gap-2">

<button class="px-4 py-2 bg-primary/20 border border-primary/40 rounded text-sm">

Todos

</button>

<button class="px-4 py-2 bg-black/40 border border-white/10 rounded text-sm">

Recibido

</button>

<button class="px-4 py-2 bg-black/40 border border-white/10 rounded text-sm">

Surtido

</button>

<button class="px-4 py-2 bg-black/40 border border-white/10 rounded text-sm">

Enviado

</button>

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


<tbody class="divide-y divide-white/5">


<tr>

<td class="p-4 font-mono text-gray-400">#ORD-8921</td>

<td>Taller Mecánico Vega</td>

<td>Ene 24, 2026</td>

<td class="text-right font-bold">$12,450</td>

<td class="text-center">

<span class="px-2 py-1 text-xs rounded-full bg-yellow-500/20 text-yellow-400">

Pendiente

</span>

</td>

<td class="text-center flex justify-center gap-3">

<button>

<span class="material-symbols-outlined">visibility</span>

</button>

<button>

<span class="material-symbols-outlined text-primary">local_shipping</span>

</button>

</td>

</tr>



<tr>

<td class="p-4 font-mono text-gray-400">#ORD-8922</td>

<td>Refaccionaria UPQ</td>

<td>Ene 23, 2026</td>

<td class="text-right font-bold">$8,320</td>

<td class="text-center">

<span class="px-2 py-1 text-xs rounded-full bg-green-500/20 text-green-400">

Surtido

</span>

</td>

<td class="text-center flex justify-center gap-3">

<button>
<span class="material-symbols-outlined">visibility</span>
</button>

<button>
<span class="material-symbols-outlined text-primary">local_shipping</span>
</button>

</td>

</tr>



<tr>

<td class="p-4 font-mono text-gray-400">#ORD-8919</td>

<td>Auto Servicios López</td>

<td>Ene 20, 2026</td>

<td class="text-right font-bold">$45,100</td>

<td class="text-center">

<span class="px-2 py-1 text-xs rounded-full bg-red-500/20 text-red-400">

Retrasado

</span>

</td>

<td class="text-center flex justify-center gap-3">

<button>
<span class="material-symbols-outlined">visibility</span>
</button>

<button>
<span class="material-symbols-outlined text-red-400">local_shipping</span>
</button>

</td>

</tr>


<tr>

<td class="p-4 font-mono text-gray-400">#ORD-8925</td>

<td>El Puma</td>

<td>Ene 25, 2026</td>

<td class="text-right font-bold">$2,150</td>

<td class="text-center">

<span class="px-2 py-1 text-xs rounded-full bg-blue-500/20 text-blue-400">

Recibido

</span>

</td>

<td class="text-center flex justify-center gap-3">

<button>
<span class="material-symbols-outlined">visibility</span>
</button>

<button>
<span class="material-symbols-outlined text-primary">local_shipping</span>
</button>

</td>

</tr>


</tbody>

</table>


<!-- PAGINACIÓN -->

<div class="flex justify-between items-center p-4 text-xs text-gray-400">

<span>

Mostrando 1-4 de 20 pedidos

</span>

<div class="flex gap-2">

<button class="px-3 py-1 bg-primary rounded">

1

</button>

<button class="px-3 py-1 bg-black/40 border border-white/10 rounded">

2

</button>

<button class="px-3 py-1 bg-black/40 border border-white/10 rounded">

3

</button>

</div>

</div>

</div>


</main>

</div>


</body>
</html>