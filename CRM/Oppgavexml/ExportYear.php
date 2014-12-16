<?php
/**
 * Class to export year to xml file
 * as far as the default Case Relations are concerned for PUM
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * 
 * Copyright (C) 2014 Coöperatieve CiviCooP U.A. <http://www.civicoop.org>
 * Licensed to MAF Norge <http://www.maf.no> under the  AGPL-3.0
 */
class CRM_Oppgavexml_ExportYear extends CRM_Oppgavexml_Config {
  protected $_tax_year = null;
  protected $_xml_file_name = null;
  protected $_xml = null;
  protected $_total_count = null;
  protected $_total_sum = null;
  /**
   * Function to export year to xml file
   * 
   * @param type $year
   * @access public
   */
  public function export_year($year) {
    $this->_tax_year = $year;
    $this->_total_count = 0;
    $this->_total_sum = 0;
    $this->_xml = new SimpleXMLElement('<?xml version="'.$this->_xml_version
      .'" encoding="'.$this->_xml_encoding.'"?>'
      . '<melding xmlns="'.$this->_melding_xmlns.'" xmlns:xsi="'.$this->_melding_xmlns_xsi
      .'" xsi:schemaLocation="'.$this->_melding_xsi_schema_location.'"></melding>');
    $leveranse = $this->_xml->addChild('leveranse');
    $leveranse->addChild('kildesystem', $this->_sender_kilde_system);
    $this->add_oppgavegiver($leveranse);
    $leveranse->addChild('inntektsaar', $this->_tax_year);
    $leveranse->addChild('oppgavegiversLeveranseReferanse', $this->_sender_leveranse_referanse);
    $leveranse->addChild('leveransetype', $this->_leveranse_type);
    $this->add_donor_lines($leveranse);
    $this->add_footer($leveranse);
    $this->set_file_name();
    $this->_xml->asXML($this->_xml_file_name);
    $this->set_exported_date();
  }
  protected function set_file_name() {
    $this->_xml_file_name = $this->_xml_file_path.'skatteinnberetninger_'.$this->_tax_year.'.xml';
  }
  /**
   * Function to add footer to XML object
   * 
   * @param object $leveranse
   * @access protected
   */
  protected function add_footer(&$leveranse) {
    $oppgaveoppsummering = $leveranse->addChild('oppgaveoppsummering');
    $oppgaveoppsummering->addChild('antallOppgaver', $this->_total_count);
    $oppgaveoppsummering->addChild('sumBeloep', $this->_total_sum);
  }
  /**
   * Function to retrieve donor lines and add each one to xml object
   * 
   * @param object $leveranse
   * @access protected
   */
  protected function add_donor_lines(&$leveranse) {
    $params = array('oppgave_year' => $this->_tax_year);
    $donors = CRM_Oppgavexml_BAO_Oppgave::get_values($params);
    foreach ($donors as $donor) {
      if (!empty($donor['donor_number'])) {
        $this->write_oppgave_element($donor, $leveranse);
      }
    }
  }
  /**
   * Function to add oppgave element for donor
   * 
   * @param array $donor
   * @param object $leveranse
   * @access protected
   */
  protected function write_oppgave_element($donor, &$leveranse) {
    $oppgave = $leveranse->addChild('oppgave');
    $eier = $oppgave->addChild('oppgaveeier');
    if ($donor['donor_type'] == 'Organisasjon') {
      $eier->addChild('organisasjonsnummer', $donor['donor_number']);
    } else {
      $eier->addChild('foedselsnummer', $donor['donor_number']);      
    }
    $eier->addChild('navn', $donor['donor_name']);
    $oppgave->addChild('beloep', $donor['deductible_amount']);
    $this->_total_count++;
    $this->_total_sum += $donor['deductible_amount'];
  }
  /**
   * Function to write test lines
   * 
   * @param object $leveranse
   * @access protected
   */
  protected function add_test_donor_lines(&$leveranse) {
    $oppgave1 = $leveranse->addChild('oppgave');
    $donor1 = $oppgave1->addChild('oppgaveeier');
    $donor1->addChild('foedselsnummer', '01010150317');
    $donor1->addChild('navn', 'KÅRE JØRGENSEN');
    $oppgave1->addChild('beloep', 550);
    $this->_total_count++;
    $this->_total_sum = $this->_total_sum + 550;
    
    $oppgave2 = $leveranse->addChild('oppgave');
    $donor2 = $oppgave2->addChild('oppgaveeier');
    $donor2->addChild('foedselsnummer', '01010750160');
    $donor2->addChild('navn', 'TOMAS TOPSTAD');
    $oppgave2->addChild('beloep', 2200);
    $this->_total_count++;
    $this->_total_sum = $this->_total_sum + 2200;
    
    $oppgave3 = $leveranse->addChild('oppgave');
    $donor3 = $oppgave3->addChild('oppgaveeier');
    $donor3->addChild('foedselsnummer', '01010750241');
    $donor3->addChild('navn', 'KARI KLAVEN');
    $oppgave3->addChild('beloep', 5000);
    $this->_total_count++;
    $this->_total_sum = $this->_total_sum + 5000;
    
    $oppgave4 = $leveranse->addChild('oppgave');
    $donor4 = $oppgave4->addChild('oppgaveeier');
    $donor4->addChild('organisasjonsnummer', '910168738');
    $donor4->addChild('navn', 'NESNA OG ALVERSUND');
    $oppgave4->addChild('beloep', 12000);
    $this->_total_count++;
    $this->_total_sum = $this->_total_sum + 12000;
    
    $oppgave5 = $leveranse->addChild('oppgave');
    $donor5 = $oppgave5->addChild('oppgaveeier');
    $donor5->addChild('foedselsnummer', '01010752171');
    $donor5->addChild('navn', 'OLE REDDIK');
    $oppgave5->addChild('beloep', 750);
    $this->_total_count++;
    $this->_total_sum = $this->_total_sum + 750;
    }
  /**
   * Function to add the oppgavegiver details to the xml
   * 
   * @param object $leveranse
   * @access protected
   */
  protected function add_oppgavegiver(&$leveranse) {
    $oppgavegiver = $leveranse->addChild('oppgavegiver');
    $oppgavegiver->addChild('organisasjonsnummer', '910069772');
    $oppgavegiver->addChild('organisasjonsnavn', ' NANNESTAD OG HEMSEDAL');
    $kontakt = $oppgavegiver->addChild('kontaktinformasjon');
    $kontakt->addChild('navn', $this->_sender_kontakt_navn);
    $kontakt->addChild('telefonnummer', $this->_sender_kontakt_telefon);
    $kontakt->addChild('varselEpostadresse', $this->_sender_kontakt_epost);
    $kontakt->addChild('varselSmsMobilnummer', $this->_sender_kontakt_mobil);
  }
  /**
   * Function to retrieve the xml file name
   * 
   * @return string $this->_xml_file_name
   * @access public
   */
  public function get_file_name() {
    return $this->_xml_file_name;
  }
  /**
   * Function to write the header with the sending org info
   * 
   * @access protected
   */
  protected function write_header() {
    $leveranse = $this->_xml_object->addChild('leveranse');
    $leveranse->addChild('kildesystem', $this->_sender_kilde_system);
    $this->_xml_object->addChild('oppgavegiver');
    $this->_xml_object->addChild();
  }
  protected function set_exported_date() {
    $export_date = date('Ymd');
    $query = 'UPDATE civicrm_oppgave SET last_exported_date = %1 WHERE oppgave_year = %2';
    $params = array(
      1 => array($export_date, 'Date'),
      2 => array($this->_tax_year, 'Positive')
    );
    CRM_Core_DAO::executeQuery($query, $params);
  }
}
