<?php

namespace Model;

class Servicio extends ActiveRecord {
    //BASE DE DATOS
    protected static $tabla = 'servicios';
    protected static $columnasDB = ['id', 'nombre', 'precio', 'salonId'];

    public $id;
    public $nombre;
    public $precio;
    public $salonId;

    public function __construct($args = []) 
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->precio = $args['precio'] ?? '';
        $this->salonId = $args['salonId'] ?? '';
    }

    public function validar() {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre del Servicio es obligatorio';
        }

        if(!$this->precio) {
            self::$alertas['error'][] = 'El Precio del Servicio es obligatorio';
        }

        if(!is_numeric($this->precio)) {
            self::$alertas['error'][] = 'El formato del Precio no es válido';
        }

        return self::$alertas;
    }
}