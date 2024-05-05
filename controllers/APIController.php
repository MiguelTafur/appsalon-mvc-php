<?php

namespace Controllers;

use Model\Cita;
use Model\Horas;
use Model\Usuario;
use Model\Servicio;
use Model\CitaServicio;

class APIController {
    public static function index() {

        $usuario = Usuario::find($_SESSION['id']);
        $id = $usuario->salonId;
        $servicios = Servicio::where2('salonId', $id);
        echo json_encode($servicios, JSON_UNESCAPED_UNICODE);
    }

    public static function horas() {
        $horas = Horas::all();
        echo json_encode($horas, JSON_UNESCAPED_UNICODE);
    }

    public static function horasDisponibles() {
        //REDIRECCIONANDO SI LA URL NO CONTIENE ?FECHA=
        if(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) !== 'fecha=' . $_GET['fecha']) {
            header('Location: /cita');
        } else if(!preg_match('/\d{4}-\d{2}-\d{2}/', $_GET['fecha'])) {
            header('Location: /cita');
        }

        $idSalon = $_SESSION['idSalon'];

        $consulta = " SELECT horas.* FROM horas, ";
        $consulta .= " (SELECT horaId FROM citas LEFT OUTER JOIN usuarios ON citas.usuarioId = usuarios.id WHERE fecha = '{$_GET['fecha']}' AND usuarios.salonId = $idSalon) as citas ";
        $consulta .= " WHERE horas.id IN (citas.horaId) ";
        $consulta .= " ORDER BY horas.id ";

        $horas = Horas::SQL($consulta);
        echo json_encode($horas, JSON_UNESCAPED_UNICODE);
        
    }

    public static function guardar() {
        $alertas = [];

        // ALMACENA LA CITA Y DEVUELVE EL ID
        $cita = new Cita($_POST);

        $citaExistente = Cita::whereAnd('fecha', $_POST['fecha'], 'horaId', $_POST['horaId']);

        if($citaExistente) {
            $alertas[] = 'Já existe um Agendamento nessa data e hora';

            $respuesta = [
                'alertas' => $alertas
            ];
            echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
            return;
        } 

        $resultado = $cita->guardar();
        $idCita = $resultado['id'];

        // ALMACENA LA CITA Y EL SERVICIO
        $idServicios = explode(",", $_POST['servicios']);

        foreach ($idServicios as $idServicio) {
            $args = [
                'citaId' => $idCita,
                'servicioId' => $idServicio
            ];

            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        }

        $respuesta = [
            'servicios' => $resultado
        ];
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
    }

    public static function eliminar() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $cita = Cita::find($id);
            $cita->eliminar();
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    }
}