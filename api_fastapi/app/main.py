from fastapi import FastAPI
from fastapi.staticfiles import StaticFiles
from fastapi.responses import HTMLResponse
import os

# DB
from app.data.database import Base, engine

# MODELOS (IMPORTANTE para crear tablas)
from app.models.usuario import Usuario
from app.models.autoparte import Autoparte
from app.models.pedido import Pedido
from app.models.detalle_pedido import DetallePedido
from app.routers import productos  



# ROUTERS
from app.routers import usuarios


# =========================
# 🚀 APP
# =========================
app = FastAPI()


# =========================
# 📁 RUTAS BASE (ARREGLADAS)
# =========================
# Esto sube un nivel: de /app/app → /app
BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))

TEMPLATES_DIR = os.path.join(BASE_DIR, "templates")
STATIC_DIR = os.path.join(BASE_DIR, "static")


# =========================
# 📁 STATIC (CSS, IMG)
# =========================
app.mount("/static", StaticFiles(directory=STATIC_DIR), name="static")


# =========================
# 🔌 ROUTERS
# =========================
app.include_router(usuarios.router)
app.include_router(productos.router)


# =========================
# 🧠 CREAR TABLAS
# =========================
Base.metadata.create_all(bind=engine)


# =========================
# 🌐 VISTAS HTML
# =========================
@app.get("/", response_class=HTMLResponse)
def root():
    return '<h1>MACUIN API funcionando 🔥</h1>'


@app.get("/login", response_class=HTMLResponse)
def login_page():
    with open(os.path.join(TEMPLATES_DIR, "login.html"), encoding="utf-8") as f:
        return f.read()


@app.get("/register", response_class=HTMLResponse)
def register_page():
    with open(os.path.join(TEMPLATES_DIR, "register.html"), encoding="utf-8") as f:
        return f.read()


@app.get("/inicio")
def inicio():
    return {"mensaje": "Bienvenida al sistema MACUIN 🔥"}


