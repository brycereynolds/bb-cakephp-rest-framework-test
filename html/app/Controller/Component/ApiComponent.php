<?php
App::import('Vendor', 'Api_0', array('file' =>'Api'.DS.'v0'.DS.'Api.php'));

/**
 * In this component include the various API version classes that need
 * to be instantiated for each version of the API that is requested.
 * Once the correct class has been created dispatch() in that class is invoked.
 */
class ApiComponent extends Component{
    public $api = null;

    /**
     * Dispatch the API request to the correct API class depending on version
     *
     * @return void
     */
    function dispatch() {

        //Want to dispatch to correct method in API class, get the name of the class
        $className = 'Api_'.str_replace('.', '_', $this->controller->params['version']);

        //Confirm class exists
        if (class_exists($className)) {
            $this->api = new $className();
        }
        else {
                //No need to worry about apiErrors just at the moment
            $this->cakeError('apiError', array('apiErrorCode'=>1001));
        }

        //Pass in controller
        $this->api->dispatch($this->controller);
    }
}