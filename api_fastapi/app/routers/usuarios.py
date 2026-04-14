from fastapi import APIRouter, Depends, Form, HTTPException, File, UploadFile
from sqlalchemy.orm import Session

from app.data.database import SessionLocal
from app.data.models.usuario import Usuario
from app.core.security import get_password_hash, verify_password, create_access_token, get_current_user

router = APIRouter(prefix="/usuarios", tags=["Usuarios"])

# DB
def get_db():
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()


# REGISTRO
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
        return {"error": "Las contraseñas no coinciden"}

    user_exist = db.query(Usuario).filter(Usuario.email == email).first()
    if user_exist:
        return {"error": "El correo ya está registrado"}

    hashed_pass = get_password_hash(password)

    nuevo = Usuario(
        nombre=nombre,
        email=email,
        telefono=telefono,
        password=hashed_pass
    )

    db.add(nuevo)
    db.commit()
    db.refresh(nuevo)

    return {"msg": "Usuario registrado exitosamente", "usuario_id": nuevo.id}


# LOGIN (sin candadito — es público)
@router.post("/login")
def login(
    email: str = Form(None),
    username: str = Form(None),
    password: str = Form(...),
    grant_type: str = Form(None),
    client_id: str = Form(None),
    client_secret: str = Form(None),
    db: Session = Depends(get_db)
):
    correo_evaluar = email or username
    if not correo_evaluar:
        return {"error": "Se requiere email o username"}

    user = db.query(Usuario).filter(Usuario.email == correo_evaluar).first()

    if not user or not verify_password(password, user.password):
        return {"error": "Credenciales inválidas"}

    access_token = create_access_token(
        data={"sub": user.email, "usuario_id": user.id, "rol": user.rol}
    )

    return {
        "msg": "Login exitoso", 
        "access_token": access_token, 
        "token_type": "bearer",
        "usuario_id": user.id, 
        "nombre": user.nombre, 
        "email": user.email, 
        "rol": user.rol,
        "imagen_perfil": user.imagen_perfil
    }


# DATOS DEL USUARIO AUTENTICADO
@router.get("/me", summary="Datos del usuario autenticado")
def datos_usuario_actual(current_user: dict = Depends(get_current_user), db: Session = Depends(get_db)):
    user = db.query(Usuario).filter(Usuario.email == current_user["email"]).first()
    if not user:
        raise HTTPException(status_code=404, detail="Usuario no encontrado")
    return {
        "id": user.id,
        "nombre": user.nombre,
        "email": user.email,
        "telefono": user.telefono,
        "rol": user.rol,
        "imagen_perfil": user.imagen_perfil
    }


# LISTAR TODOS LOS USUARIOS (CRUD - Read All)
@router.get("/", summary="Listar todos los usuarios")
def listar_usuarios(current_user: dict = Depends(get_current_user), db: Session = Depends(get_db)):
    usuarios = db.query(Usuario).all()
    return [
        {
            "id": u.id,
            "nombre": u.nombre,
            "email": u.email,
            "telefono": u.telefono,
            "rol": u.rol
        }
        for u in usuarios
    ]


# OBTENER USUARIO POR ID (CRUD - Read One)
@router.get("/{usuario_id}", summary="Obtener usuario por ID")
def obtener_usuario(usuario_id: int, current_user: dict = Depends(get_current_user), db: Session = Depends(get_db)):
    user = db.query(Usuario).filter(Usuario.id == usuario_id).first()
    if not user:
        raise HTTPException(status_code=404, detail="Usuario no encontrado")
    return {
        "id": user.id,
        "nombre": user.nombre,
        "email": user.email,
        "telefono": user.telefono,
        "rol": user.rol
    }


# ACTUALIZAR USUARIO (CRUD - Update)
@router.put("/{usuario_id}", summary="Actualizar perfil de usuario")
def actualizar_usuario(
    usuario_id: int,
    nombre: str = Form(...),
    email: str = Form(...),
    telefono: str = Form(...),
    rol: str = Form("cliente"),
    current_user: dict = Depends(get_current_user),
    db: Session = Depends(get_db)
):
    user = db.query(Usuario).filter(Usuario.id == usuario_id).first()
    if not user:
        raise HTTPException(status_code=404, detail="Usuario no encontrado")

    user.nombre = nombre
    user.email = email
    user.telefono = telefono
    user.rol = rol
    db.commit()
    return {"msg": f"Usuario {usuario_id} actualizado exitosamente"}


# SUBIR FOTO DE PERFIL
@router.post("/{usuario_id}/upload_perfil", summary="Subir imagen de perfil")
def upload_perfil(
    usuario_id: int, 
    file: UploadFile = File(...), 
    current_user: dict = Depends(get_current_user), 
    db: Session = Depends(get_db)
):
    import os
    import secrets
    
    user = db.query(Usuario).filter(Usuario.id == usuario_id).first()
    if not user:
        raise HTTPException(status_code=404, detail="Usuario no encontrado")

    # Si es un empleado normal o el propio usuario
    if current_user["rol"] != "admin" and current_user["email"] != user.email:
        raise HTTPException(status_code=403, detail="No tienes permisos para alterar este usuario")

    base_dir = os.path.dirname(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))
    img_dir = os.path.join(base_dir, "static", "img_perfiles")
    os.makedirs(img_dir, exist_ok=True)

    ext = file.filename.split('.')[-1]
    filename = f"user_{user.id}_{secrets.token_hex(4)}.{ext}"
    file_path = os.path.join(img_dir, filename)

    with open(file_path, "wb") as buffer:
        buffer.write(file.file.read())

    url = f"http://localhost:8001/static/img_perfiles/{filename}"
    user.imagen_perfil = url
    db.commit()

    return {"msg": "Imagen de perfil actualizada", "url": url}