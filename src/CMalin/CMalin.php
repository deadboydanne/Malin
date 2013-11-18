<?php

/**
 * Main class for Malin, holds everything.
 *
 * @package MalinCore
 */
class CMalin implements ISingleton {

   	/**
	 * Members
	 */
	private static $instance = null;
	public $config = array();
	public $request;
	public $data;
	public $db;
	public $views;
  	public $user; 
  	public $session;
	public $timer = array();


   /**
    * Singleton pattern. Get the instance of the latest created object or create a new one. 
    * @return CMalin The instance of this class.
    */
   public static function Instance() {
      if(self::$instance == null) {
         self::$instance = new CMalin();
      }
      return self::$instance;
   }
   
   /**
    * Constructor
    */
  /**
   * Constructor
   */
  protected function __construct() {
    // time page generation
    $this->timer['first'] = microtime(true); 
    
    // include the site specific config.php and create a ref to $ly to be used by config.php
    $ma = &$this;
    require(MALIN_SITE_PATH.'/config.php');
    
    // Start a named session
    session_name($this->config['session_name']);
    session_start();
    $this->session = new CSession($this->config['session_key']);
    $this->session->PopulateFromSession();
    
    // Set default date/time-zone
    date_default_timezone_set('UTC');
    
    // Create a database object.
    if(isset($this->config['database'][0]['dsn'])) {
      $this->db = new CDatabase($this->config['database'][0]['dsn']);
    }
    
    // Create a container for all views and theme data
    $this->views = new CViewContainer();
    
    // Create a object for the user
    $this->user = new CMUser($this);
  }
   
   /**
    * Frontcontroller, check url and route to controllers.
    */
  public function FrontControllerRoute() {

    // Step 1
    // Take current url and divide it in controller, method and parameters

    // Step 2
    // Check if there is a callable method in the controller class, if then call it

	    // Take current url and divide it in controller, method and parameters
    $this->request = new CRequest();
    $this->request->Init($this->config['base_url']);
    $controller = $this->request->controller;
    $method     = $this->request->method;
    $arguments  = $this->request->arguments;
	
	    // Is the controller enabled in config.php?
    $controllerExists    = isset($this->config['controllers'][$controller]);
    $controllerEnabled    = false;
    $className             = false;
    $classExists           = false;

    if($controllerExists) {
      $controllerEnabled    = ($this->config['controllers'][$controller]['enabled'] == true);
      $className               = $this->config['controllers'][$controller]['class'];
      $classExists           = class_exists($className);
    }

	    // Check if controller has a callable method in the controller class, if then call it
    if($controllerExists && $controllerEnabled && $classExists) {
      $rc = new ReflectionClass($className);
      if($rc->implementsInterface('IController')) {
        if($rc->hasMethod($method)) {
          $controllerObj = $rc->newInstance();
          $methodObj = $rc->getMethod($method);
          $methodObj->invokeArgs($controllerObj, $arguments);
        } else {
          die("404. " . get_class() . ' error: Controller does not contain method.');
        }
      } else {
        die('404. ' . get_class() . ' error: Controller does not implement interface IController.');
      }
    } 
    else { 
      die('404. Page is not found.');
    }
	
   $formattedMethod = str_replace(array('_', '-'), '', $method);

  }
  
	/**
	 * ThemeEngineRender, renders the reply of the request to HTML or whatever.
	 */
  public function ThemeEngineRender() {
    // Save to session before output anything
    $this->session->StoreInSession();
  
    // Is theme enabled?
    if(!isset($this->config['theme'])) {
      return;
    }
    
    // Get the paths and settings for the theme
    $themeName 	= $this->config['theme']['name'];
    $themePath 	= MALIN_INSTALL_PATH . "/themes/{$themeName}";
    $themeUrl		= $this->request->base_url . "themes/{$themeName}";
    
    // Add stylesheet path to the $ly->data array
    $this->data['stylesheet'] = "{$themeUrl}/".$this->config['theme']['stylesheet'];


    // Include the global functions.php and the functions.php that are part of the theme
    $ma = &$this;
    include(MALIN_INSTALL_PATH . '/themes/functions.php');
    $functionsPath = "{$themePath}/functions.php";
    if(is_file($functionsPath)) {
      include $functionsPath;
    }


    // Extract $ly->data to own variables and handover to the template file
    // Extract $ly->data to own variables and handover to the template file
    extract($this->data);      
    extract($this->views->GetData());
    $templateFile = (isset($this->config['theme']['template_file'])) ? $this->config['theme']['template_file'] : 'default.tpl.php';
    include("{$themePath}/{$templateFile}");
  }

  
}