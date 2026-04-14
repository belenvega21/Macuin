from pydantic import BaseModel

class UsuarioBase(BaseModel):
    nombre: str
    correo: str
    password: str

class UsuarioResponse(UsuarioBase):
    id: int
    imagen_perfil: str | None = None

    class Config:
        from_attributes = True