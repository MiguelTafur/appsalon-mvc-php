<?php 

namespace Model;

class Salon extends ActiveRecord {
    protected static $tabla = 'salon';
    protected static $columnasDB = ['id', 'codigo'];

    public $id;
    public $codigo;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->codigo = $args['codigo'] ?? '';
    }
}