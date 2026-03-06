<!DOCTYPE html>
<html class="dark" lang="es">

<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Registrar Pieza | MACUIN</title>

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
dashboardbg:"#1A1A1A"
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
background:rgba(30,30,30,0.6);
backdrop-filter:blur(12px);
border:1px solid rgba(190,0,0,0.2);
}

.glass-panel-heavy{
background:rgba(20,20,20,0.85);
backdrop-filter:blur(20px);
border:1px solid rgba(190,0,0,0.3);
}

</style>

</head>


<body class="bg-dashboardbg text-white font-body min-h-screen overflow-hidden">

<div class="flex h-screen">

<!-- SIDEBAR -->

<aside class="w-20 lg:w-64 glass-panel-heavy border-r border-white/10 flex flex-col justify-between">

<div>

<div class="h-20 flex items-center justify-center lg:px-6 border-b border-white/10">

<div class="hidden lg:block">
<h1 class="font-display font-bold text-lg">MACUIN</h1>
<p class="text-xs text-gray-400 uppercase">Autopartes</p>
</div>

</div>

<nav class="mt-8 px-2 lg:px-4 space-y-2">

<a href="/admin" class="flex items-center p-3 rounded-lg text-gray-400 hover:bg-white/5">
<span class="material-symbols-outlined">dashboard</span>
<span class="hidden lg:block ml-3 text-sm">Dashboard</span>
</a>

<a href="/inventario" class="flex items-center p-3 rounded-lg bg-primary/10 border border-primary/30">
<span class="material-symbols-outlined">inventory_2</span>
<span class="hidden lg:block ml-3 text-sm">Inventario</span>
</a>

</nav>

</div>

</aside>


<!-- MAIN -->

<main class="flex-1 flex flex-col overflow-hidden">


<header class="h-20 glass-panel border-b border-white/10 flex items-center px-8">

<h2 class="text-xl font-display font-bold uppercase">

Inventario
<span class="text-primary mx-2">/</span>
<span class="text-gray-400 text-base normal-case">Nueva Autoparte</span>

</h2>

</header>


<div class="flex-1 overflow-y-auto p-10 flex justify-center">

<div class="w-full max-w-4xl glass-panel rounded-2xl p-8">


<h3 class="text-2xl font-display font-bold mb-8">

Registro de Nueva Pieza

</h3>


<form class="grid grid-cols-1 md:grid-cols-2 gap-6">


<!-- NOMBRE -->

<div>

<label class="text-sm text-gray-400">Nombre de la Pieza</label>

<input
type="text"
placeholder="Ej. Balatas de freno"
class="w-full bg-black/40 border border-gray-700 rounded-lg p-3 mt-1">

</div>


<!-- SKU -->

<div>

<label class="text-sm text-gray-400">ID de la pieza</label>

<input
type="text"
placeholder="MAC-0001"
class="w-full bg-black/40 border border-gray-700 rounded-lg p-3 mt-1">

</div>


<!-- MARCA -->

<div>

<label class="text-sm text-gray-400">Marca</label>

<input
type="text"
placeholder="Brembo"
class="w-full bg-black/40 border border-gray-700 rounded-lg p-3 mt-1">

</div>


<!-- STOCK -->

<div>

<label class="text-sm text-gray-400">Stock Inicial</label>

<input
type="number"
placeholder="0"
class="w-full bg-black/40 border border-gray-700 rounded-lg p-3 mt-1">

</div>


<!-- PRECIO -->

<div>

<label class="text-sm text-gray-400">Precio</label>

<input
type="number"
placeholder="0.00"
class="w-full bg-black/40 border border-gray-700 rounded-lg p-3 mt-1">

</div>


<!-- CATEGORIA -->

<div>

<label class="text-sm text-gray-400">Categoría</label>

<select class="w-full bg-black/40 border border-gray-700 rounded-lg p-3 mt-1">

<option>Motor</option>
<option>Frenos</option>
<option>Suspensión</option>
<option>Eléctrico</option>

</select>

</div>


<!-- DESCRIPCION -->

<div class="md:col-span-2">

<label class="text-sm text-gray-400">Descripción</label>

<textarea
rows="4"
class="w-full bg-black/40 border border-gray-700 rounded-lg p-3 mt-1"></textarea>

</div>


<!-- BOTONES -->

<div class="md:col-span-2 flex justify-end gap-4 mt-6">

<a href="/inventario" class="px-6 py-3 border border-gray-600 rounded-lg text-gray-400">
Cancelar
</a>

<button class="px-8 py-3 bg-yellow-400 text-black font-bold rounded-lg">

Guardar Pieza

</button>

</div>

</form>

</div>

</div>

</main>

</div>

</body>
</html>