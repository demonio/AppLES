<?php
/**
 */
class EventosController extends OrganizadoresController
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
        $this->mis_eventos = (new Eventos)->misEventos();
    }

    #
    public function formulario()
    {
    }

    #
    public function crear()
    {
        (new Eventos)->crearEvento($_POST);
    }
}
