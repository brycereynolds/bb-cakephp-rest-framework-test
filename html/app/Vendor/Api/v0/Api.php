<?php

App::import('Vendor', 'Api/Api');

class Api_0 extends Api{
    public $version = 0;
 
    function postsGet() {
        //Get posts, store them in an array and set them for the view?
        //Whatever code would go into the api_get() method in the posts controller, should go in here
        //With only one minor alteration, calls to $this-> should be replaced with $this->controller->
 
        $posts = $this->controller->Post->find('all', array(
            'contain'=>false
        ));
        $this->controller->set(compact('posts'));
    }
}