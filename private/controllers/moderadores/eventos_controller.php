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
    public function aceptar($aid)
    {
        (new Eventos)->aceptar($aid);
        Redirect::to('/moderadores/eventos');
    }

    #
    public function formulario($aid='')
    {
    }

    #
    public function ver($aid)
    {
        $this->evento = (new Eventos)->uno($aid);
        $this->apuntado = (new Eventos_usuarios)->apuntado($aid);
        $this->apuntados = (new Eventos_usuarios)->apuntados([$this->evento]);
        $this->reservas = (new Eventos_usuarios)->reservas([$this->evento]);
        View::setPath('eventos');
    }
}
