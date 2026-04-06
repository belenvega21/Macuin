from fastapi import APIRouter, Depends
from sqlalchemy.orm import Session
from app.data.database import SessionLocal
from app.models.autoparte import Autoparte
from app.models.pedido import Pedido
from app.models.detalle_pedido import DetallePedido
import pandas as pd
from fastapi.responses import FileResponse
from docx import Document 
from reportlab.platypus import SimpleDocTemplate, Paragraph
from reportlab.lib.styles import getSampleStyleSheet

router = APIRouter(prefix="/reportes", tags=["Reportes"])


def get_db():
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()


# INVENTARIO
@router.get("/inventario")
def reporte_inventario(db: Session = Depends(get_db)):
    return db.query(Autoparte).all()


# TOTAL PRODUCTOS
@router.get("/total-productos")
def total_productos(db: Session = Depends(get_db)):
    total = db.query(Autoparte).count()
    return {"total_productos": total}


# PEDIDOS
@router.get("/pedidos")
def total_pedidos(db: Session = Depends(get_db)):
    total = db.query(Pedido).count()
    return {"total_pedidos": total}


# MÁS VENDIDOS
@router.get("/mas-vendidos")
def productos_mas_vendidos(db: Session = Depends(get_db)):
    detalles = db.query(DetallePedido).all()

    conteo = {}
    for d in detalles:
        conteo[d.autoparte_id] = conteo.get(d.autoparte_id, 0) + d.cantidad

    return conteo


# EXCEL
@router.get("/inventario/excel")
def exportar_excel(db: Session = Depends(get_db)):
    productos = db.query(Autoparte).all()

    data = [
        {"nombre": p.nombre, "precio": p.precio, "stock": p.stock}
        for p in productos
    ]

    df = pd.DataFrame(data)
    file_path = "/tmp/reporte.xlsx"
    df.to_excel(file_path, index=False)

    return FileResponse(file_path, filename="reporte.xlsx")


# WORD
@router.get("/inventario/word")
def exportar_word(db: Session = Depends(get_db)):
    productos = db.query(Autoparte).all()

    doc = Document()
    doc.add_heading("Reporte de Inventario", 0)

    for p in productos:
        doc.add_paragraph(f"{p.nombre} - ${p.precio} - Stock: {p.stock}")

    file_path = "/tmp/reporte.docx"
    doc.save(file_path)

    return FileResponse(file_path, filename="reporte.docx")


# PDF
@router.get("/inventario/pdf")
def exportar_pdf(db: Session = Depends(get_db)):
    productos = db.query(Autoparte).all()

    file_path = "/tmp/reporte.pdf"
    doc = SimpleDocTemplate(file_path)
    styles = getSampleStyleSheet()

    contenido = []
    contenido.append(Paragraph("Reporte de Inventario", styles["Title"]))

    for p in productos:
        texto = f"{p.nombre} - ${p.precio} - Stock: {p.stock}"
        contenido.append(Paragraph(texto, styles["Normal"]))

    doc.build(contenido)

    return FileResponse(file_path, filename="reporte.pdf")