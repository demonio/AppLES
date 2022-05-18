<?php
/**
 */
class EventosController extends RegistradosController
{
    #
	protected function before_filter()
	{
        if ($action = Input::post('action')) {
            unset($_POST['action']);
            if (method_exists($this, $action)) {
                $this->$action();
            }
        }
    }

    #
    public function index()
    {
        $this->eventos = (new Eventos)->misEventos();
    }

    #
    public function formulario($aid='')
    {
        $this->evento = (new Eventos)->miEvento($aid);
    }

    #
    public function borrar()
    {
        (new Eventos)->borrarEvento($_POST);
        Redirect::to('/organizadores/eventos');
    }

    #
    public function crear()
    {
        unset($_POST['aid']);
        (new Eventos)->salvarEvento($_POST);
        Redirect::to('/organizadores/eventos');
    }

    #
    public function editar()
    {
        (new Eventos)->salvarEvento($_POST);
        Redirect::to('/organizadores/eventos');
    }
}
