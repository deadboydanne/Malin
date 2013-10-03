<?php
/**
 * Holding a instance of CLydia to enable use of $this in subclasses.
 *
 * @package LydiaCore
 */
class CObject {

   public $config;
   public $request;
   public $data;

   /**
    * Constructor
    */
   protected function __construct() {
    $ma = CMalin::Instance();
    $this->config   = &$ma->config;
    $this->request  = &$ma->request;
    $this->data     = &$ma->data;
  }

}