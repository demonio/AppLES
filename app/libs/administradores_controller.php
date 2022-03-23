<?php
/**
 * @see Controller nuevo controller
 */
require_once CORE_PATH . 'kumbia/controller.php';

/**
 * Controlador para proteger los controladores que heredan
 * Para empezar a crear una convención de seguridad y módulos
 *
 * Todas las controladores heredan de esta clase en un nivel superior
 * por lo tanto los métodos aquí definidos estan disponibles para
 * cualquier controlador.
 *
 * @category Kumbia
 * @package Controller
 */
abstract class AdministradoresController extends Controller
{
    final protected function initialize()
    {
        if (Input::post('usuario')) {
            if (preg_match('/(borrarse|entrar|registrar|resetear)/', Input::post('usuario'))) {
                $accion = Input::post('usuario');
                (new Usuarios)->$accion(Input::post());
            }
        }

        if ( ! Session::get('idu')) {
            #throw new KumbiaException('No puedes pasar');
            View::template('acceso');
            return false;
        }

        Input::isAjax() ? View::template(null) : View::template('admin');

        $this->usuario = (new Usuario)->uno();
    }

    final protected function finalize()
    {  
    }
}
