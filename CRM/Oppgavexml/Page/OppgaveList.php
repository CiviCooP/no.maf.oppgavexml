<?php
/**
 * Page Oppgavelist to list all donor oppgaves for a year or a contact
 * 
 * @author Erik Hommel <erik.hommel@civicoop.org>
 * 
 * Copyright (C) 2014 Coöperatieve CiviCooP U.A. <http://www.civicoop.org>
 * Licensed to MAF Norge <http://www.maf.no> under the  AGPL-3.0
 */
require_once 'CRM/Core/Page.php';

class CRM_Oppgavexml_Page_OppgaveList extends CRM_Core_Page {
  
  protected $_display_context = null;
  protected $_context_contact_id = null;
  protected $_context_tax_year = null;
  /**
   * Standard run function created when generating page with Civix
   * 
   * @access public
   */
  function run() {
    $this->set_page_configuration();
    $display_oppgaves = $this->get_oppgaves();
    $this->assign('oppgaves', $display_oppgaves);
    parent::run();
  }
  /**
   * Function to get the data from civicrm_oppgave
   * 
   * @return array $oppgaves
   * @access protected
   */
  protected function get_oppgaves() {
    if ($this->_display_context == 'contact') {
      $params = $this->set_contact_params();
    } else {
      $params = $this->set_year_params();
    }
    $oppgaves = CRM_Oppgavexml_BAO_Oppgave::get_values($params);
    foreach ($oppgaves as $oppgave_id => $oppgave) {
      $oppgaves[$oppgave_id]['actions'] = $this->set_row_actions($oppgave);
      if (!empty($oppgave['last_modified_user_id'])) {
        $oppgaves[$oppgave_id]['last_modified_user'] = $this->get_contact_name($oppgave['last_modified_user_id']);
      }
    }
    return $oppgaves;
  }
  protected function get_contact_name($contact_id) {
    $params = array(
      'id' => $contact_id,
      'return' => 'display_name'
    );
    $name = civicrm_api3('Contact', 'Getvalue', $params);
    return $name;
  }
  /**
   * Function to set the row action urls and links for each row
   * 
   * @param int $oppgave
   * @return array $page_actions
   * @access protected
   */
  protected function set_row_actions($oppgave) {
    $page_actions = array();
    $edit_url = CRM_Utils_System::url('civicrm/oppgave', 'action=update&oid='.
      $oppgave['id'].'&year='.$oppgave['oppgave_year']);
    $page_actions[] = '<a class="action-item" title="Edit" href="'.$edit_url.'">Edit</a>';
    $delete_url = CRM_Utils_System::url('civicrm/oppgave', 'action=delete&oid='.
      $oppgave['id'].'&year='.$oppgave['oppgave_year']);
    $page_actions[] = '<a class="action-item" title="Delete" href="'.$delete_url.'">Delete</a>';
    return $page_actions;
  }
  /**
   * Function to set the params if the context is contact (tab on Contact Summary)
   * 
   * @return array $params
   * @access protected
   */
  protected function set_contact_params() {
    $params = array('contact_id' => $this->_context_contact_id);
    return $params;
  }
  /**
   * Function to set the params if the context is year (Manage from Skatteinnberetninger list)
   * 
   * @return array $params
   * @access protected
   */  
  protected function set_year_params() {
    $params = array('year' => $this->_context_tax_year);
    return $params;
  }
  /**
   * Function to set the page configuration
   * 
   * @access protected
   */
  protected function set_page_configuration() {
    CRM_Utils_System::setTitle(ts('Donoroppgave'));    
    $snippet = CRM_Utils_Request::retrieve('snippet', 'Positive');
    if (!empty($snippet)) {
      $this->_display_context = 'contact';
      $this->_context_contact_id = CRM_Utils_Request::retrieve('cid', 'Positive');
    } else {
      $this->_display_context = 'year';
      $this->_context_tax_year = CRM_Utils_Request::retrieve('year', 'Positive');
    }
    $this->assign('display_type', $this->_display_context);
    $this->assign('add_url', CRM_Utils_System::url('civicrm/oppgave', 'action=add&cid='.$this->_context_contact_id.'&year='.$this->_context_tax_year, true));  
    $session = CRM_Core_Session::singleton();
    $session->pushUserContext(CRM_Utils_System::url('civicrm/oppgavelist', 'year='.$this->_context_tax_year));
  }
}
