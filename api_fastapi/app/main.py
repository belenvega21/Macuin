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
from app.data.models.detalle_pedido import DetallePedido
from app.data.models.pedido import Pedido

# ROUTERS
from app.routers import usuarios, autopartes, pedidos, reportes, reportes_extra

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
            # 1. Admin General original
            admin_orig = db.query(Usuario).filter(Usuario.email == "admin@macuin.com").first()
            if not admin_orig:
                db.add(Usuario(
                    nombre="Administrador", email="admin@macuin.com", telefono="1234567890",
                    password=get_password_hash("admin123"), rol="admin"
                ))
            
            # 2. Correo macuin legacy
            admin_mac = db.query(Usuario).filter(Usuario.email == "correo@macuin.com").first()
            if not admin_mac:
                db.add(Usuario(
                    nombre="Macuin Admin", email="correo@macuin.com", telefono="0000000000",
                    password=get_password_hash("password"), rol="admin"
                ))
            
            # 3. Belen (Owner)
            belen_exist = db.query(Usuario).filter(Usuario.email == "belen@macuin.com").first()
            if not belen_exist:
                db.add(Usuario(
                    nombre="Belén Vega", email="belen@macuin.com", telefono="4421234567",
                    password=get_password_hash("belen123"), rol="admin"
                ))

            db.commit()
            print("Usuarios maestros restaurados existosamente.")

            # SEED de Autopartes
            from app.data.models.autoparte import Autoparte
            if db.query(Autoparte).count() == 0:
                print("Catálogo vacío. Insertando dataset de prueba...")
                bateria = Autoparte(
                    nombre="Batería Automotriz LTH", descripcion="Batería premium de alto rendimiento",
                    precio=2150.0, stock=10, marca="LTH", categoria="electrico",
                    imagen="/static/img/BATERÍA AUTOMOTRIZ .webp"
                )
                bujias = Autoparte(
                    nombre="Sistema de Bujías Iridium", descripcion="Bujías de encendido rápido Iridium",
                    precio=450.0, stock=50, marca="NGK", categoria="motor",
                    imagen="/static/img/Bujía premium.webp"
                )
                frenos = Autoparte(
                    nombre="Frenos de Cerámica Premium", descripcion="Balatas de alto frenado y resistencia",
                    precio=950.0, stock=20, marca="Brembo", categoria="frenos",
                    imagen="/static/img/FRENOS DE CERÁMICA.webp"
                )
                filtro = Autoparte(
                    nombre="Filtro de Aire Alto Flujo", descripcion="Mayor entrada de oxígeno para el motor",
                    precio=320.0, stock=30, marca="K&N", categoria="motor",
                    imagen="/static/img/Filtro de aire.webp"
                )
                db.add_all([bateria, bujias, frenos, filtro])
                db.commit()
                print("Dataset de catálogo insertado correctamente.")

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
app.include_router(reportes_extra.router)


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
