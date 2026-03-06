<!DOCTYPE html>
<html class="dark" lang="es">

<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Editar Autoparte | MACUIN</title>

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
yellow:"#FFD700",
dashboard:"#121212"
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
background:rgba(20,20,20,0.9);
backdrop-filter:blur(20px);
border:1px solid rgba(190,0,0,0.4);
}

.modal-bg{
background:rgba(0,0,0,0.8);
backdrop-filter:blur(6px);
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

<a href="/inventario" class="flex items-center gap-3 p-3 bg-primary/10 border border-primary/30 rounded">

<span class="material-symbols-outlined">inventory_2</span>
Inventario

</a>

</nav>

</aside>



<!-- CONTENIDO -->

<main class="flex-1 p-10 overflow-y-auto">


<h2 class="text-2xl font-display font-bold uppercase mb-8">

Editar Autoparte

</h2>


<div class="glass-panel p-8 rounded-xl grid grid-cols-2 gap-10">


<!-- IMAGEN -->

<div>

<h3 class="text-sm text-gray-400 mb-3 uppercase">

Vista previa

</h3>

<div class="border-2 border-primary rounded-lg overflow-hidden">

<img
src="https://via.placeholder.com/400x300"
class="w-full">

</div>

<button class="mt-4 w-full bg-white/10 hover:bg-white/20 py-2 rounded">

Cambiar imagen

</button>

</div>



<!-- FORMULARIO -->

<div class="space-y-6">


<div>

<label class="text-xs text-gray-400 uppercase">

Nombre de la pieza

</label>

<input
type="text"
value="Balatas Cerámicas de Freno"
class="w-full bg-black/40 border border-white/10 p-3 rounded">

</div>


<div>

<label class="text-xs text-gray-400 uppercase">

Descripción

</label>

<textarea
rows="4"
class="w-full bg-black/40 border border-white/10 p-3 rounded">

Detalles técnicos y especificaciones...

</textarea>

</div>


<div class="grid grid-cols-2 gap-6">

<div>

<label class="text-xs text-gray-400 uppercase">

Marca

</label>

<input
type="text"
value="Duralast"
class="w-full bg-black/40 border border-white/10 p-3 rounded">

</div>


<div>

<label class="text-xs text-gray-400 uppercase">

Categoría

</label>

<select
class="w-full bg-black/40 border border-white/10 p-3 rounded">

<option>Frenos</option>
<option>Motor</option>
<option>Suspensión</option>

</select>

</div>

</div>



<div class="grid grid-cols-2 gap-6">

<div>

<label class="text-xs text-gray-400 uppercase">

Precio compra

</label>

<input
type="text"
value="519"
class="w-full bg-black/40 border border-white/10 p-3 rounded">

</div>


<div>

<label class="text-xs text-gray-400 uppercase">

Precio venta

</label>

<input
type="text"
value="619"
class="w-full bg-black/40 border border-white/10 p-3 rounded">

</div>

</div>



<div class="grid grid-cols-2 gap-6">

<div>

<label class="text-xs text-gray-400 uppercase">

Stock mínimo

</label>

<input
type="number"
value="10"
class="w-full bg-black/40 border border-white/10 p-3 rounded">

</div>


<div>

<label class="text-xs text-gray-400 uppercase">

Stock actual

</label>

<input
type="number"
value="42"
class="w-full bg-black/40 border border-white/10 p-3 rounded">

</div>

</div>



<div class="flex justify-end gap-4 pt-6">


<button onclick="abrirModal()"
class="px-6 py-3 bg-gray-700 hover:bg-gray-600 rounded">

Descartar

</button>


<button
class="px-8 py-3 bg-yellow text-black font-bold rounded hover:bg-yellow-400 flex items-center gap-2">

<span class="material-symbols-outlined">
save
</span>

Guardar cambios

</button>

</div>

</div>

</div>


</main>


</div>



<!-- MODAL DESCARTAR -->

<div id="modalDescartar" class="hidden fixed inset-0 flex items-center justify-center modal-bg">


<div class="bg-[#0f0f0f] border border-primary/40 rounded-xl p-8 w-[420px] text-center shadow-2xl">


<div class="w-16 h-16 mx-auto mb-6 rounded-full bg-primary/20 flex items-center justify-center border border-primary">

<span class="material-symbols-outlined text-primary text-4xl">

warning

</span>

</div>


<h3 class="text-xl font-display font-bold mb-3">

¿Descartar cambios?

</h3>


<p class="text-gray-400 text-sm mb-6">

Tienes cambios sin guardar.  
Si sales ahora se perderá la información editada.

</p>


<div class="flex gap-4">


<a
href="/inventario"
class="flex-1 bg-gray-700 hover:bg-gray-600 py-3 rounded text-sm font-bold">

Sí, descartar

</a>


<button onclick="cerrarModal()"
class="flex-1 bg-yellow hover:bg-yellow-400 text-black py-3 rounded text-sm font-bold">

Continuar editando

</button>


</div>

</div>

</div>



<script>

function abrirModal(){
document.getElementById("modalDescartar").classList.remove("hidden")
}

function cerrarModal(){
document.getElementById("modalDescartar").classList.add("hidden")
}

</script>


</body>
</html>