<?php

namespace Controllers;

use Model\Servicio;
use MVC\Router;

class ServicioController {
    public static function index(Router $router) {

        isSession();

        isAdmin();

        $id = $_SESSION['id'];

        $servicios = Servicio::where2('salonId', $id);

        $router->render('servicios/index', [
            'nombre' => $_SESSION['nombre'],
            'servicios' => $servicios
        ]);
    }

    public static function crear(Router $router) {

        isSession();

        isAdmin();

        $servicio = new Servicio;
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servicio->sincronizar($_POST);  

            $alertas = $servicio->validar();

            if(empty($alertas)) {
                //ASIGNA EL ID DEL USUARIO
                $servicio->usuarioId = $_SESSION['id'];
                $resultado = $servicio->guardar();
                
                if($resultado) {
                    Servicio::setAlerta('exito', 'Serviço Salvo');

                    header('Refresh: 2; url=/servicios');
                }
            }
        }

        $alertas = Servicio::getAlertas();

        $router->render('servicios/crear', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function actualizar(Router $router) {

        isSession();

        isAdmin();

        if(!is_numeric($_GET['id'])) return;
        $servicio = Servicio::find($_GET['id']);
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servicio->sincronizar($_POST);  

            $alertas = $servicio->validar();

            if(empty($alertas)) {
                //ASIGNA EL ID DEL USUARIO
                $servicio->usuarioId = $_SESSION['id'];
                $resultado = $servicio->guardar();
                if($resultado) {
                    Servicio::setAlerta('exito', 'Serviço Atualizado');

                    header('Refresh: 2; url=/servicios');
                }
            }
        }

        $alertas = Servicio::getAlertas();

        $router->render('servicios/actualizar', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function eliminar() {
        isSession();

        isAdmin();
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $servicio = Servicio::find($id);
            $servicio->eliminar();
            header('Location: /servicios');   
        }
    }
}