from fastapi import APIRouter

router = APIRouter()

@router.get("/productos")
def obtener_productos():
    return [
        {
            "id": 1,
            "nombre": "Filtro de aire",
            "descripcion": "Alta eficiencia",
            "precio": 250,
            "imagen": "https://via.placeholder.com/300"
        }
    ]