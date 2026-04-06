from pydantic import BaseModel

class UsuarioBase(BaseModel):
    nombre: str
    correo: str
    password: str

class UsuarioResponse(UsuarioBase):
    id: int

    class Config:
        from_attributes = True