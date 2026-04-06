from pydantic import BaseModel

class AutoparteBase(BaseModel):
    nombre: str
    descripcion: str
    precio: float
    stock: int

class AutoparteResponse(AutoparteBase):
    id: int

    class Config:
        from_attributes = True