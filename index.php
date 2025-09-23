<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Manejar preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Obtener la ruta solicitada
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Remover el directorio base si existe (ajustar según tu servidor)
$base_path = ''; // Cambiar por la ruta base en tu servidor si es necesario
if (!empty($base_path) && strpos($path, $base_path) === 0) {
    $path = substr($path, strlen($base_path));
}

// Dividir la ruta en segmentos
$segments = explode('/', trim($path, '/'));

// Router principal
try {
    switch ($segments[0]) {
        case '':
        case 'index.php':
            // Ruta raíz - información de la API
            echo json_encode([
                'message' => 'API de Artesanías',
                'version' => '1.0',
                'endpoints' => [
                    '/auth/login' => 'POST - Iniciar sesión',
                    '/auth/register' => 'POST - Registrar usuario',
                    '/products' => 'GET - Listar productos, POST - Crear producto',
                    '/users' => 'GET - Listar usuarios, PUT - Actualizar usuario',
                    '/orders' => 'GET - Listar pedidos, POST - Crear pedido',
                    '/favoritos' => 'GET - Listar favoritos, POST - Agregar favorito',
                    '/metodos_pago' => 'GET - Listar métodos de pago',
                    '/envios' => 'GET - Listar envíos',
                    '/resenas' => 'POST - Agregar reseña'
                ]
            ]);
            break;

        case 'auth':
            handleAuth($segments, $method);
            break;

        case 'products':
            handleProducts($segments, $method);
            break;

        case 'users':
            handleUsers($segments, $method);
            break;

        case 'orders':
            handleOrders($segments, $method);
            break;

        case 'favoritos':
            handleFavoritos($segments, $method);
            break;

        case 'metodos_pago':
            handleMetodosPago($segments, $method);
            break;

        case 'envios':
            handleEnvios($segments, $method);
            break;

        case 'resenas':
            handleResenas($segments, $method);
            break;

        default:
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint no encontrado']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error interno del servidor: ' . $e->getMessage()]);
}

// Funciones para manejar cada módulo
function handleAuth($segments, $method) {
    $action = isset($segments[1]) ? $segments[1] : '';
    
    switch ($action) {
        case 'login':
            if ($method === 'POST') {
                include 'auth/login.php';
            } else {
                methodNotAllowed();
            }
            break;
        case 'register':
            if ($method === 'POST') {
                include 'auth/register.php';
            } else {
                methodNotAllowed();
            }
            break;
        case 'change_password':
            if ($method === 'POST') {
                include 'auth/change_password.php';
            } else {
                methodNotAllowed();
            }
            break;
        case 'edit_profile':
            if ($method === 'PUT' || $method === 'POST') {
                include 'auth/edit_profile.php';
            } else {
                methodNotAllowed();
            }
            break;
        default:
            http_response_code(404);
            echo json_encode(['error' => 'Acción de auth no encontrada']);
            break;
    }
}

function handleProducts($segments, $method) {
    $action = isset($segments[1]) ? $segments[1] : '';
    
    switch ($method) {
        case 'GET':
            if ($action === 'detail' && isset($segments[2])) {
                $_GET['id'] = $segments[2];
                include 'products/detail.php';
            } elseif ($action === 'by_vendedor' && isset($segments[2])) {
                $_GET['vendedor_id'] = $segments[2];
                include 'products/list_by_vendedor.php';
            } else {
                include 'products/list.php';
            }
            break;
        case 'POST':
            if ($action === 'upload') {
                include 'products/upload.php';
            } else {
                include 'products/add.php';
            }
            break;
        case 'PUT':
            include 'products/update.php';
            break;
        case 'DELETE':
            include 'products/delete.php';
            break;
        default:
            methodNotAllowed();
            break;
    }
}

function handleUsers($segments, $method) {
    switch ($method) {
        case 'PUT':
        case 'POST':
            include 'usuarios_update.php';
            break;
        default:
            methodNotAllowed();
            break;
    }
}

function handleOrders($segments, $method) {
    $action = isset($segments[1]) ? $segments[1] : '';
    
    switch ($method) {
        case 'GET':
            include 'orders/list.php';
            break;
        case 'POST':
            if ($action === 'by_user') {
                // El archivo espera POST con usuario_id
                include 'orders/list_by_user.php';
            } else {
                include 'orders/create.php';
            }
            break;
        default:
            methodNotAllowed();
            break;
    }
}

function handleFavoritos($segments, $method) {
    switch ($method) {
        case 'POST':
            include 'favoritos/add.php';
            break;
        case 'DELETE':
            include 'favoritos/delete.php';
            break;
        default:
            methodNotAllowed();
            break;
    }
}

function handleMetodosPago($segments, $method) {
    switch ($method) {
        case 'GET':
            include 'metodos_pago/list.php';
            break;
        case 'POST':
            include 'metodos_pago/add.php';
            break;
        case 'DELETE':
            include 'metodos_pago/delete.php';
            break;
        default:
            methodNotAllowed();
            break;
    }
}

function handleEnvios($segments, $method) {
    $action = isset($segments[1]) ? $segments[1] : '';
    
    switch ($method) {
        case 'POST':
            if ($action === 'by_user') {
                // El archivo espera POST con usuario_id
                include 'envios/list_by_user.php';
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Endpoint de envíos no encontrado']);
            }
            break;
        default:
            methodNotAllowed();
            break;
    }
}

function handleResenas($segments, $method) {
    switch ($method) {
        case 'POST':
            include 'resenas/add.php';
            break;
        default:
            methodNotAllowed();
            break;
    }
}

function methodNotAllowed() {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}
?>