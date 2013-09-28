<?php
/**
 * Standard controller layout.
 * 
 * @package MalinCore
 */
class CCIndex implements IController {

   /**
     * Implementing interface IController. All controllers must have an index action.
    */
  public function Index() {
    global $ma;
    $ma->data['title'] = "The Index Controller";
    $ma->data['main'] = "<h1>The Index Controller</h1>";
  }

} 