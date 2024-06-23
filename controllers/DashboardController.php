<?php

namespace Controllers;

use Model\Proyecto;
use Model\Usuario;
use MVC\Router;


class DashboardController{
    public static function index(Router $router){

        session_start();
        isAuth();

        $id = $_SESSION['id'];

        $proyectos = Proyecto::belongsTo('propietarioId', $id);

        $router->render('dashboard/index' ,[
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
        ]);

    }

    public static function crear_proyecto(Router $router){

        session_start();
        isAuth();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $proyecto = new Proyecto($_POST);

            //validacion
            $alertas = $proyecto->validarProyecto();
            
            if(empty($alertas)){
                //generar una url unica
                $hash = md5(uniqid());
                $proyecto->url = $hash;

                // almacenar el creador del proyecto
                $proyecto->propietarioId = $_SESSION['id'];


                // guardar el proyecto
                $proyecto->guardar();

                //redireccionar
                header('Location: /proyecto?id=' . $proyecto->url);
            }
            
        }

        $router->render('dashboard/crear-proyecto' ,[
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas
        ]);
    }

    public static function proyecto(Router $router){
        session_start();
        isAuth();

        $token = $_GET['id'];

        if(!$token){
            header('Location: /dashboard');
        }

        //revisar que la persona que visita el proyecto es quien lo creo
        $proyecto = Proyecto::where('url', $token);
        if($proyecto->propietarioId !== $_SESSION['id']){
            header('Location: /dashboard');
        }

        $router->render('dashboard/proyecto' ,[
            'titulo' => $proyecto->proyecto
        ]);
    }

    public static function perfil(Router $router){

        session_start();
        isAuth();
        $alertas = [];

        $usuario = Usuario::find($_SESSION['id']);

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $usuario->sincronizar($_POST);

            $alertas = $usuario->validar_perfil();

            if(empty($alertas)){

                $existeUsuario = Usuario::where('email', $usuario->email);

                if($existeUsuario && $existeUsuario->id !== $usuario->id){
                    // mostrar msj de error
                    Usuario::setAlerta('error', 'el email ya está en uso');
                    $alertas = $usuario->getAlertas();
                } else {
                    
                    // guardar el usuario
                    $usuario->guardar();

                    Usuario::setAlerta('exito', 'guardado correctamente');
                    $alertas = $usuario->getAlertas();

                    // asignar el nombre nuevo a la barra
                    $_SESSION['nombre'] = $usuario->nombre;
                }

            }

        }

        
        $router->render('dashboard/perfil' ,[
            'titulo' => 'Perfil',
            'usuario' => $usuario,
            'alertas' => $alertas

        ]);
    }

    public static function cambiar_password(Router $router){
        session_start();
        isAuth();

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $usuario = Usuario::find($_SESSION['id']);

            // sincronizar con los datos del user
            $usuario->sincronizar($_POST);

            $alertas= $usuario->nuevo_password();

            if(empty($alertas)){

                $resultado = $usuario->comprobar_password();

                if($resultado){

                    $usuario->password = $usuario->password_nuevo;

                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);

                    // hash password
                    $usuario->hashPassword();
                    
                    // actualizar
                    $resultado = $usuario->guardar();

                    if($resultado){
                        Usuario::setAlerta('exito', 'La contraseña se cambió correctamente');
                        $alertas = $usuario->getAlertas();
                    }
                    // asignar el nuevo password

                } else {
                    Usuario::setAlerta('error', 'La contraseña es incorrecta');
                    $alertas = $usuario->getAlertas();
                }

            }
            
        }
        
        $router->render('dashboard/cambiar-password' ,[
            'titulo' => 'Cambiar Contraseña',
            'alertas' => $alertas
        ]);
    }
}