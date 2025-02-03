import mysql.connector
from datetime import datetime

# Conectar a la base de datos MySQL
db_connection = mysql.connector.connect(
    host="localhost",    # Cambia esto si tu servidor está en otro host
    user="root",         # Tu usuario de MySQL
    password="",  # Tu contraseña de MySQL
    database="erpgkm"  # El nombre de tu base de datos
)

cursor = db_connection.cursor()

# Consulta SQL con los datos que quieres insertar
query = """INSERT INTO tickets (
                idClienteGeneral, idCliente, numero_ticket, tipoServicio, fecha_creacion,
                idTipotickets, idEstadoots, idTecnico, idUsuario, idTienda, fallaReportada,
                esRecojo, direccion, idMarca, idModelo, serie, fechaCompra, lat, lng
            ) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"""

# Valor para fecha_creacion y fechaCompra, usaremos la fecha y hora actual si no se pasa un valor
fecha_actual = datetime.now().strftime('%Y-%m-%d %H:%M:%S')

# Asegúrate de que las columnas como `esRecojo`, `direccion`, `serie` y otras que permiten NULL no estén causando problemas.
# Si alguna columna NO permite NULL, asegúrate de pasar un valor adecuado en lugar de `None`.

values = (
    4, 13, 'PECEF345555555555', 3, fecha_actual,  # `fecha_creacion` con fecha actual
    None, 17, 7, 4, 6, 'SDSADSDSDSD', '', '', 2, 2, '', '', '', ''  # Valores vacíos en lugar de None para texto
)

# Imprimir la consulta y los valores antes de ejecutarla (esto ayudará a depurar)
print("Consulta SQL:", query)
print("Valores a insertar:", values)

# Insertar 100 veces
for _ in range(1000000):
    cursor.execute(query, values)

# Confirmar los cambios
db_connection.commit()

# Cerrar la conexión
cursor.close()
db_connection.close()

print("100 registros insertados correctamente.")