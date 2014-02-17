<?php
App::uses('ResourcesController', 'Controller');
/**
 * Resources Controller
 *
 */
class Resourcesv0Controller extends ResourcesController {
    public $version = '0';

    public $components = array('Paginator');

    // GET /recipes.format
    public function api_index(){
        $this->api_list();
    }

    // GET /recipes.format
    public function api_list(){
        $model = $this->Resource;
        $q = $this->request->query;

        // TEMPORARY :: This is all stuff that will be abstracted into a REST plugin

        $fields = array();
        $conditions = array();
        $order = array();
        $group = array();
        $limit = 10;    // things like this should be configurable at a higher level
        $offset = 0;
        $contain = array();

        // Limit fields in our results
        // It may be that the meta short-hand is used at the Model
        // level or perhaps the Service level
        if(isset($q['fields'])){
            // When limiting we do not want to grab recursively
            // Also we need to add in meta:group type partial responses

            $fields = array();

            // We allow deep model linking when asking for data (eg. fields=name, description, meta:OtherModel (name, comment))
            $_metaFields = explode("meta:", str_replace("meta:", "meta:metadetails:", $q['fields']));

            foreach ($_metaFields as &$fieldGroup){

                // We are looking at meta string data
                if(strpos($fieldGroup, 'metadetails:') === 0){

                    $_fields = explode(" ", str_replace(array('metadetails:', '(', ')', ','), '', $fieldGroup));

                    // The first value after meta: must always be the name of the group we want
                    // It is possible we just want everything in this group
                    $metaName = array_shift($_fields);

                    if(!empty($_fields)){
                        foreach($_fields as &$f){
                            $f = trim($f);
                            if(empty($f)) continue;
                            $contain[] = $metaName . "." . $f;
                        }
                    }else{
                        $contain[] = $metaName . "";
                    }

                }else{

                    // This is a basic field request. Just trim the data
                    // and add it to our request.
                    $_fields = explode(",", $fieldGroup);
                    foreach($_fields as &$f){
                        $f = trim($f);
                        if(empty($f)) continue;
                        $fields[] = $model->name . "." . $f;
                    }

                }

            }

        }

        // Standard group and order strings to affect results
        if(isset($q['order'])) $order = $q['order'];
        if(isset($q['group'])){
            $fields[] = "count(DISTINCT " . $model->name . ".id) as count";
            $group = $q['group'];
        }

        // Pagination controls
        if(isset($q['limit'])) $limit = $q['limit'];
        if(isset($q['offset'])) $offset = $q['offset'];

        // Remaining parameters may be conditions
        $_q = $q;
        if(isset($_q['fields']))    unset($_q['fields']);
        if(isset($_q['order']))     unset($_q['order']);
        if(isset($_q['group']))     unset($_q['group']);
        if(isset($_q['limit']))     unset($_q['limit']);
        if(isset($_q['offset']))    unset($_q['offset']);
        if(!empty($_q)) $conditions = $_q;

        $query = array(
            'fields'        => $fields,
            'conditions'    => $conditions,
            'order'         => $order,
            'group'         => $group,
            'limit'         => $limit,
            'offset'        => $offset,
            'contain'       => $contain,
        );

        // Results
        $_data = $model->find('all', $query);

        // Get a count if we need to
        // if(count($results) >= $limit){
        //     $count = $this->Resource->find('count', array(
        //         'conditions'    => $conditions,
        //         'group'         => $group,
        //         'contain'       => $contain,
        //     ));
        // }else{
        //     $count = count($results);
        // }


        // Format pagination information

        pr($_data);
        exit();
        $this->set(array(
            '_pagination' => null,
            '_data' => $_data,
            '_serialize' => array('_data')
        ));
    }




    // GET /recipes/123.format
    public function api_view($id){
        echo "GET view($id) version: " . $this->version;
        exit();
    }

}
