@extends('admin.dashboard')

@section('titulo', 'Analíticas y Reportes')

@section('contenido')
<div class="flex flex-col lg:flex-row items-center justify-between gap-4 mb-8">
    <div class="flex items-center gap-4">
        <div class="w-16 h-16 bg-purple-500/10 rounded-[1.5rem] flex items-center justify-center text-purple-400 shadow-[0_0_20px_rgba(168,85,247,0.15)]">
            <span class="material-symbols-rounded text-[32px]">insights</span>
        </div>
        <div>
            <h2 class="text-4xl font-black tracking-tight mb-1">Métricas Inteligentes</h2>
            <p class="text-gray-400 font-medium">Informes consolidados y análisis de volumen para métricas clave.</p>
        </div>
    </div>
    
    <!-- Filtro de Fechas Profesional (Flatpickr) -->
    <div class="bg-[#111] border border-white/5 rounded-[2rem] p-2 flex items-center gap-3 shadow-2xl">
        <span class="material-symbols-rounded text-gray-500 pl-3">calendar_month</span>
        <div class="flex items-center gap-2 bg-black/40 border border-white/10 rounded-xl px-4 py-2 group hover:border-purple-400/50 transition focus-within:border-purple-400">
            <input type="text" id="rango_fechas" placeholder="Rango de Análisis..." class="bg-transparent text-sm text-white focus:outline-none w-48 font-bold">
            <span class="material-symbols-rounded text-gray-600 text-[18px] group-hover:text-purple-400 transition">event</span>
        </div>
        <button onclick="limpiarFiltro()" class="p-2.5 rounded-xl bg-white/5 text-gray-500 hover:bg-white/10 hover:text-white transition border border-white/5" title="Limpiar Filtros">
            <span class="material-symbols-rounded text-[20px]">filter_alt_off</span>
        </button>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    
    <!-- 1. Módulo Exportar Inventario -->
    <div class="card p-8 flex flex-col justify-between group overflow-hidden relative min-h-[300px]">
        <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-yellow-400/10 rounded-full blur-3xl pointer-events-none transition group-hover:bg-yellow-400/20"></div>
        <div>
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-white/5 rounded-2xl flex items-center justify-center text-gray-300 border border-white/5 group-hover:scale-110 transition">
                    <span class="material-symbols-rounded text-[28px]">inventory_2</span>
                </div>
                <span class="px-3 py-1 bg-yellow-400/10 text-yellow-400 font-bold text-[10px] uppercase tracking-widest rounded-full border border-yellow-400/20">Módulo Directorio</span>
            </div>
            <h3 class="text-2xl font-bold text-white mb-2 leading-tight">Stock Activo (Inventario)</h3>
            <p class="text-gray-400 text-sm font-medium mb-6">Genera un documento profesional con la lista completa de autopartes, imágenes y valoración de existencias.</p>
        </div>

        <!-- Botones Inventario -->
        <div class="flex flex-wrap gap-3 mt-auto items-center">
            <button onclick="previewJSON('inventario')" class="px-4 py-3 rounded-full bg-white/5 hover:bg-white/10 transition text-sm font-bold text-white flex items-center gap-2">
                <span class="material-symbols-rounded text-[18px]">visibility</span> Ver
            </button>
            <div class="h-6 w-px bg-white/10 mx-1"></div>
            <a href="http://localhost:8001/reportes/inventario/pdf" target="_blank" 
               class="flex items-center gap-2 bg-yellow-400 text-black font-black px-5 py-3 rounded-full hover:bg-yellow-500 shadow-[0_0_15px_rgba(255,193,7,0.3)] hover:shadow-[0_0_25px_rgba(255,193,7,0.5)] transition hover:-translate-y-1 text-sm">
                PDF
            </a>
            <a href="http://localhost:8001/reportes/inventario/excel" target="_blank" class="flex items-center gap-2 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-bold px-5 py-3 rounded-full hover:bg-emerald-500 hover:text-white transition text-sm">
                Excel
            </a>
            <a href="http://localhost:8001/reportes/inventario/word" target="_blank" class="flex items-center gap-2 bg-blue-500/10 text-blue-400 border border-blue-500/20 font-bold px-5 py-3 rounded-full hover:bg-blue-500 hover:text-white transition text-sm">
                Word
            </a>
        </div>
    </div>

    <!-- 2. Módulo Exportar Clientes -->
    <div class="card p-8 flex flex-col justify-between group overflow-hidden relative min-h-[300px]">
        <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-pink-500/10 rounded-full blur-3xl pointer-events-none transition group-hover:bg-pink-500/20"></div>
        <div>
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-white/5 rounded-2xl flex items-center justify-center text-gray-300 border border-white/5 group-hover:scale-110 transition">
                    <span class="material-symbols-rounded text-[28px]">group</span>
                </div>
                <span class="px-3 py-1 bg-pink-500/10 text-pink-400 font-bold text-[10px] uppercase tracking-widest rounded-full border border-pink-500/20">Módulo Usuarios</span>
            </div>
            <h3 class="text-2xl font-bold text-white mb-2 leading-tight">Directorio de Clientes</h3>
            <p class="text-gray-400 text-sm font-medium mb-6">Listado de cuentas maestras del ecosistema. Exporta registros de usuarios con sus perfiles asignados.</p>
        </div>

        <div class="flex flex-wrap gap-3 mt-auto items-center">
            <button onclick="previewJSON('clientes')" class="px-4 py-3 rounded-full bg-white/5 hover:bg-white/10 transition text-sm font-bold text-white flex items-center gap-2">
                <span class="material-symbols-rounded text-[18px]">visibility</span> Ver
            </button>
            <div class="h-6 w-px bg-white/10 mx-1"></div>
            <a href="http://localhost:8001/reportes/clientes/pdf" target="_blank" class="flex items-center gap-2 bg-pink-500 text-white font-black px-5 py-3 rounded-full hover:bg-pink-600 shadow-[0_0_15px_rgba(236,72,153,0.4)] transition hover:-translate-y-1 text-sm">
                PDF
            </a>
            <a href="http://localhost:8001/reportes/clientes/excel" target="_blank" class="flex items-center gap-2 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-bold px-5 py-3 rounded-full hover:bg-emerald-500 hover:text-white transition text-sm">
                Excel
            </a>
            <a href="http://localhost:8001/reportes/clientes/word" target="_blank" class="flex items-center gap-2 bg-blue-500/10 text-blue-400 border border-blue-500/20 font-bold px-5 py-3 rounded-full hover:bg-blue-500 hover:text-white transition text-sm">
                Word
            </a>
        </div>
    </div>

    <!-- 3. Módulo Exportar Pedidos (Historial) -->
    <div class="card p-8 flex flex-col justify-between group overflow-hidden relative min-h-[300px]">
        <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-sky-500/10 rounded-full blur-3xl pointer-events-none transition group-hover:bg-sky-500/20"></div>
        <div>
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-white/5 rounded-2xl flex items-center justify-center text-gray-300 border border-white/5 group-hover:scale-110 transition">
                    <span class="material-symbols-rounded text-[28px]">local_shipping</span>
                </div>
                <span class="px-3 py-1 bg-sky-500/10 text-sky-400 font-bold text-[10px] uppercase tracking-widest rounded-full border border-sky-500/20">Módulo Operaciones</span>
            </div>
            <h3 class="text-2xl font-bold text-white mb-2 leading-tight">Historial de Pedidos</h3>
            <p class="text-gray-400 text-sm font-medium mb-6">Auditoría logística de todas las órdenes, estados de paquetería y recepción. (Reacciona al filtro de fechas).</p>
        </div>

        <div class="flex flex-wrap gap-3 mt-auto items-center">
            <button onclick="previewJSON('pedidos')" class="px-4 py-3 rounded-full bg-white/5 hover:bg-white/10 transition text-sm font-bold text-white flex items-center gap-2">
                <span class="material-symbols-rounded text-[18px]">visibility</span> Ver
            </button>
            <div class="h-6 w-px bg-white/10 mx-1"></div>
            <a href="#" onclick="downloadFiltered(event, '/reportes/pedidos/pdf')" 
               class="flex items-center gap-2 bg-sky-500 text-white font-black px-5 py-3 rounded-full hover:bg-sky-600 shadow-[0_0_15px_rgba(14,165,233,0.4)] transition hover:-translate-y-1 text-sm">
                PDF
            </a>
            <a href="#" onclick="downloadFiltered(event, '/reportes/pedidos/excel')" class="flex items-center gap-2 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-bold px-5 py-3 rounded-full hover:bg-emerald-500 hover:text-white transition text-sm">
                Excel
            </a>
            <a href="#" onclick="downloadFiltered(event, '/reportes/pedidos/word')" class="flex items-center gap-2 bg-blue-500/10 text-blue-400 border border-blue-500/20 font-bold px-5 py-3 rounded-full hover:bg-blue-500 hover:text-white transition text-sm">
                Word
            </a>
        </div>
    </div>

    <!-- 4. Módulo Exportar Ventas -->
    <div class="card p-8 flex flex-col justify-between group overflow-hidden relative min-h-[300px]">
        <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-purple-500/10 rounded-full blur-3xl pointer-events-none transition group-hover:bg-purple-500/20"></div>
        <div>
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-white/5 rounded-2xl flex items-center justify-center text-gray-300 border border-white/5 group-hover:scale-110 transition">
                    <span class="material-symbols-rounded text-[28px]">monitoring</span>
                </div>
                <span class="px-3 py-1 bg-purple-500/10 text-purple-400 font-bold text-[10px] uppercase tracking-widest rounded-full border border-purple-500/20">Módulo Analítico</span>
            </div>
            <h3 class="text-2xl font-bold text-white mb-2 leading-tight">Reporte de Ventas</h3>
            <p class="text-gray-400 text-sm font-medium mb-6">Extrae reportes detallados sobre la rentabilidad y líneas populares (Reacciona al filtro de fechas).</p>
        </div>

        <div class="flex flex-wrap gap-3 mt-auto items-center">
            <button onclick="previewJSON('ventas')" class="px-4 py-3 rounded-full bg-white/5 hover:bg-white/10 transition text-sm font-bold text-white flex items-center gap-2">
                <span class="material-symbols-rounded text-[18px]">visibility</span> Ver
            </button>
            <div class="h-6 w-px bg-white/10 mx-1"></div>
            <a href="#" onclick="downloadFiltered(event, '/reportes/ventas/pdf')" 
               class="flex items-center gap-2 bg-purple-500 text-white font-black px-5 py-3 rounded-full hover:bg-purple-600 shadow-[0_0_15px_rgba(168,85,247,0.4)] transition hover:-translate-y-1 text-sm">
                PDF
            </a>
            <a href="#" onclick="downloadFiltered(event, '/reportes/ventas/excel')" class="flex items-center gap-2 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-bold px-5 py-3 rounded-full hover:bg-emerald-500 hover:text-white transition text-sm">
                Excel
            </a>
            <a href="#" onclick="downloadFiltered(event, '/reportes/ventas/word')" class="flex items-center gap-2 bg-blue-500/10 text-blue-400 border border-blue-500/20 font-bold px-5 py-3 rounded-full hover:bg-blue-500 hover:text-white transition text-sm">
                Word
            </a>
        </div>
    </div>

</div>

<!-- System Info Card -->
<div class="card p-8 bg-gradient-to-r from-[#111] to-[#151515] border-white/5 flex flex-col md:flex-row items-center justify-between gap-6 mb-10">
    <div class="flex items-center gap-6">
        <div class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center border border-white/10">
            <span class="material-symbols-rounded text-[32px] text-gray-500">cloud_done</span>
        </div>
        <div>
            <h4 class="font-bold text-white text-lg">Reportes Generados en Vivo</h4>
            <p class="text-gray-400 text-sm">El motor FastAPI construye los 4 documentos con la última información y filtro de fechas al instante.</p>
        </div>
    </div>
    <span class="px-4 py-2 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-full font-bold text-xs uppercase tracking-widest flex items-center gap-2">
        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span> Sistema en línea
    </span>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    var API = 'http://localhost:8001';

    const Toast = Swal.mixin({
        toast: true, position: 'bottom-end', showConfirmButton: false, timer: 3000,
        background: '#111', color: '#fff', customClass: { popup: 'rounded-2xl border border-gray-800' }
    });

    let picker;
    document.addEventListener('DOMContentLoaded', () => {
        picker = flatpickr("#rango_fechas", {
            mode: "range",
            dateFormat: "Y-m-d",
            locale: "es"
        });
    });

    function getDateParams() {
        if (!picker || !picker.selectedDates || picker.selectedDates.length < 1) return { start: '', end: '' };
        
        const start = picker.selectedDates[0] ? picker.selectedDates[0].toISOString().split('T')[0] : '';
        const end = picker.selectedDates[1] ? picker.selectedDates[1].toISOString().split('T')[0] : '';
        return { start, end };
    }

    function limpiarFiltro() {
        picker.clear();
        Toast.fire({ icon: 'success', title: 'Filtros de análisis restablecidos' });
    }

    // Función que arma URLs con filtros y las abre
    function downloadFiltered(e, endpoint) {
        e.preventDefault();
        const { start, end } = getDateParams();

        // Si es un módulo que depende de fechas, validar
        if ((endpoint.includes('pedidos') || endpoint.includes('ventas')) && !start && !end) {
            Swal.fire({
                html: `
                    <div class="text-center">
                        <div class="w-20 h-20 rounded-full bg-yellow-400/10 text-yellow-400 flex items-center justify-center mx-auto mb-6 border border-yellow-400/20 shadow-[0_0_20px_rgba(250,204,21,0.2)]">
                            <span class="material-symbols-rounded text-4xl">date_range</span>
                        </div>
                        <h3 class="text-xl font-black text-white mb-2">¿Generar sin filtro?</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">No seleccionaste un rango de fechas. Se exportarán <span class="font-bold text-white">todos los registros</span> disponibles.</p>
                    </div>
                `,
                background: '#111', color: '#fff', width: 420,
                customClass: { popup: 'rounded-[2rem] border border-white/10' },
                showCancelButton: true,
                confirmButtonColor: '#facc15',
                cancelButtonColor: '#374151',
                confirmButtonText: '<span class="text-black font-bold">Sí, exportar todo</span>',
                cancelButtonText: 'Seleccionar fechas'
            }).then(result => {
                if (result.isConfirmed) {
                    const url = new URL(API + endpoint);
                    window.open(url.toString(), '_blank');
                }
            });
            return;
        }

        let url = new URL(API + endpoint);
        if (start) url.searchParams.append('start_date', start + 'T00:00:00');
        if (end) url.searchParams.append('end_date', end + 'T23:59:59');
        window.open(url.toString(), '_blank');
        Toast.fire({icon:'success', title:'Generando documento...'});
    }

    // Modal para pre-visualizar datos como PDF embebido
    async function previewJSON(tipo) {
        Swal.fire({
            html: `
                <div class="text-center py-8">
                    <span class="material-symbols-rounded animate-spin text-[40px] text-yellow-400 mb-4">progress_activity</span>
                    <p class="text-gray-400 font-bold">Construyendo vista previa...</p>
                </div>
            `,
            background: '#111', color: '#fff', width: 400,
            customClass: { popup: 'rounded-[2rem] border border-white/10' },
            allowOutsideClick: false, showConfirmButton: false
        });

        try {
            const { start, end } = getDateParams();

            let pdfEndpoint = '';
            if (tipo === 'inventario') pdfEndpoint = '/reportes/inventario/pdf';
            if (tipo === 'clientes') pdfEndpoint = '/reportes/clientes/pdf';
            if (tipo === 'pedidos') pdfEndpoint = '/reportes/pedidos/pdf';
            if (tipo === 'ventas') pdfEndpoint = '/reportes/ventas/pdf';

            let pdfUrl = new URL(API + pdfEndpoint);
            if (tipo === 'pedidos' || tipo === 'ventas') {
                if (start) pdfUrl.searchParams.append('start_date', start + 'T00:00:00');
                if (end) pdfUrl.searchParams.append('end_date', end + 'T23:59:59');
            }
            pdfUrl = pdfUrl.toString();

            Swal.fire({
                html: `
                    <div class="text-left">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-purple-500/10 rounded-xl flex items-center justify-center text-purple-400 border border-purple-500/20">
                                <span class="material-symbols-rounded">description</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-white text-lg">Vista Previa</h3>
                                <p class="text-xs text-gray-500 uppercase tracking-widest font-bold">${tipo}</p>
                            </div>
                        </div>
                        <div class="w-full h-[60vh] rounded-2xl overflow-hidden border border-white/10 bg-gray-900">
                            <iframe src="${pdfUrl}" width="100%" height="100%" frameborder="0"></iframe>
                        </div>
                    </div>
                `,
                width: '85%',
                background: '#111', color: '#fff',
                customClass: { popup: 'rounded-[2rem] border border-white/10 shadow-2xl' },
                confirmButtonColor: '#eab308',
                confirmButtonText: '<span class="text-black font-bold">Cerrar Vista</span>',
                showCloseButton: true
            });
        } catch(e) {
            Swal.fire({
                html: `
                    <div class="text-center">
                        <div class="w-20 h-20 rounded-full bg-red-500/10 text-red-500 flex items-center justify-center mx-auto mb-6 border border-red-500/20">
                            <span class="material-symbols-rounded text-4xl">error</span>
                        </div>
                        <h3 class="text-xl font-black text-white mb-2">Error de Conexión</h3>
                        <p class="text-gray-400 text-sm">No se pudo generar la vista previa. Verifica que FastAPI esté en línea.</p>
                    </div>
                `,
                background: '#111', color: '#fff', width: 400,
                customClass: { popup: 'rounded-[2rem] border border-white/10' },
                confirmButtonColor: '#ef4444', confirmButtonText: 'Cerrar'
            });
        }
    }
</script>

@endsection