<?php
/**
 * A model for managing Malin modules.
 * 
 * @package MalinCore
 */
class CMModules extends CObject {

  /**
   * Properties
   */
  private $malinCoreModules = array('CMalin', 'CDatabase', 'CRequest', 'CViewContainer', 'CSession', 'CObject');
  private $malinCMFModules = array('CForm', 'CCPage', 'CCBlog', 'CMUser', 'CCUser', 'CMContent', 'CCContent', 'CFormUserLogin', 'CFormUserProfile', 'CFormUserCreate', 'CFormContent', 'CHTMLPurifier');


  /**
   * Constructor
   */
  public function __construct() { parent::__construct(); }


  /**
   * A list of all available controllers/methods
   *
   * @returns array list of controllers (key) and an array of methods
   */
  public function AvailableControllers() {        
    $controllers = array();
    foreach($this->config['controllers'] as $key => $val) {
      if($val['enabled']) {
        $rc = new ReflectionClass($val['class']);
        $controllers[$key] = array();
        $methods = $rc->getMethods(ReflectionMethod::IS_PUBLIC);
        foreach($methods as $method) {
          if($method->name != '__construct' && $method->name != '__destruct' && $method->name != 'Index') {
            $methodName = mb_strtolower($method->name);
            $controllers[$key][] = $methodName;
          }
        }
        sort($controllers[$key], SORT_LOCALE_STRING);
      }
    }
    ksort($controllers, SORT_LOCALE_STRING);
    return $controllers;
  }


  /**
   * Read and analyse all modules.
   *
   * @returns array with a entry for each module with the module name as the key. 
   *                Returns boolean false if $src can not be opened.
   */
  public function ReadAndAnalyse() {
    $src = MALIN_INSTALL_PATH.'/src';
    if(!$dir = dir($src)) throw new Exception('Could not open the directory.');
    $modules = array();
    while (($module = $dir->read()) !== false) {
      if(is_dir("$src/$module") && class_exists($module)) {
        $modules[$module] = $this->GetDetailsOfModule($module);
      }
    }
    $dir->close();
    ksort($modules, SORT_LOCALE_STRING);
    return $modules;
  }
  

  /**
   * Get info and details about a module.
   *
   * @param $module string with the module name.
   * @returns array with information on the module.
   */
  private function GetDetailsOfModule($module) {
    $details = array();
    if(class_exists($module)) {
      $rc = new ReflectionClass($module);
      $details['name']          = $rc->name;
      $details['filename']      = $rc->getFileName();
      $details['doccomment']    = $rc->getDocComment();
      $details['interface']     = $rc->getInterfaceNames();
      $details['isController']  = $rc->implementsInterface('IController');
      $details['isModel']       = preg_match('/^CM[A-Z]/', $rc->name);
      $details['hasSQL']        = $rc->implementsInterface('IHasSQL');
      $details['isManageable']  = $rc->implementsInterface('IModule');
      $details['isMalinCore']   = in_array($rc->name, $this->malinCoreModules);
      $details['isMalinCMF']    = in_array($rc->name, $this->malinCMFModules);
      $details['publicMethods']     = $rc->getMethods(ReflectionMethod::IS_PUBLIC);
      $details['protectedMethods']  = $rc->getMethods(ReflectionMethod::IS_PROTECTED);
      $details['privateMethods']    = $rc->getMethods(ReflectionMethod::IS_PRIVATE);
      $details['staticMethods']     = $rc->getMethods(ReflectionMethod::IS_STATIC);
    }
    return $details;
  }
  

  /**
   * Get info and details about the methods of a module.
   *
   * @param $module string with the module name.
   * @returns array with information on the methods.
   */
  private function GetDetailsOfModuleMethods($module) {
    $methods = array();
    if(class_exists($module)) {
      $rc = new ReflectionClass($module);
      $classMethods = $rc->getMethods();
      foreach($classMethods as $val) {
        $methodName = $val->name;
        $rm = $rc->GetMethod($methodName);
        $methods[$methodName]['name']          = $rm->getName();
        $methods[$methodName]['doccomment']    = $rm->getDocComment();
        $methods[$methodName]['startline']     = $rm->getStartLine();
        $methods[$methodName]['endline']       = $rm->getEndLine();
        $methods[$methodName]['isPublic']      = $rm->isPublic();
        $methods[$methodName]['isProtected']   = $rm->isProtected();
        $methods[$methodName]['isPrivate']     = $rm->isPrivate();
        $methods[$methodName]['isStatic']      = $rm->isStatic();
      }
    }
    ksort($methods, SORT_LOCALE_STRING);
    return $methods;
  }
  

  /**
   * Get info and details about a module.
   *
   * @param $module string with the module name.
   * @returns array with information on the module.
   */
  public function ReadAndAnalyseModule($module) {
    $details = $this->GetDetailsOfModule($module);
    $details['methods'] = $this->GetDetailsOfModuleMethods($module);
    return $details;
  }
  

  /**
   * Install all modules.
   *
   * @returns array with a entry for each module and the result from installing it.
   */
  public function Install() {
    $allModules = $this->ReadAndAnalyse();
    uksort($allModules, function($a, $b) {
        return ($a == 'CMUser' ? -1 : ($b == 'CMUser' ? 1 : 0));
      }
    );
    $installed = array();
    foreach($allModules as $module) {
      if($module['isManageable']) {
        $classname = $module['name'];
        $rc = new ReflectionClass($classname);
        $obj = $rc->newInstance();
        $method = $rc->getMethod('Manage');
        $installed[$classname]['name']    = $classname;
        $installed[$classname]['result']  = $method->invoke($obj, 'install');
      }
    }
    //ksort($installed, SORT_LOCALE_STRING);
    return $installed;
  }

  public function saveAdminAcc($acronym, $password, $password1, $name, $email) {
	  if(isset($_POST['DoCreate'])){
		  if(empty($acronym)){
			  $_SESSION['installAdminErrorMsg'] = "*Please type in an acronym.";
			  header("location: ".$this->request->CreateUrl('module', 'installadmin'));
			  exit;
		  }
		  if(empty($password)){
			  $_SESSION['installAdminErrorMsg'] = "*Please type in a password.";
			  header("location: ".$this->request->CreateUrl('module', 'installadmin'));
			  exit;
		  }
		  if($password != $password1){
			  $_SESSION['installAdminErrorMsg'] = "*Your passwords does not match.";
			  header("location: ".$this->request->CreateUrl('module', 'installadmin'));
			  exit;
		  }
		  if(empty($name)){
			  $_SESSION['installAdminErrorMsg'] = "*Please type in your name.";
			  header("location: ".$this->request->CreateUrl('module', 'installadmin'));
			  exit;
		  }
		  if(empty($email)){
			  $_SESSION['installAdminErrorMsg'] = "*Please type in your E-mail.";
			  header("location: ".$this->request->CreateUrl('module', 'installadmin'));
			  exit;
		  }
		  
		  try{
			  $this->user->Create($acronym, $password, $name, $email);
			  
			  return array('success', 'Successfully created admin account.');

			} catch(Exception$e) {
			  die("$e<br/>Failed to open database: " . $this->config['database'][0]['dsn']);
			}  		  		  
	  }else{
		  
		  return array('error', 'Could not create admin account');
	  }
	  
  }

  public function savePages($headerName, $headerSlogan, $footerHeadline, $startActive, $guestActive, $blogActive, $pageOneActive, $pageTwoActive, $startName, $guestName, $blogName, $pageOneName, $pageTwoName) {
	  if(isset($_POST['CreatePages']) || isset($_POST['doUpdate'])){
		  if(isset($_POST['CreatePages'])){
			  if(empty($headerName)){
				  $_SESSION['installPagesErrorMsg'] = "*Please type in a name for the webpage.";
				  header("location: ".$this->request->CreateUrl('module', 'installpages'));
				  exit;
			  }
		  }
		  if(isset($_POST['doUpdate'])){
			  if(empty($headerName)){
				  header("location: ".$this->request->CreateUrl('acp', 'managePages'));
				  exit;
			  }
		  }
		  
		  try{
			  
			  $this->user->savePageSettings($headerName, $headerSlogan, $footerHeadline, $startActive, $guestActive, $blogActive, $pageOneActive, $pageTwoActive, $startName, $guestName, $blogName, $pageOneName, $pageTwoName);
			  
			  return array('success', 'Successfully setup your pages.');

			} catch(Exception$e) {
			  die("$e<br/>Failed to open database: " . $this->config['database'][0]['dsn']);
			}  		  		  
	  }else{
		  
		  return array('error', 'Could not create admin account');
	  }
	  
  }

  public function saveStyle($backgroundColor, $foregroundColor, $menuSelectedColor, $headerBottomBorderColor, $menuSelectBorderColor, $aColor, $aHoverColor, $fontColor, $font) {
	  if(isset($_POST['CreateStyle']) || isset($_POST['doSubmit'])){
		  
		  try{
			  
			  $this->user->saveStyleSettings($backgroundColor, $foregroundColor, $menuSelectedColor, $headerBottomBorderColor, $menuSelectBorderColor, $aColor, $aHoverColor, $fontColor, $font);
			  
			  return array('success', 'Successfully setup your style.');

			} catch(Exception$e) {
			  die("$e<br/>Failed to open database: " . $this->config['database'][0]['dsn']);
			}  		  		  
	  }else{
		  
		  return array('error', 'Could setup your style');
	  }
	  
  }

}