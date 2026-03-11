<!DOCTYPE html>
<html class="dark" lang="es">
<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login | MACUIN Autopartes</title>

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
yellowaccent: "#FFD700",
backgrounddark: "#121212"
},
fontFamily: {
display: ["Space Grotesk"],
body: ["Noto Sans"]
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

</style>

</head>

<body class="bg-backgrounddark text-white font-body min-h-screen flex items-center justify-center relative overflow-hidden">

<!-- Fondo -->
<div class="absolute inset-0">

<div class="absolute bottom-[-10%] left-[-10%] w-[80%] h-[60%] bg-gradient-to-tr from-primary/20 to-transparent rounded-full blur-[150px]"></div>

<div class="absolute top-[-10%] right-[-10%] w-[60%] h-[60%] bg-gradient-to-bl from-yellowaccent/10 to-transparent rounded-full blur-[120px]"></div>

</div>


<main class="relative z-10 w-full max-w-lg p-6">

<div class="flex flex-col items-center mb-8">

<h1 class="text-3xl font-display font-bold">
MACUIN <span class="text-primary">AUTOPARTES</span>
</h1>

<p class="text-gray-400 text-sm mt-1">
INGRESO AL SISTEMA
</p>

</div>


<div class="glass-panel rounded-2xl p-8 w-full">

<h2 class="text-xl font-bold mb-6 text-center">
Inicio de Sesión
</h2>


<form method="POST" action="/admin">

@csrf


<div class="mb-4">

<label class="text-xs text-gray-400">Rol</label>

<select name="rol" class="w-full mt-1 p-3 bg-black border border-gray-700 rounded">

<option>Administrador</option>


</select>

</div>


<div class="mb-4">

<label class="text-xs text-gray-400">Correo de empleado</label>

<input type="email"
name="email"
class="w-full mt-1 p-3 bg-black border border-gray-700 rounded"
placeholder="correo@macuin.com">

</div>


<div class="mb-6">

<label class="text-xs text-gray-400">Contraseña</label>

<input type="password"
name="password"
class="w-full mt-1 p-3 bg-black border border-gray-700 rounded"
placeholder="********">

</div>


<button class="w-full bg-yellowaccent text-black font-bold py-3 rounded hover:bg-yellow-400 transition">

INGRESAR

</button>

</form>

</div>

</main>

</body>
</html>