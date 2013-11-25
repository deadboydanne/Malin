<?php
/**
 * Site configuration, this file is changed by user per site.
 *
 */


/**
 * Set level of error reporting
 */
error_reporting(-1);
ini_set('display_errors', 1);


/**
 * How to hash password of new users, choose from: plain, md5salt, md5, sha1salt, sha1.
 */
$ma->config['hashing_algorithm'] = 'sha1salt';

/**
 * Allow or disallow creation of new user accounts.
 */
$ma->config['create_new_users'] = true;

/**
 * Set what to show as debug or developer information in the get_debug() theme helper.
 */
$ma->config['debug']['malin'] = true;
$ma->config['debug']['session'] = true;
$ma->config['debug']['timer'] = true;
$ma->config['debug']['db-num-queries'] = true;
$ma->config['debug']['db-queries'] = true;




/**
 * Set database(s).
 */
$ma->config['database'][0]['dsn'] = 'sqlite:' . MALIN_SITE_PATH . '/data/.ht.sqlite';




/**
 * What type of urls should be used?
 * 
 * default      = 0      => index.php/controller/method/arg1/arg2/arg3
 * clean        = 1      => controller/method/arg1/arg2/arg3
 * querystring  = 2      => index.php?q=controller/method/arg1/arg2/arg3
 */
$ma->config['url_type'] = 1;




/**
 * Set a base_url to use another than the default calculated
 */
$ma->config['base_url'] = null;




/**
 * Define session name
 */
$ma->config['session_name'] = preg_replace('/[:\.\/-_]/', '', __DIR__);
$ma->config['session_key']  = 'malin';




/**
 * Define server timezone
 */
$ma->config['timezone'] = 'Europe/Stockholm';




/**
 * Define internal character encoding
 */
$ma->config['character_encoding'] = 'UTF-8';




/**
 * Define language
 */
$ma->config['language'] = 'en';




/**
 * Define the controllers, their classname and enable/disable them.
 *
 * The array-key is matched against the url, for example: 
 * the url 'developer/dump' would instantiate the controller with the key "developer", that is 
 * CCDeveloper and call the method "dump" in that class. This process is managed in:
 * $ma->FrontControllerRoute();
 * which is called in the frontcontroller phase from index.php.
 */
$ma->config['controllers'] = array(
  'index'     => array('enabled' => true,'class' => 'CCIndex'),
  'developer' => array('enabled' => true,'class' => 'CCDeveloper'),
  'guestbook' => array('enabled' => true,'class' => 'CCGuestbook'),
  'user'      => array('enabled' => true,'class' => 'CCUser'),
  'theme'     => array('enabled' => true,'class' => 'CCTheme'),
  'page'      => array('enabled' => true,'class' => 'CCPage'),
  'blog'      => array('enabled' => true,'class' => 'CCBlog'),
  'content'   => array('enabled' => true,'class' => 'CCContent'), 
  'acp'       => array('enabled' => true,'class' => 'CCAdminControlPanel'),
);


/**
* Settings for the theme.
*/
$ma->config['theme'] = array(
  'name'            => 'grid',            // The name of the theme in the theme directory
  'stylesheet'      => 'style.php',       // Main stylesheet to include in template files
  'template_file'   => 'index.tpl.php',   // Default template file, else use default.tpl.php
  // A list of valid theme regions
  'regions' => array('flash','featured-first','featured-middle','featured-last',
    'primary','sidebar','triptych-first','triptych-middle','triptych-last',
    'footer-column-one','footer-column-two','footer-column-three','footer-column-four',
    'footer',
  ),
  // Add static entries for use in the template file. 
  'data' => array(
    'header' => '<h1>Malin</h1>',
    'slogan' => 'A PHP-based MVC-inspired CMF',
    'favicon' => 'logo_80x80.png',
    'logo' => 'logo_80x80.png',
    'logo_width'  => 80,
    'logo_height' => 80,
    'footer' => '<p>Malin © by Daniel Schäder (nds_se@hotmail.com)</p>',
  ),
);