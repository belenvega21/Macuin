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



<main class="flex-1 p-8 overflow-y-auto">


<h2 class="text-2xl font-display font-bold uppercase mb-6">

Directorio / Clientes

</h2>



<div class="grid grid-cols-12 gap-6 h-[80vh]">



<div class="col-span-4 glass-panel rounded-xl p-4 overflow-y-auto">


<h3 class="text-lg font-bold mb-4">

Clientes Activos

</h3>


<input
placeholder="Buscar cliente..."
class="w-full mb-4 bg-black/40 border border-white/10 rounded p-2 text-sm">


<div class="space-y-3">


<div class="p-3 bg-primary/20 border border-primary rounded cursor-pointer">

<p class="font-bold">Taller Mecánico Vega</p>

<p class="text-xs text-gray-400">ID: CLI-8821</p>

</div>


<div class="p-3 bg-white/5 rounded hover:bg-white/10 cursor-pointer">

<p>Motores del Victor</p>
<p class="text-xs text-gray-400">ID: CLI-9402</p>

</div>


<div class="p-3 bg-white/5 rounded hover:bg-white/10 cursor-pointer">

<p>Chistines</p>
<p class="text-xs text-gray-400">ID: CLI-7731</p>

</div>


<div class="p-3 bg-white/5 rounded hover:bg-white/10 cursor-pointer">

<p>Taller Mecánico Mau</p>
<p class="text-xs text-gray-400">ID: CLI-1029</p>

</div>


<div class="p-3 bg-white/5 rounded hover:bg-white/10 cursor-pointer">

<p>Frenos y Más</p>
<p class="text-xs text-gray-400">ID: CLI-5592</p>

</div>


</div>


</div>



<!-- DETALLE CLIENTE -->

<div class="col-span-8 glass-panel rounded-xl p-6">


<div class="flex justify-between mb-6">

<div>

<h3 class="text-xl font-bold">

Taller Mecánico Vega

</h3>

<p class="text-sm text-gray-400">

+52 55 7890 5165

</p>

<p class="text-sm text-gray-400">

vega9021@gmail.com

</p>

</div>


<span class="bg-green-500/20 text-green-400 px-3 py-1 text-xs rounded">

Verificado

</span>


</div>



<div class="grid grid-cols-2 gap-4 mb-6">


<div class="bg-white/5 p-4 rounded">

<p class="text-xs text-gray-400">Total pedidos</p>

<p class="text-xl font-bold text-yellow">

142

</p>

</div>


<div class="bg-white/5 p-4 rounded">

<p class="text-xs text-gray-400">Piezas más pedidas</p>

<ul class="text-sm mt-2">

<li>Filtros de Aceite x450</li>
<li>Balatas Cerámica x320</li>
<li>Bujías Iridium x180</li>

</ul>

</div>


</div>



<h4 class="font-bold mb-3">

Historial Reciente

</h4>


<table class="w-full text-sm">

<thead class="text-gray-400 text-xs">

<tr>

<th>Pedido</th>
<th>Fecha</th>
<th>Monto</th>
<th>Estatus</th>
<th></th>

</tr>

</thead>


<tbody class="divide-y divide-white/5">


<tr>

<td>#ORD-8921</td>

<td>Ene 24 2026</td>

<td>$12,450</td>

<td>

<span class="text-yellow">

En surtido

</span>

</td>

<td>

<span class="material-symbols-outlined text-sm">

visibility

</span>

</td>

</tr>


<tr>

<td>#ORD-8850</td>

<td>Ene 18 2026</td>

<td>$3,200</td>

<td class="text-green-400">

Entregado

</td>

<td>

<span class="material-symbols-outlined text-sm">

visibility

</span>

</td>

</tr>


<tr>

<td>#ORD-8712</td>

<td>Ene 05 2026</td>

<td>$8,990</td>

<td class="text-green-400">

Entregado

</td>

<td>

<span class="material-symbols-outlined text-sm">

visibility

</span>

</td>

</tr>


</tbody>

</table>


</div>


</div>


</main>


</div>


</body>
</html>