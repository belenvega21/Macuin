from flask import Flask, render_template, request, redirect, url_for, send_file
from reportlab.pdfgen import canvas
import os

app = Flask(__name__)

# =========================
# 🧠 "BASE DE DATOS"
# =========================
users_db = {}
USER_LOGGED = None

# =========================
# 📦 PRODUCTOS
# =========================
productos = [
    {
        "id": 1,
        "nombre": "Filtro de aire",
        "descripcion": "Alta eficiencia",
        "precio": 250,
        "imagen": "/static/img/Filtro de aire.webp",
        "categoria": "motor",
        "marca": "ford",
        "modelo": "focus"
    },
    {
        "id": 2,
        "nombre": "Bujía premium",
        "descripcion": "Mayor rendimiento",
        "precio": 180,
        "imagen": "/static/img/Bujía premium.webp",
        "categoria": "motor",
        "marca": "bmw",
        "modelo": "m3"
    },
    {
        "id": 3,
        "nombre": "Aceite sintético",
        "descripcion": "Protección total",
        "precio": 900,
        "imagen": "/static/img/Aceite sintético.jpg",
        "categoria": "aceite",
        "marca": "chevrolet",
        "modelo": "aveo"
    },
    {
        "id": 4,
        "nombre": "Pastillas de freno",
        "descripcion": "Alta resistencia",
        "precio": 600,
        "imagen": "/static/img/pastillas de freno.jpg",
        "categoria": "frenos",
        "marca": "ford",
        "modelo": "mustang"
    },
    {
        "id": 5,
        "nombre": "Amortiguadores",
        "descripcion": "Mejor estabilidad",
        "precio": 1200,
        "imagen": "/static/img/Amortiguadores.jpeg",
        "categoria": "suspension",
        "marca": "audi",
        "modelo": "a4"
    },
    {
        "id": 6,
        "nombre": "Radiador",
        "descripcion": "Sistema de enfriamiento",
        "precio": 1500,
        "imagen": "/static/img/Radiador.jpeg",
        "categoria": "motor",
        "marca": "bmw",
        "modelo": "x5"
    }
]

marcas = [
    {"nombre": "FORD", "imagen": "/static/img/ford.png"},
    {"nombre": "BMW", "imagen": "/static/img/bmw.png"},
    {"nombre": "TOYOTA", "imagen": "/static/img/toyota.png"},
    {"nombre": "CHEVROLET", "imagen": "/static/img/che.png"}
]

# =========================
# 🛒 CARRITOS Y PEDIDOS
# =========================
carritos = {}
pedidos = {}

# =========================
# 🔐 LOGIN PAGE
# =========================
@app.route("/login")
def login():
    return render_template("login.html")

# =========================
# 🔐 LOGIN
# =========================
@app.route("/login", methods=["POST"])
def do_login():
    global USER_LOGGED

    email = request.form["email"]
    password = request.form["password"]

    if email in users_db and users_db[email]["password"] == password:
        USER_LOGGED = email
        return redirect("/inicio")

    return redirect("/login")

# =========================
# 📝 REGISTER PAGE
# =========================
@app.route("/register")
def register():
    return render_template("register.html")

# =========================
# 📝 REGISTER
# =========================
@app.route("/auth/registro", methods=["POST"])
def do_register():
    nombre = request.form["nombre"]
    email = request.form["email"]
    telefono = request.form["telefono"]
    password = request.form["password"]
    confirmar = request.form["confirmar"]

    if password != confirmar or email in users_db:
        return redirect("/register")

    users_db[email] = {
        "nombre": nombre,
        "telefono": telefono,
        "password": password
    }

    return redirect("/login")

# =========================
# 🏠 HOME
# =========================
@app.route("/inicio")
def inicio():
    global USER_LOGGED

    if not USER_LOGGED:
        return redirect("/login")

    return render_template("inicio.html", productos=productos, marcas=marcas)

# =========================
# 📦 CATÁLOGO
# =========================
@app.route("/catalogo")
def catalogo():
    busqueda = request.args.get("q", "").lower()
    categoria = request.args.get("categoria", "").lower()
    marca = request.args.get("marca", "").lower()

    filtrados = productos

    if busqueda:
        filtrados = [p for p in filtrados if busqueda in p["nombre"].lower()]

    if categoria:
        filtrados = [p for p in filtrados if p["categoria"].lower() == categoria]

    if marca:
        filtrados = [p for p in filtrados if p["marca"].lower() == marca]

    return render_template("catalogo.html", productos=filtrados, marcas=marcas)

# =========================
# ➕ AGREGAR AL CARRITO (CORRECTO)
# =========================
@app.route("/agregar/<int:producto_id>")
def agregar_carrito(producto_id):
    global USER_LOGGED

    if not USER_LOGGED:
        return redirect("/login")

    if USER_LOGGED not in carritos:
        carritos[USER_LOGGED] = []

    producto = next((p for p in productos if p["id"] == producto_id), None)

    if producto:
        carritos[USER_LOGGED].append(producto)

    return redirect("/carrito")

# =========================
# 🛒 VER CARRITO
# =========================
@app.route("/carrito")
def ver_carrito():
    global USER_LOGGED

    if not USER_LOGGED:
        return redirect("/login")

    items = carritos.get(USER_LOGGED, [])
    total = sum(p["precio"] for p in items)

    return render_template("carrito.html", carrito=items, total=total)

# =========================
# 💳 COMPRAR
# =========================
@app.route("/comprar")
def comprar():
    global USER_LOGGED

    if USER_LOGGED not in carritos or len(carritos[USER_LOGGED]) == 0:
        return redirect("/catalogo")

    if USER_LOGGED not in pedidos:
        pedidos[USER_LOGGED] = []

    pedidos[USER_LOGGED].append({
        "productos": carritos[USER_LOGGED],
        "estado": "En proceso"
    })

    carritos[USER_LOGGED] = []

    return redirect("/perfil")

# =========================
# 👤 PERFIL
# =========================
@app.route("/perfil")
def perfil():
    global USER_LOGGED

    if not USER_LOGGED:
        return redirect("/login")

    user_pedidos = pedidos.get(USER_LOGGED, [])

    total_gastado = 0
    for pedido in user_pedidos:
        for p in pedido["productos"]:
            total_gastado += p["precio"]

    return render_template(
        "perfil.html",
        pedidos=user_pedidos,
        total_gastado=total_gastado
    )

# =========================
# 🧾 GENERAR PDF
# =========================
@app.route("/recibo/<int:pedido_id>")
def generar_recibo(pedido_id):
    global USER_LOGGED

    if not USER_LOGGED:
        return redirect("/login")

    user_pedidos = pedidos.get(USER_LOGGED, [])

    if pedido_id >= len(user_pedidos):
        return "Pedido no encontrado"

    pedido = user_pedidos[pedido_id]

    file_path = f"recibo_{USER_LOGGED}_{pedido_id}.pdf"

    c = canvas.Canvas(file_path)

    c.setFont("Helvetica-Bold", 16)
    c.drawString(180, 800, "RECIBO DE COMPRA")

    c.setFont("Helvetica", 12)
    c.drawString(50, 750, f"Cliente: {USER_LOGGED}")
    c.drawString(50, 730, f"Pedido ID: {pedido_id}")

    y = 700
    total = 0

    for p in pedido["productos"]:
        c.drawString(50, y, f"{p['nombre']} - ${p['precio']}")
        total += p["precio"]
        y -= 20

    c.setFont("Helvetica-Bold", 12)
    c.drawString(50, y - 10, f"Total: ${total}")

    c.save()

    return send_file(file_path, as_attachment=True)

# =========================
# 🚪 LOGOUT
# =========================
@app.route("/logout")
def logout():
    global USER_LOGGED
    USER_LOGGED = None
    return redirect("/login")

# =========================
# 🚀 RUN SERVER
# =========================
if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000, debug=True)