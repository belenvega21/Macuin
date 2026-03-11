<!DOCTYPE html>
<html class="dark" lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Reportes | MACUIN</title>

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

<a href="/clientes" class="flex items-center gap-3 p-3 text-gray-400 hover:bg-white/5 rounded">
<span class="material-symbols-outlined">group</span>
Clientes
</a>

<a href="/reportes" class="flex items-center gap-3 p-3 bg-primary/10 border border-primary/40 rounded">
<span class="material-symbols-outlined">assessment</span>
Reportes
</a>

</nav>

</aside>


<!-- CONTENIDO -->

<main class="flex-1 p-10 overflow-y-auto">


<h2 class="text-3xl font-display font-bold mb-2">

Centro de Reportes

</h2>

<p class="text-gray-400 mb-10">

Seleccione los parámetros necesarios para generar los informes estratégicos.

</p>


<div class="grid grid-cols-3 gap-8">


<!-- REPORTE PEDIDOS -->

<div class="glass-panel rounded-xl p-6 flex flex-col">

<h3 class="font-bold text-lg mb-1">

Reportes de Pedidos

</h3>

<p class="text-xs text-gray-400 mb-6">

Historial y estatus

</p>


<label class="text-xs text-gray-400 mb-1">Desde</label>
<input type="date" class="bg-black/40 border border-white/10 rounded p-2 mb-3 text-sm">


<label class="text-xs text-gray-400 mb-1">Hasta</label>
<input type="date" class="bg-black/40 border border-white/10 rounded p-2 mb-5 text-sm">


<button class="bg-yellow text-black font-bold py-3 rounded">

Generar reporte PDF

</button>

</div>



<!-- REPORTE CLIENTES -->

<div class="glass-panel rounded-xl p-6 flex flex-col">

<h3 class="font-bold text-lg mb-1">

Reportes de Clientes

</h3>

<p class="text-xs text-gray-400 mb-6">

Actividad y cartera

</p>


<label class="text-xs text-gray-400 mb-1">Desde</label>
<input type="date" class="bg-black/40 border border-white/10 rounded p-2 mb-3 text-sm">


<label class="text-xs text-gray-400 mb-1">Hasta</label>
<input type="date" class="bg-black/40 border border-white/10 rounded p-2 mb-5 text-sm">


<button class="bg-yellow text-black font-bold py-3 rounded">

Generar reporte PDF

</button>

</div>



<!-- REPORTE VENTAS -->

<div class="glass-panel rounded-xl p-6 flex flex-col">

<h3 class="font-bold text-lg mb-1">

Reportes de Ventas

</h3>

<p class="text-xs text-gray-400 mb-6">

Ingresos y proyecciones

</p>


<label class="text-xs text-gray-400 mb-1">Desde</label>
<input type="date" class="bg-black/40 border border-white/10 rounded p-2 mb-3 text-sm">


<label class="text-xs text-gray-400 mb-1">Hasta</label>
<input type="date" class="bg-black/40 border border-white/10 rounded p-2 mb-5 text-sm">




<a href="/reporte-general" class="bg-yellow text-black font-bold py-3 rounded text-center block">
Generar reporte PDF
</a>


</div>


</div>


</main>

</div>

</body>
</html>