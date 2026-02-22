<?php
declare(strict_types=1);

final class Auth {
    /**
     * Verifica si el usuario está logueado y gestiona el tiempo de inactividad.
     */
    public static function requireLogin(): void {
        // 1. Verificar si existe la sesión básica
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL . '/login.php');
            exit;
        }

        // 2. Lógica de Inactividad (Ejemplo: 20 minutos)
        $tiempoMaximoInactividad = 20 * 60; // 1200 segundos
        $ahora = time();

        if (isset($_SESSION['ultima_actividad'])) {
            $tiempoTranscurrido = $ahora - $_SESSION['ultima_actividad'];

            if ($tiempoTranscurrido > $tiempoMaximoInactividad) {
                // Destruir sesión y redirigir con mensaje de error
                self::logout();
                header('Location: ' . BASE_URL . '/login.php?error=timeout');
                exit;
            }
        }

        // 3. Actualizar la marca de tiempo para la siguiente petición
        $_SESSION['ultima_actividad'] = $ahora;
    }

    /**
     * Limpia la sesión por completo.
     */
    public static function logout(): void {
        session_unset();
        session_destroy();
        // Borrar cookie de sesión si existe
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
    }

    /**
     * Verifica si el usuario tiene un rol específico.
     */
    public static function hasRole(string $role): bool {
        return ($_SESSION['rol'] ?? '') === $role;
    }

    /**
     * Restringe el acceso a ciertos roles.
     */
    public static function only(array $roles): void {
        // Primero aseguramos que esté logueado
        self::requireLogin();

        $rolUsuario = $_SESSION['rol'] ?? null;

        if (!$rolUsuario || !in_array($rolUsuario, $roles, true)) {
            http_response_code(403);
            die('Acceso no autorizado: Su rol no tiene permisos para esta acción.');
        }
    }
}