<?php
/**
 */
class IndexController extends AppController
{
    #
    public function index()
    {
        return Redirect::to('/eventos');
    }
}
