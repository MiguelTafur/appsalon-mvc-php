<?php 

namespace Controllers;

use Classes\Email;
use Model\Salon;
use Model\Usuario;
use MVC\Router;

class LoginController {

    public static function login(Router $router){
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);

            $alertas = $auth->validarLogin();

            if(empty($alertas)) {
                //COMPRPBAR QUE EL USUARIO EXISTA
                $usuario = Usuario::where('email', $auth->email);

                if($usuario) {
                    //VERIFICAR EL PASSWORD 
                    if ($usuario->comprobarPasswordAndVerificado($auth->password)) {
                        //VERIFICA EL SALON DEL USUARIO CORRESPONDIENTE
                        $salon = Salon::find($usuario->salonId);
                        
                        if($auth->salonId === $salon->codigo) {
                            //AUTENTICAR EL USUARIO
                            isSession();

                            $_SESSION['id'] = $usuario->id;
                            $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                            $_SESSION['email'] = $usuario->email;
                            $_SESSION['idSalon'] = $salon->id;
                            $_SESSION['salon'] = $salon->codigo;
                            $_SESSION['login'] = true;

                            if($usuario->admin === "1") {
                                $_SESSION['admin'] = $usuario->admin ?? null;
                                header('Location: /admin');
                            }else {
                                header('Location: /cita');
                            }
                        } else {
                            Usuario::setAlerta('error', 'El Código está incorrecto');
                        }
                    }
                } else {
                    Usuario::setAlerta('error', 'Usuario no encontrado');
                }
            }
        }else {
            $auth = "";
        }

        $alertas = Usuario::getAlertas();



        $router->render('auth/login', [
            'alertas' => $alertas,
            'usuario' => $auth
        ]);
    }

    public static function logout(){
        isSession();

        $_SESSION = [];

        header("Location: /");
    }

    public static function olvide(Router $router){

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmailYCodigo();
            
            if(empty($alertas)) {
                $usuario = Usuario::where('email', $auth->email);

                if($usuario && $usuario->confirmado === "1") {

                    //VERIFICA EL SALON DEL USUARIO CORRESPONDIENTE
                    $salon = Salon::find($usuario->salonId);
                    
                    if($salon->codigo === $auth->salonId) {

                        //GENERAR TOKEN
                        $usuario->crearToken();
                        $usuario->guardar();

                        //ENVIAR EMAIL
                        $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                        $email->enviarConfirmacion();

                        Usuario::setAlerta('exito', 'Revisa tu Email');
                    } else {
                        Usuario::setAlerta('error', 'El Código está incorrecto');
                    }
                } else {
                    Usuario::setAlerta('error', 'El usuario no existe o no confirmado');
                }
            }
        } else {
            $auth = "";
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide-password', [
            'alertas' => $alertas,
            'usuario' => $auth
        ]);
    }

    public static function recuperar(Router $router){

        $alertas = [];
        $error = false;

        $token = s($_GET['token']);
        
        //BUSCAR USUARIO POR SU TOKEN
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)) {
            Usuario::setAlerta('error', 'Token no válido');
            $error = true;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            //LEER EL NUEVO PASSWORD Y GUARDARLO
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();

            if(empty($alertas)) {
                $usuario->password = null;

                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = '';

                $resultado = $usuario->guardar();
                if($resultado) {
                    Usuario::setAlerta('exito', 'Password Actualizado Correctamente');

                    header('Refresh: 3; url=/');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    public static function crear(Router $router){

        $usuario = new Usuario();

        //ALERTAS VACIAS
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();
            
            //REVISAR QUE ALERTA ESTE VACIO
            if(empty($alertas)) {
                //VERIFICAR EL SALON
                $salon = Salon::where('codigo', $usuario->salonId);
                if($salon) {
                    //VERIFICAR QUE EL RESULTADO YA ESTE REGISTRADO
                    $resultado = $usuario->existeUsuario(intval($salon->id));

                    if($resultado->num_rows) {
                        $alertas = Usuario::getAlertas();
                    }else {
                        //Hashear Password
                        $usuario->hashPassword();

                        //GENERAR UN TOKEN UNICO
                        $usuario->crearToken();

                        //ENVIAR EL EMAIL
                        $email = new Email($usuario->email, $usuario->nombre, $usuario->token); 

                        $email->enviarEmail();

                        //SETEANDO EL VALOR DE "salonId"
                        $usuario->salonId = $salon->id;
                        //CREAR EL USUARIO
                        $resultado = $usuario->guardar();
                        if($resultado) {
                            header('Location: /mensaje');
                        }
                    }
                } else {
                    Usuario::setAlerta('error', 'Codigo de acceso no identificado');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router) {
        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router) {
        $alertas = [];

        $token = s($_GET['token']);

        $usuario = Usuario::where('token', $token);

        if(empty($usuario)) {
            //MOSTRAR EL MENSAJE DE ERROR
            Usuario::setAlerta('error', 'Token no válido');
        }else {
            //MODIFICAR A USUARIO CONFIRMADO
            $usuario->confirmado = "1";
            $usuario->token = '';
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta comprobada correctamente');
        }

        //OBTENER ALERTAS
        $alertas = Usuario::getAlertas();

        //RENDERIZAR LA VISTA
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }

}