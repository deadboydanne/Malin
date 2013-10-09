<?php
/**
 * Holding a instance of CLydia to enable use of $this in subclasses.
 *
 * @package LydiaCore
 */
class CObject {

   /**
    * Members
    */
   public $config;
   public $request;
   public $data;
   public $db;
   public $views;   

   /**
    * Constructor
    */
   protected function __construct() {
    $ma = CMalin::Instance();
    $this->config   = &$ma->config;
    $this->request  = &$ma->request;
    $this->data     = &$ma->data;
    $this->db       = &$ma->db;
    $this->views    = &$ma->views;
  }

}