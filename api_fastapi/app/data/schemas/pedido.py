from pydantic import BaseModel
from typing import List, Optional

class ProductoPedido(BaseModel):
    id: int
    cantidad: int

class PedidoBase(BaseModel):
    usuario_id: int
    productos: List[ProductoPedido]

class PedidoEstado(BaseModel):
    estado: str  # recibido, en_proceso, enviado, entregado, cancelado
    paqueteria: Optional[str] = None
    num_seguimiento: Optional[str] = None