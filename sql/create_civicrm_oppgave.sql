CREATE TABLE IF NOT EXISTS `civicrm_oppgave` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `oppgave_year` CHAR(4) NULL,
  `contact_id` INT NULL,
  `donor_type` VARCHAR(45) NULL,
  `donor_name` VARCHAR(128) NULL,
  `donor_number` VARCHAR(45) NULL,
  `deductible_amount` INT(11) NULL,
  `loaded_date` DATE NULL,
  `last_modified_date` DATE NULL,
  `last_modified_user_id` INT NULL,
  `last_exported_date` DATE NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `year_contact INDEX` (`oppgave_year` ASC, `contact_id` ASC))
ENGINE = InnoDB;