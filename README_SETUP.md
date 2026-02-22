# Galería Web (PHP puro + AdminLTE)

## 1) Configurar base de datos

1. Crea una BD (ej: `galeria`).
2. Ejecuta el script de tablas.
3. Configura la conexión en: `app/config/database.php`.

## 2) Crear un usuario administrador

El campo `usuarios.password_hash` guarda el hash generado por `password_hash()` de PHP.

### Opción rápida (recomendada): generar hash en PHP

Crea un archivo temporal, por ejemplo `public/_hash.php`:

```php
<?php
echo password_hash('MiClaveSegura123', PASSWORD_BCRYPT);
```

Abre el archivo en el navegador, copia el hash y luego inserta:

```sql
INSERT INTO usuarios (username, password_hash, rol, activo)
VALUES ('admin', 'AQUI_EL_HASH', 'ADMIN', 1);
```

## 3) Probar la app

Levanta el servidor en `public/`:

```bash
cd galeria_web/public
php -S localhost:8000
```

Entra a:
- `http://localhost:8000/login.php`
