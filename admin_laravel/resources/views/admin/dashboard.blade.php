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

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

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
  transition: all 0.3s ease;
}

.glass-panel-heavy{
  background:rgba(20,20,20,0.85);
  backdrop-filter:blur(20px);
  border:1px solid rgba(190,0,0,0.3);
}

.card-glow:hover{
  box-shadow:0 0 20px rgba(190,0,0,0.2);
  border-color:rgba(190,0,0,0.5);
  transform: translateY(-2px);
}

.stat-icon {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>

</head>


<body class="bg-dashboardbg text-white font-body min-h-screen overflow-hidden">

<div class="relative flex h-screen">

<!-- SIDEBAR -->
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

<a href="/inventario" class="flex items-center p-3 rounded-lg text-gray-400 hover:bg-white/5 transition">
<span class="material-symbols-outlined">inventory_2</span>
<span class="hidden lg:block ml-3 text-sm">Inventario</span>
</a>

<a href="/pedidos" class="flex items-center p-3 rounded-lg text-gray-400 hover:bg-white/5 transition">
<span class="material-symbols-outlined">shopping_cart</span>
<span class="hidden lg:block ml-3 text-sm">Pedidos</span>
</a>

<a href="/clientes" class="flex items-center p-3 rounded-lg text-gray-400 hover:bg-white/5 transition">
<span class="material-symbols-outlined">group</span>
<span class="hidden lg:block ml-3 text-sm">Clientes</span>
</a>

<a href="/reportes" class="flex items-center p-3 rounded-lg text-gray-400 hover:bg-white/5 transition">
<span class="material-symbols-outlined">assessment</span>
<span class="hidden lg:block ml-3 text-sm">Reportes</span>
</a>

</nav>
</div>

<div class="p-4 border-t border-white/10">
<a href="/" class="flex items-center p-3 rounded-lg text-red-400 hover:bg-red-900/10 transition">
<span class="material-symbols-outlined">logout</span>
<span class="hidden lg:block ml-3 text-sm">Cerrar sesión</span>
</a>
</div>

</aside>


<!-- MAIN CONTENT -->
<main class="flex-1 flex flex-col overflow-hidden">

<!-- HEADER -->
<header class="h-20 glass-panel border-b border-white/10 flex items-center justify-between px-8">
<h2 class="text-xl font-display font-bold uppercase">
Panel de Control
<span class="text-primary mx-2">/</span>
<span class="text-gray-400 text-base normal-case">Vista General</span>
</h2>

<div class="flex items-center gap-6">
<span class="material-symbols-outlined text-gray-400 cursor-pointer hover:text-white transition">notifications</span>
<div class="flex items-center gap-3">
<div class="text-right hidden md:block">
<p class="text-sm font-bold" id="user-name">Admin</p>
<p class="text-xs text-primary">Administrador</p>
</div>
<div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-primarydark flex items-center justify-center">
<span class="material-symbols-outlined text-white" style="font-size:20px;">person</span>
</div>
</div>
</div>
</header>


<!-- CONTENT -->
<div class="flex-1 overflow-y-auto p-8">

<!-- STAT CARDS ROW -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">

<!-- TOTAL VENTAS -->
<div class="glass-panel p-5 rounded-2xl card-glow flex items-center gap-4">
<div class="stat-icon bg-primary/20">
<span class="material-symbols-outlined text-primary" style="font-size:24px;">payments</span>
</div>
<div>
<h3 class="text-gray-400 text-xs uppercase">Total Ventas</h3>
<p class="text-2xl font-display font-bold mt-1" id="stat-ventas">$0</p>
</div>
</div>

<!-- PEDIDOS POR SURTIR -->
<div class="glass-panel p-5 rounded-2xl card-glow flex items-center gap-4">
<div class="stat-icon bg-amber-500/20">
<span class="material-symbols-outlined text-amber-400" style="font-size:24px;">pending_actions</span>
</div>
<div>
<h3 class="text-gray-400 text-xs uppercase">Pedidos por Surtir</h3>
<p class="text-2xl font-display font-bold mt-1" id="stat-pendientes">0</p>
</div>
</div>

<!-- STOCK BAJO -->
<div class="glass-panel p-5 rounded-2xl card-glow flex items-center gap-4">
<div class="stat-icon bg-yellow-500/20">
<span class="material-symbols-outlined text-yellow-400" style="font-size:24px;">warning</span>
</div>
<div>
<h3 class="text-gray-400 text-xs uppercase">Stock Bajo</h3>
<p class="text-2xl font-display font-bold text-yellow-400 mt-1" id="stat-stock-bajo">0 Items</p>
</div>
</div>

<!-- CLIENTES -->
<div class="glass-panel p-5 rounded-2xl card-glow flex items-center gap-4">
<div class="stat-icon bg-emerald-500/20">
<span class="material-symbols-outlined text-emerald-400" style="font-size:24px;">group</span>
</div>
<div>
<h3 class="text-gray-400 text-xs uppercase">Clientes</h3>
<p class="text-2xl font-display font-bold mt-1" id="stat-clientes">0</p>
</div>
</div>

</div>


<!-- CHARTS ROW -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">

<!-- CHART: Rendimiento -->
<div class="glass-panel rounded-2xl p-6">
<h3 class="font-display font-bold mb-4 flex items-center gap-2">
<span class="material-symbols-outlined text-primary" style="font-size:20px;">bar_chart</span>
Rendimiento de Pedidos
</h3>
<div style="height:240px; position:relative;">
<canvas id="chart-pedidos"></canvas>
</div>
</div>

<!-- CHART: Productos más vendidos -->
<div class="glass-panel rounded-2xl p-6">
<h3 class="font-display font-bold mb-4 flex items-center gap-2">
<span class="material-symbols-outlined text-accentyellow" style="font-size:20px;">star</span>
Productos Más Vendidos
</h3>
<div style="height:240px; position:relative;">
<canvas id="chart-vendidos"></canvas>
</div>
</div>

</div>


<!-- RECENT ORDERS TABLE -->
<div class="glass-panel rounded-2xl p-6">
<div class="flex items-center justify-between mb-4">
<h3 class="font-display font-bold flex items-center gap-2">
<span class="material-symbols-outlined text-primary" style="font-size:20px;">receipt_long</span>
Pedidos Recientes
</h3>
<a href="/pedidos" class="text-sm text-primary hover:underline">Ver todos →</a>
</div>

<div class="overflow-x-auto">
<table class="w-full text-sm">
<thead>
<tr class="border-b border-white/10 text-gray-400 text-xs uppercase">
<th class="text-left pb-3">ID</th>
<th class="text-left pb-3">Usuario</th>
<th class="text-left pb-3">Productos</th>
<th class="text-left pb-3">Estado</th>
</tr>
</thead>
<tbody id="tabla-pedidos">
<tr><td colspan="4" class="text-gray-500 py-4 text-center">Cargando...</td></tr>
</tbody>
</table>
</div>
</div>


</div>

</main>

</div>

<!-- SCRIPTS -->
<script>
const API = 'http://localhost:8001';

// ====== CARGAR DATOS ======
async function loadDashboard() {
  try {
    // Fetch all data in parallel
    const [autopartes, pedidos, masVendidos] = await Promise.all([
      fetch(API + '/autopartes/').then(r => r.json()),
      fetch(API + '/pedidos/').then(r => r.json()),
      fetch(API + '/reportes/mas-vendidos').then(r => r.json())
    ]);

    // ---- STATS ----
    // Total Ventas (sum of precio * cantidad from pedidos)
    let totalVentas = 0;
    const autopartesMap = {};
    autopartes.forEach(a => { autopartesMap[a.id] = a; });
    
    pedidos.forEach(p => {
      if (p.estado !== 'cancelado') {
        (p.productos || []).forEach(prod => {
          const ap = autopartesMap[prod.autoparte_id];
          if (ap) totalVentas += ap.precio * prod.cantidad;
        });
      }
    });
    document.getElementById('stat-ventas').textContent = '$' + totalVentas.toLocaleString('es-MX');

    // Pedidos por surtir (recibido or en_proceso)
    const pendientes = pedidos.filter(p => p.estado === 'recibido' || p.estado === 'en_proceso').length;
    document.getElementById('stat-pendientes').textContent = pendientes;

    // Stock bajo (less than 5 units)
    const stockBajo = autopartes.filter(a => a.stock < 5).length;
    document.getElementById('stat-stock-bajo').textContent = stockBajo + ' Items';

    // Clientes
    try {
      // This endpoint requires auth, try without token for count
      const usrRes = await fetch(API + '/reportes/clientes');
      const usuarios = await usrRes.json();
      document.getElementById('stat-clientes').textContent = Array.isArray(usuarios) ? usuarios.length : 0;
    } catch(e) {
      document.getElementById('stat-clientes').textContent = '—';
    }

    // ---- CHART: Pedidos por estado ----
    const estados = ['recibido', 'en_proceso', 'enviado', 'entregado', 'cancelado'];
    const etiquetas = ['Recibido', 'En Proceso', 'Enviado', 'Entregado', 'Cancelado'];
    const colores = ['#3b82f6', '#f59e0b', '#8b5cf6', '#22c55e', '#ef4444'];
    const conteoEstados = estados.map(est => pedidos.filter(p => p.estado === est).length);

    new Chart(document.getElementById('chart-pedidos'), {
      type: 'bar',
      data: {
        labels: etiquetas,
        datasets: [{
          label: 'Pedidos',
          data: conteoEstados,
          backgroundColor: colores,
          borderRadius: 8,
          borderSkipped: false
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false }
        },
        scales: {
          x: { ticks: { color: '#888' }, grid: { display: false } },
          y: { ticks: { color: '#888', stepSize: 1 }, grid: { color: 'rgba(255,255,255,0.05)' } }
        }
      }
    });

    // ---- CHART: Más vendidos (donut) ----
    const vendidosEntries = Object.entries(masVendidos);
    const topVendidos = vendidosEntries
      .sort((a, b) => b[1] - a[1])
      .slice(0, 6);

    const nombresVendidos = topVendidos.map(([id]) => {
      const ap = autopartesMap[parseInt(id)];
      return ap ? ap.nombre.substring(0, 20) : `ID ${id}`;
    });
    const cantidadesVendidos = topVendidos.map(([, cant]) => cant);

    new Chart(document.getElementById('chart-vendidos'), {
      type: 'doughnut',
      data: {
        labels: nombresVendidos.length ? nombresVendidos : ['Sin datos'],
        datasets: [{
          data: cantidadesVendidos.length ? cantidadesVendidos : [1],
          backgroundColor: ['#BE0000', '#FFD700', '#3b82f6', '#22c55e', '#f59e0b', '#8b5cf6'],
          borderWidth: 0
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '60%',
        plugins: {
          legend: {
            position: 'right',
            labels: { color: '#ccc', padding: 12, font: { size: 11 } }
          }
        }
      }
    });

    // ---- TABLE: Últimos 5 pedidos ----
    const tbody = document.getElementById('tabla-pedidos');
    const ultimos = pedidos.slice(-5).reverse();

    if (ultimos.length === 0) {
      tbody.innerHTML = '<tr><td colspan="4" class="text-gray-500 py-4 text-center">No hay pedidos aún</td></tr>';
    } else {
      tbody.innerHTML = ultimos.map(p => {
        const estadoColor = {
          recibido: 'bg-blue-500/20 text-blue-400',
          en_proceso: 'bg-amber-500/20 text-amber-400',
          enviado: 'bg-purple-500/20 text-purple-400',
          entregado: 'bg-green-500/20 text-green-400',
          cancelado: 'bg-red-500/20 text-red-400'
        }[p.estado] || 'bg-gray-500/20 text-gray-400';

        return `<tr class="border-b border-white/5 hover:bg-white/5 transition">
          <td class="py-3 font-mono">#${p.id}</td>
          <td class="py-3">Usuario ${p.usuario_id}</td>
          <td class="py-3">${(p.productos || []).length} productos</td>
          <td class="py-3"><span class="px-2 py-1 rounded-full text-xs font-bold ${estadoColor}">${p.estado}</span></td>
        </tr>`;
      }).join('');
    }

  } catch(e) {
    console.error('Dashboard error:', e);
  }
}

loadDashboard();
</script>

</body>
</html>