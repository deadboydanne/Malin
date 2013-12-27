<?php
/**
 * Standard controller layout.
 * 
 * @package LydiaCore
 */
class CCIndex extends CObject implements IController {


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
    $modules = new CMModules();
	$ma = CMalin::Instance();
	$checkAdmin = "";
	$databaseNotSet = "";
    $controllers = $modules->AvailableControllers();
	if(filesize(MALIN_SITE_PATH.'/data/.ht.sqlite') > 10){
		$checkAdmin = $this->user->getAdmin();
	}else{
		$databaseNotSet = true;
	}
		if($checkAdmin == false || $ma->user['hasRoleAdmin'] || $databaseNotSet == true){
			$this->views->SetTitle('Index')
						->AddInclude(__DIR__ . '/index.tpl.php', array(), 'primary')
						->AddInclude(__DIR__ . '/sidebar.tpl.php', array('controllers'=>$controllers), 'sidebar');
		}else{
			header('location: '.$this->request->CreateUrl("my"));
		}
  }

}