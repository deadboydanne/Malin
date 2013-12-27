<?php
/**
 * Sample controller for a site builder.
 */
class CCMycontroller extends CObject implements IController {

  /**
   * Constructor
   */
  public function __construct() { parent::__construct(); }
  

  /**
   * The startpage
   */
  public function Index() {
	$pages = $this->user->viewPages();
	$pageTitle = $pages[0]['startName'];
    $this->views->SetTitle($pageTitle)
                ->AddInclude(__DIR__ . '/page.tpl.php', array());
  }


  /**
   * The blog.
   */
  public function Blog() {
    $content = new CMContent();
	$ma = CMalin::Instance();
    if($ma->user['hasRoleAdmin']) {
		$admin = true;
	}else{
		$admin = false;
	}
    $this->views->SetTitle('My blog')
                ->AddInclude(__DIR__ . '/blog.tpl.php', array(
                  'contents' => $content->ListAll(array('type'=>'post', 'order-by'=>'title', 'order-order'=>'DESC')), 'admin' => $admin,
                ));
  }


  /**
   * The guestbook.
   */
  public function Guestbook() {
    $guestbook = new CMGuestbook();
    $form = new CFormMyGuestbook($guestbook);
    $status = $form->Check();
    if($status === false) {
      $this->AddMessage('notice', 'The form could not be processed.');
      $this->RedirectToControllerMethod();
    } else if($status === true) {
      $this->RedirectToControllerMethod();
    }
    
    $this->views->SetTitle('My Guestbook')
         ->AddInclude(__DIR__ . '/guestbook.tpl.php', array(
            'entries'=>$guestbook->ReadAll(), 
            'form'=>$form,
         ));
  }
  
  /**
   * The extra page one
   */
  public function extraPageOne() {
	$pages = $this->user->viewPages();
	$pageTitle = $pages[0]['pageOneName'];
    $this->views->SetTitle($pageTitle)
                ->AddInclude(__DIR__ . '/extraPageOne.tpl.php', array());
  }
  
  /**
   * The extra page two
   */
  public function extraPageTwo() {
	$pages = $this->user->viewPages();
	$pageTitle = $pages[0]['pageTwoName'];
    $this->views->SetTitle($pageTitle)
                ->AddInclude(__DIR__ . '/extraPageTwo.tpl.php', array());
  }

  /**
   * The extra page one
   */
  public function accessDenied() {
    $this->views->SetTitle('Access Denied')
                ->AddInclude(__DIR__ . '/accessDenied.tpl.php', array());
  }

} 


/**
 * Form for the guestbook
 */
class CFormMyGuestbook extends CForm {

  /**
   * Properties
   */
  private $object;

  /**
   * Constructor
   */
  public function __construct($object) {
    parent::__construct();
    $this->objecyt = $object;
    $this->AddElement(new CFormElementTextarea('data', array('label'=>'Add entry:')))
         ->AddElement(new CFormElementSubmit('add', array('callback'=>array($this, 'DoAdd'), 'callback-args'=>array($object))));
  }
  

  /**
   * Callback to add the form content to database.
   */
  public function DoAdd($form, $object) {
    return $object->Add(strip_tags($form['data']['value']));
  }
 
 
}