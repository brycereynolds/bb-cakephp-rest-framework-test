<?php
App::uses('AppController', 'Controller');
/**
 * Resources Controller
 *
 */
class ResourcesController extends AppController {
    public $version = 'none';

    public $uses = array('Resource');
    public $components = array('RequestHandler');

/**
 * Scaffold
 *
 * @var mixed
 */
    public $scaffold;

    // GET /recipes.format
    public function api_index(){
        $this->api_list();
    }

    // GET /recipes.format
    public function api_list(){
        $results = $this->Resource->find();
        pr($results);
        exit();
    }

    // GET /recipes/123.format
    public function api_view($id){
        echo "GET view($id) version: " . $this->version;
        exit();
    }

    // POST /recipes.format
    public function api_add(){
        echo "POST add() version: " . $this->version;
        exit();
    }

    // POST /recipes/123.format
    // PUT /recipes/123.format
    public function api_edit($id){
        echo "POST/PUT edit($id) version: " . $this->version;
        exit();
    }

    // DELETE /recipes/123.format
    public function api_delete($id){
        echo "DELETE delete($id) version: " . $this->version;
        exit();
    }

}
