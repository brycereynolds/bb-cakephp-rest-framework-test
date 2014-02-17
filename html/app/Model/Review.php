<?php
App::uses('AppModel', 'Model');
/**
 * Review Model
 *
 * @property Resource $Resource
 */
class Review extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
    public $validate = array(
        'resource_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
        'comment' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
            ),
        ),
    );

/**
 * belongsTo associations
 *
 * @var array
 */
    public $belongsTo = array(
        'Resource' => array(
            'className' => 'Resource',
            'foreignKey' => 'resource_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
}
