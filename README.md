no.maf.oppgavexml
=================

CiviCRM native extension for MAF Norge to manage yearly tax deduction files

This extension was developed for MAF Norge and reflects their processing for yearly declaration of tax deductible contribution amounts per contact. Feel free to use and customize.

<h2>Overall</h2>

Once a year the total deductible amount for each donor has to be declared to the tax authorities. This can be done from CiviCRM in three different steps:
<ul>
<li>Load - a scheduled job which loads the donors with their deductible amount for the year into a separate file. You can also reload to add donors from CiviCRM that have not been picked up before (because the amount was too small or the person/organisasjonsnummer was not there).</li>
<li>Manage - manage the donor data in the separate file, updating donor information for the tax authorities or even adding donor data</li>
<li>Export - a scheduled job which exports the donors in the separate file into an XML-file</li>
</ul>

<h2>Load and reload</h2>
There is a scheduled job called 'Skatteinnberetninger - Load' that needs two parameters:
<ol><li>year - the year being loaded in 4 digits (so for example 2014)</li><li>reload - if it is an initial load (reload=0) or a reload (reload=1). A reload only loads donors that are not in the file yet.</li></ol>
If you run the scheduled job, it will load all donors for which the total deductible amount is bigger than the minimum amount set in the configuration file CRM/Opggavexml/Config.php if the personsnummer or organisasjonsnummer is not empty. As the scheduled job will only be run a couple of times a year, it has been set to ' inactive'  and you need to run it with the 'Execute now'  option.

<h2>Manage</h2>
In the CiviCRM Contribution menu, a menu option Skatteinnberetninger is added. It will show you a list of years in the database, and you can manage a year to view the details of the year.
If you click on manage you will get a list of the donors currently in the year file (table <em>civicrm_oppgave</em>) with their type, number and deductible amount. You will also see the date the donor data has been loaded. And if applicalble, when the data has been modified by whom and when the data has been exported. From this list you can edit the donor data, delete the donor line and add a new donor line. Please note that this will NOT change anything in the CiviCRM contributions, but only in this tax file.

<h2>Tax data in the contact summary tab</h2>
A tab is added to the Contact Summary overview, showing the tax declaration data for the donor.

<h2>Export</h2>
There is a scheduled job called ' Skatteinnberetninger - Export'  that needs the year parameter (a 4 digit number, for example year=2014). The scheduled job will create an xml file in the path specified in the configuration file CRM/Oppgavexml/Config.php. As the scheduled job will only be run a couple of times a year, it has been set to ' inactive'  and you need to run it with the 'Execute now'  option.

<h2>Configuration file</h2>
The configuration of the extension is in the file CRM/Oppgavexml/CRM/Config.php. 

If the data about the sending organization needs to change, this can be modified in this part:
```php
  protected function set_sender_info() {
    $this->_sender_kilde_system = 'CiviCRM';
    $this->_sender_organisasjonsnummer = 'xxxxxxxx';
    $this->_sender_organisasjonsnavn = 'Mission Aviation Fellowship Norge';
    $this->_sender_kontakt_navn = 'Steinar Sødal';
    $this->_sender_kontakt_telefon = 'xxxxxx';
    $this->_sender_kontakt_mobil = 'xxxxxxx';
    $this->_sender_kontakt_epost = 'xxxxxxx';
    $this->_leveranse_type = 'ordinaer';
  }
```
The path where the result xml file will be put can be set in this part:
```php
  protected function set_xml_file_path() {
    $this->_xml_file_path = '/folder/folder/';
  }
```

<h2>Installation instructions</h2>
After installation, make sure you change the paramters of the scheduled jobs before you can run them!


