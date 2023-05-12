<?php

/* Base Controller
 * Loads the  models and views
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

    // Render the view
    public function view($view, $data = [])
    {
        if (file_exists('../app/views/' . $view . '.php')) {
            require_once '../app/views/' . $view . '.php';
        } else {
            die('View does not exist.');
        }
    }
}
