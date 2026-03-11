<!DOCTYPE html>
<html class="dark" lang="es">

<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard | MACUIN</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;700&family=Noto+Sans:wght@400;500;700&display=swap" rel="stylesheet">

<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">

<script src="https://cdn.tailwindcss.com"></script>

<script>
tailwind.config = {
darkMode: "class",
theme: {
extend: {

colors:{
primary:"#BE0000",
primarydark:"#8a0000",
accentyellow:"#FFD700",
dashboardbg:"#1A1A1A",
surfacedark:"#1e1e1e"
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

.card-glow:hover{
box-shadow:0 0 20px rgba(190,0,0,0.2);
border-color:rgba(190,0,0,0.5);
}

.telemetry-grid{
background-size:40px 40px;
background-image:
linear-gradient(to right, rgba(190,0,0,0.05) 1px, transparent 1px),
linear-gradient(to bottom, rgba(190,0,0,0.05) 1px, transparent 1px);
}

</style>

</head>


<body class="bg-dashboardbg text-white font-body min-h-screen overflow-hidden">

<div class="relative flex h-screen">

<aside class="w-20 lg:w-64 glass-panel-heavy border-r border-white/10 flex flex-col justify-between">

<div>

<div class="h-20 flex items-center justify-center lg:justify-start lg:px-6 border-b border-white/10">

<div class="hidden lg:block">
<h1 class="font-display font-bold text-lg tracking-wider">MACUIN</h1>
<p class="text-[10px] text-gray-400 uppercase">Autopartes</p>
</div>

</div>


<nav class="mt-8 px-2 lg:px-4 space-y-2">

<a href="/admin" class="flex items-center p-3 rounded-lg bg-primary/10 border border-primary/30">

<span class="material-symbols-outlined">dashboard</span>
<span class="hidden lg:block ml-3 text-sm">Dashboard</span>

</a>


<a href="#" class="flex items-center p-3 rounded-lg text-gray-400 hover:bg-white/5">

<span class="material-symbols-outlined">inventory_2</span>
<span class="hidden lg:block ml-3 text-sm">Inventario</span>

</a>


<a href="#" class="flex items-center p-3 rounded-lg text-gray-400 hover:bg-white/5">

<span class="material-symbols-outlined">shopping_cart</span>
<span class="hidden lg:block ml-3 text-sm">Pedidos</span>

</a>


<a href="#" class="flex items-center p-3 rounded-lg text-gray-400 hover:bg-white/5">

<span class="material-symbols-outlined">group</span>
<span class="hidden lg:block ml-3 text-sm">Clientes</span>

</a>


<a href="#" class="flex items-center p-3 rounded-lg text-gray-400 hover:bg-white/5">

<span class="material-symbols-outlined">assessment</span>
<span class="hidden lg:block ml-3 text-sm">Reportes</span>

</a>

</nav>

</div>


<div class="p-4 border-t border-white/10">

<a href="#" class="flex items-center p-3 rounded-lg text-red-400 hover:bg-red-900/10">

<span class="material-symbols-outlined">logout</span>
<span class="hidden lg:block ml-3 text-sm">Salir</span>

</a>

</div>

</aside>



<main class="flex-1 flex flex-col overflow-hidden">

<header class="h-20 glass-panel border-b border-white/10 flex items-center justify-between px-8">

<h2 class="text-xl font-display font-bold uppercase">

Panel de Control
<span class="text-primary mx-2">/</span>
<span class="text-gray-400 text-base normal-case">Vista General</span>

</h2>


<div class="flex items-center gap-6">

<span class="material-symbols-outlined text-gray-400">notifications</span>

<div class="flex items-center gap-3">

<div class="text-right hidden md:block">

<p class="text-sm font-bold">Belén Vega</p>
<p class="text-xs text-primary">Administrador</p>

</div>

<div class="w-10 h-10 rounded-full bg-gray-700"></div>

</div>

</div>

</header>



<div class="flex-1 overflow-y-auto p-8">

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">


<div class="glass-panel p-6 rounded-2xl card-glow">

<h3 class="text-gray-400 text-sm uppercase">Total Ventas</h3>

<p class="text-3xl font-display font-bold mt-2">$845,230</p>

</div>


<div class="glass-panel p-6 rounded-2xl card-glow">

<h3 class="text-gray-400 text-sm uppercase">Pedidos por surtir</h3>

<p class="text-3xl font-display font-bold mt-2">24</p>

</div>


<div class="glass-panel p-6 rounded-2xl border-yellow-500/30">

<h3 class="text-gray-400 text-sm uppercase">Stock Bajo</h3>

<p class="text-3xl font-display font-bold text-yellow-400 mt-2">8 Items</p>

</div>


</div>



<div class="glass-panel rounded-2xl p-6">

<h3 class="font-display font-bold mb-4">Rendimiento en tiempo real</h3>

<div class="flex items-end gap-3 h-56">

<div class="bg-primary/40 w-full h-[40%]"></div>
<div class="bg-primary/50 w-full h-[65%]"></div>
<div class="bg-primary/60 w-full h-[55%]"></div>
<div class="bg-primary/70 w-full h-[80%]"></div>
<div class="bg-primary/60 w-full h-[45%]"></div>
<div class="bg-primary/80 w-full h-[90%]"></div>

</div>

</div>


</div>

</main>

</div>

</body>
</html>