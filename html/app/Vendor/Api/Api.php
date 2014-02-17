<?php

App::import('Inflector');

class Api extends Object{
    public $controller;
    public $version = null;
    public $rendered = false;

    /**
     * Error codes for API errors
     */
    private $errorCodes = array(
        1001 => array('httpCode'=>500, 'errorMessage'=>'Version does not exist.'),
        1002 => array('httpCode'=>500, 'errorMessage'=>'Unknown output file.'),
        1003 => array('httpCode'=>500, 'errorMessage'=>'Unrecognised method.'),
    );

    /**
     * Retrieve data relating to an API error code
     *
     * @param $errorCode Int API error code
     * @return Array Data associated with the error code.
     */
    public function getError($errorCode) {
        if (in_array($errorCode, array_keys($this->errorCodes))) {
            return $this->errorCodes[$errorCode];
        }
        return false;
    }

    /**
     * Render the API request using a version to select the view.
     *
     * @return void
     */
    function render() {

        $controller = strtolower(Inflector::underscore($this->controller->name));
        $version = $this->version;
        $folder = $this->controller->RequestHandler->prefers();
        $view = str_replace('api_', '', $this->controller->action);

        //Check file exists before rendering
        $viewFile = new File(VIEWS . "/$controller/api/$version/$folder/$view.ctp");

        if ($viewFile->exists()) {
            $this->controller->render("/$controller/api/$version/$folder/$view");
            $this->rendered = true;
        }
        else {

            echo $version;
            echo 'WE ARE TRYING TO GET OUR API VERSION';
            exit();

            /**
             * Try to get previous missing view from previous API version
             * Perhaps this is not the best approach? But allows us to reuse earlier API version
             * views without duplicating files for new API releases.
             */
            $version = substr(strstr($version, '.'), 1);

            //NOTE: the version of our API actually started at 0.3, so you might need to edit some of the for loops in the code for your particular case
            for ($i = $version; $i >= 3; $i--) {
                $viewFile = new File(VIEWS . "/$controller/api/v$i/$folder/$view.ctp");
                if ($viewFile->exists()) {
                    $this->controller->render("/$controller/api/v$i/$folder/$view");
                    $this->rendered = true;
                    break;
                }
            }
        }
        if (!$this->rendered) {
            $this->cakeError('apiError', array('apiErrorCode'=>1002));
        }
    }

    /**
     * Dispatch the API request to the corresponding method.
     *
     * @param $controller Object Controller of original request
     * @return void
     */
    function dispatch(&$controller) {

        $this->controller =& $controller;

        $controllerName = $this->controller->name;
        $actionName = $this->controller->action;

        $functionName = Inflector::variable(str_replace('api', Inflector::variable($controllerName), $actionName));

        //Check that method exists
        if (method_exists($this, $functionName)) {
            $this->$functionName();
        }
        else {
            $this->cakeError('apiError', array('apiErrorCode'=>1003));
        }

        //Render if it hasn't already done so
        if (!$this->rendered) {
            $this->render();
        }
    }
}