<?php
App::uses('AppModel', 'Model');
// App::uses('SuperContainable', 'SuperContainable');

/**
 * Resource Model
 *
 * @property Review $Review
 */
class Resource extends AppModel {
    public $actsAs = array('Containable');
    public $name = 'Resource';

/**
 * Validation rules
 *
 * @var array
 */
    public $validate = array(
        'type' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty')
            ),
        ),
    );

/**
 * hasMany associations
 *
 * @var array
 */
    public $hasMany = array(
        'Review' => array(
            'className' => 'Review',
            'foreignKey' => 'resource_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );

/**
 * This is a hasOne hack that really means we have many but we are using a different
 * Containable behavior ('SuperContainable') b/c the original version executes multiple
 * queries against the db. We would rather it just create a query and execute it once.
 *
 * @var array
 */
    // public $hasOne = array(
    //     'Review' => array(
    //         'className' => 'Review',
    //         'combine' => 'resource_id',
    //         'parent' => 'Resource',
    //         'hasOne' => false,
    //         'default_fields' => array('Review.id'),
    //         'conditions' => array('Review.deleted IS NULL')
    //     ),
    // );


    # Our holder for the SuperContainable class that gets created
    # when we call find(). We keep it for our afterFind() method
    # usage. After the afterFind() it should be released.
    // private $_superContainable;

    // private $_superContainableResultFormat = array('Resource' => array('Review' => null));

    // public function find($type = 'all', $query = array()) {
    //     $params = array(
    //         'skip_formatting' => isset($query['skip_formatting']) ? $query['skip_formatting'] : false,
    //         'name' => $this->name,
    //         'schema' => $this->hasOne,
    //         'default_conditions' => array('Resource.deleted IS NULL'),
    //         'default_fields' => array('Resource.id'));

    //     $this->_superContainable = new SuperContainable($params, $query);

    //     return parent::find($type, $this->_superContainable->query(), array("cache" => false));
    // }

    // public function afterFind($results, $primary = false) {
    //     return $this->_superContainable->afterFind($results, $this->_superContainableResultFormat);
    // }

}
