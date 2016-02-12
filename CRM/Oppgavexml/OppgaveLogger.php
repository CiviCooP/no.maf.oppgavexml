<?php

/**
 * OppgaveLogger for load
 *
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @date 11 Feb 2016
 * @license AGPL-3.0
 */
class CRM_Oppgavexml_OppgaveLogger {
  private $logFile = null;
  function __construct($fileName = "maf_tax_log") {
    $config = CRM_Core_Config::singleton();
    $runDate = new DateTime('now');
    $fileName = $config->configAndLogDir.$fileName."_".$runDate->format('YmdHis');
    $this->logFile = fopen($fileName, 'w');
  }

  public function logMessage($type, $message) {
    $this->addMessage($type, $message);
  }

  /**
   * Method to log the message
   *
   * @param $type
   * @param $message
   */
  private function addMessage($type, $message) {
    fputs($this->logFile, date('Y-m-d h:i:s'));
    fputs($this->logFile, ' ');
    fputs($this->logFile, $type);
    fputs($this->logFile, ' ');
    fputs($this->logFile, $message);
    fputs($this->logFile, "\n");
  }
}