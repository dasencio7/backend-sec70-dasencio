<?php
include_once '../version2.php';

// Parametros
$existeId = false;
$valorId = 0;
$existeAccion = false;
$valorAccion = 0;

// Parseo de parámetros
if (isset($_parametros) && count($_parametros) > 0) {
    foreach ($_parametros as $p) {
        if (strpos($p, 'id=') !== false) {
            $existeId = true;
            $valorId = intval(explode('=', $p)[1]);
        }
        if (strpos($p, 'accion=') !== false) {
            $existeAccion = true;
            $valorAccion = explode('=', $p)[1];
        }
    }
}

if ($_version == 'v2') {
    if ($_mantenedor == 'info-contacto') {
        include_once 'controller.php';
        include_once '../conexion.php';
        $control = new Controlador();
        
        switch ($_metodo) {
            case 'GET':
                if ($_header == $_token_get) {
                    $lista = $control->getAll();
                    http_response_code(200);
                    echo json_encode(['data' => $lista]);
                } else {
                    http_response_code(401);
                    echo json_encode(['error' => 'No tiene autorización para GET']);
                }
                break;

            case 'POST':
                if ($_header == $_token_post) {
                    $body = json_decode(file_get_contents("php://input", true));
                    if (isset($body->nombre)) {
                        $respuesta = $control->postNuevo($body);
                        if ($respuesta) {
                            http_response_code(201);
                            echo json_encode(['data' => $respuesta]);
                        } else {
                            http_response_code(409);
                            echo json_encode(['error' => 'Conflicto: el dato ya existe']);
                        }
                    } else {
                        http_response_code(400);
                        echo json_encode(['error' => 'Datos de entrada inválidos']);
                    }
                } else {
                    http_response_code(401);
                    echo json_encode(['error' => 'No tiene autorización para POST']);
                }
                break;

            case 'PATCH':
                if ($_header == $_token_patch) {
                    if ($existeId && $existeAccion) {
                        $accion = $valorAccion === 'encender' ? true : ($valorAccion === 'apagar' ? false : null);
                        if ($accion !== null) {
                            $respuesta = $control->patchEncenderApagar($valorId, $accion);
                            http_response_code(200);
                            echo json_encode(['data' => $respuesta]);
                        } else {
                            http_response_code(400);
                            echo json_encode(['error' => 'Acción inválida']);
                        }
                    } else {
                        http_response_code(400);
                        echo json_encode(['error' => 'Faltan parámetros']);
                    }
                } else {
                    http_response_code(401);
                    echo json_encode(['error' => 'No tiene autorización para PATCH']);
                }
                break;

            case 'PUT':
                if ($_header == $_token_put) {
                    $body = json_decode(file_get_contents("php://input", true));
                    if (isset($body->nombre) && isset($body->id)) {
                        $respuesta = $control->putNombreById($body->nombre, $body->id);
                        http_response_code(200);
                        echo json_encode(['data' => $respuesta]);
                    } else {
                        http_response_code(400);
                        echo json_encode(['error' => 'Datos de entrada inválidos']);
                    }
                } else {
                    http_response_code(401);
                    echo json_encode(['error' => 'No tiene autorización para PUT']);
                }
                break;

            case 'DELETE':
                if ($_header == $_token_delete) {
                    if ($existeId) {
                        $respuesta = $control->deleteById($valorId);
                        http_response_code(200);
                        echo json_encode(['data' => $respuesta]);
                    } else {
                        http_response_code(400);
                        echo json_encode(['error' => 'Falta el parámetro id']);
                    }
                } else {
                    http_response_code(401);
                    echo json_encode(['error' => 'No tiene autorización para DELETE']);
                }
                break;

            default:
                http_response_code(405);
                echo json_encode(['error' => 'Método no permitido']);
                break;
        }
    }
}
?>
