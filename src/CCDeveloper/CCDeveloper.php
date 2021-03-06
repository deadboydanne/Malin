<?php
/**
 * Controller for development and testing purpose, helpful methods for the developer.
 * 
 * @package MalinCore
 */
class CCDeveloper extends CObject implements IController {

  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
  }
  /**
    * Implementing interface IController. All controllers must have an index action.
   */
  public function Index() {  
    $this->Menu();
  }

/**
     * Display all items of the CObject.
    */
   public function DisplayObject() {   
      $this->Menu();
      
      $this->data['main'] .= <<<EOD
<h2>Dumping content of CDeveloper</h2>
<p>Here is the content of the controller, including properties from CObject which holds access to common resources in CLydia.</p>
EOD;
      $this->data['main'] .= '<pre>' . htmlentities(print_r($this, true)) . '</pre>';
   }

  /**
    * Create a list of links in the supported ways.
   */
  public function Links() {  
    $this->Menu();
    
    $ma = CMalin::Instance();
    
    $url = 'developer/links';
    $current      = $ma->request->CreateUrl($url);

    $ma->request->cleanUrl = false;
    $ma->request->querystringUrl = false;    
    $default      = $ma->request->CreateUrl($url);
    
    $ma->request->cleanUrl = true;
    $clean        = $ma->request->CreateUrl($url);    
    
    $ma->request->cleanUrl = false;
    $ma->request->querystringUrl = true;    
    $querystring  = $ma->request->CreateUrl($url);
    
    $ma->data['main'] .= <<<EOD
<h2>CRequest::CreateUrl()</h2>
<p>Here is a list of urls created using above method with various settings. All links should lead to
this same page.</p>
<ul>
<li><a href='$current'>This is the current setting</a>
<li><a href='$default'>This would be the default url</a>
<li><a href='$clean'>This should be a clean url</a>
<li><a href='$querystring'>This should be a querystring like url</a>
</ul>
<p>Enables various and flexible url-strategy.</p>
EOD;
  }


  /**
    * Create a method that shows the menu, same for all methods
   */
  private function Menu() {
    $ma = CMalin::Instance();
    $menu = array('developer', 'developer/index', 'developer/links');
    
    $html = null;
    foreach($menu as $val) {
      $html .= "<li><a href='" . $ma->request->CreateUrl($val) . "'>$val</a>";  
    }
    
    $ma->data['title'] = "The Developer Controller";
    $ma->data['main'] = <<<EOD
<h1>The Developer Controller</h1>
<p>This is what you can do for now:</p>
<ul>
$html
</ul>
EOD;
  }
  
} 