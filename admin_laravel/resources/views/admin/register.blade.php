<!DOCTYPE html>
<html class="dark" lang="es">

<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Registro Administrador | MACUIN</title>

<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;700&family=Noto+Sans:wght@400;500;700&display=swap" rel="stylesheet">

<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">

<script src="https://cdn.tailwindcss.com"></script>

<script>
tailwind.config = {
darkMode: "class",
theme: {
extend: {
colors: {
primary: "#bd0000",
accentyellow: "#FFD700",
backgrounddark: "#121212",
surfacedark:"#1e1e1e"
},
fontFamily: {
display:["Space Grotesk"],
body:["Noto Sans"]
}
}
}
}
</script>

<style>

.glass-panel{
background:rgba(18,18,18,.7);
backdrop-filter:blur(20px);
border:1px solid rgba(189,0,0,.3);
box-shadow:0 0 40px rgba(189,0,0,.1);
}

.glow-border::before{
content:'';
position:absolute;
inset:-1px;
border-radius:inherit;
background:linear-gradient(45deg,#bd0000,transparent,#bd0000);
opacity:.3;
filter:blur(10px);
}

</style>

</head>


<body class="bg-backgrounddark text-white font-body min-h-screen flex items-center justify-center relative overflow-hidden">

<!-- fondo -->
<div class="absolute inset-0">

<div class="absolute bottom-[-10%] left-[-10%] w-[80%] h-[60%] bg-gradient-to-tr from-primary/20 to-transparent rounded-full blur-[150px]"></div>

<div class="absolute top-[-10%] right-[-10%] w-[60%] h-[60%] bg-gradient-to-bl from-accentyellow/10 to-transparent rounded-full blur-[120px]"></div>

</div>


<main class="relative z-10 w-full max-w-lg p-6 my-10">


<div class="flex flex-col items-center mb-8">

<h1 class="text-3xl font-display font-bold">
MACUIN <span class="text-primary">AUTOPARTES</span>
</h1>

<p class="text-gray-400 text-sm">
SISTEMA DE ADMINISTRACIÓN
</p>

</div>


<div class="glass-panel glow-border rounded-2xl p-8 w-full relative">

<h2 class="text-xl font-bold mb-6 text-center">
Registro de Equipo Administrativo
</h2>


<form method="POST" action="/register" class="space-y-5">

@csrf


<!-- clave secreta -->

<div>

<label class="text-xs text-gray-400">Clave secreta admin</label>

<input type="text"
name="clave"
class="w-full p-3 mt-1 bg-black border border-gray-700 rounded"
placeholder="Clave de autorización">

</div>


<!-- nombre -->

<div>

<label class="text-xs text-gray-400">Nombre completo</label>

<input type="text"
name="nombre"
class="w-full p-3 mt-1 bg-black border border-gray-700 rounded"
placeholder="Nombre completo">

</div>


<!-- correo -->

<div>

<label class="text-xs text-gray-400">Correo de trabajo</label>

<input type="email"
name="email"
class="w-full p-3 mt-1 bg-black border border-gray-700 rounded"
placeholder="correo@macuin.com">

</div>


<!-- rol -->

<div>

<label class="text-xs text-gray-400">Rol</label>

<select name="rol" class="w-full p-3 mt-1 bg-black border border-gray-700 rounded">

<option>Ventas</option>
<option>Almacén</option>
<option>Gerencia</option>

</select>

</div>


<!-- contraseña -->

<div>

<label class="text-xs text-gray-400">Contraseña</label>

<input type="password"
name="password"
class="w-full p-3 mt-1 bg-black border border-gray-700 rounded">

</div>


<div>

<label class="text-xs text-gray-400">Confirmar contraseña</label>

<input type="password"
name="password_confirmation"
class="w-full p-3 mt-1 bg-black border border-gray-700 rounded">

</div>


<button class="w-full bg-accentyellow text-black font-bold py-3 rounded hover:bg-yellow-400">

AUTORIZAR ACCESO

</button>


</form>


<div class="mt-6 text-center">

<p class="text-sm text-gray-400">

¿Ya eres administrador?

<a href="/login" class="text-accentyellow font-bold">
Inicia sesión aquí
</a>

</p>

</div>

</div>

</main>

</body>
</html>