<?php

namespace Model;


class Usuario extends ActiveRecord {
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'email', 'password', 'token', 'confirmado'];

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? null;
        $this->password_actual = $args['password_actual'] ?? null;
        $this->password_nuevo = $args['password_nuevo'] ?? null;
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
    }


    // validar el login de usuarios
    public function validarLogin(){
        if(!$this->email){
            self::$alertas['error'][] = 'El email es obligatorio';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            self::$alertas['error'][]= 'Email no válido';
        }
        if(!$this->password){
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }

        return self::$alertas;
    }

    //validacion para cuentas nuevas
    public function validarNuevaCuenta(){
        if(!$this->nombre){
            self::$alertas['error'][] = 'El nombre de usuario es obligatorio';
        }
        if(!$this->email){
            self::$alertas['error'][] = 'El email es obligatorio';
        }
        if(!$this->password){
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }
        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'La contraseña debe contener al menos 6 caracteres';
        }
        if($this->password !== $this->password2) {
            self::$alertas['error'][] = 'Las contraseñas deben coincidir';
        }

        return self::$alertas;
    }

    // valida un email
    public function validarEmail(){
        if(!$this->email){
            self::$alertas['error'][]= 'El email es obligatiorio';
        }

        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            self::$alertas['error'][]= 'Email no válido';
        }

        return self::$alertas;
    }

    //valida el password
    public function validarPassword(){

        if(!$this->password){
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }

        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'La contraseña debe contener al menos 6 caracteres';
        }

        return self::$alertas;
    }

    public function validar_perfil(){
        if(!$this->nombre){
            self::$alertas['error'][] = 'El nombre es obligatiorio';
        }
        if(!$this->email){
            self::$alertas['error'][] = 'El email es obligatiorio';
        }
        return self::$alertas;
    }

    public function nuevo_password() : array {
        if(!$this->password_actual){
            self::$alertas['error'][] = 'La contraseña no puede ser vacía';
        }
        if(!$this->password_nuevo){
            self::$alertas['error'][] = 'La nueva contraseña no puede ser vacía';
        }
        if(strlen($this->password_nuevo) < 6){
            self::$alertas['error'][] = 'La nueva contraseña debe contener al menos 6 caracteres';
        }
        return self::$alertas;
    }

    // comprobar el password
    public function comprobar_password() : bool {
        return password_verify($this->password_actual, $this->password);
    }

    //hashea el password
    public function hashPassword() : void {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    // generar un token
    public function crearToken() :void {
        $this->token = md5(uniqid());
    }
}