<?php

namespace Model;

class Usuario extends ActiveRecord {
    //BASE DE DATOS
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password', 'telefono', 'admin', 'confirmado', 'token', 'salonId'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;
    public $salonId;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? '0';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? '';
        $this->salonId = $args['salonId'] ?? '';
    }

    public function validarNuevaCuenta() {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'O Nome do Cliente é obrigatório';
        }

        if(!$this->apellido) {
            self::$alertas['error'][] = 'O Sobrenome do Cliente é obrigatório';
        }

        if(!$this->telefono) {
            self::$alertas['error'][] = 'O Telefone do Cliente é obrigatório';
        }

        if(!$this->email) {
            self::$alertas['error'][] = 'O Email do Cliente é obrigatório';
        }

        if(!$this->password) {
            self::$alertas['error'][] = 'A Senha do Cliente é obrigatória';
        }

        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'A Senha deve conter no mínimo 6 caracteres';
        }

        if(!$this->salonId) {
            self::$alertas['error'][] = 'O Código é obrigatório';
        }

        return self::$alertas;
    }

    public function validarLogin() {
        if(!$this->email) {
            self::$alertas['error'][] = 'O Email é obrigatório';
        }

        if(!$this->password) {
            self::$alertas['error'][] = 'A Senha é obrigatória';
        }

        if(!$this->salonId) {
            self::$alertas['error'][] = 'O Código é obrigatório';
        }

        return self::$alertas;
    }

    public function validarEmailYCodigo() {
        if(!$this->email) {
            self::$alertas['error'][] = 'O Email é obrigatório';
        }

        if(!$this->salonId) {
            self::$alertas['error'][] = 'O Código é obrigatório';
        }

        return self::$alertas;
    }

    public function validarPassword() {
        if(!$this->password) {
            self::$alertas['error'][] = 'A Senha obrigatória';
        }

        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'A Senha deve conter no mínimo 6 caracteres';
        }

        return self::$alertas;
    }

    public function existeUsuario(int $codigo) {
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' AND salonId = " . $codigo . " LIMIT 1";

        $resultado = self::$db->query($query);

        if($resultado->num_rows) {
            self::$alertas['error'][] = 'O usuário já tem registro';
        }

        return $resultado;
    }

    public function hashPassword() {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken() {
        $this->token = uniqid();
    }

    public function comprobarPasswordAndVerificado($password) {
        $resultado = password_verify($password, $this->password);

        if(!$resultado || !$this->confirmado) {
            self::$alertas['error'][] = 'Senha errada ou sua conta ainda não foi confirmada';
        } else {
            return true;
        }
    }
}