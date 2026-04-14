from pydantic import BaseModel
from typing import Optional

class AutoparteBase(BaseModel):
    sku: Optional[str] = None
    nombre: str
    descripcion: str
    precio: float
    stock: int
    imagen: Optional[str] = "/static/img/car.webp"
    categoria: Optional[str] = "motor"
    marca: Optional[str] = "desconocida"
    modelo: Optional[str] = "general"

class AutoparteResponse(AutoparteBase):
    id: int

    class Config:
        from_attributes = True