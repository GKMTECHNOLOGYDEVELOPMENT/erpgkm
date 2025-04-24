import smtplib
from email.message import EmailMessage
import pymysql
from datetime import datetime, timedelta

# 🛠 Configuración base de datos
DB_CONFIG = {
    'host': 'localhost',
    'user': 'root',
    'password': '',
    'database': 'erpgkm'
}

# 📬 Configuración de correo
SMTP_SERVER = 'smtp.gmail.com'
SMTP_PORT = 587
EMAIL_ADDRESS = 'gkmtechnology@gmail.com'
EMAIL_PASSWORD = 'wmxo ylzo nwtn opme'

def enviar_correo(destinatario, asunto, cuerpo):
    msg = EmailMessage()
    msg['Subject'] = asunto
    msg['From'] = EMAIL_ADDRESS
    msg['To'] = destinatario
    msg.set_content(cuerpo)

    with smtplib.SMTP(SMTP_SERVER, SMTP_PORT) as smtp:
        smtp.starttls()
        smtp.login(EMAIL_ADDRESS, EMAIL_PASSWORD)
        smtp.send_message(msg)
        print(f"📤 Correo enviado a {destinatario}")

def verificar_solicitudes():
    conn = pymysql.connect(**DB_CONFIG)
    cursor = conn.cursor(pymysql.cursors.DictCursor)

    # 📅 Buscar solicitudes recientes
    tiempo_limite = datetime.now() - timedelta(minutes=10)
    query = """
        SELECT 
            s.idSolicitudentrega, s.comentario, s.fechaHora, s.idTipoServicio,
            t.numero_ticket, t.idTickets, t.idCliente, t.direccion, t.fallaReportada,
            t.fecha_creacion, t.idTienda, t.serie, t.idMarca, t.idModelo,
            t.lat, t.lng, t.linkubicacion
        FROM solicitudentrega s
        JOIN tickets t ON s.idTickets = t.idTickets
        WHERE s.estado = 0 AND s.fechaHora >= %s
    """
    cursor.execute(query, (tiempo_limite,))
    solicitudes = cursor.fetchall()

    for solicitud in solicitudes:
        tipo = solicitud['idTipoServicio']
        numero_ticket = solicitud['numero_ticket']

        if tipo == 2:
            # 📦 Caso: Recojo pendiente
            asunto = f"⏰ Ticket #{numero_ticket} - Coordinación pendiente después de 48h"
            cuerpo = f"""
Estimado equipo,

Se ha generado una alerta para el ticket #{numero_ticket}, ya que han pasado más de 48 horas desde el ingreso del equipo y **aún no se ha coordinado el recojo** correspondiente.

🔹 Ticket ID: {solicitud['idTickets']}
🔹 Número de Ticket: {numero_ticket}
🔹 Cliente ID: {solicitud['idCliente']}
🔹 Dirección de recojo: {solicitud['direccion'] or 'No registrada'}
🔹 Falla reportada: {solicitud['fallaReportada'] or 'Sin descripción'}
🔹 Fecha de creación: {solicitud['fecha_creacion']}
🔹 Tienda origen: {solicitud['idTienda']}
🔹 Serie del equipo: {solicitud['serie'] or 'No especificada'}
🔹 Marca/Modelo: {solicitud['idMarca']} / {solicitud['idModelo']}
🔹 Coordenadas: {solicitud['lat']}, {solicitud['lng']}
🔹 Link ubicación: {solicitud['linkubicacion'] or 'No proporcionado'}

📝 Comentario: {solicitud['comentario']}
            """
        elif tipo == 3:
            # 🔬 Caso: Revisión pendiente en laboratorio
            asunto = f"🔬 Ticket #{numero_ticket} - Revisión en laboratorio pendiente"
            cuerpo = f"""
Hola equipo de laboratorio,

Este ticket aún **no ha sido recepcionado ni procesado en laboratorio**, a pesar de haber pasado más de 48 horas desde su creación o último movimiento.

🔹 Ticket ID: {solicitud['idTickets']}
🔹 Número de Ticket: {numero_ticket}
🔹 Cliente ID: {solicitud['idCliente']}
🔹 Falla reportada: {solicitud['fallaReportada'] or 'No especificada'}
🔹 Fecha de creación: {solicitud['fecha_creacion']}
🔹 Tienda origen: {solicitud['idTienda']}
🔹 Serie del equipo: {solicitud['serie'] or 'No proporcionada'}
🔹 Marca/Modelo: {solicitud['idMarca']} / {solicitud['idModelo']}
🔹 Dirección asociada: {solicitud['direccion'] or 'No disponible'}
🔹 Link ubicación: {solicitud['linkubicacion'] or 'Sin link'}

📝 Comentario: {solicitud['comentario']}

Se solicita verificar el estado de este equipo y proceder con su atención.
            """
        else:
            print(f"⚠️ Tipo de servicio desconocido para solicitud #{solicitud['idSolicitudentrega']}")
            continue

        # 👇 Cambia esto por el correo real de coordinación o laboratorio
        enviar_correo('saldarriagacruz31@gmail.com', asunto, cuerpo)

    cursor.close()
    conn.close()

if __name__ == '__main__':
    verificar_solicitudes()
