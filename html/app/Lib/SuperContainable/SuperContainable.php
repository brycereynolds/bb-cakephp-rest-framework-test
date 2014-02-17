<?php

class SuperContainable {
    private $_query;
    private $_params;
    private $_skipFormatting;

    /**
     * $params => Extensions to setup SuperContainable like which fields to grab by default, which models to use by default
     *
     *  // Optional Parameters
     *  default_conditions   : These conditions should be used if overrides are not given. Use Cake style.
     *  default_models       : Include these models if user does not specify which models to include.
     *  skip_formatting      : By default formats will use the models pre-set format. This step can be skipped with this parameter.
     * 
     * $query => Standard Cake style "option" you would see on find() - like conditions, fields, group, etc
     * 
     * @param array $params Required. Requires that at least a $params['schema'] is defined. This is your model $hasOne mapping.
     * @param array $query Optional
     */
    function __construct($params, $query = array()) {
        if(!isset($params['name'])) throw new CakeException("SuperContainable requires that a name is set");
        if(!isset($params['schema'])) throw new CakeException("SuperContainable requires that a schema is set");

        if(isset($params['skip_formatting'])) $this->_skipFormatting = $params['skip_formatting'];

        $this->_params = $params;
        $this->_query = $query;

        # Set defaults
        $this->_defaultConditions();

        # Set models
        $this->_query['contain'] = !isset($this->_query['contain']) ? (isset($this->_params['default_models']) ? $this->_params['default_models'] : array()) : $this->_query['contain'];

        # Validate we have the right models with our given set of fields and conditions
        $this->_validateModelsExistForFields();
        $this->_validateModelsExistForConditions();

        # Validate that for every model their parent is being included - that way our queries will work
        $this->_validateModelParentsExist();

        # Set default fields
        $this->_defaultFields();

    }

    /**
     * Return our query object.
     * @return array Will be a standard Cake style $options field used in find()
     */
    public function query(){
        return $this->_query;
    }


    /**
     * SuperContainable needs to turn single dimensional query results into
     * a tree based on definitions setup by user.
     * @param  array  $results Required. Result set as returned from Cake.
     * @param  array  $format Required. This is the model nesting you expect to see in your results.
     * @param  boolean $primary
     */
    public function afterFind($results, $format){
        if(!$results || $this->_skipFormatting) return $results;
        if(!$format) throw new CakeException("SuperContainble requires a result format is passed to afterFind");

        $return = array();
        $count = 0;
        $lastPrimaryId = false;
        foreach($results as &$row){
            # If we have not set our primary ID or our current id does not equal it then set it
            if(!$lastPrimaryId || $row[$this->_params['name']]['id'] != $lastPrimaryId){
                # If this is not the first time we are setting it increment count
                if($lastPrimaryId) $count++;
                $lastPrimaryId = $row[$this->_params['name']]['id'];
            }
            $this->_flatten($format, $row, $return, $count);
        }

        return BBHash::expand($return);
    }



    /**
     * Pass in a list of defaults and those will be set on $query['conditions'] if those
     * keys are not already set.
     */
    private function _defaultConditions(){
        if(!isset($this->_params['default_conditions'])) return;
        foreach ($this->_params['default_conditions'] as $d => $val) {
            if(is_int($d))
                $this->_query['conditions'][] = $val;
            else
                if(!isset($this->_query['conditions'][$d])) $this->_query['conditions'][$d] = $val;
        }
    }


    /**
     * Based on a list of 'fields' in $query check that the models in 'contain'
     * are present. Eg. If I ask for User.name then we need to make sure 'User' is within
     * contain otherwise we will not be joining that table.
     */
    private function _validateModelsExistForFields(){
        if(isset($this->_query['fields'])){
            if(!isset($this->_query['contain'])) $this->_query['contain'] = array();

            foreach($this->_query['fields'] as &$f){
                $tmp = explode(".", $f);
                if(!isset($tmp[0])){
                    unset($f);
                    continue;
                }
                if($tmp[0] == $this->_params['name']) continue;
                if(!isset($this->_query['contain'][$tmp[0]])) $this->_query['contain'][] = $tmp[0];
            }
        }
    }

    /**
     * Based on a list of 'conditions' in $query check that the models in 'contain'
     * are present. Eg. If I ask for User.name = 'John' then we need to make sure 'User' is within
     * contain otherwise we will not be joining that table.
     */
    private function _validateModelsExistForConditions(){
        if(isset($this->_query['conditions'])){
            if(!isset($this->_query['contain'])) $this->_query['contain'] = array();

            foreach($this->_query['conditions'] as $k => $v){

                // Conditions can be [0] = "Observation.deleted IS NULL" OR ["Learner.participant_user_id"] = 1
                if(is_int($k))
                    $condition = $v;
                else
                    $condition = $k;

                $tmp = explode(".", $condition);
                if(!isset($tmp[1])){
                    continue;
                }

                if($tmp[0] == $this->_params['name']) continue;
                if(!isset($this->_query['contain'][$tmp[0]])) $this->_query['contain'][] = $tmp[0];
            }
        }
    }


    /**
     * Make sure that for each value in contain the parent of that value is also present.
     * Checks recursively.
     */
    private function _validateModelParentsExist() {
        foreach($this->_query['contain'] as $node){
            if(!isset($this->_params['schema'][$node]['parent']) 
                || in_array($this->_params['schema'][$node]['parent'], $this->_query['contain'])
                || $this->_params['schema'][$node]['parent'] == $this->_params['name']
            ){
                continue;
            }

            $this->_query['contain'][] = $this->_params['schema'][$node]['parent'];
            $this->_validateModelParentsExist($this->_query['contain']);
        }
    }


    /** Set default fields based on what the model requested. Also set default fields based on what our formatting need. */
    private function _defaultFields(){
        if(!isset($this->_query['fields'])) $this->_query['fields'] = array();

        # Setup the default fields our model has requested we use
        foreach ($this->_params['default_fields'] as &$field) {
            if(!in_array($field, $this->_query['fields'])) $this->_query['fields'][] = $field;
        }

        # Set the other default fields we know we need based on our model to model mappings in the schema
        foreach($this->_query['contain'] as &$c){
            if(strpos($c, '.') > 0) continue;
            if(in_array($c . '.*', $this->_query['fields'])) continue;

            # We always grab the 'id'
            if(!in_array($c . '.id', $this->_query['fields'])) $this->_query['fields'][] = $c . '.id';

            # All models need something they group under (like Participants to ActivityGroups need Participant.activity_group_id)
            if(isset($this->_params['schema'][$c]['combine']) && !in_array($c . '.' .$this->_params['schema'][$c]['combine'], $this->_query['fields']))
                $this->_query['fields'][] = $c . '.' .$this->_params['schema'][$c]['combine'];

            # If we set a 'key' value we want to use that as our unique index - ensure it is present
            if(isset($this->_params['schema'][$c]['key']) && !in_array($c . '.' .$this->_params['schema'][$c]['key'], $this->_query['fields']))
                $this->_query['fields'][] = $c . '.' .$this->_params['schema'][$c]['key'];

        }
    }



    /**
     * Turn our Cake result set into a flat hash with hierarchy coded as keys.
     * @param  array $format Format you expect results to be returned in
     * @param  array $row    Individual row to run nesting against
     * @param  array $values Flattened data describing the hierarchy found. This is your results.
     * @param  string $name  Used by the method recursively
     */
    private function _flatten($format, $row, &$values, $name = false){
        $name_pre = $name === false ? "" : $name . '.';

        foreach($format as $key => $n){

            if(isset($row[$key])){

                $ourId = isset($this->_params['schema'][$key]['key']) ? $this->_params['schema'][$key]['key'] : 'id';

                # We are assuming if our result has an 'id' column yet it is blank then we do not want this record
                if(array_key_exists($ourId, $row[$key]) && !$row[$key][$ourId]) continue;

                if(isset($this->_params['schema'][$key]['hasOne']) && $this->_params['schema'][$key]['hasOne']){
                    $name = $name_pre . $key;
                }else if($key == $this->_params['name']){
                    $name = $row[$key][$ourId];
                }else{
                    $name = $name_pre . $key . '.' . $row[$key][$ourId];
                }

                foreach($row[$key] as $k=>$v){
                    $values[$name . '.' . $k] = $v;
                }
            }

            if(is_array($n)){
                $this->_flatten($n, $row, $values, $name);
            }
        }
    }

}

