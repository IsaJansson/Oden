<?php
/* 
* Helpers for theming, available for all themes in their template files and functions.php.
* This file is included right before the themes own functions.php
*/

 // Print debuginformation from the framework.
function get_debug() {
  $oden = COden::Instance();  
  if(empty($oden->config['debug'])) {
      return;
  }

  // Get the debug output 
  $html = null;
  if(isset($oden->config['debug']['db-num-queries']) && $oden->config['debug']['db-num-queries'] && isset($oden->db)) {
    $flash = $oden->session->GetFlash('database_numQueries');
    $flash = $flash ? "$flash + " : null;
    $html .= "<p>Database made $flash" . $oden->db->GetNumQueries() . " queries.</p>";
  }    
  if(isset($oden->config['debug']['db-queries']) && $oden->config['debug']['db-queries'] && isset($oden->db)) {
    $flash = $oden->session->GetFlash('database_queries');
    $queries = $oden->db->GetQueries();
    if($flash) {
      $queries = array_merge($flash, $queries);
    }
    $html .= "<p>Database made the following queries.</p><pre>" . implode('<br/><br/>', $queries) . "</pre>";
  }    
  if(isset($oden->config['debug']['timer']) && $oden->config['debug']['timer']) {
    $html .= "<p>Page was loaded in " . round(microtime(true) - $oden->timer['first'], 5)*1000 . " msecs.</p>";
  }    
  if(isset($oden->config['debug']['oden']) && $oden->config['debug']['oden']) {
    $html .= "<hr><h3>Debuginformation</h3><p>The content of COden:</p><pre>" . htmlent(print_r($oden, true)) . "</pre>";
  }    
  if(isset($oden->config['debug']['session']) && $oden->config['debug']['session']) {
    $html .= "<hr><h3>SESSION</h3><p>The content of COden->session:</p><pre>" . htmlent(print_r($oden->session, true)) . "</pre>";
    $html .= "<p>The content of \$_SESSION:</p><pre>" . htmlent(print_r($_SESSION, true)) . "</pre>";
  }  
  return $html;
}

 // Get messages stored in flash-session.
function get_messages_from_session() {
  $messages = COden::Instance()->session->GetMessages();
  $html = null;
  if(!empty($messages)) {
    foreach($messages as $val) {
      $valid = array('info', 'notice', 'success', 'warning', 'error', 'alert');
      $class = (in_array($val['type'], $valid)) ? $val['type'] : 'info';
      $html .= "<div class='$class'>{$val['message']}</div>\n";
    }
  }
  return $html;
}

// Create a url be prepending the base_url 
function base_url($url = null) {
  return COden::Instance()->request->base_url . trim($url, '/');
}

// Prepend the theme_url, which is the url to the current theme directory.
function theme_url($url) {
  $oden = COden::Instance();
  return "{$oden->request->base_url}theme/{$oden->config['theme']['name']}/{$url}";
}

// Create a url to an internal resource.
function create_url($url=null) {
  return COden::Instance()->request->CreateUrl($url);
}

// Return the current url
function current_url() {
  return COden::Instance()->request->current_url;
}

// Render all views 
function render_views() {
  return COden::Instance()->views->Render();
}

// Login menu. Creates a menu which reflects if the user if logged in or not.
function login_menu() {
  $oden = COden::Instance();
  if($oden->user['isAuthenticated']) {
    $items = "<a href='" . create_url('user/profile') . "'><img class='gravatar' src='" . get_gravatar(20) . "' alt=''>" . $oden->user['acronym'] . "</a> ";
    if($oden->user['hasRoleAdministrator']) {
      $items .= "<a href='" . create_url('acp') . "'>acp</a> ";
    }
    $items .= "<a href='" . create_url('user/logout') . "'>logout</a> ";
  } else {
    $items = "<a href='" . create_url('user/login') . "'>login</a> ";
  }
  return "<nav>$items</nav>";
}

// Get a gravatar based on the users email
function get_gravatar($size=null) {
  return 'http://www.gravatar.com/avatar/' . md5(strtolower(trim(COden::Instance()->user['email']))) . '.jpg?' . ($size ? "s=$size" : null);
}


