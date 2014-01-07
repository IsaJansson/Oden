<?php
/**
* A container to hold a bunch of views.
* @package OdenCore
*/

class CViewContainer {

   private $data = array();
   private $views = array();

   // Constructor
   public function __construct() { ; }

   // Getters.
  public function GetData() { return $this->data; }

     // Set any variable that should be available for the theme engine.
   public function SetVariable($key, $value) {
     $this->data[$key] = $value;
     return $this;
  }

   // Set the title of the page.
   public function SetTitle($value) {
    return $this->SetVariable('title', $value);
  }

   /**
    * Add a view as file to be included and optional variables.
    * @param $file string path to the file to be included.
    * @param vars array containing the variables that should be avilable for the included file.
    * @param $region string the theme region, uses string 'default' as default region.
    */
   public function AddInclude($file, $variables=array(), $region='default') {
      $this->views[$region][] = array('type' => 'include', 'file' => $file, 'variables' => $variables);
      return $this;
  }


  /**
  * Add text and optional varables 
  * @param $string string, content to be displayed 
  * @param $vars, array containing the variables that should be avilable for the includeed file
  * @param $regions string, the theme region uses string 'default' as default region
  * @return $this
  */
  public function AddString($string, $variables=array(), $region='default') {
    $this->views[$region][] = array('type' => 'string', 'string' => $string, 'variables' => $variables);
    return $this;
  }

  /**
  * Check if there exists views for a specific region 
  * @param $region string/array, the themes region(s)
  * @return boolean, true if region has a view, else false
  */
  public function RegionHasView($region) {
    if(is_array($region)) {
      foreach($region as $val) {
        if(isset($this->views[$val])) {
          return true;
        }
      }
      return false;
    } else {
      return(isset($this->views[$region]));
    }
  }

  // Add inline style
  public function AddStyle($value) {
    if(isset($this->data['inline-style'])) {
      $this->data['inline-style'] .= $value;
    }
    else {
      $this->data['inline-style'] = $value;
    }
    return $this;
  }


   // Render all views according to their type.
  public function Render($region='default') {
    if(!isset($this->views[$region])) return;
    foreach($this->views[$region] as $view) {
      switch($view['type']) {
        case 'include': if(isset($view['variables'])) extract($view['variables']); include($view['file']); break;
        case 'string':  if(isset($view['variables'])) extract($view['variables']); echo $view['string']; break;
      }
    }
  }



}