<?php
/**
* A form for editing the user profile
* @package OdenCore
*/

class CFormUserProfile extends CForm {
	// Constructor 
	public function __construct($object, $user) {
		parent::__construct();
		$this->AddElement(new CFormElementText('acronym', array('readonly'=>true, 'value'=>$user['acronym'])))
			->AddElement(new CFormElementPassword('password'))
			->AddElement(new CFormElementPassword('password1', array('lable'=>'Password again:')))
			->AddElement(new CFormElementSubmit('change_password', array('callback'=>array($object, 'DoChangePassword'))))
			->AddElement(new CFormElementText('name', array('value'=>$user['name'], 'required'=>true)))
			->AddElement(new CFormElementText('email', array('value'=>$user['email'], 'required'=>true)))
			->AddElement(new CFormElementSubmit('save', array('callback'=>array($object, 'DoProfileSave'))))
			->AddElement(new CFormElementSubmit('delete', array('callback'=>array($object, 'DoProfileDelete'))));
	}
	
}