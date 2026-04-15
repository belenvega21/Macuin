@extends('admin.dashboard')

@section('titulo', 'Gestión de Órdenes')

@section('contenido')
<div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 mb-8">
    <div class="flex items-center gap-4">
        <div class="w-16 h-16 bg-emerald-500/10 rounded-[1.5rem] flex items-center justify-center text-emerald-400 shadow-[0_0_20px_rgba(16,185,129,0.15)]">
            <span class="material-symbols-rounded text-[32px]">local_mall</span>
        </div>
        <div>
            <h2 class="text-4xl font-black tracking-tight mb-1">Centro de Órdenes</h2>
            <p class="text-gray-400 font-medium">Procesamiento y seguimiento de ventas corporativas.</p>
        </div>
    </div>

    <!-- Filtro de Fechas Global (Professional Flatpickr) -->
    <div class="bg-[#111] border border-white/5 rounded-[2rem] p-3 flex items-center gap-3 shadow-2xl">
        <span class="material-symbols-rounded text-gray-500 pl-3">calendar_month</span>
        <div class="flex items-center gap-2 bg-black/40 border border-white/10 rounded-xl px-4 py-2 hover:border-yellow-400/50 transition focus-within:border-yellow-400">
            <input type="text" id="rango_fechas" placeholder="Seleccionar rango..." class="bg-transparent text-sm text-white focus:outline-none w-48 font-medium">
            <span class="material-symbols-rounded text-gray-600 text-[18px]">event</span>
        </div>
        <button onclick="aplicarFiltroFechas()" class="bg-yellow-400 text-black font-black px-6 py-2.5 rounded-xl hover:bg-yellow-500 transition text-sm flex items-center gap-2 shadow-[0_0_20px_rgba(255,193,7,0.2)]">
            <span class="material-symbols-rounded text-[20px]">filter_alt</span> Filtrar
        </button>
        <button onclick="limpiarFiltroFechas()" class="bg-white/5 text-gray-400 font-bold px-3 py-2.5 rounded-xl hover:bg-white/10 transition text-sm border border-white/5" title="Limpiar filtro">
            <span class="material-symbols-rounded text-[20px]">filter_alt_off</span>
        </button>
    </div>
</div>

<div class="card p-8 min-h-[500px]">

    <div class="flex justify-between items-center mb-8">
        <div class="flex gap-4 flex-wrap">
            <button onclick="filtrarEstado(''); document.querySelectorAll('.tab-btn').forEach(b=>b.classList.remove('bg-white/10','text-white')); this.classList.add('bg-white/10','text-white');"
                    class="tab-btn px-6 py-2.5 rounded-full text-sm font-bold bg-white/10 text-white hover:bg-white/20 transition border border-white/5">
                Todos
            </button>
            <button onclick="filtrarEstado('recibido'); document.querySelectorAll('.tab-btn').forEach(b=>b.classList.remove('bg-white/10','text-white')); this.classList.add('bg-white/10','text-white');"
                    class="tab-btn px-6 py-2.5 rounded-full text-sm font-bold text-gray-500 hover:bg-white/20 hover:text-white transition border border-transparent">
                <span class="w-2 h-2 rounded-full bg-blue-400 inline-block mr-2"></span>Recibidos
            </button>
            <button onclick="filtrarEstado('en_proceso'); document.querySelectorAll('.tab-btn').forEach(b=>b.classList.remove('bg-white/10','text-white')); this.classList.add('bg-white/10','text-white');"
                    class="tab-btn px-6 py-2.5 rounded-full text-sm font-bold text-gray-500 hover:bg-white/20 hover:text-white transition border border-transparent">
                <span class="w-2 h-2 rounded-full bg-yellow-400 inline-block mr-2"></span>En Proceso
            </button>
            <button onclick="filtrarEstado('entregado'); document.querySelectorAll('.tab-btn').forEach(b=>b.classList.remove('bg-white/10','text-white')); this.classList.add('bg-white/10','text-white');"
                    class="tab-btn px-6 py-2.5 rounded-full text-sm font-bold text-gray-500 hover:bg-white/20 hover:text-white transition border border-transparent">
                <span class="w-2 h-2 rounded-full bg-emerald-400 inline-block mr-2"></span>Entregados
            </button>
        </div>
    </div>

    <!-- Tabla de Pedidos Profesional -->
    <div class="overflow-x-auto rounded-[1.5rem] border border-white/5 bg-white/[0.01]">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-[11px] text-gray-500 uppercase tracking-[0.2em] font-black border-b border-white/5 bg-black/20">
                    <th class="px-8 py-6">ID / Fase</th>
                    <th class="px-8 py-6">Cliente Corporativo</th>
                    <th class="px-8 py-6">Inversión</th>
                    <th class="px-8 py-6">Última Actividad</th>
                    <th class="px-8 py-6 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla-pedidos" class="divide-y divide-white/[0.03]">
                <tr>
                    <td colspan="5" class="py-32 text-center">
                        <span class="material-symbols-rounded animate-spin text-[48px] text-gray-700">sync</span>
                        <p class="text-gray-500 mt-4 font-bold tracking-widest text-xs uppercase">Sincronizando Órdenes...</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>



</div>

<script>
    let pedidosCache = @json($pedidos) || [];
    let usuariosCache = {};
    let productosCache = {};

    try {
        const initialUsers = @json($usuarios) || [];
        const initialProds = @json($productos) || [];
        
        if (Array.isArray(initialUsers)) {
            initialUsers.forEach(u => { if(u && u.id) usuariosCache[u.id] = u; });
        }
        if (Array.isArray(initialProds)) {
            initialProds.forEach(p => { if(p && p.id) productosCache[p.id] = p; });
        }
    } catch (e) {
        console.error("Error inicializando caché desde Blade:", e);
    }

    function logDebug(msg, isError = false) {
        console.log(`[Diagnostic] ${msg}`);
        const dbg = document.getElementById('debug-log');
        const lines = document.getElementById('debug-lines');
        if (dbg && lines) {
            dbg.classList.remove('hidden');
            const line = document.createElement('div');
            line.className = isError ? 'text-red-400' : 'text-gray-400';
            line.innerHTML = `<span class="text-white opacity-20">[${new Date().toLocaleTimeString()}]</span> ${msg}`;
            lines.prepend(line);
        }
    }

    window.onerror = (msg, url, line) => { logDebug(`CRITICAL JS: ${msg} line ${line}`, true); return false; };

    let picker;
    logDebug("Cargando script de dashboard...");

    // EJECUCIÓN INMEDIATA SIN EVENTOS DEFERIDOS O BLOQUEADOS
    logDebug("Iniciando aplicación (Synchronous)...");
    try {
        picker = flatpickr("#rango_fechas", {
            mode: "range", dateFormat: "Y-m-d", locale: "es",
            onClose: (selectedDates) => { if(selectedDates.length === 2) aplicarFiltroFechas(); }
        });
        logDebug("Calendario listo.");
    } catch (e) { logDebug("Fallo Calendario: " + e.message, true); }
    
    const count = Array.isArray(pedidosCache) ? pedidosCache.length : 0;
    logDebug(`Datos iniciales (Blade): ${count} pedidos. Usuarios: ${Object.keys(usuariosCache).length}`);
    
    if (count > 0) {
        try {
            renderTablaPedidos(pedidosCache);
        } catch(e) {
            document.getElementById('tabla-pedidos').innerHTML = `<tr><td colspan="5" class="py-20 text-center text-red-500 font-bold">ERROR FATAL AL RENDERIZAR CACHE INICIAL: ${e.message} <br> ${e.stack}</td></tr>`;
        }
    } else {
        logDebug("Sin pedidos en caché inicial, esperando sync API...");
    }
    
    cargarPedidos();

    async function cargarPedidos() {
        try {
            const authHeaders = JWT_TOKEN ? { 'Authorization': 'Bearer ' + JWT_TOKEN } : {};
            const [pedidosRes, usuariosRes, productosRes] = await Promise.all([
                fetch(API + '/pedidos/', { headers: authHeaders }),
                fetch(API + '/usuarios/', { headers: authHeaders }),
                fetch(API + '/autopartes/', { headers: authHeaders })
            ]);

            if (!pedidosRes.ok || !usuariosRes.ok || !productosRes.ok) {
                throw new Error(`Error en la respuesta del servidor (Pedidos: ${pedidosRes.status}, Usuarios: ${usuariosRes.status}, Productos: ${productosRes.status})`);
            }

            pedidosCache = await pedidosRes.json();
            const usrArray = await usuariosRes.json();
            if(Array.isArray(usrArray)) usrArray.forEach(u => usuariosCache[u.id] = u);
            const prodArray = await productosRes.json();
            if(Array.isArray(prodArray)) prodArray.forEach(p => productosCache[p.id] = p);

            renderTablaPedidos(pedidosCache);
        } catch (e) {
            console.error('Error de sincronización:', e);
            document.getElementById('tabla-pedidos').innerHTML = `
                <tr>
                    <td colspan="5" class="py-20 text-center text-red-500 font-bold font-mono text-sm uppercase tracking-widest">
                        Error de Sincronización Core<br>
                        <span class="text-[10px] text-gray-500 mt-2 block">${e.message}</span>
                    </td>
                </tr>`;
        }
    }

    function renderTablaPedidos(data) {
        const container = document.getElementById('tabla-pedidos');
        if (data.length === 0) {
            container.innerHTML = `<tr><td colspan="5" class="py-32 text-center bg-white/[0.01]"><span class="material-symbols-rounded text-[64px] text-gray-700 mb-4 tracking-tighter">inventory_2</span><p class="text-gray-500 font-bold text-lg tracking-tight">Sin registros detectados</p></td></tr>`;
            return;
        }

        container.innerHTML = data.map(p => {
            const us = usuariosCache[p.usuario_id] || { nombre: 'Desconocido', email: 'N/A' };
            const isDone = (p.estado || '').toLowerCase() === 'entregado';
            const isCanc = (p.estado || '').toLowerCase() === 'cancelado';
            const status = (p.estado || 'recibido').toLowerCase();

            let badgeBase = 'bg-yellow-400/5 text-yellow-500 border-yellow-400/20';
            let dotColor = 'bg-yellow-400';
            if(isDone) { badgeBase = 'bg-emerald-500/5 text-emerald-400 border-emerald-500/20'; dotColor = 'bg-emerald-400'; }
            else if(isCanc) { badgeBase = 'bg-red-500/5 text-red-500 border-red-500/20'; dotColor = 'bg-red-500'; }
            else if(status === 'en_ruta' || status === 'enviado') { badgeBase = 'bg-blue-500/5 text-blue-400 border-blue-500/20'; dotColor = 'bg-blue-400'; }
            else if(status === 'recibido') { badgeBase = 'bg-sky-500/5 text-sky-400 border-sky-500/20'; dotColor = 'bg-sky-400'; }

            const totalNum = parseFloat(p.total) || 0;
            const totalStr = '$' + totalNum.toLocaleString('en-US', {minimumFractionDigits:2});
            const dateStr = p.fecha ? String(p.fecha).replace('T', ' ').split('.')[0] : 'N/A';

            return `
            <tr class="group hover:bg-white/[0.02] transition-colors border-b border-white/[0.03] last:border-none">
                <td class="px-8 py-6">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-white/5 rounded-xl flex items-center justify-center font-mono text-[10px] font-black text-gray-500 border border-white/5 group-hover:border-yellow-400/30 group-hover:text-yellow-400 transition-all shadow-inner">
                            #${p.id}
                        </div>
                        <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-[0.1em] border ${badgeBase} flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full ${dotColor} ${!isDone && !isCanc ? 'animate-pulse' : ''}"></span>
                            ${status.replace('_', ' ')}
                        </span>
                    </div>
                </td>
                <td class="px-8 py-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-gray-800 to-gray-700 flex items-center justify-center text-[11px] font-black text-white border border-white/10 shadow-lg uppercase">
                            ${us.nombre.charAt(0)}
                        </div>
                        <div>
                            <p class="font-bold text-sm text-white leading-tight group-hover:text-yellow-400 transition-colors">${us.nombre}</p>
                            <p class="text-[11px] text-gray-500 mt-0.5 font-medium">${us.email}</p>
                        </div>
                    </div>
                </td>
                <td class="px-8 py-6 text-sm font-black text-white tracking-tight">${totalStr}</td>
                <td class="px-8 py-6">
                    <p class="text-xs text-gray-400 font-bold tracking-tight mb-0.5">${dateStr}</p>
                    <p class="text-[10px] text-gray-500 font-black uppercase tracking-[0.15em] opacity-50">Web Checkout</p>
                </td>
                <td class="px-8 py-6 text-right">
                    <div class="flex justify-end gap-2">
                        <button onclick='verDetallePedido(${p.id})' title="Detalles Inmersivos" class="w-10 h-10 bg-white/5 rounded-xl flex items-center justify-center text-gray-500 hover:bg-white/10 hover:text-white transition-all border border-transparent hover:border-white/10">
                            <span class="material-symbols-rounded text-[20px]">visibility</span>
                        </button>
                        <button onclick='actualizarPedidoModal(${p.id})' title="Gestionar Logística" class="w-10 h-10 bg-white/5 rounded-xl flex items-center justify-center text-yellow-500/70 hover:bg-yellow-400 hover:text-black transition-all border border-transparent hover:border-yellow-400/50">
                            <span class="material-symbols-rounded text-[20px]">settings</span>
                        </button>
                    </div>
                </td>
            </tr>`;
        }).join('');
    }

    function aplicarFiltroFechas() {
        if (!picker.selectedDates || picker.selectedDates.length < 2) {
            renderTablaPedidos(pedidosCache);
            return;
        }
        const start = picker.selectedDates[0];
        const end = picker.selectedDates[1];
        end.setHours(23, 59, 59, 999);

        const filtered = pedidosCache.filter(p => {
            if (!p.fecha) return false;
            // Asegurar que la fecha sea parseable reemplazando espacio por T si es necesario
            const fechaStr = p.fecha.toString();
            const sanitizedDate = fechaStr.includes('T') ? fechaStr : fechaStr.replace(' ', 'T');
            const date = new Date(sanitizedDate);
            return date >= start && date <= end;
        });
        renderTablaPedidos(filtered);
        Toast.fire({ icon: 'info', title: `Filtrado exitoso: ${filtered.length} resultados` });
    }

    function limpiarFiltroFechas() {
        picker.clear();
        renderTablaPedidos(pedidosCache);
        Toast.fire({ icon: 'success', title: 'Filtros restablecidos' });
    }

    function filtrarEstado(estado) {
        if (!estado) {
            renderTablaPedidos(pedidosCache);
            return;
        }
        const filtered = pedidosCache.filter(p => p.estado === estado);
        renderTablaPedidos(filtered);
    }

    function verDetallePedido(pedidoId) {
        const pedido = pedidosCache.find(p => p.id === pedidoId);
        if (!pedido) return;

        const us = usuariosCache[pedido.usuario_id] || { nombre: 'Desconocido', email: 'N/A' };
        const items = pedido.productos || [];
        const detalles = items.map(d => {
            const prod = productosCache[d.autoparte_id] || { nombre: 'Producto #'+d.autoparte_id, precio: 0 };
            return `<tr class="border-b border-white/5 hover:bg-white/[0.02] transition"><td class="py-4 text-sm text-white font-bold">${prod.nombre}</td><td class="py-4 text-sm text-center text-gray-500 font-mono">x${d.cantidad}</td><td class="py-4 text-sm text-right font-black text-white">$${parseFloat(prod.precio * d.cantidad).toLocaleString()}</td></tr>`;
        }).join('');

        Swal.fire({
            html: `
                <div class="text-left">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-16 h-16 bg-yellow-400/10 rounded-2xl flex items-center justify-center font-black text-yellow-400 font-mono text-xl border border-yellow-400/20 shadow-inner">#${pedido.id}</div>
                        <div><h3 class="text-2xl font-black text-white tracking-tight">${us.nombre}</h3><p class="text-gray-500 text-sm font-medium tracking-wide">${us.email}</p></div>
                    </div>
                    <div class="space-y-6 overflow-hidden">
                        <div class="bg-black/40 rounded-3xl border border-white/5 p-6 shadow-inner">
                            <table class="w-full">
                                <thead><tr class="text-[10px] text-gray-500 uppercase tracking-[0.2em] font-black border-b border-white/5"><th class="pb-3 text-left">Concepto</th><th class="pb-3 text-center">Cant.</th><th class="pb-3 text-right">Subtotal</th></tr></thead>
                                <tbody>${detalles}</tbody>
                            </table>
                        </div>
                        <div class="flex justify-between items-center px-4">
                            <span class="text-xs text-gray-500 uppercase font-black tracking-[0.15em]">Total Inversión</span>
                            <span class="text-3xl font-black text-white tracking-tighter">$${parseFloat(pedido.total).toLocaleString()}</span>
                        </div>
                    </div>
                    <div class="mt-8 flex gap-3">
                        <a href="${API}/reportes/pedidos/${pedido.id}/recibo/pdf" target="_blank" class="flex-1 bg-white/5 hover:bg-white/10 text-white font-bold py-4 rounded-2xl transition border border-white/10 flex items-center justify-center gap-2"><span class="material-symbols-rounded">picture_as_pdf</span> Imprimir Ticket</a>
                    </div>
                </div>
            `,
            background: '#111', color: '#fff', width: 500, showConfirmButton: false, showCloseButton: true, customClass: { popup: 'rounded-[2.5rem] border border-white/10 shadow-2xl overflow-hidden' }
        });
    }

    function actualizarPedidoModal(pedidoId) {
        const pedido = pedidosCache.find(p => p.id === pedidoId);
        if (!pedido) return;
        Swal.fire({
            html: `
                <div class="text-left">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-16 h-16 bg-gradient-to-br from-yellow-400/20 to-yellow-400/5 text-yellow-500 flex items-center justify-center rounded-2xl border border-yellow-400/20 shadow-inner">
                            <span class="material-symbols-rounded text-3xl">local_shipping</span>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-white tracking-tight">Estatus Logístico</h3>
                            <p class="text-xs text-gray-500 uppercase tracking-widest font-black">Expediente #${pedido.id}</p>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-3" id="swal-estado-grid">
                            ${['recibido','en_proceso','en_ruta','entregado','cancelado'].map(st => `
                                <button type="button" data-val="${st}" class="estado-opt px-4 py-4 rounded-2xl border text-xs font-black uppercase tracking-widest flex items-center gap-3 transition ${pedido.estado === st ? 'bg-yellow-400/10 border-yellow-400/30 text-yellow-400 shadow-inner' : 'bg-white/5 border-white/5 text-gray-500 hover:border-white/20'}">
                                    <span class="w-2 h-2 rounded-full ${pedido.estado === st ? 'bg-yellow-400 shadow-[0_0_10px_rgba(250,204,21,0.5)]' : 'bg-gray-700'}"></span>
                                    ${st.replace('_',' ')}
                                </button>
                            `).join('')}
                        </div>
                        <input type="hidden" id="swal-estado-val" value="${pedido.estado}">
                        
                        <div class="space-y-3">
                            <label class="text-[10px] text-gray-500 font-black uppercase tracking-widest ml-4">Empresa de Mensajería</label>
                            <select id="swal-paqueteria" class="w-full bg-black/40 border border-white/5 text-white rounded-2xl px-6 py-4 text-sm focus:ring-1 focus:ring-yellow-400 focus:border-yellow-400 transition font-bold appearance-none cursor-pointer">
                                <option value="" ${!pedido.paqueteria ? 'selected' : ''}>Seleccionar paquetería...</option>
                                <option value="DHL" ${pedido.paqueteria === 'DHL' ? 'selected' : ''}>DHL</option>
                                <option value="FedEx" ${pedido.paqueteria === 'FedEx' ? 'selected' : ''}>FedEx</option>
                                <option value="Estafeta" ${pedido.paqueteria === 'Estafeta' ? 'selected' : ''}>Estafeta</option>
                                <option value="UPS" ${pedido.paqueteria === 'UPS' ? 'selected' : ''}>UPS</option>
                                <option value="Paquetexpress" ${pedido.paqueteria === 'Paquetexpress' ? 'selected' : ''}>Paquetexpress</option>
                            </select>
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] text-gray-500 font-black uppercase tracking-widest ml-4">Nº de Seguimiento Global</label>
                            <div class="relative group">
                                <input id="swal-seguimiento" class="w-full bg-black/40 border border-white/5 text-white rounded-2xl px-6 py-4 text-sm focus:ring-1 focus:ring-yellow-400 focus:border-yellow-400 transition font-mono font-bold placeholder-gray-700 pr-24" value="${pedido.num_seguimiento||''}" placeholder="TRKXXXXXXX">
                                <button type="button" onclick="const rnd = 'TRK-' + Math.random().toString(36).substring(2, 6).toUpperCase() + '-' + Math.random().toString(36).substring(2, 6).toUpperCase(); document.getElementById('swal-seguimiento').value = rnd; Swal.clickConfirm();" 
                                        class="absolute right-2 top-2 bottom-2 px-4 rounded-xl bg-yellow-400 text-black text-[10px] font-black uppercase tracking-tighter hover:bg-yellow-500 transition shadow-lg">
                                    Generar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `,
            background: '#111', color: '#fff', width: 480, showCancelButton: true, confirmButtonColor: '#facc15', cancelButtonColor: '#222', 
            confirmButtonText: '<span class="text-black font-black uppercase tracking-widest text-xs">Guardar Cambios</span>', 
            cancelButtonText: '<span class="text-gray-500 font-black uppercase tracking-widest text-xs">Cerrar</span>',
            customClass: { popup: 'rounded-[3rem] border border-white/10 shadow-3xl p-4', confirmButton: 'rounded-2xl py-4 transition-all hover:scale-105 shadow-xl', cancelButton: 'rounded-2xl py-4' },
            didOpen: () => {
                document.querySelectorAll('.estado-opt').forEach(btn => btn.addEventListener('click', function() {
                    document.querySelectorAll('.estado-opt').forEach(b => b.className = 'estado-opt px-4 py-4 rounded-2xl border text-xs font-black uppercase tracking-widest flex items-center gap-3 transition bg-white/5 border-white/5 text-gray-500 hover:border-white/20');
                    this.className = 'estado-opt px-4 py-4 rounded-2xl border text-xs font-black uppercase tracking-widest flex items-center gap-3 transition bg-yellow-400/10 border-yellow-400/30 text-yellow-400 shadow-inner';
                    document.getElementById('swal-estado-val').value = this.dataset.val;
                }));
            },
            preConfirm: () => ({
                estado: document.getElementById('swal-estado-val').value,
                paqueteria: document.getElementById('swal-paqueteria').value.trim(),
                num_seguimiento: document.getElementById('swal-seguimiento').value.trim()
            })
        }).then(result => {
            if (result.isConfirmed) {
                fetch(`${API}/pedidos/${pedido.id}/estado`, {
                    method: 'PUT',
                    headers: { 'Authorization': 'Bearer ' + JWT_TOKEN, 'Content-Type': 'application/json' },
                    body: JSON.stringify(result.value)
                }).then(r => {
                    if(r.ok) { Toast.fire({ icon: 'success', title: 'Orden actualizada Maestro' }); cargarPedidos(); }
                    else { Toast.fire({ icon: 'error', title: 'Error de servidor' }); }
                });
            }
        });
    }
</script>
    <div id="debug-log" class="hidden fixed bottom-4 left-4 bg-black/95 border border-red-500/50 p-5 rounded-2xl z-[9999] max-w-sm max-h-64 overflow-y-auto font-mono text-[10px] text-red-500 shadow-2xl backdrop-blur-xl">
        <div class="flex items-center justify-between mb-3">
            <span class="font-black uppercase tracking-widest text-white flex items-center gap-2"><span class="w-2 h-2 bg-red-500 rounded-full animate-ping"></span> Console de Diagnóstico</span>
            <button onclick="document.getElementById('debug-log').remove()" class="text-white hover:text-red-500 transition">✕</button>
        </div>
        <div id="debug-lines" class="space-y-1.5"></div>
    </div>
@endsection