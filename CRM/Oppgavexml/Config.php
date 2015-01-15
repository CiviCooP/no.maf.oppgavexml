<?php
/**
 * Class following Singleton pattern for specific extension configuration
 * as far as the default Case Relations are concerned for PUM
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 */
class CRM_Oppgavexml_Config {
  /*
   * singleton pattern
   */
  static private $_singleton = NULL;
  /*
   * property to hold the name of the folder where the exported xml file is put
   */
  protected $_xml_file_path = null;
  /*
   * property to hold the maximum amount deductible per contact
   */
  protected $_max_deductible_amount = null;
  /*
   * property to hold the minimum amount deductible per contact
   */
  protected $_min_deductible_amount = null;
  /*
   * property to hold the tax declaration file status
   */
  protected $_tax_declaration_status = array();
  /*
   * property for custom group and custom fields for personsnummer and organisasjonsnummer
   */
  protected $_contact_custom_group_table = null;
  protected $_personsnummer_custom_field_column = null;
  protected $_organisasjonsnummer_custom_field_column = null;
  /*
   * properties to hold the information about the sending organisation
   * and the file type
   */
  protected $_sender_kilde_system = null;
  protected $_sender_organisasjonsnummer = null;
  protected $_sender_organisasjonsnavn = null;
  protected $_sender_kontakt_navn = null;
  protected $_sender_kontakt_telefon = null;
  protected $_sender_kontakt_epost = null;
  protected $_sender_kontakt_mobil = null;
  protected $_leveranse_type = null;
  /*
   * properties about the xml file and
   */
  protected $_xml_version = null;
  protected $_xml_encoding = null;
  protected $_melding_xmlns = null;
  protected $_melding_xmlns_xsi = null;
  protected $_melding_xsi_schema_location = null;
  /**
   * Constructor
   */
  function __construct() {
    $this->set_xml_file_path(); 
    $this->set_max_deductible_amount();
    $this->set_min_deductible_amount();
    $this->set_sender_info();
    $this->set_xml_headers();
    $this->set_custom_data();
  }
  /**
   * Function to return the custom group table name for the maf_norway_import custom group
   * 
   * @return int
   * @access public
   */
  public function get_contact_custom_group_table() {
    return $this->_contact_custom_group_table;
  }
  /**
   * Function to return the custom field column for personsnummer
   * 
   * @return int
   * @access public
   */
  public function get_personsnummer_custom_field_column() {
    return $this->_personsnummer_custom_field_column;
  }
  /**
   * Function to return the custom field column for organisasjonsnummer
   * 
   * @return int
   * @access public
   */
  public function get_organisasjonsnummer_custom_field_column() {
    return $this->_organisasjonsnummer_custom_field_column;
  }
  /**
   * Function to return the sender kilde system
   * 
   * @return string
   * @access public
   */
  public function get_sender_kilde_system() {
    return $this->_sender_kilde_system;
  }
  /**
   * Function to return the sender organisasjonsnummer
   * (organisation number)
   * 
   * @return string
   * @access public
   */
  public function get_sender_organisasjonsnummer() {
    return $this->_sender_organisasjonsnummer;
  }
  /**
   * Function to return the sender organisasjonsnavn 
   * (organisation name)
   * 
   * @return string
   * @access public
   */
  public function get_sender_organisasjonsnavn() {
    return $this->_sender_organisasjonsnavn;
  }
  /**
   * Function to return the sender kontakt navn
   * (contactperson name)
   * 
   * @return string
   * @access public
   */
  public function get_sender_kontakt_navn() {
    return $this->_sender_kontakt_navn;
  }
  /**
   * Function to return the sender kontakt telefon
   * (contactperson phone)
   * 
   * @return string
   * @access public
   */
  public function get_sender_kontakt_telefon() {
    return $this->_sender_kontakt_telefon;
  }
  /**
   * Function to return the sender kontakt epost
   * (contactperson email)
   * 
   * @return string
   * @access public
   */
  public function get_sender_kontakt_epost() {
    return $this->_sender_kontakt_epost;
  }
  /**
   * Function to return the sender kontakt mobil
   * (contactperson mobile)
   * 
   * @return string
   * @access public
   */
  public function get_sender_kontakt_mobil() {
    return $this->_sender_kontakt_mobil;
  }
  /**
   * Function to return the leveranse type
   * (file supplier type)
   * 
   * @return string
   * @access public
   */
  public function get_leveranse_type() {
    return $this->_leveranse_type;
  }
  /**
   * Function to return xml version for the xml file
   * 
   * @return string
   * @access public
   */
  public function get_xml_version() {
    return $this->_xml_version;
  }
  /**
   * Function to return the xml encoding for the xml file
   * 
   * @return string
   * @access public
   */
  public function get_xml_encoding() {
    return $this->_xml_encoding;
  }
  /**
   * Function to return the xmlns for the xml file
   * 
   * @return string
   * @access public
   */
  public function get_melding_xmlns() {
    return $this->_melding_xmlns;
  }
  /**
   * Function to return the xmlns:xsi for the xml file
   * 
   * @return string
   * @access public
   */
  public function get_melding_xmlns_xsi() {
    return $this->_melding_xmlns_xsi;
  }
  /**
   * Function to return the xsi:schemaLocation for the xml file
   * 
   * @return string
   * @access public
   */
  public function get_melding_xsi_schema_location() {
    return $this->_melding_xsi_schema_location;
  }
  /**
   * Function returns min deductible amount
   * 
   * @return int
   * @access public
   */
 public function get_min_deductible_amount() {
   return $this->_min_deductible_amount;
 }
  /**
   * Function returns max deductible amount
   * 
   * @return int
   * @access public
   */
  public function get_max_deductible_amount() {
    return $this->_max_deductible_amount;
  }
  /**
   * Function returns path for storing xml file
   * 
   * @return string
   * @access public
   */
  public function get_xml_file_path() {
    return $this->_xml_file_path;
  }
  /**
   * Function to return singleton object
   * 
   * @return object $_singleton
   * @access public
   * @static
   */
  public static function &singleton() {
    if (self::$_singleton === NULL) {
      self::$_singleton = new CRM_Oppgavexml_Config();
    }
    return self::$_singleton;
  }
  /**
   * Function to set the path for storign the xml file
   * 
   * @access protected
   */
  protected function set_xml_file_path() {
    $this->_xml_file_path = '/home/maf/taxfiles/';
  }
  /**
   * Function to set the maximum deductible amount
   * Any amount higher than this will be topped to this amount
   * 
   * @access protected
   */
  protected function set_max_deductible_amount() {
    $this->_max_deductible_amount = 16800;
  }
  /**
   * Function to set the minimum deductible amount
   * 
   * @access protected
   */
  protected function set_min_deductible_amount() {
    $this->_min_deductible_amount = 500;
  }
  /**
   * Function to set the sender information for the xml file
   * 
   * @access protected
   */
  protected function set_sender_info() {
    $this->_sender_kilde_system = 'CiviCRM';
    $this->_sender_organisasjonsnummer = '980421899';
    $this->_sender_organisasjonsnavn = 'Mission Aviation Fellowship Norge';
    $this->_sender_kontakt_navn = 'Steinar Sødal';
    $this->_sender_kontakt_telefon = '90576090';
    $this->_sender_kontakt_mobil = '90576090';
    $this->_sender_kontakt_epost = 'steinar@maf.no';
    $this->_leveranse_type = 'ordinaer';
  }
  /**
   * Function to set the xml header information
   * 
   * @access protected
   */
  protected function set_xml_headers() {
    $this->_xml_version = '1.0';
    $this->_xml_encoding = 'UTF-8';
    $this->_melding_xmlns = 'urn:ske:fastsetting:innsamling:gavefrivilligorganisasjon:v2';
    $this->_melding_xmlns_xsi = 'http://www.w3.org/2001/XMLSchema-instance';
    $this->_melding_xsi_schema_location = 'urn:ske:fastsetting:innsamling:gavefrivilligorganisasjon:v2 gavefrivilligorganisasjon_v2_0.xsd';
  }
  /**
   * Function to set custom data
   * 
   * @throws Exception when custom group maf_norway_import not found
   * @access protected
   */
  protected function set_custom_data() {
    $custom_group_params = array('name' => 'maf_norway_import');
    try {
      $custom_group = civicrm_api3('CustomGroup', 'Getsingle', $custom_group_params);
      $this->_contact_custom_group_table = $custom_group['table_name'];
    } catch (CiviCRM_API3_Exception $ex) {
      throw new Exception('Could not find custom group with name maf_norway_import, '
        . 'error from API CustomGroup Getsingle: '.$ex->getMessage());
    }
    $custom_fields = civicrm_api3('CustomField', 'Get', 
      array('custom_group_id' => $custom_group['id']));
    foreach ($custom_fields['values'] as $custom_field) {
      if ($custom_field['name'] == 'personsnummer') {
        $this->_personsnummer_custom_field_column = $custom_field['column_name'];
      }
      if ($custom_field['name'] == 'organisasjonsnummer') {
        $this->_organisasjonsnummer_custom_field_column = $custom_field['column_name'];
      }
    }
  }
}
