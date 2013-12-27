<?php
/**
 * A model for an authenticated user.
 * 
 * @package MalinCore
 */
class CMUser extends CObject implements IHasSQL, ArrayAccess, IModule {


  /**
   * Properties
   */
  public $profile;




  /**
   * Constructor
   */
  public function __construct($ma=null) {
    parent::__construct($ma);
    $profile = $this->session->GetAuthenticatedUser();
    $this->profile = is_null($profile) ? array() : $profile;
    $this['isAuthenticated'] = is_null($profile) ? false : true;
    if(!$this['isAuthenticated']) {
      $this['id'] = 1;
      $this['acronym'] = 'anonomous';      
    }
  }




  /**
   * Implementing ArrayAccess for $this->profile
   */
  public function offsetSet($offset, $value) { if (is_null($offset)) { $this->profile[] = $value; } else { $this->profile[$offset] = $value; }}
  public function offsetExists($offset) { return isset($this->profile[$offset]); }
  public function offsetUnset($offset) { unset($this->profile[$offset]); }
  public function offsetGet($offset) { return isset($this->profile[$offset]) ? $this->profile[$offset] : null; }

  /**
   * Implementing interface IModule. Manage install/update/deinstall and equal actions.
   *
   * @param string $action what to do.
   */
  public function Manage($action=null) {
    switch($action) {
      case 'install': 
        try {
          $this->db->ExecuteQuery(self::SQL('drop table user2group'));
          $this->db->ExecuteQuery(self::SQL('drop table group'));
          $this->db->ExecuteQuery(self::SQL('drop table user'));
          $this->db->ExecuteQuery(self::SQL('create table user'));
          $this->db->ExecuteQuery(self::SQL('create table group'));
          $this->db->ExecuteQuery(self::SQL('create table user2group'));
          $this->db->ExecuteQuery(self::SQL('insert into user'), array('anonomous', 'Anonomous, not authenticated', null, 'plain', null, null));
          $this->db->ExecuteQuery(self::SQL('insert into group'), array('admin', 'The Administrator Group'));
          $this->db->ExecuteQuery(self::SQL('insert into group'), array('user', 'The User Group'));
          return array('success', 'Successfully created the database tables');
        } catch(Exception$e) {
          die("$e<br/>Failed to open database: " . $this->config['database'][0]['dsn']);
        }   
      break;
      
      default:
        throw new Exception('Unsupported action for this module.');
      break;
    }
  }
  
  /**
   * Implementing interface IHasSQL. Encapsulate all SQL used by this class.
   *
   * @param string $key the string that is the key of the wanted SQL-entry in the array.
   */
  public static function SQL($key=null) {
    $queries = array(
      'drop table user'         		=> "DROP TABLE IF EXISTS User;",
      'drop table group'        		=> "DROP TABLE IF EXISTS Groups;",
      'drop table user2group'   		=> "DROP TABLE IF EXISTS User2Groups;",
      'create table user'       		=> "CREATE TABLE IF NOT EXISTS User (id INTEGER PRIMARY KEY, acronym TEXT KEY, name TEXT, email TEXT, algorithm TEXT, salt TEXT, password TEXT, created DATETIME default (datetime('now')), updated DATETIME default NULL);",
      'create table group'      		=> "CREATE TABLE IF NOT EXISTS Groups (id INTEGER PRIMARY KEY, acronym TEXT KEY, name TEXT, created DATETIME default (datetime('now')), updated DATETIME default NULL);",
      'create table user2group' 		=> "CREATE TABLE IF NOT EXISTS User2Groups (idUser INTEGER, idGroups INTEGER, created DATETIME default (datetime('now')), PRIMARY KEY(idUser, idGroups));",
      'insert into user'        		=> 'INSERT INTO User (acronym,name,email,algorithm,salt,password) VALUES (?,?,?,?,?,?);',
      'insert into group'       		=> 'INSERT INTO Groups (acronym,name) VALUES (?,?);',
      'insert into user2group'  		=> 'INSERT INTO User2Groups (idUser,idGroups) VALUES (?,?);',
      'check user password'     		=> 'SELECT * FROM User WHERE (acronym=? OR email=?);',
      'get group memberships'   		=> 'SELECT * FROM Groups AS g INNER JOIN User2Groups AS ug ON g.id=ug.idGroups WHERE ug.idUser=?;',
      'update profile'          		=> "UPDATE User SET name=?, email=?, updated=datetime('now') WHERE id=?;",
      'update groups'          			=> "UPDATE Groups SET name=?, acronym=?, updated=datetime('now') WHERE id=?;",
      'update password'         		=> "UPDATE User SET algorithm=?, salt=?, password=?, updated=datetime('now') WHERE id=?;",
      'update adminConfig'      		=> 'UPDATE adminConfig SET headerTitle=?, headerSlogan=?, footerHeadline=?, startActive=?, guestActive=?, blogActive=?, pageOneActive=?, pageTwoActive=?, startName=?, guestName=?, blogName=?, pageOneName=?, pageTwoName=? WHERE id=1;',
      'update adminStyleConfig' 		=> 'UPDATE adminStyleConfig SET backgroundColor=?, foregroundColor=?, menuSelectedColor=?, headerBottomBorderColor=?, menuSelectBorderColor=?, aColor=?, aHoverColor=?, fontColor=?, font=? WHERE id=1;',
	  'select *'						=> 'SELECT * FROM User ORDER BY id ASC;',
	  'select * adminStyleConfig'		=> 'SELECT * FROM adminStyleConfig WHERE id=1;',
	  'select * adminConfig'			=> 'SELECT * FROM adminConfig WHERE id=1;',
	  'select * by id'					=> 'SELECT * FROM User WHERE (id=?);',
	  'select * by admin'				=> 'SELECT * FROM User WHERE (acronym="admin");',
	  'select * groups'					=> 'SELECT * FROM Groups ORDER BY id ASC;',
	  'select * groups by id'			=> 'SELECT * FROM Groups WHERE (id=?);',
	  'get group memberships'			=> 'SELECT * FROM Groups INNER JOIN User2Groups ON Groups.id=User2Groups.idGroups WHERE User2Groups.idUser=?;',
	  'delete from user2groups'			=> 'DELETE FROM User2Groups WHERE (idUser=?)',
	  'delete group from user2groups'	=> 'DELETE FROM User2Groups WHERE (idGroups=?)',
	  'delete from user'				=> 'DELETE FROM user WHERE (id=?)',
	  'delete from groups'				=> 'DELETE FROM groups WHERE (id=?)',
	  'check acronym'					=> 'SELECT count(1) FROM User WHERE (acronym=?);',
     );
    if(!isset($queries[$key])) {
      throw new Exception("No such SQL query, key '$key' was not found.");
    }
    return $queries[$key];
  }

  /**
   * Login by autenticate the user and password. Store user information in session if success.
   *
   * Set both session and internal properties.
   *
   * @param string $akronymOrEmail the emailadress or user akronym.
   * @param string $password the password that should match the akronym or emailadress.
   * @returns booelan true if match else false.
   */
  public function Login($akronymOrEmail, $password) {
    $user = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('check user password'), array($akronymOrEmail, $akronymOrEmail));
    $user = (isset($user[0])) ? $user[0] : null;
    if(!$user) {
      return false;
    } else if(!$this->CheckPassword($password, $user['algorithm'], $user['salt'], $user['password'])) {
      return false;
    }
    unset($user['algorithm']);
    unset($user['salt']);
    unset($user['password']);
    if($user) {
      $user['isAuthenticated'] = true;
      $user['groups'] = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('get group memberships'), array($user['id']));
      foreach($user['groups'] as $val) {
        if($val['id'] == 1) {
          $user['hasRoleAdmin'] = true;
        }
        if($val['id'] == 2) {
          $user['hasRoleUser'] = true;
        }
      }
      $this->profile = $user;
      $this->session->SetAuthenticatedUser($this->profile);
    }
    return ($user != null);
  }
  



  /**
   * Logout. Clear both session and internal properties.
   */
  public function Logout() {
    $this->session->UnsetAuthenticatedUser();
    $this->profile = array();
    $this->AddMessage('success', "You have logged out.");
  }
  


  /**
   * Save user profile to database and update user profile in session.
   *
   * @returns boolean true if success else false.
   */
  public function Save() {
    $this->db->ExecuteQuery(self::SQL('update profile'), array($this['name'], $this['email'], $this['id']));
    $this->session->SetAuthenticatedUser($this->profile);
    return $this->db->RowCount() === 1;
  }
  
  /**
   * Create password.
   *
   * @param $plain string the password plain text to use as base.
   * @param $algorithm string stating what algorithm to use, plain, md5, md5salt, sha1, sha1salt. 
   * defaults to the settings of site/config.php.
   * @returns array with 'salt' and 'password'.
   */
  public function CreatePassword($plain, $algorithm=null) {
    $password = array(
      'algorithm'=>($algorithm ? $algoritm : CMalin::Instance()->config['hashing_algorithm']),
      'salt'=>null
    );
    switch($password['algorithm']) {
      case 'sha1salt': $password['salt'] = sha1(microtime()); $password['password'] = sha1($password['salt'].$plain); break;
      case 'md5salt': $password['salt'] = md5(microtime()); $password['password'] = md5($password['salt'].$plain); break;
      case 'sha1': $password['password'] = sha1($plain); break;
      case 'md5': $password['password'] = md5($plain); break;
      case 'plain': $password['password'] = $plain; break;
      default: throw new Exception('Unknown hashing algorithm');
    }
    return $password;
  }
  
  /**
   * Change user password.
   *
   * @param $plain string plaintext of the new password
   * @returns boolean true if success else false.
   */
  public function ChangePassword($plain, $userId=null) {
	if($userId == null){
		$userId = $this['id'];
	}
    $password = $this->CreatePassword($plain);
    $this->db->ExecuteQuery(self::SQL('update password'), array($password['algorithm'], $password['salt'], $password['password'], $userId));
    return $this->db->RowCount() === 1;
  }

  
  /**
   * Check if password matches.
   *
   * @param $plain string the password plain text to use as base.
   * @param $algorithm string the algorithm mused to hash the user salt/password.
   * @param $salt string the user salted string to use to hash the password.
   * @param $password string the hashed user password that should match.
   * @returns boolean true if match, else false.
   */
  public function CheckPassword($plain, $algorithm, $salt, $password) {
    switch($algorithm) {
      case 'sha1salt': return $password === sha1($salt.$plain); break;
      case 'md5salt': return $password === md5($salt.$plain); break;
      case 'sha1': return $password === sha1($plain); break;
      case 'md5': return $password === md5($plain); break;
      case 'plain': return $password === $plain; break;
      default: throw new Exception('Unknown hashing algorithm');
    }
  }
  
    /**
   * Create new user.
   *
   * @param $acronym string the acronym.
   * @param $password string the password plain text to use as base. 
   * @param $name string the user full name.
   * @param $email string the user email.
   * @returns boolean true if user was created or else false and sets failure message in session.
   */
  public function Create($acronym, $password, $name, $email) {
    $pwd = $this->CreatePassword($password);
    $this->db->ExecuteQuery(self::SQL('insert into user'), array($acronym, $name, $email, $pwd['algorithm'], $pwd['salt'], $pwd['password']));
	$idUser = $this->db->LastInsertId();
	if($acronym == 'admin'){
		$this->db->ExecuteQuery(self::SQL('insert into user2group'), array($idUser, 1));
		$this->db->ExecuteQuery(self::SQL('insert into user2group'), array($idUser, 2));
	}else{
		$this->db->ExecuteQuery(self::SQL('insert into user2group'), array($idUser, 2));
	}
    if($this->db->RowCount() == 0) {
      $this->AddMessage('error', "Failed to create user.");
      return false;
    }
    return true;
  }
  
   /**
   * Save settings for your pages
   */
  
  public function savePageSettings($headerName, $headerSlogan, $footerHeadline, $startActive, $guestActive, $blogActive, $pageOneActive, $pageTwoActive, $startName, $guestName, $blogName, $pageOneName, $pageTwoNam){
  	$this->db->ExecuteQuery(self::SQL('update adminConfig'), array($headerName, $headerSlogan, $footerHeadline, $startActive, $guestActive, $blogActive, $pageOneActive, $pageTwoActive, $startName, $guestName, $blogName, $pageOneName, $pageTwoNam));
  }

   /**
   * Save settings for your style
   */

  public function saveStyleSettings($backgroundColor, $foregroundColor, $menuSelectedColor, $headerBottomBorderColor, $menuSelectBorderColor, $aColor, $aHoverColor, $fontColor, $font){
  	$this->db->ExecuteQuery(self::SQL('update adminStyleConfig'), array($backgroundColor, $foregroundColor, $menuSelectedColor, $headerBottomBorderColor, $menuSelectBorderColor, $aColor, $aHoverColor, $fontColor, $font));
  }

   /**
   * Get the entire user list
   */
  
  public function userList(){
      $userList = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select *'));
	  return $userList;
  }

   /**
   * Get all info of specified user
   */

  public function viewUser($userId){
      $user['user'] = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select * by id'), array($userId));
      $user['groups'] = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select * groups'));
      $user['user2groups'] = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('get group memberships'), array($userId));
	  return $user;
  }
  
   /**
   * Get all info of specified user
   */

  public function deleteUser($userId){
      $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('delete from user'), array($userId));
	  header("location: ".$this->request->CreateUrl('acp', 'manageUsers'));
  }

   /**
   * Save user changes made by admin
   */

  public function saveUser($userId, $name, $email){
    $this->db->ExecuteQuery(self::SQL('update profile'), array($name, $email, $userId));
  }

   /**
   * Delete user from groups
   */

  public function deleteUserFromGroups($userId){
    $this->db->ExecuteQuery(self::SQL('delete from user2groups'), array($userId));
  }

   /**
   * Insert user into groups
   */

  public function addUserToGroups($userId, $groupId){
    $this->db->ExecuteQuery(self::SQL('insert into user2group'), array($userId, $groupId));
  }

   /**
   * Check if acronym already exists
   */

  public function checkAcronym($acronym){
    $acronymCheck = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('check acronym'), array($acronym));
	if($acronymCheck[0]['count(1)'] > 0){
		return false;
	}else{
		return true;
	}
  }
  
   /**
   * Get all info of specified user
   */

  public function deleteGroup($groupId){
      $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('delete from groups'), array($groupId));
	  header("location: ".$this->request->CreateUrl('acp', 'manageGroups'));
  }

   /**
   * Delete group connection to users
   */

  public function deleteGroupConnections($groupId){
    $this->db->ExecuteQuery(self::SQL('delete group from user2groups'), array($groupId));
  }

   /**
   * Save group changes made by admin
   */

  public function saveGroup($groupId, $name, $acronym){
    $this->db->ExecuteQuery(self::SQL('update groups'), array($name, $acronym, $groupId));
  }

   /**
   * create group
   */

  public function createGroup($name, $acronym){
    $this->db->ExecuteQuery(self::SQL('insert into group' ), array($acronym, $name));
  }

   /**
   * Get all info of specified group
   */

  public function viewGroup($groupId){
      $groups = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select * groups by id'), array($groupId));
	  return $groups;
  }

   /**
   * Get the entire group list
   */
  
  public function groupList(){
      $groupList = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select * groups'));
	  return $groupList;
  }

   /**
   * Get all current pages settings
   */

  public function viewPages(){
      $pages = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select * adminConfig'));
	  return $pages;
  }
  
   /**
   * Get all current style settings
   */

  public function viewStyle(){
      $style = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select * adminStyleConfig'));
	  return $style;
  }
  
   /**
   * Get admin
   */

  public function getAdmin(){
      $checkAdmin = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select * by admin'));
	  return $checkAdmin;
  }

}