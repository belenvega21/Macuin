from pydantic import BaseModel
from typing import List

class ProductoPedido(BaseModel):
    id: int
    cantidad: int

class PedidoBase(BaseModel):
    usuario_id: int
    productos: List[ProductoPedido]