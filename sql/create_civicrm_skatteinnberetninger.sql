CREATE TABLE IF NOT EXISTS `civicrm_skatteinnberetninger` (
  `year` CHAR(4) NOT NULL,
  `status_id` INT(11) NULL,
  PRIMARY KEY (`year`),
  UNIQUE INDEX `year_UNIQUE` (`year` DESC))
ENGINE = InnoDB