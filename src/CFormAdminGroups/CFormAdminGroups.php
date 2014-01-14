<?php

class CFormAdminGroups extends CForm {
	// Constructor 
	public function __construct($object, $group) {
	    parent::__construct();
	    $this->AddElement(new CFormElementText('acronym', array('value'=>$group['acronym'])))
	         ->AddElement(new CFormElementText('name', array('value'=>$group['name'], 'required'=>true)))
	         ->AddElement(new CFormElementHidden('id', array('value'=>$group['id'])))
	         ->AddElement(new CFormElementSubmit('save', array('callback'=>array($object, 'DoSaveGroup'))))
	         ->AddElement(new CFormElementSubmit('delete', array('callback'=>array($object, 'DoDeleteGroup'))));

	         
	    $this->SetValidation('acronym', array('not_empty'))
	         ->SetValidation('name', array('not_empty'));
	}
}