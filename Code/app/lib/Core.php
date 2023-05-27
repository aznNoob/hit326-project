<?php
/* App Core Class
* Creates URL & loads core Controller
* URL Format - /controller/method/parameters
*/

class Core
{
    // Initalise controller, method and parameters with default values
    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $parameters = [];

    public function __construct()
    {
        $URL = $this->getURL();

        // Look in controllers for first value
        if (isset($URL[0]) && file_exists('../app/controllers/' . ucwords($URL[0]) . '.php')) {
            // If the controller exists, set it as the controller
            $this->currentController = ucwords($URL[0]);
            // Unset the 0 index in URL array
            unset($URL[0]);
        }

        require_once '../app/controllers/' . $this->currentController . '.php';

        $this->currentController = new $this->currentController;

        #Check for second part of the URL (method)
        if (isset($URL[1])) {
            #Check to see if the method exists in the controller
            if (method_exists($this->currentController, $URL[1])) {
                $this->currentMethod = $URL[1];
                unset($URL[1]);
            }
        }

        #Get the parameters
        $this->parameters = $URL ? array_values($URL) : [null];

        #Callback with array of parameters
        call_user_func_array([$this->currentController, $this->currentMethod], $this->parameters);
    }

    // Parses the URL
    public function getURL()
    {
        if (isset($_GET['url'])) {
            $URL = rtrim($_GET['url'], '/');
            $URL = filter_var($URL, FILTER_SANITIZE_URL);
            $URL = explode('/', $URL);
            return $URL;
        }
    }
}
