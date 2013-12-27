<?php
/**
 * Admin Control Panel to manage admin stuff.
 * 
 * @package LydiaCore
 */
class CCAdminControlPanel extends CObject implements IController {




  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
  }




  /**
   * Show profile information of the user.
   */
  public function Index() {
  $ma = CMalin::Instance();
    if($ma->user['hasRoleAdmin']) {
		$this->views->SetTitle('ACP: Admin Control Panel')
					->AddInclude(__DIR__ . '/index.tpl.php', array(), 'primary')
					->AddInclude(__DIR__ . '/sidebar.tpl.php', array(), 'sidebar');
	}else{
		header('location: '.$this->request->CreateUrl("my", "accessDenied"));
	}
  }
 
  /**
   * Show all users and set up links to all users profiles
   */

  public function manageUsers() {
  $ma = CMalin::Instance();
    if($ma->user['hasRoleAdmin']) {
		$userList = $this->user->userList();
		$this->views->SetTitle('Manage Users')
					->AddInclude(__DIR__ . '/manageUsers.tpl.php', array('userList' => $userList), 'primary')
					->AddInclude(__DIR__ . '/sidebar.tpl.php', array(), 'sidebar');
	}else{
		header('location: '.$this->request->CreateUrl("my", "accessDenied"));
	}
  }

  /**
   * Manage user data for admin
   */

  public function manageUser($userId) {
	  $result = "";
	  if(isset($_POST['changePassword'])){
		  
		  $password = $_POST['password'];
		  $password1 = $_POST['password1'];
		  $userId = $_POST['id'];
		  
		  if(empty($password)){
			  $result = array('error', 'Please type in a password.');
		  }else if($password != $password1){
			  $result = array('error', 'Your passwords does not match.');
		  }else{
			  $this->user->ChangePassword($password, $userId);
			  $result = array('success', 'New password successfully saved.');
		  }
	  }
	  
	  if(isset($_POST['doDelete'])){
		  $userId = $_POST['id'];
		  $this->user->deleteUserFromGroups($userId);
		  $this->user->deleteUser($userId);
		  exit();
	  }
	  if(isset($_POST['doSubmit'])){
		  
		  $userId = $_POST['id'];
		  $email = $_POST['email'];
		  $name = $_POST['name'];
		  
				  if(empty($email)){
			  $result = array('error', 'Please type in your E-mail.');
				  }elseif(empty($name)){
			  $result = array('error', 'Please type in your name.');
				  }else{
					  $this->user->saveUser($userId, $name, $email);
					  $this->user->deleteUserFromGroups($userId);
					  foreach($_POST['groups'] as $groupId){
					  	$this->user->addUserToGroups($userId, $groupId);
					  }
					  $result = array('success', 'User successfully updated');
				  }
	  }

  $ma = CMalin::Instance();
    if($ma->user['hasRoleAdmin']) {
	$user = $this->user->viewUser($userId);
	$this->views->SetTitle('Manage User')
				->AddInclude(__DIR__ . '/manageUser.tpl.php', array('result' => $result, 'user' => $user['user']['0'], 'groups' => $user['groups'], 'user2groups' => $user['user2groups'], 'is_authenticated'=>$this->user['isAuthenticated']), 'primary')
				->AddInclude(__DIR__ . '/sidebar.tpl.php', array(), 'sidebar');
	}else{
		header('location: '.$this->request->CreateUrl("my", "accessDenied"));
	}
  }
  
  /**
   * Manage group data for admin
   */

  public function manageGroup($groupId) {
	  $result = "";
	  
	  if(isset($_POST['doDelete'])){
		  $groupId = $_POST['id'];
		  $this->user->deleteGroupConnections($groupId);
		  $this->user->deleteGroup($groupId);
		  exit();
	  }
	  if(isset($_POST['doSubmit'])){
		  
		  $groupId = $_POST['id'];
		  $acronym = $_POST['acronym'];
		  $name = $_POST['name'];
		  
				  if(empty($acronym)){
			  $result = array('error', 'Please type in group acronym.');
				  }elseif(empty($name)){
			  $result = array('error', 'Please type in group name.');
				  }else{
					  $this->user->saveGroup($groupId, $name, $acronym);
					  $result = array('success', 'Group successfully updated');
				  }
	  }

  $ma = CMalin::Instance();
    if($ma->user['hasRoleAdmin']) {
	$group = $this->user->viewGroup($groupId);
	$this->views->SetTitle('Manage Group')
				->AddInclude(__DIR__ . '/manageGroup.tpl.php', array('result' => $result, 'group' => $group, 'is_authenticated'=>$this->user['isAuthenticated']), 'primary')
				->AddInclude(__DIR__ . '/sidebar.tpl.php', array(), 'sidebar');
	}else{
		header('location: '.$this->request->CreateUrl("my", "accessDenied"));
	}
  }

  /**
   * Manage group data for admin
   */

  public function createGroup() {
	  $result = "";
	  
	  if(isset($_POST['doSubmit'])){
		  
		  $acronym = $_POST['acronym'];
		  $name = $_POST['name'];
		  
				  if(empty($acronym)){
			  $result = array('error', 'Please type in group acronym.');
				  }elseif(empty($name)){
			  $result = array('error', 'Please type in group name.');
				  }else{
					  $this->user->createGroup($name, $acronym);
					  $result = array('success', 'Group successfully created');
				  }
	  }

  $ma = CMalin::Instance();
    if($ma->user['hasRoleAdmin']) {
	$this->views->SetTitle('Manage Group')
				->AddInclude(__DIR__ . '/createGroup.tpl.php', array('result' => $result, 'is_authenticated'=>$this->user['isAuthenticated']), 'primary')
				->AddInclude(__DIR__ . '/sidebar.tpl.php', array(), 'sidebar');
	}else{
		header('location: '.$this->request->CreateUrl("my", "accessDenied"));
	}
  }

   /**
   * Create a new user.
   */
  public function Create() {
    $form = new CFormUserCreate($this);
    if($form->Check() === false) {
      $this->AddMessage('notice', 'You must fill in all values.');
      $this->RedirectToController('Create');
    }
    $this->views->SetTitle('Create user')
                ->AddInclude(__DIR__ . '/create.tpl.php', array('form' => $form->GetHTML()))   
				->AddInclude(__DIR__ . '/sidebar.tpl.php', array(), 'sidebar');
  }
   /**
   * Perform a creation of a user as callback on a submitted form.
   *
   * @param $form CForm the form that was submitted
   */
  public function DoCreate($form) {
	  $validateAcronym = $this->user->checkAcronym($form['acronym']['value']);
    if($form['password']['value'] != $form['password1']['value'] || empty($form['password']['value']) || empty($form['password1']['value'])) {
      $this->AddMessage('error', 'Password does not match or is empty.');
      $this->RedirectToController('create');
	}else if($validateAcronym == false){
      $this->AddMessage('error', 'acronym already exists.');
      $this->RedirectToController('create');
    } else if($this->user->Create($form['acronym']['value'], 
                           $form['password']['value'],
                           $form['name']['value'],
                           $form['email']['value']
                           )) {
      $this->AddMessage('success', "You have successfully created {$form['name']['value']}'s account.");
      $this->RedirectToController('create');
    } else {
      $this->AddMessage('notice', "Failed to create an account.");
      $this->RedirectToController('create');
    }
  }
 
  /**
   * Show all groups and set up links to all groups profiles
   */

  public function manageGroups() {
  $ma = CMalin::Instance();
    if($ma->user['hasRoleAdmin']) {
		$groupList = $this->user->groupList();
		$this->views->SetTitle('Manage groups')
					->AddInclude(__DIR__ . '/manageGroups.tpl.php', array('groupList' => $groupList), 'primary')
					->AddInclude(__DIR__ . '/sidebar.tpl.php', array(), 'sidebar');
	}else{
		header('location: '.$this->request->CreateUrl("my", "accessDenied"));
	}
  }

    /**
   * Manage your pages settings
   */
  public function managePages() {
    $modules = new CMModules();
	$managePagesResult = "";
	if(isset($_POST['doUpdate'])){
		if(!empty($_POST['headerName'])){
			
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
			$managePagesResult = $modules->savePages($headerName, $headerSlogan, $footerHeadline, $startActive, $guestActive, $blogActive, $pageOneActive, $pageTwoActive, $startName, $guestName, $blogName, $pageOneName, $pageTwoName);
			header('location: '.$this->request->CreateUrl("acp", "managePages"));
			exit();
		}else{
			$managePagesResult = array('error', 'Type in Header name');
		}
	}
  $ma = CMalin::Instance();
    if($ma->user['hasRoleAdmin']) {
	$pages = $this->user->viewPages();
    $this->views->SetTitle('Manage your pages')
                ->AddInclude(__DIR__ . '/managePages.tpl.php', array('pages' => $pages, 'result' => $managePagesResult), 'primary')
                ->AddInclude(__DIR__ . '/sidebar.tpl.php', array(), 'sidebar');
	}else{
		header('location: '.$this->request->CreateUrl("my", "accessDenied"));
	}
  }

    /**
   * Manage your style settings
   */
  public function manageStyle() {
    $modules = new CMModules();
	$manageStyleResult = "";
	if(isset($_POST['doSubmit'])){
			
			$backgroundColor = "";
			$foregroundColor = "";
			$menuSelectedColor = "";
			$headerBottomBorderColor = "";
			$menuSelectBorderColor = "";
			$aColor = "";
			$aHoverColor = "";
			$fontColor = "";
			$font = "";
	
			if(isset($_POST['backgroundColor'])){
				$backgroundColor = $_POST['backgroundColor'];
			}
			if(isset($_POST['foregroundColor'])){
				$foregroundColor = $_POST['foregroundColor'];
			}
			if(isset($_POST['menuSelectedColor'])){
				$menuSelectedColor = $_POST['menuSelectedColor'];
			}
			if(isset($_POST['headerBottomBorderColor'])){
				$headerBottomBorderColor = $_POST['headerBottomBorderColor'];
			}
			if(isset($_POST['menuSelectBorderColor'])){
				$menuSelectBorderColor = $_POST['menuSelectBorderColor'];
			}
			if(isset($_POST['aColor'])){
				$aColor = $_POST['aColor'];
			}
			if(isset($_POST['aHoverColor'])){
				$aHoverColor = $_POST['aHoverColor'];
			}
			if(isset($_POST['fontColor'])){
				$fontColor = $_POST['fontColor'];
			}
			if(isset($_POST['font'])){
				$font = $_POST['font'];
			}
			$manageStyleResult = $modules->saveStyle($backgroundColor, $foregroundColor, $menuSelectedColor, $headerBottomBorderColor, $menuSelectBorderColor, $aColor, $aHoverColor, $fontColor, $font);
	}
  $ma = CMalin::Instance();
    if($ma->user['hasRoleAdmin']) {
	$style = $this->user->viewStyle();
    $this->views->SetTitle('Manage your style')
                ->AddInclude(__DIR__ . '/manageStyle.tpl.php', array('style' => $style, 'result' => $manageStyleResult), 'primary')
                ->AddInclude(__DIR__ . '/sidebar.tpl.php', array(), 'sidebar');
	}else{
		header('location: '.$this->request->CreateUrl("my", "accessDenied"));
	}
  }

}