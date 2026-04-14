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
  transition: all 0.3s ease;
}

.glass-panel:hover {
  border-color: rgba(255,215,0,0.3);
  box-shadow: 0 8px 32px rgba(255,215,0,0.08);
  transform: translateY(-2px);
}

.report-icon-circle {
  width: 52px;
  height: 52px;
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.btn-download {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 4px;
  padding: 9px 0;
  border-radius: 8px;
  font-weight: 700;
  font-size: 12px;
  text-decoration: none;
  transition: all 0.25s;
  text-align: center;
}

.btn-download:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(0,0,0,0.3);
  filter: brightness(1.15);
}

.date-input {
  width: 100%;
  background: rgba(0,0,0,0.6);
  border: 1px solid rgba(255,255,255,0.1);
  border-radius: 8px;
  padding: 8px 10px;
  font-size: 13px;
  color: white;
  color-scheme: dark;
  transition: all 0.25s;
  cursor: pointer;
}

.date-input:focus {
  border-color: #FFD700;
  outline: none;
  box-shadow: 0 0 8px rgba(255,215,0,0.2);
}

.date-input.input-error {
  border-color: #ef4444 !important;
  box-shadow: 0 0 6px rgba(239,68,68,0.25);
}

.field-error-report {
  color: #ef4444;
  font-size: 11px;
  margin-top: 2px;
  display: none;
}

/* Flatpickr dark theme tweaks */
.flatpickr-calendar {
  background: #1A1A1A !important;
  border: 1px solid rgba(255,255,255,0.1) !important;
  box-shadow: 0 10px 25px rgba(0,0,0,0.5) !important;
}
.flatpickr-day {
  color: #ccc !important;
}
.flatpickr-day.selected {
  background: #BE0000 !important;
  border-color: #BE0000 !important;
  color: #fff !important;
}
.flatpickr-day:hover {
  background: rgba(255,255,255,0.1) !important;
}
</style>

<!-- Flatpickr Core & Dark Theme -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/dark.css">
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
<a href="/admin" class="flex items-center gap-3 p-3 text-gray-400 hover:bg-white/5 rounded transition">
<span class="material-symbols-outlined">dashboard</span>
Dashboard
</a>
<a href="/inventario" class="flex items-center gap-3 p-3 text-gray-400 hover:bg-white/5 rounded transition">
<span class="material-symbols-outlined">inventory_2</span>
Inventario
</a>
<a href="/pedidos" class="flex items-center gap-3 p-3 text-gray-400 hover:bg-white/5 rounded transition">
<span class="material-symbols-outlined">shopping_cart</span>
Pedidos
</a>
<a href="/clientes" class="flex items-center gap-3 p-3 text-gray-400 hover:bg-white/5 rounded transition">
<span class="material-symbols-outlined">group</span>
Clientes
</a>
<a href="/reportes" class="flex items-center gap-3 p-3 bg-primary/10 border border-primary/40 rounded text-white">
<span class="material-symbols-outlined">assessment</span>
Reportes
</a>
</nav>

<div class="p-4 border-t border-white/10">
<a href="/" class="flex items-center gap-3 p-3 text-gray-400 hover:bg-white/5 rounded transition">
<span class="material-symbols-outlined">logout</span>
Cerrar sesión
</a>
</div>

</aside>


<!-- CONTENIDO -->
<main class="flex-1 p-10 overflow-y-auto">

<!-- HEADER -->
<div class="mb-8">
<h2 class="text-3xl font-display font-bold mb-2 flex items-center gap-3">
<span class="material-symbols-outlined text-yellow" style="font-size:32px;">analytics</span>
Centro de Reportes
</h2>
<p class="text-gray-400">
Selecciona un módulo y rango de fechas para exportar informes estratégicos del negocio.
</p>
</div>

<!-- STATS ROW -->
<div class="grid grid-cols-4 gap-4 mb-8">
<div class="glass-panel rounded-xl p-4 text-center">
<span class="material-symbols-outlined text-cyan-400 mb-1" style="font-size:26px;">precision_manufacturing</span>
<p class="text-2xl font-bold" id="stat-productos">—</p>
<p class="text-xs text-gray-400">Autopartes</p>
</div>
<div class="glass-panel rounded-xl p-4 text-center">
<span class="material-symbols-outlined text-emerald-400 mb-1" style="font-size:26px;">receipt_long</span>
<p class="text-2xl font-bold" id="stat-pedidos">—</p>
<p class="text-xs text-gray-400">Pedidos</p>
</div>
<div class="glass-panel rounded-xl p-4 text-center">
<span class="material-symbols-outlined text-amber-400 mb-1" style="font-size:26px;">person_search</span>
<p class="text-2xl font-bold" id="stat-usuarios">—</p>
<p class="text-xs text-gray-400">Clientes</p>
</div>
<div class="glass-panel rounded-xl p-4 text-center">
<span class="material-symbols-outlined text-rose-400 mb-1" style="font-size:26px;">speed</span>
<p class="text-2xl font-bold" id="stat-vendidos">—</p>
<p class="text-xs text-gray-400">Más vendidos</p>
</div>
</div>

<!-- 4 REPORT CARDS -->
<div class="grid grid-cols-2 gap-6">

<!-- 1. INVENTARIO -->
<div class="glass-panel rounded-xl p-6 flex flex-col" id="card-inventario">
<div class="flex items-center gap-4 mb-4">
<div class="report-icon-circle bg-cyan-500/20">
<span class="material-symbols-outlined text-cyan-400" style="font-size:26px;">warehouse</span>
</div>
<div>
<h3 class="font-bold text-lg">Inventario</h3>
<p class="text-xs text-gray-400">Stock, precios y disponibilidad de piezas</p>
</div>
</div>

<div class="grid grid-cols-2 gap-3 mb-1">
<div>
<label class="text-xs text-gray-400">Desde</label>
<input type="text" class="date-input" id="inv-desde">
<div class="field-error-report" id="err-inv-desde">Selecciona una fecha de inicio.</div>
</div>
<div>
<label class="text-xs text-gray-400">Hasta</label>
<input type="text" class="date-input" id="inv-hasta">
<div class="field-error-report" id="err-inv-hasta">Selecciona una fecha final.</div>
</div>
</div>

<div class="grid grid-cols-4 gap-2 mt-auto pt-3">
<button type="button" class="btn-download bg-gray-600 hover:bg-gray-700 text-white" onclick="descargar('inv','http://localhost:8001/reportes/inventario/pdf')">
<span class="material-symbols-outlined" style="font-size:15px;">visibility</span> Ver
</button>
<button type="button" class="btn-download bg-red-600 hover:bg-red-700 text-white" onclick="descargar('inv','http://localhost:8001/reportes/inventario/pdf')">
<span class="material-symbols-outlined" style="font-size:15px;">picture_as_pdf</span> PDF
</button>
<button type="button" class="btn-download bg-emerald-600 hover:bg-emerald-700 text-white" onclick="descargar('inv','http://localhost:8001/reportes/inventario/excel')">
<span class="material-symbols-outlined" style="font-size:15px;">grid_on</span> XLSX
</button>
<button type="button" class="btn-download bg-blue-600 hover:bg-blue-700 text-white" onclick="descargar('inv','http://localhost:8001/reportes/inventario/word')">
<span class="material-symbols-outlined" style="font-size:15px;">article</span> DOCX
</button>
</div>
</div>


<!-- 2. PEDIDOS -->
<div class="glass-panel rounded-xl p-6 flex flex-col" id="card-pedidos">
<div class="flex items-center gap-4 mb-4">
<div class="report-icon-circle bg-emerald-500/20">
<span class="material-symbols-outlined text-emerald-400" style="font-size:26px;">package_2</span>
</div>
<div>
<h3 class="font-bold text-lg">Pedidos</h3>
<p class="text-xs text-gray-400">Historial, estatus y fechas de entrega</p>
</div>
</div>

<div class="grid grid-cols-2 gap-3 mb-1">
<div>
<label class="text-xs text-gray-400">Desde</label>
<input type="text" class="date-input" id="ped-desde">
<div class="field-error-report" id="err-ped-desde">Selecciona una fecha de inicio.</div>
</div>
<div>
<label class="text-xs text-gray-400">Hasta</label>
<input type="text" class="date-input" id="ped-hasta">
<div class="field-error-report" id="err-ped-hasta">Selecciona una fecha final.</div>
</div>
</div>

<div class="grid grid-cols-4 gap-2 mt-auto pt-3">
<button type="button" class="btn-download bg-gray-600 hover:bg-gray-700 text-white" onclick="descargar('ped','http://localhost:8001/reportes/pedidos/pdf')">
<span class="material-symbols-outlined" style="font-size:15px;">visibility</span> Ver
</button>
<button type="button" class="btn-download bg-red-600 hover:bg-red-700 text-white" onclick="descargar('ped','http://localhost:8001/reportes/pedidos/pdf')">
<span class="material-symbols-outlined" style="font-size:15px;">picture_as_pdf</span> PDF
</button>
<button type="button" class="btn-download bg-emerald-600 hover:bg-emerald-700 text-white" onclick="descargar('ped','http://localhost:8001/reportes/pedidos/excel')">
<span class="material-symbols-outlined" style="font-size:15px;">grid_on</span> XLSX
</button>
<button type="button" class="btn-download bg-blue-600 hover:bg-blue-700 text-white" onclick="descargar('ped','http://localhost:8001/reportes/pedidos/word')">
<span class="material-symbols-outlined" style="font-size:15px;">article</span> DOCX
</button>
</div>
</div>


<!-- 3. VENTAS -->
<div class="glass-panel rounded-xl p-6 flex flex-col" id="card-ventas">
<div class="flex items-center gap-4 mb-4">
<div class="report-icon-circle bg-amber-500/20">
<span class="material-symbols-outlined text-amber-400" style="font-size:26px;">payments</span>
</div>
<div>
<h3 class="font-bold text-lg">Ventas</h3>
<p class="text-xs text-gray-400">Ingresos totales y productos más vendidos</p>
</div>
</div>

<div class="grid grid-cols-2 gap-3 mb-1">
<div>
<label class="text-xs text-gray-400">Desde</label>
<input type="text" class="date-input" id="ven-desde">
<div class="field-error-report" id="err-ven-desde">Selecciona una fecha de inicio.</div>
</div>
<div>
<label class="text-xs text-gray-400">Hasta</label>
<input type="text" class="date-input" id="ven-hasta">
<div class="field-error-report" id="err-ven-hasta">Selecciona una fecha final.</div>
</div>
</div>

<div class="grid grid-cols-4 gap-2 mt-auto pt-3">
<button type="button" class="btn-download bg-gray-600 hover:bg-gray-700 text-white" onclick="descargar('ven','http://localhost:8001/reportes/ventas/pdf')">
<span class="material-symbols-outlined" style="font-size:15px;">visibility</span> Ver
</button>
<button type="button" class="btn-download bg-red-600 hover:bg-red-700 text-white" onclick="descargar('ven','http://localhost:8001/reportes/ventas/pdf')">
<span class="material-symbols-outlined" style="font-size:15px;">picture_as_pdf</span> PDF
</button>
<button type="button" class="btn-download bg-emerald-600 hover:bg-emerald-700 text-white" onclick="descargar('ven','http://localhost:8001/reportes/ventas/excel')">
<span class="material-symbols-outlined" style="font-size:15px;">grid_on</span> XLSX
</button>
<button type="button" class="btn-download bg-blue-600 hover:bg-blue-700 text-white" onclick="descargar('ven','http://localhost:8001/reportes/ventas/word')">
<span class="material-symbols-outlined" style="font-size:15px;">article</span> DOCX
</button>
</div>
</div>


<!-- 4. CLIENTES -->
<div class="glass-panel rounded-xl p-6 flex flex-col" id="card-clientes">
<div class="flex items-center gap-4 mb-4">
<div class="report-icon-circle bg-rose-500/20">
<span class="material-symbols-outlined text-rose-400" style="font-size:26px;">contacts</span>
</div>
<div>
<h3 class="font-bold text-lg">Clientes</h3>
<p class="text-xs text-gray-400">Directorio, actividad y datos de contacto</p>
</div>
</div>

<div class="grid grid-cols-2 gap-3 mb-1">
<div>
<label class="text-xs text-gray-400">Desde</label>
<input type="text" class="date-input" id="cli-desde">
<div class="field-error-report" id="err-cli-desde">Selecciona una fecha de inicio.</div>
</div>
<div>
<label class="text-xs text-gray-400">Hasta</label>
<input type="text" class="date-input" id="cli-hasta">
<div class="field-error-report" id="err-cli-hasta">Selecciona una fecha final.</div>
</div>
</div>

<div class="grid grid-cols-4 gap-2 mt-auto pt-3">
<button type="button" class="btn-download bg-gray-600 hover:bg-gray-700 text-white" onclick="descargar('cli','http://localhost:8001/reportes/clientes/pdf')">
<span class="material-symbols-outlined" style="font-size:15px;">visibility</span> Ver
</button>
<button type="button" class="btn-download bg-red-600 hover:bg-red-700 text-white" onclick="descargar('cli','http://localhost:8001/reportes/clientes/pdf')">
<span class="material-symbols-outlined" style="font-size:15px;">picture_as_pdf</span> PDF
</button>
<button type="button" class="btn-download bg-emerald-600 hover:bg-emerald-700 text-white" onclick="descargar('cli','http://localhost:8001/reportes/clientes/excel')">
<span class="material-symbols-outlined" style="font-size:15px;">grid_on</span> XLSX
</button>
<button type="button" class="btn-download bg-blue-600 hover:bg-blue-700 text-white" onclick="descargar('cli','http://localhost:8001/reportes/clientes/word')">
<span class="material-symbols-outlined" style="font-size:15px;">article</span> DOCX
</button>
</div>
</div>

</div>

</main>

</div>

<!-- SCRIPTS -->
<script>
const API = 'http://localhost:8001';

// Cargar estadísticas en vivo
async function loadStats() {
  try {
    const [prod, ped, usr, vend] = await Promise.all([
      fetch(API + '/reportes/total-productos').then(r => r.json()),
      fetch(API + '/reportes/pedidos').then(r => r.json()),
      fetch(API + '/reportes/clientes').then(r => r.json()),
      fetch(API + '/reportes/mas-vendidos').then(r => r.json())
    ]);
    document.getElementById('stat-productos').textContent = prod.total_productos || 0;
    document.getElementById('stat-pedidos').textContent = ped.total_pedidos || 0;
    document.getElementById('stat-usuarios').textContent = Array.isArray(usr) ? usr.length : 0;
    document.getElementById('stat-vendidos').textContent = Object.keys(vend).length || 0;
  } catch(e) {
    console.log('Stats error:', e);
  }
}
loadStats();

// Validación de fechas con texto rojo
function validarFechas(prefix) {
  const desde = document.getElementById(prefix + '-desde');
  const hasta = document.getElementById(prefix + '-hasta');
  const errDesde = document.getElementById('err-' + prefix + '-desde');
  const errHasta = document.getElementById('err-' + prefix + '-hasta');

  let valido = true;

  // Reset
  desde.classList.remove('input-error');
  hasta.classList.remove('input-error');
  errDesde.style.display = 'none';
  errHasta.style.display = 'none';
  errHasta.textContent = 'Selecciona una fecha final.';

  if (!desde.value) {
    desde.classList.add('input-error');
    errDesde.style.display = 'block';
    valido = false;
  }

  if (!hasta.value) {
    hasta.classList.add('input-error');
    errHasta.style.display = 'block';
    valido = false;
  }

  if (desde.value && hasta.value && desde.value > hasta.value) {
    hasta.classList.add('input-error');
    errHasta.textContent = 'La fecha final debe ser posterior a la inicial.';
    errHasta.style.display = 'block';
    valido = false;
  }

  return valido;
}

// Descargar reporte — valida fechas, luego abre la URL
function descargar(prefix, url) {
  if (validarFechas(prefix)) {
    const desde = document.getElementById(prefix + '-desde').value;
    const hasta = document.getElementById(prefix + '-hasta').value;
    window.open(url + '?desde=' + desde + '&hasta=' + hasta, '_blank');
  }
}
</script>

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>
<script>
  flatpickr(".date-input", {
    locale: "es",
    dateFormat: "Y-m-d",
    minDate: "2020-01-01",
    maxDate: "today",
  });
</script>

</body>
</html>