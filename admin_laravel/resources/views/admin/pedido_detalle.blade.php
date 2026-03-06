<!DOCTYPE html>
<html class="dark" lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Detalle Pedido | MACUIN</title>

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

<a href="/pedidos" class="flex items-center gap-3 p-3 bg-primary/10 border border-primary/40 rounded">

<span class="material-symbols-outlined">shopping_cart</span>
Pedidos

</a>

</nav>

</aside>


<!-- CONTENIDO -->

<main class="flex-1 p-10 overflow-y-auto">


<h2 class="text-2xl font-display font-bold uppercase mb-6">

Gestión / Estatus de Pedido

</h2>


<div class="grid grid-cols-3 gap-6">


<!-- INFORMACION PEDIDO -->

<div class="col-span-2 glass-panel rounded-xl p-6">


<div class="flex justify-between border-b border-white/10 pb-4 mb-6">

<div>

<h3 class="text-xl font-bold">

Pedido #ORD-8921

</h3>

<p class="text-sm text-gray-400">

Taller Mecánico Vega

</p>

<p class="text-xs text-gray-500">

24 Ene 2026 · 10:30 AM

</p>

</div>


<div class="text-right">

<p class="text-xs text-gray-400">

Total estimado

</p>

<p class="text-2xl font-bold">

$12,450

</p>

</div>

</div>


<!-- PIEZAS -->

<div class="space-y-4">


<div class="flex justify-between items-center bg-white/5 p-4 rounded">

<div>

<p class="font-medium">Balatas Cerámicas</p>
<p class="text-xs text-gray-500">Marca: Duralast</p>

</div>

<div class="flex gap-8 text-sm">

<span>SKU 4092</span>
<span>Cant. 2</span>

<span class="text-green-400">

Disponible

</span>

</div>

</div>


<div class="flex justify-between items-center bg-white/5 p-4 rounded">

<div>

<p class="font-medium">Filtro de Aceite</p>
<p class="text-xs text-gray-500">Marca: Gonher</p>

</div>

<div class="flex gap-8 text-sm">

<span>SKU 5050</span>
<span>Cant. 5</span>

<span class="text-green-400">

Disponible

</span>

</div>

</div>


<div class="flex justify-between items-center bg-red-900/20 p-4 rounded border border-red-600">

<div>

<p class="font-medium">Amortiguadores</p>
<p class="text-xs text-red-300">Marca: Monroe</p>

</div>

<div class="flex gap-8 text-sm">

<span>SKU 9900</span>
<span>Cant. 2</span>

<span class="text-red-400">

Agotado

</span>

</div>

</div>


</div>


<!-- NOTAS -->

<div class="mt-6">

<p class="text-xs text-gray-400 uppercase mb-2">

Notas del pedido

</p>

<div class="bg-black/40 p-4 rounded text-sm italic">

"El cliente solicitó factura y entrega en la puerta lateral del taller."

</div>

</div>


</div>


<!-- TIMELINE -->

<div class="glass-panel rounded-xl p-6 flex flex-col">


<h3 class="text-lg font-bold mb-6">

Línea de Tiempo

</h3>


<div class="space-y-6 text-sm">


<div>

<p class="text-green-400 font-bold">

Orden recibida

</p>

<p class="text-gray-400 text-xs">

24 Ene 10:30 AM

</p>

</div>


<div>

<p class="text-yellow font-bold">

En surtido

</p>

<p class="text-gray-400 text-xs">

Recolectando piezas

</p>

</div>


<div class="opacity-50">

<p>

Listo para envío

</p>

</div>


<div class="opacity-50">

<p>

Entregado

</p>

</div>


</div>


<button class="mt-auto mt-8 bg-primary hover:bg-red-700 p-4 rounded font-bold">

Avanzar Estatus →

</button>


</div>


</div>


</main>


</div>


</body>
</html>