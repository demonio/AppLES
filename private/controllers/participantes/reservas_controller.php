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
        #_var::die($this->apuntados);
    }

    #
    public function apuntarse($aid)
    {
        (new Eventos_usuarios)->apuntarse($aid);
        return Redirect::to('/participantes/reservas');
    }

    #
    public function desapuntarse($aid)
    {
        (new Eventos_usuarios)->desapuntarse($aid);
        return Redirect::to('/participantes/reservas');
    }
}
