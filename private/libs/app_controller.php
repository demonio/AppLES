<?php
require_once CORE_PATH . 'kumbia/controller.php';
/**
 */
abstract class AppController extends Controller
{
    final protected function initialize()
    {
        # Autologin con galleta

        # Permitir login
        if (Input::post('usuario')) {
            if (preg_match('/(borrarse|entrar|registrar|resetear)/', Input::post('usuario'))) {
                $accion = Input::post('usuario');
                (new Usuarios)->$accion(Input::post());
            }
        }
        
        Input::isAjax() ? View::template('ajax') : View::template('default');

        $this->usuario = (new Usuarios)->uno();
    }

    final protected function finalize()
    {
    }
}
