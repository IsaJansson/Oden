<?php
/**
* A utility class to easy creating and handling of forms
* @package OdenCore
*/
class CFormElement implements ArrayAccess{

  public $attributes;
  public $characterEncoding;

  /**
   * Constructor
   * @param string, name of the element
   * @param array, attributes to set to the element. Default is an empty array
   */
  public function __construct($name, $attributes=array()) {
    $this->attributes = $attributes;    
    $this['name'] = $name;
    if(is_callable('COden::Instance()')) {
      $this->characterEncoding = COden::Instance()->config['character_encoding'];
    }
    else {
      $this->characterEncoding = 'UTF-8';
    }
  }
  
  
  // Implementing ArrayAccess for this->attributes
  public function offsetSet($offset, $value) { if (is_null($offset)) { $this->attributes[] = $value; } 
  else { $this->attributes[$offset] = $value; }}
  public function offsetExists($offset) { return isset($this->attributes[$offset]); }
  public function offsetUnset($offset) { unset($this->attributes[$offset]); }
  public function offsetGet($offset) { return isset($this->attributes[$offset]) ? $this->attributes[$offset] : null; }


  // Get HTML code for a element. 
  public function GetHTML() {
    $id         = isset($this['id']) ? $this['id'] : 'form-element-' . $this['name'];
    $class      = isset($this['class']) ? " {$this['class']}" : null;
    $validates  = (isset($this['validation-pass']) && $this['validation-pass'] === false) ? ' validation-failed' : null;
    $class      = (isset($class) || isset($validates)) ? " class='{$class}{$validates}'" : null;
    $name       = " name='{$this['name']}'";
    $label      = isset($this['label']) ? ($this['label'] . (isset($this['required']) && $this['required'] ? "<span class='form-element-required'>*</span>" : null)) : null;
    $autofocus  = isset($this['autofocus']) && $this['autofocus'] ? " autofocus='autofocus'" : null;    
    $readonly   = isset($this['readonly']) && $this['readonly'] ? " readonly='readonly'" : null;    
    $type       = isset($this['type']) ? " type='{$this['type']}'" : null;
    $onlyValue  = isset($this['value']) ? htmlent($this['value'], ENT_COMPAT, $this->characterEncoding) : null;
    $value      = isset($this['value']) ? " value='{$onlyValue}'" : null;

    $messages = null;
    if(isset($this['validation_messages'])) {
      $message = null;
      foreach($this['validation_messages'] as $val) {
        $message .= "<li>{$val}</li>\n";
      }
      $messages = "<ul class='validation-message'>\n{$message}</ul>\n";
    }
    
    if($type && $this['type'] == 'submit') {
      return "<p><input id='$id'{$type}{$class}{$name}{$value}{$autofocus}{$readonly} /></p>\n";
    } 
    else if($type && $this['type'] == 'textarea') {
        return "<p><label for='$id'>$label</label><br><textarea id='$id'{$type}{$class}{$name}{$autofocus}{$readonly}>{$onlyValue}</textarea></p>\n"; 
    } 
    else if($type && $this['type'] == 'hidden') {
        return "<input id='$id'{$type}{$class}{$name}{$value} />\n"; 
    }
    else if($type && $this['type'] == 'select') {
        return "<p><label for='$id'>$label</label><br><select id='$id'{$type}{$class}{$name}{$value}>
          <option value='{$type}{$class}{$name}{$value} selected'>{$onlyValue}</option>
          <option value='plain'>Plain</option>
          <option value='htmlpurify'>HTML Purify</option>
          <option value='bbcode'>BB Code</option>
          <option value='make_clickable'>Make Clickable</option>
          <option value='markdownextra'>Markdown Extra</option>
          <option value='smartypants'>Smarty Pants</option>
        </select></p>\n"; 
    } 
    else {
      return "<p><label for='$id'>$label</label><br><input id='$id'{$type}{$class}{$name}{$value}{$autofocus}{$readonly} />{$messages}</p>\n";                          
    }
  }


  // Use the element name as label if label is not set.
  public function UseNameAsDefaultLabel() {
    if(!isset($this['label'])) {
      $this['label'] = ucfirst(strtolower(str_replace(array('-','_'), ' ', $this['name']))).':';
    }
  }


  // Use the element name as value if value is not set.
  public function UseNameAsDefaultValue() {
    if(!isset($this['value'])) {
      $this['value'] = ucfirst(strtolower(str_replace(array('-','_'), ' ', $this['name'])));
    }
  }

    /**
   * Validate the form element value according to a ruleset.
   * @param $rules, array of validation rules.
   * returns boolean true if all rules pass, else false.
   */
  public function Validate($rules) {
    $tests = array(
      'fail' => array(
        'message' => 'Will always fail.', 
        'test' => 'return false;',
      ),
      'pass' => array(
        'message' => 'Will always pass.', 
        'test' => 'return true;',
      ),
      'not_empty' => array(
        'message' => 'Can not be empty.', 
        'test' => 'return $value != "";',
      ),
    );
    $pass = true;
    $messages = array();
    $value = $this['value'];
    foreach($rules as $key => $val) {
      $rule = is_numeric($key) ? $val : $key;
      if(!isset($tests[$rule])) throw new Exception('Validation of form element failed, no such validation rule exists.');
      if(eval($tests[$rule]['test']) === false) {
        $messages[] = $tests[$rule]['message'];
        $pass = false;
      }
    }
    if(!empty($messages)) $this['validation_messages'] = $messages;
    return $pass;
  }


}


class CFormElementText extends CFormElement {
  /**
   * Constructor
   * @param string name of the element.
   * @param array attributes to set to the element. Default is an empty array.
   */
  public function __construct($name, $attributes=array()) {
    parent::__construct($name, $attributes);
    $this['type'] = 'text';
    $this->UseNameAsDefaultLabel();
  }
}

class CFormElementTextarea extends CFormElement {
  /**
   * Constructor
   * @param string name of the element
   * @param array attributes to set to the element. Default is an empty array
   */
  public function __construct($name, $attributes=array()) {
    parent::__construct($name, $attributes);
    $this['type'] = 'textarea';
    $this->UseNameAsDefaultLabel();
  }
}


class CFormElementHidden extends CFormElement {
  /**
   * Constructor
   * @param string, name of the element
   * @param array, attributes to set to the element. Default is an empty array
   */
  public function __construct($name, $attributes=array()) {
    parent::__construct($name, $attributes);
    $this['type'] = 'hidden';
  }
}


class CFormElementPassword extends CFormElement {
  /**
   * Constructor
   * @param string name of the element.
   * @param array attributes to set to the element. Default is an empty array.
   */
  public function __construct($name, $attributes=array()) {
    parent::__construct($name, $attributes);
    $this['type'] = 'password';
    $this->UseNameAsDefaultLabel();
  }
}


class CFormElementSubmit extends CFormElement {
  /**
   * Constructor
   * @param string, name of the element
   * @param array, attributes to set to the element. Default is an empty array
   */
  public function __construct($name, $attributes=array()) {
    parent::__construct($name, $attributes);
    $this['type'] = 'submit';
    $this->UseNameAsDefaultValue();
  }
}

class CFormElementSelect extends CFormElement {
  /**
   * Constructor
   * @param string, name of the element
   * @param array, attributes to set to the element. Default is an empty array
   */
  public function __construct($name, $attributes=array()) {
    parent::__construct($name, $attributes);
    $this['type'] = 'select';
    $this->UseNameAsDefaultValue();
  }
}

class CForm implements ArrayAccess {

  public $form;     // array with settings for the form
  public $elements; // array with all form elements
  
  // Constructor
  public function __construct($form=array(), $elements=array()) {
    $this->form = $form;
    $this->elements = $elements;
  }

  // Implementing ArrayAccess for this->elements
  public function offsetSet($offset, $value) { if (is_null($offset)) { $this->elements[] = $value; } else { $this->elements[$offset] = $value; }}
  public function offsetExists($offset) { return isset($this->elements[$offset]); }
  public function offsetUnset($offset) { unset($this->elements[$offset]); }
  public function offsetGet($offset) { return isset($this->elements[$offset]) ? $this->elements[$offset] : null; }

  // Add a form element
  public function AddElement($element) {
    $this[$element['name']] = $element;
    return $this;
  }
  
  // Return HTML for the form
  public function GetHTML($type=null) {
    $id     = isset($this->form['id'])      ? " id='{$this->form['id']}'" : null;
    $class  = isset($this->form['class'])   ? " class='{$this->form['class']}'" : null;
    $name   = isset($this->form['name'])    ? " name='{$this->form['name']}'" : null;
    $action = isset($this->form['action'])  ? " action='{$this->form['action']}'" : null;
    $method = " method='post'";

    if($type == 'form') {
      return "<form{$id}{$class}{$name}{$action}{$method}>";
    }
    
    $elements = $this->GetHTMLForElements();
    $html = <<< EOD
\n<form{$id}{$class}{$name}{$action}{$method}>
<fieldset>
{$elements}
</fieldset>
</form>
EOD;
    return $html;
  }
 
  // Return HTML for the elements
  public function GetHTMLForElements() {
    $html = null;
    foreach($this->elements as $element) {
      $html .= $element->GetHTML();
    }
    return $html;
  }
  
  // Check if a form was submitted and perform validation and call callbacks
  public function Check() {
    $validates = null;
    $callbackStatus = null;
    $values = array();
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      unset($_SESSION['form-failed']);
      $validates = true;
      foreach($this->elements as $element) {
        if(isset($_POST[$element['name']])) {
          $values[$element['name']]['value'] = $element['value'] = $_POST[$element['name']];
          if(isset($element['validation'])) {
            $element['validation-pass'] = $element->Validate($element['validation']);
            if($element['validation-pass'] === false) {
              $values[$element['name']] = array('value'=>$element['value'], 'validation-messages'=>$element['validation-messages']);
              $validates = false;
            }
          }
          if(isset($element['callback']) && $validates) {
            if(isset($element['callback-args'])) {
                                            if(call_user_func_array($element['callback'], array_merge(array($this), $element['callback-args'])) === false) {
                                              $callbackStatus = false;
                                            }
                                    } else {
              if(call_user_func($element['callback'], $this) === false) {
                                              $callbackStatus = false;
              }
            }
          }
        }
      }
    } else if(isset($_SESSION['form-failed'])) {
      foreach($_SESSION['form-failed'] as $key => $val) {
        $this[$key]['value'] = $val['value'];
        if(isset($val['validation-messages'])) {
          $this[$key]['validation-messages'] = $val['validation-messages'];
          $this[$key]['validation-pass'] = false;
        }
      }
      unset($_SESSION['form-failed']);
    }
    if($validates === false || $callbackStatus === false) {
      $_SESSION['form-failed'] = $values;
    }
    if($callbackStatus === false)
      return false;
    else 
      return $validates;
  }

    /**
   * Set validation to a form element
   * @param $element string, the name of the formelement to add validation rules to.
   * @param $rules array of validation rules.
   * @return $this CForm
   */
  public function SetValidation($element, $rules) {
    $this[$element]['validation'] = $rules;
    return $this;
  }
  
  
}