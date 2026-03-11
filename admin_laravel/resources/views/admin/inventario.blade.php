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
theme:{
extend:{
colors:{
primary:"#BE0000",
dashboard:"#1A1A1A"
},
fontFamily:{
display:["Space Grotesk","sans-serif"],
body:["Noto Sans","sans-serif"]
}
}
}
}
</script>

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




<main class="flex-1 p-10 overflow-y-auto">


<h2 class="text-2xl font-display font-bold uppercase mb-6">

Inventario / Catálogo de Autopartes

</h2>




<input
type="text"
placeholder="Buscar producto..."
class="w-full bg-black/40 border border-white/10 rounded p-3 mb-6">



<!-- TABLA -->

<div class="bg-[#121212] border border-white/10 rounded-lg overflow-hidden">


<table class="w-full text-left">

<thead class="bg-white/5 text-xs uppercase text-gray-400">

<tr>

<th class="p-4">ID</th>
<th class="p-4">Imagen</th>
<th class="p-4">Nombre</th>
<th class="p-4">Marca</th>
<th class="p-4">Categoría</th>
<th class="p-4">Precio</th>
<th class="p-4 text-center">Acciones</th>

</tr>

</thead>


<tbody class="divide-y divide-white/10">


<tr>

<td class="p-4">#4092</td>

<td class="p-4">
<img src="{{ asset('images/balatas.avif') }}" class="w-10 h-10 object-cover rounded"></td>

<td class="p-4">Balatas Cerámicas</td>
<td class="p-4">Duralast</td>
<td class="p-4">Frenos</td>
<td class="p-4">$619</td>

<td class="p-4 flex gap-3 justify-center">

<a href="/inventario/editar" class="text-blue-400 hover:text-blue-300">

<span class="material-symbols-outlined">
edit
</span>

</a>

<button class="text-red-400 hover:text-red-300">

<span class="material-symbols-outlined">
delete
</span>

</button>

</td>

</tr>



<tr>

<td class="p-4">#4093</td>

<td class="p-4">
<img src="{{ asset('images/alternador.jpeg') }}" class="w-10 h-10 object-cover rounded">
<td class="p-4">Alternador 12V</td>
<td class="p-4">Bosch</td>
<td class="p-4">Eléctrico</td>
<td class="p-4">$2450</td>

<td class="p-4 flex gap-3 justify-center">

<a href="/inventario/editar" class="text-blue-400 hover:text-blue-300">

<span class="material-symbols-outlined">
edit
</span>

</a>

<button class="text-red-400 hover:text-red-300">

<span class="material-symbols-outlined">
delete
</span>

</button>

</td>

</tr>


</tbody>

</table>

</div>



<!-- BOTON AGREGAR -->

<a
href="/inventario/crear"
class="fixed bottom-10 right-10 bg-primary w-16 h-16 rounded-full flex items-center justify-center shadow-lg hover:bg-red-700">

<span class="material-symbols-outlined text-3xl">
add
</span>

</a>



</main>


</div>

</body>
</html>