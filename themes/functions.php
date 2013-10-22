<?php
/**
 * Helpers for theming, available for all themes in their template files and functions.php.
 * This file is included right before the themes own functions.php
 */
 


/**
 * Print debuginformation from the framework.
 */
function get_debug() {
  // Only if debug is wanted.
  $ma = CMalin::Instance();  
  if(empty($ma->config['debug'])) {
    return;
  }
  
  // Get the debug output
  $html = null;
  if(isset($ma->config['debug']['db-num-queries']) && $ma->config['debug']['db-num-queries'] && isset($ma->db)) {
    $flash = $ma->session->GetFlash('database_numQueries');
    $flash = $flash ? "$flash + " : null;
    $html .= "<p>Database made $flash" . $ma->db->GetNumQueries() . " queries.</p>";
  }    
  if(isset($ma->config['debug']['db-queries']) && $ma->config['debug']['db-queries'] && isset($ma->db)) {
    $flash = $ma->session->GetFlash('database_queries');
    $queries = $ma->db->GetQueries();
    if($flash) {
      $queries = array_merge($flash, $queries);
    }
    $html .= "<p>Database made the following queries.</p><pre>" . implode('<br/><br/>', $queries) . "</pre>";
  }    
  if(isset($ma->config['debug']['timer']) && $ma->config['debug']['timer']) {
    $html .= "<p>Page was loaded in " . round(microtime(true) - $ma->timer['first'], 5)*1000 . " msecs.</p>";
  }    
  if(isset($ma->config['debug']['malin']) && $ma->config['debug']['malin']) {
    $html .= "<hr><h3>Debuginformation</h3><p>The content of CMalin:</p><pre>" . htmlent(print_r($ma, true)) . "</pre>";
  }    
  if(isset($ma->config['debug']['session']) && $ma->config['debug']['session']) {
    $html .= "<hr><h3>SESSION</h3><p>The content of CMalin->session:</p><pre>" . htmlent(print_r($ma->session, true)) . "</pre>";
    $html .= "<p>The content of \$_SESSION:</p><pre>" . htmlent(print_r($_SESSION, true)) . "</pre>";
  }    
  return $html;
}




/**
 * Get messages stored in flash-session.
 */
function get_messages_from_session() {
  $messages = CMalin::Instance()->session->GetMessages();
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

/**
 * Login menu. Creates a menu which reflects if user is logged in or not.
 */
function login_menu() {
  $ma = CMalin::Instance();
  if($ma->user['isAuthenticated']) {
    $items = "<a href='" . create_url('user/profile') . "'>" . $ma->user['acronym'] . "</a> ";
    if($ma->user['hasRoleAdministrator']) {
      $items .= "<a href='" . create_url('acp') . "'>acp</a> ";
    }
    $items .= "<a href='" . create_url('user/logout') . "'>logout</a> ";
  } else {
    $items = "<a href='" . create_url('user/login') . "'>login</a> ";
  }
  return "<nav>$items</nav>";
}

/**
 * Prepend the base_url.
 */
function base_url($url=null) {
  return CMalin::Instance()->request->base_url . trim($url, '/');
}

/**
 * Create a url to an internal resource.
 *
 * @param string the whole url or the controller. Leave empty for current controller.
 * @param string the method when specifying controller as first argument, else leave empty.
 * @param string the extra arguments to the method, leave empty if not using method.
 */
function create_url($urlOrController=null, $method=null, $arguments=null) {
  return CMalin::Instance()->request->CreateUrl($urlOrController, $method, $arguments);
}




/**
 * Prepend the theme_url, which is the url to the current theme directory.
 */
function theme_url($url) {
  $ma = CMalin::Instance();
  return "{$ma->request->base_url}themes/{$ma->config['theme']['name']}/{$url}";
}




/**
 * Return the current url.
 */
function current_url() {
  return CMalin::Instance()->request->current_url;
}




/**
 * Render all views.
 */
function render_views() {
  return CMalin::Instance()->views->Render();
}
