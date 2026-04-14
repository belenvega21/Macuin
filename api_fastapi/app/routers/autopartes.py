from fastapi import APIRouter, Depends, HTTPException, File, UploadFile
from sqlalchemy.orm import Session
from app.data.database import SessionLocal
from app.data.models.autoparte import Autoparte
from app.data.schemas.autoparte import AutoparteBase
from app.core.security import get_current_user, get_current_admin
import os
import secrets

router = APIRouter(prefix="/autopartes", tags=["Autopartes"])

def get_db():
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()

# GET — público (catálogo)
@router.get("/", summary="Listar autopartes")
def obtener_autopartes(db: Session = Depends(get_db)):
    return db.query(Autoparte).all()

# GET — por ID
@router.get("/{id}", summary="Obtener autoparte por ID")
def obtener_autoparte(id: int, db: Session = Depends(get_db)):
    item = db.query(Autoparte).filter(Autoparte.id == id).first()
    if not item:
        raise HTTPException(status_code=404, detail="Autoparte no encontrada")
    return item

# POST — protegido
@router.post("/", summary="Crear autoparte")
def crear_autoparte(data: AutoparteBase, current_user: dict = Depends(get_current_admin), db: Session = Depends(get_db)):
    nuevo = Autoparte(**data.dict())
    db.add(nuevo)
    db.commit()
    db.refresh(nuevo)
    return nuevo

# POST — Upload image
@router.post("/upload", summary="Subir imagen de autoparte")
def upload_imagen(file: UploadFile = File(...), current_user: dict = Depends(get_current_admin)):
    # Create the directory if it doesn't exist
    base_dir = os.path.dirname(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))
    img_dir = os.path.join(base_dir, "static", "img_productos")
    os.makedirs(img_dir, exist_ok=True)

    # Generate standard random name
    ext = file.filename.split('.')[-1]
    filename = f"{secrets.token_hex(8)}.{ext}"
    file_path = os.path.join(img_dir, filename)

    with open(file_path, "wb") as buffer:
        buffer.write(file.file.read())

    # Devolver URL asumiendo host en port 8000
    # En producción esto sería variable de entorno o relativo
    url = f"http://localhost:8001/static/img_productos/{filename}"
    return {"url": url}

# PUT — protegido
@router.put("/{id}", summary="Actualizar autoparte")
def actualizar_autoparte(id: int, data: AutoparteBase, current_user: dict = Depends(get_current_user), db: Session = Depends(get_db)):
    item = db.query(Autoparte).filter(Autoparte.id == id).first()
    if not item:
        raise HTTPException(status_code=404, detail="Autoparte no encontrada")
    item.nombre = data.nombre
    item.descripcion = data.descripcion
    item.precio = data.precio
    item.stock = data.stock
    item.imagen = data.imagen
    item.categoria = data.categoria
    item.marca = data.marca
    item.modelo = data.modelo
    db.commit()
    db.refresh(item)
    return item

# DELETE — protegido
@router.delete("/{id}", summary="Eliminar autoparte")
def eliminar_autoparte(id: int, current_user: dict = Depends(get_current_user), db: Session = Depends(get_db)):
    item = db.query(Autoparte).filter(Autoparte.id == id).first()
    if not item:
        raise HTTPException(status_code=404, detail="Autoparte no encontrada")
    db.delete(item)
    db.commit()
    return {"msg": "Eliminado"}