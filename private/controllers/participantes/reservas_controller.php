<?php
/**
 */
class ReservasController extends RegistradosController
{
    #
    public function index()
    {
        $this->eventos = (new Eventos_usuarios)->misReservas();
        $this->apuntados = (new Eventos_usuarios)->apuntados($this->eventos);
        $this->reservas = (new Eventos_usuarios)->reservas($this->eventos);
    }

    #
    public function apuntados($aid)
    {
        $this->menores = (new Usuarios_menores)->inscritos();
        $this->apuntado = (new Eventos_usuarios)->apuntado($aid);
        $this->eventos_aid = $aid;
    }

    #
    public function confirmar()
    {
        (new Eventos_usuarios)->apuntarse(Input::post());
        return Redirect::to('/participantes/reservas');
    }
}
