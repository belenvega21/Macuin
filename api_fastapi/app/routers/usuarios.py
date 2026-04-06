from fastapi import APIRouter, Depends, Form
from sqlalchemy.orm import Session
from fastapi.responses import RedirectResponse

from app.data.database import SessionLocal
from app.models.usuario import Usuario

router = APIRouter()

# 🔌 DB
def get_db():
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()


# =========================
# REGISTRO
# =========================
@router.post("/registro")
def registro(
    nombre: str = Form(...),
    email: str = Form(...),
    telefono: str = Form(...),
    password: str = Form(...),
    confirmar: str = Form(...),
    db: Session = Depends(get_db)
):
    if password != confirmar:
        return RedirectResponse("/register?error=1", status_code=303)

    user_exist = db.query(Usuario).filter(Usuario.email == email).first()
    if user_exist:
        return RedirectResponse("/register?error=2", status_code=303)

    nuevo = Usuario(
        nombre=nombre,
        email=email,
        telefono=telefono,
        password=password
    )

    db.add(nuevo)
    db.commit()

    return RedirectResponse("/login", status_code=303)


# =========================
# LOGIN
# =========================
@router.post("/login")
def login(
    email: str = Form(...),
    password: str = Form(...),
    db: Session = Depends(get_db)
):
    user = db.query(Usuario).filter(Usuario.email == email).first()

    if not user or user.password != password:
        return RedirectResponse("/login?error=1", status_code=303)

    return RedirectResponse("/inicio", status_code=303)