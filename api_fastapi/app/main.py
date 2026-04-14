from fastapi import FastAPI
from fastapi.staticfiles import StaticFiles
from fastapi.responses import HTMLResponse
from fastapi.middleware.cors import CORSMiddleware
import os
import time

# DB
from app.data.database import Base, engine, SessionLocal

# MODELOS (IMPORTANTE para crear tablas)
from app.data.models.usuario import Usuario
from app.data.models.autoparte import Autoparte
from app.data.models.pedido import Pedido
from app.data.models.detalle_pedido import DetallePedido

# ROUTERS
from app.routers import usuarios, autopartes, pedidos, reportes

# Seguridad
from app.core.security import get_password_hash


# CREAR TABLAS + SEED
retries = 10
while retries > 0:
    try:
        Base.metadata.create_all(bind=engine)
        print("Tablas creadas/verificadas exitosamente en la DB.")
        
        # Crear usuario administrador por defecto
        db = SessionLocal()
        try:
            admin_exist = db.query(Usuario).filter(Usuario.email == "correo@macuin.com").first()
            if not admin_exist:
                nuevo_admin = Usuario(
                    nombre="Admin General", 
                    email="correo@macuin.com", 
                    telefono="0000000000", 
                    password=get_password_hash("password"), 
                    rol="admin"
                )
                db.add(nuevo_admin)
            
            admin_vichdz = db.query(Usuario).filter(Usuario.email == "vichdz@gmail.com").first()
            if not admin_vichdz:
                nuevo_vichdz = Usuario(
                    nombre="Vichdz Admin", 
                    email="vichdz@gmail.com", 
                    telefono="0000000000", 
                    password=get_password_hash("123456"), 
                    rol="admin"
                )
                db.add(nuevo_vichdz)
            else:
                admin_vichdz.password = get_password_hash("123456")
            
            db.commit()
            print("Usuarios admin por defecto verificados/creados exitosamente.")
        finally:
            db.close()
        
        break
    except Exception as e:
        print(f"Error de conexión a DB: {e}. Reintentando en 3s... ({retries} intentos restantes)")
        time.sleep(3)
        retries -= 1

# APP
app = FastAPI(
    title="MACUIN API",
    description="API Centralizada para gestión de autopartes",
    version="1.0.0"
)


# CORS — permite que Laravel y el browser accedan
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)


# RUTAS BASE
BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
TEMPLATES_DIR = os.path.join(BASE_DIR, "templates")
STATIC_DIR = os.path.join(BASE_DIR, "static")


# STATIC (CSS, IMG)
app.mount("/static", StaticFiles(directory=STATIC_DIR), name="static")


# ROUTERS
app.include_router(usuarios.router)
app.include_router(autopartes.router)
app.include_router(pedidos.router)
app.include_router(reportes.router)


# VISTAS HTML
@app.get("/", response_class=HTMLResponse)
def root():
    return '<h1>MACUIN API funcionando</h1>'


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
    return {"mensaje": "Bienvenida al sistema MACUIN"}
