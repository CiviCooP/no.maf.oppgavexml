<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Config
 *
 * @author erik
 */
class CRM_Oppgavexml_Config {
  /*
   * property to hold the name of the folder where the exported xml file is put
   */
  protected $_xml_file_path = null;
  /*
   * property to hold the maximum amount deductible per contact
   */
  protected $_max_deductible_amount = null;
  /*
   * property to hold the tax declaration file status
   */
  protected $_tax_declaration_status = array();
  /**
   * Constructor
   */
  function __construct() {
    $this->_xml_file_path = '/var/www/localmaf/sites/default/civicrm_extensions/'
      . 'no.maf.oppgavexml/';
    $this->_max_deductible_amount = 16800;
  }
  
  public function get_max_deductible_amount() {
    return $this->_max_deductible_amount;
  }
  
  public function get_xml_file_path() {
    return $this->_xml_file_path;
  }
}
