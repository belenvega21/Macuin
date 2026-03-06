<!DOCTYPE html>
<html class="dark" lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Historial Cliente | MACUIN</title>

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
background:rgba(20,20,20,0.85);
backdrop-filter:blur(20px);
border:1px solid rgba(255,255,255,0.08);
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

<a href="/pedidos" class="flex items-center gap-3 p-3 text-gray-400 hover:bg-white/5 rounded">
<span class="material-symbols-outlined">shopping_cart</span>
Pedidos
</a>

<a href="/clientes" class="flex items-center gap-3 p-3 bg-primary/10 border border-primary/40 rounded">
<span class="material-symbols-outlined">group</span>
Clientes
</a>

</nav>

</aside>


<!-- CONTENIDO -->

<main class="flex-1 p-8 overflow-y-auto">

<div class="flex justify-between items-center mb-6">

<div class="flex items-center gap-4">

<div class="w-14 h-14 bg-black border border-primary rounded-lg flex items-center justify-center">
<span class="material-symbols-outlined text-2xl">storefront</span>
</div>

<div>

<p class="text-sm text-gray-400 uppercase">Historial completo</p>

<h2 class="text-2xl font-display font-bold">

Taller Mecánico Vega

</h2>

</div>

</div>

<a href="/clientes" class="flex items-center gap-2 text-sm bg-white/5 border border-white/10 px-4 py-2 rounded hover:bg-white/10">

<span class="material-symbols-outlined text-primary">arrow_back</span>

Regresar al perfil

</a>

</div>



<!-- TABLA -->

<div class="glass-panel rounded-xl overflow-hidden">

<table class="w-full text-sm">

<thead class="bg-black/40 text-gray-400 text-xs">

<tr>

<th class="p-4">ID Pedido</th>
<th>Fecha</th>
<th>Monto</th>
<th>Piezas</th>
<th>Estatus</th>

</tr>

</thead>


<tbody class="divide-y divide-white/5">


<tr class="hover:bg-white/5">

<td class="p-4 font-mono">#ORD-8921</td>
<td>Ene 24 2026</td>
<td class="font-bold">$12,450</td>
<td>15</td>
<td>

<span class="bg-yellow/20 text-yellow px-2 py-1 text-xs rounded">

En surtido

</span>

</td>

</tr>


<tr class="hover:bg-white/5">

<td class="p-4 font-mono">#ORD-8850</td>
<td>Ene 18 2026</td>
<td class="font-bold">$3,200</td>
<td>4</td>
<td class="text-green-400">

Entregado

</td>

</tr>


<tr class="hover:bg-white/5">

<td class="p-4 font-mono">#ORD-8712</td>
<td>Ene 05 2026</td>
<td class="font-bold">$8,990</td>
<td>8</td>
<td class="text-green-400">

Entregado

</td>

</tr>


<tr class="hover:bg-white/5">

<td class="p-4 font-mono text-gray-500">#ORD-8600</td>
<td class="text-gray-500">Dic 28 2025</td>
<td class="text-gray-500">$1,500</td>
<td class="text-gray-500">2</td>
<td class="text-red-400">

Cancelado

</td>

</tr>


<tr class="hover:bg-white/5">

<td class="p-4 font-mono">#ORD-8544</td>
<td>Dic 15 2025</td>
<td class="font-bold">$4,250</td>
<td>5</td>
<td class="text-green-400">

Entregado

</td>

</tr>


<tr class="hover:bg-white/5">

<td class="p-4 font-mono">#ORD-8420</td>
<td>Nov 30 2025</td>
<td class="font-bold">$15,600</td>
<td>22</td>
<td class="text-green-400">

Entregado

</td>

</tr>


<tr class="hover:bg-white/5">

<td class="p-4 font-mono">#ORD-8310</td>
<td>Nov 12 2025</td>
<td class="font-bold">$2,100</td>
<td>3</td>
<td class="text-green-400">

Entregado

</td>

</tr>


</tbody>

</table>



<!-- PAGINACION -->

<div class="flex justify-between items-center p-4 text-xs text-gray-400 border-t border-white/10">

<p>

Mostrando 1-7 de 142 pedidos

</p>


<div class="flex gap-2">

<button class="px-3 py-1 bg-primary rounded">

1

</button>

<button class="px-3 py-1 bg-white/5 rounded">

2

</button>

<button class="px-3 py-1 bg-white/5 rounded">

3

</button>

</div>

</div>


</div>


</main>

</div>

</body>
</html>