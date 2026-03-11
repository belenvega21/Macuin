<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});




Route::get('/admin', function () {
    return view('admin.dashboard');
});



Route::get('/inventario', function () {
    return view('admin.inventario');
});

Route::get('/inventario/crear', function () {
    return view('admin.crear_pieza');
});

Route::get('/inventario/editar', function () {
    return view('admin.editar_pieza');
});



Route::get('/pedidos', function () {
    return view('admin.pedidos');
});

Route::get('/pedido-detalle', function () {
    return view('admin.pedido_detalle');
});



Route::get('/clientes', function () {
    return view('admin.clientes');
});

Route::get('/historial-cliente', function () {
    return view('admin.historial_cliente');
});



Route::get('/reportes', function () {
    return view('admin.reportes');
});

Route::get('/reporte-pdf', function () {
    return view('admin.reporte_pdf');
});