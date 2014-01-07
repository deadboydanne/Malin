<?php
/**
 * A model for an authenticated user.
 * 
 * @package MalinCore
 */
class CMConfig extends CObject implements IHasSQL, ArrayAccess, IModule {


  /**
   * Properties
   */
  public $profile;


  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
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
          $this->db->ExecuteQuery(self::SQL('drop table adminConfig'));
          $this->db->ExecuteQuery(self::SQL('drop table adminStyleConfig'));
          $this->db->ExecuteQuery(self::SQL('create table adminConfig'));
          $this->db->ExecuteQuery(self::SQL('create table adminStyleConfig'));
          $this->db->ExecuteQuery(self::SQL('insert into adminConfig'), array('Malin', 'A PHP-based MVC-inspired CMF', 'Malin © by Daniel Schäder (nds_se@hotmail.com)', 'on', 'on', 'on', 'on', 'on', 'Startpage', 'Guestbook', 'Blog', 'Extra page 1', 'Extra page 2'));
          $this->db->ExecuteQuery(self::SQL('insert into adminStyleConfig'), array('CBFFC9', 'FFFFFF', '00FF12', '00A30B', '00A30B', '436370', '990000', '000000', '"Courier New", Courier, monospace, sans-serif'));

          return array('success', 'Successfully created the database tables and created default style settings.');
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
      'drop table adminConfig'         => "DROP TABLE IF EXISTS adminConfig;",
	  'drop table adminStyleConfig'    => "DROP TABLE IF EXISTS adminStyleConfig;",
      'create table adminStyleConfig'  => "CREATE TABLE IF NOT EXISTS `adminStyleConfig` ('id' INTEGER PRIMARY KEY, `backgroundColor` text NOT NULL,`foregroundColor` text NOT NULL,`menuSelectedColor` text NOT NULL,`headerBottomBorderColor` text NOT NULL,`menuSelectBorderColor` text NOT NULL,`aColor` text NOT NULL,`aHoverColor` text NOT NULL,`fontColor` text NOT NULL,`font` text NOT NULL)",
	  'create table adminConfig'       => 'CREATE TABLE "adminConfig" ("id" INTEGER PRIMARY KEY, "headerTitle" text NOT NULL ,"headerSlogan" text  DEFAULT (null) ,"footerHeadline" text  DEFAULT (null),"startActive" tinyint(4)  DEFAULT (null) ,"guestActive" tinyint(4) ,"blogActive" tinyint(4) DEFAULT (null) ,"pageOneActive" tinyint(4) ,"pageTwoActive" tinyint(4) ,"startName" TEXT,"guestName" TEXT,"blogName" TEXT,"pageOneName" TEXT,"pageTwoName" TEXT)',
      'insert into adminStyleConfig'   => 'INSERT INTO adminStyleConfig (backgroundColor,foregroundColor,menuSelectedColor,headerBottomBorderColor,menuSelectBorderColor,aColor,aHoverColor,fontColor,font) VALUES (?,?,?,?,?,?,?,?,?);',
      'insert into adminConfig'   	   => 'INSERT INTO adminConfig (headerTitle,headerSlogan,footerHeadline,startActive,guestActive,blogActive,pageOneActive,pageTwoActive,startName,guestName,blogName,pageOneName,pageTwoName) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?);',
      'insert into group'              => 'INSERT INTO Groups (acronym,name) VALUES (?,?);',
      'insert into user2group'         => 'INSERT INTO User2Groups (idUser,idGroups) VALUES (?,?);',
      'check user password'            => 'SELECT * FROM User WHERE (acronym=? OR email=?);',
      'get group memberships'          => 'SELECT * FROM Groups AS g INNER JOIN User2Groups AS ug ON g.id=ug.idGroups WHERE ug.idUser=?;',
      'update config'                  => "UPDATE User SET name=?, email=?, updated=datetime('now') WHERE id=?;",
	  'select * from adminStyleConfig' => 'SELECT * FROM adminStyleConfig;',
     );
    if(!isset($queries[$key])) {
      throw new Exception("No such SQL query, key '$key' was not found.");
    }
    return $queries[$key];
  }

  
}