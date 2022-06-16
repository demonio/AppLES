<?php
/**
 */
class MenoresController extends RegistradosController
{
    #
    public function index()
    {
        $this->inscritos = (new Usuarios_menores)->inscritos();
    }

    #
    public function inscribir()
    {
        if (Input::post('action') == 'inscribir_menor') {
            (new Usuarios_menores)->inscribir(Input::post());
        }
        $this->inscritos = (new Usuarios_menores)->inscritos();

        View::select('inscritos');

        if ( ! Input::isAjax()) {
            return Redirect::to('/participantes/menores');
        }
    }

    #
    public function quitar($aid)
    {
        (new Usuarios_menores)->quitar($aid);

        $this->inscritos = (new Usuarios_menores)->inscritos();

        View::select('inscritos');

        if ( ! Input::isAjax()) {
            return Redirect::to('/participantes/menores');
        }
    }
}
