<?php
/**
* To manage and analyse all modules of Malin.
* 
* @package MalinCore
*/
class CCModules extends CObject implements IController {
	
	public $createAdminResult;

  /**
   * Constructor
   */
  public function __construct() { parent::__construct(); }


  /**
   * Show a index-page and display what can be done through this controller.
   */
  public function Index() {
    $modules = new CMModules();
    $controllers = $modules->AvailableControllers();
    $allModules = $modules->ReadAndAnalyse();
    $this->views->SetTitle('Manage Modules')
                ->AddInclude(__DIR__ . '/index.tpl.php', array('controllers'=>$controllers), 'primary')
                ->AddInclude(__DIR__ . '/sidebar.tpl.php', array('modules'=>$allModules), 'sidebar');
  }

  /**
   * Install database
   */
  public function Install() {
    $modules = new CMModules();
    $results = $modules->Install();
    $allModules = $modules->ReadAndAnalyse();
    $this->views->SetTitle('Install Modules')
                ->AddInclude(__DIR__ . '/install.tpl.php', array('modules'=>$results), 'primary')
                ->AddInclude(__DIR__ . '/sidebar.tpl.php', array('modules'=>$allModules), 'sidebar');
  }
  
    /**
   * Show install admin form to create admin account
   */
  public function Installadmin() {
    $modules = new CMModules();
	if(isset($_POST['DoSubmit'])){unset($_SESSION['installAdminErrorMsg']);}
    $allModules = $modules->ReadAndAnalyse();
    $this->views->SetTitle('Install Admin Account')
                ->AddInclude(__DIR__ . '/installadmin.tpl.php', array(), 'primary')
                ->AddInclude(__DIR__ . '/sidebar.tpl.php', array('modules'=>$allModules), 'sidebar');
  }
  
    /**
   * Show install pages to set up your different pages
   */
  public function Installpages() {
	$createAdminResult = "";
    $modules = new CMModules();
	unset($_SESSION['installAdminErrorMsg']);
	if(isset($_POST['DoCreate'])){
		$createPagesResult = $modules->saveAdminAcc($_POST['acronym'], $_POST['password'], $_POST['password1'], $_POST['name'], $_POST['email']);
	}
    $allModules = $modules->ReadAndAnalyse();
    $this->views->SetTitle('Set up your pages')
                ->AddInclude(__DIR__ . '/installpages.tpl.php', array('result' => $createPagesResult), 'primary')
                ->AddInclude(__DIR__ . '/sidebar.tpl.php', array('modules'=>$allModules), 'sidebar');
  }
  
    /**
   * Show install style form to choose your style
   */
  public function Installstyle() {
    $modules = new CMModules();
	unset($_SESSION['installPagesErrorMsg']);
	if(isset($_POST['CreatePages'])){
		
		$headerName = "";
		$headerSlogan = "";
		$footerHeadline = "";
		$startActive = "";
		$guestActive = "";
		$blogActive = "";
		$pageOneActive = "";
		$pageTwoActive = "";
		$startName = "";
		$guestName = "";
		$blogName = "";
		$pageOneName = "";
		$pageTwoName = "";

		if(isset($_POST['headerName'])){
			$headerName = $_POST['headerName'];
		}
		if(isset($_POST['headerSlogan'])){
			$headerSlogan = $_POST['headerSlogan'];
		}
		if(isset($_POST['footerHeadline'])){
			$footerHeadline = $_POST['footerHeadline'];
		}
		if(isset($_POST['checkStart'])){
			$startActive = $_POST['checkStart'];
		}
		if(isset($_POST['checkGuest'])){
			$guestActive = $_POST['checkGuest'];
		}
		if(isset($_POST['checkBlog'])){
			$blogActive = $_POST['checkBlog'];
		}
		if(isset($_POST['checkPage1'])){
			$pageOneActive = $_POST['checkPage1'];
		}
		if(isset($_POST['checkPage2'])){
			$pageTwoActive = $_POST['checkPage2'];
		}
		if(isset($_POST['textStart'])){
			$startName = $_POST['textStart'];
		}
		if(isset($_POST['textGuest'])){
			$guestName = $_POST['textGuest'];
		}
		if(isset($_POST['textBlog'])){
			$blogName = $_POST['textBlog'];
		}
		if(isset($_POST['textPage1'])){
			$pageOneName = $_POST['textPage1'];
		}
		if(isset($_POST['textPage2'])){
			$pageTwoName = $_POST['textPage2'];
		}
		$createAdminResult = $modules->savePages($headerName, $headerSlogan, $footerHeadline, $startActive, $guestActive, $blogActive, $pageOneActive, $pageTwoActive, $startName, $guestName, $blogName, $pageOneName, $pageTwoName);
	}
    $allModules = $modules->ReadAndAnalyse();
    $this->views->SetTitle('Setup your style')
                ->AddInclude(__DIR__ . '/installstyle.tpl.php', array('result' => $createAdminResult), 'primary')
                ->AddInclude(__DIR__ . '/sidebar.tpl.php', array('modules'=>$allModules), 'sidebar');
  }

    /**
   * Install Style and finish install
   */
  public function Installfinish() {
    $modules = new CMModules();
	if(isset($_POST['CreateStyle'])){
		$createStyleResult = $modules->saveStyle($_POST['backgroundColor'], $_POST['foregroundColor'], $_POST['menuSelectedColor'], $_POST['headerBottomBorderColor'], $_POST['menuSelectBorderColor'], $_POST['aColor'], $_POST['aHoverColor'], $_POST['fontColor'], $_POST['font']);
	}
    $allModules = $modules->ReadAndAnalyse();
    $this->views->SetTitle('Installation finished')
                ->AddInclude(__DIR__ . '/installfinish.tpl.php', array('result' => $createStyleResult), 'primary')
                ->AddInclude(__DIR__ . '/sidebar.tpl.php', array('modules'=>$allModules), 'sidebar');
  }

    /**
   * Show a module and its parts.
   */
  public function View($module) {
    if(!preg_match('/^C[a-zA-Z]+$/', $module)) {throw new Exception('Invalid characters in module name.');}
    $modules = new CMModules();
    $controllers = $modules->AvailableControllers();
    $allModules = $modules->ReadAndAnalyse();
    $aModule = $modules->ReadAndAnalyseModule($module);
    $this->views->SetTitle('Manage Modules')
                ->AddInclude(__DIR__ . '/view.tpl.php', array('module'=>$aModule), 'primary')
                ->AddInclude(__DIR__ . '/sidebar.tpl.php', array('modules'=>$allModules), 'sidebar');
  }
  
}