@echo off
cd /d "C:\xampp\htdocs\Enamora Laravel\enamorapic"
C:\xampp\mysql\bin\mysql.exe -u root db_enamorapic < database\db_enamorapic.sql
echo Database import complete!
