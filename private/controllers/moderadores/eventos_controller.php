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
        $this->eventos = (new Eventos)->porAceptar();
    }

    #
    public function formulario($aid='')
    {
        #$this->evento = (new Eventos)->miEvento($aid);
    }

    #
    public function aceptar($aid)
    {
        (new Eventos)->aceptar($aid);
        Redirect::to('/moderadores/eventos');
    }
}
