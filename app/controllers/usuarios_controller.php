<?php
/**
 */
class UsuariosController extends AppController
{
    #
    public function entrar()
    {
        if (Session::get('idu')) {
            Redirect::to('/');
        }
        View::select(null, 'acceso');
    }
    
    #
    public function salir()
    {
        Session::delete('idu');
        Redirect::to('/');
    }
    
    #
    public function validar($clave)
    {
        (new Usuarios)->validar($clave);
        Redirect::to('/usuarios/entrar');
    }

}
