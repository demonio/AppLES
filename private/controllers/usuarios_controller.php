<?php
/**
 */
class UsuariosController extends AppController
{
    #
    public function entrar()
    {
        if (Session::get('aid')) {
            Redirect::to('/');
        }
        View::select(null, 'acceso');
    }
    
    #
    public function reseteada($clave)
    {
        (new Usuarios)->reseteada($clave);
        Redirect::to('/usuarios/entrar');
    }
    
    #
    public function salir()
    {
        Session::delete('aid');
        Redirect::to('/');
    }
    
    #
    public function validar($clave)
    {
        (new Usuarios)->validar($clave);
        Redirect::to('/usuarios/entrar');
    }
}
