<?php
// Configuración general de la aplicación
return [
    'app' => [
        'name' => 'API Artesanías',
        'version' => '1.0.0',
        'debug' => true, // Cambiar a false en producción
        'timezone' => 'America/Mexico_City'
    ],
    
    'database' => [
        'host' => 'localhost', // En hosting compartido suele ser localhost
        'dbname' => 'gurubackend_artesanias', // Probablemente sea así: usuario_nombrebd
        'username' => 'gurubackend', // Tu usuario del sitio
        'password' => 'KxXOSG38nSA5nvAUlD8M', // Tu contraseña del sitio
        'charset' => 'utf8mb4'
    ],
    
    'uploads' => [
        'path' => __DIR__ . '/uploads/',
        'url' => 'https://gurubackend.usbtopia.usbbog.edu.co/uploads/',
        'max_size' => 5 * 1024 * 1024, // 5MB
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp']
    ],
    
    'security' => [
        'jwt_secret' => 'tu_clave_secreta_jwt_aqui_cambiala_en_produccion',
        'password_cost' => 12,
        'session_lifetime' => 3600 // 1 hora
    ],
    
    'api' => [
        'rate_limit' => 100, // requests por minuto
        'pagination' => [
            'default_limit' => 20,
            'max_limit' => 100
        ]
    ]
];
?>