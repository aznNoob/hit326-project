<?php

/* Base Controller
** Loads the models and views
*/

class Controller
{
    // Load the model
    public function model($model)
    {
        require_once '../app/models/' . $model . '.php';

        //Instantiate model
        return new $model();
    }

    // Load the controller
    public function controller($controller)
    {
        require_once '../app/controllers/' . $controller . '.php';

        //Instantiate controller
        return new $controller();
    }

    // Render the view with optional data
    public function view($view, $data = [])
    {
        if (file_exists('../app/views/' . $view . '.php')) {
            require_once '../app/views/' . $view . '.php';
        } else {
            die('View does not exist.');
        }
    }
}
