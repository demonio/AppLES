<?php
/**
 */
class EventosController extends AppController
{
    #
    public function index()
    {
        $this->eventos = (new Eventos)->aceptados();
        $this->apuntados = (new Eventos_usuarios)->apuntados($this->eventos);
    }

    #
    public function ver($aid)
    {
        $this->evento = (new Eventos)->uno($aid);
        $this->apuntado = (new Eventos_usuarios)->apuntado($aid);
    }
}
