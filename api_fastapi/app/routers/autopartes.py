from fastapi import APIRouter, Depends
from sqlalchemy.orm import Session
from app.data.database import SessionLocal
from app.models.autoparte import Autoparte
from app.schemas.autoparte import AutoparteBase

router = APIRouter(prefix="/autopartes", tags=["Autopartes"])

def get_db():
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()

# GET
@router.get("/")
def obtener_autopartes(db: Session = Depends(get_db)):
    return db.query(Autoparte).all()

# POST
@router.post("/")
def crear_autoparte(data: AutoparteBase, db: Session = Depends(get_db)):
    nuevo = Autoparte(**data.dict())
    db.add(nuevo)
    db.commit()
    db.refresh(nuevo)
    return nuevo

# PUT
@router.put("/{id}")
def actualizar_autoparte(id: int, data: AutoparteBase, db: Session = Depends(get_db)):
    item = db.query(Autoparte).filter(Autoparte.id == id).first()
    if item:
        item.nombre = data.nombre
        item.descripcion = data.descripcion
        item.precio = data.precio
        item.stock = data.stock
        db.commit()
        return item
    return {"error": "No encontrado"}

# DELETE
@router.delete("/{id}")
def eliminar_autoparte(id: int, db: Session = Depends(get_db)):
    item = db.query(Autoparte).filter(Autoparte.id == id).first()
    if item:
        db.delete(item)
        db.commit()
        return {"msg": "Eliminado"}
    return {"error": "No encontrado"}