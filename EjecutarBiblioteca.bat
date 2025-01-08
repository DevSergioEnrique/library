@echo off

:: Ruta del directorio de XAMPP (actualiza esta ruta si es necesario)
set xampp_path=C:\xampp

:: Iniciar Apache y MySQL
echo Iniciando servicios de Apache y MySQL...
start "" "%xampp_path%\xampp_start.exe"

:: Esperar unos segundos para garantizar que los servicios se inicien
timeout /t 2 /nobreak >nul

:: Abrir el proyecto en el navegador (ajusta la URL a tu proyecto)
echo Abriendo el proyecto en el navegador...
start http://localhost/bibliotecaPPP

:: Esperar a que el usuario cierre el script
echo Presiona cualquier tecla para cerrar los servicios cuando termines...
pause>nul

:: Detener Apache y MySQL al cerrar
echo Cerrando servicios de Apache y MySQL...
start "" "%xampp_path%\xampp_stop.exe"

:: Finalizar el script
exit
