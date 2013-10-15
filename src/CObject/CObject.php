<?php
/**
 * Holding a instance of CLydia to enable use of $this in subclasses.
 *
 * @package LydiaCore
 */
class CObject {

   /**
    * Members
    */
   public $config;
   public $request;
   public $data;
   public $db;
   public $views;
   public $session;
	
   /**
    * Constructor
    */
   protected function __construct() {
    $ma = CMalin::Instance();
    $this->config   = &$ma->config;
    $this->request  = &$ma->request;
    $this->data     = &$ma->data;
    $this->db       = &$ma->db;
    $this->views    = &$ma->views;
    $this->session  = &$ma->session;
  }

	/**
	 * Redirect to another url and store the session
	 */
	protected function RedirectTo($urlOrController=null, $method=null) {
    $ma = CMalin::Instance();
    if(isset($ma->config['debug']['db-num-queries']) && $ma->config['debug']['db-num-queries'] && isset($ma->db)) {
      $this->session->SetFlash('database_numQueries', $this->db->GetNumQueries());
    }    
    if(isset($ma->config['debug']['db-queries']) && $ma->config['debug']['db-queries'] && isset($ma->db)) {
      $this->session->SetFlash('database_queries', $this->db->GetQueries());
    }    
    if(isset($ma->config['debug']['timer']) && $ma->config['debug']['timer']) {
	    $this->session->SetFlash('timer', $ma->timer);
    }    
    $this->session->StoreInSession();
    header('Location: ' . $this->request->CreateUrl($urlOrController, $method));
  }
  
	/**
	 * Redirect to a method within the current controller. Defaults to index-method. Uses RedirectTo().
	 *
	 * @param string method name the method, default is index method.
	 */
	protected function RedirectToController($method=null) {
    $this->RedirectTo($this->request->controller, $method);
  }




	/**
	 * Redirect to a controller and method. Uses RedirectTo().
	 *
	 * @param string controller name the controller or null for current controller.
	 * @param string method name the method, default is current method.
	 */
	protected function RedirectToControllerMethod($controller=null, $method=null) {
	  $controller = is_null($controller) ? $this->request->controller : null;
	  $method = is_null($method) ? $this->request->method : null;	  
    $this->RedirectTo($this->request->CreateUrl($controller, $method));
  }

}