<?php

class CFormAdminUsers extends CForm {
	// Constructor 
	public function __construct($object, $user, $allGroups, $memberships) {
		parent::__construct();

		$outputGroups = array();
    	foreach($allGroups as $group) {
            array_push($outputGroups, $group['name']);
    	}

		$outputMemberships = array();
		foreach($memberships as $key => $val) {
			array_push($outputMemberships, $val['name']);
		}


		$this->AddElement(new CFormElementHidden('id', array('value'=>$user['id'])))
			 ->AddElement(new CFormElementText('acronym', array('readonly'=>true, 'value'=>$user['acronym'])))
	         ->AddElement(new CFormElementPassword('password'))
	         ->AddElement(new CFormElementPassword('password1', array('label'=>'Password again:')))
	         ->AddElement(new CFormElementSubmit('change_password', array('callback'=>array($object, 'DoChangePassword'))))
	         ->AddElement(new CFormElementText('name', array('value'=>$user['name'], 'required'=>true)))
	         ->AddElement(new CFormElementText('email', array('value'=>$user['email'], 'required'=>true)))
			 ->AddElement(new CFormElementCheckboxMultiple('groups', array('values'=>$outputGroups, 'checked'=>$outputMemberships)))
			 ->AddElement(new CFormElementSubmit('save', array('callback'=>array($object, 'DoSaveProfile'))))
			 ->AddElement(new CFormElementSubmit('delete', array('callback'=>array($object, 'DoDeleteProfile'))));
	}
}