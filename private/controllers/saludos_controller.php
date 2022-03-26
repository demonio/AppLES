<?php
/**
 */
class SaludosController extends AppController
{
    #
    public function todos() {
        $this->todos_los_saludos = Saludos::all();
    }
    
    public function ultimo() {
        $this->ultimo = (new Saludos)->ultimoSaludo();
    }
}
