DROP DATABASE IF EXISTS fibbage;
CREATE DATABASE fibbage;
USE fibbage;

CREATE TABLE IF NOT EXISTS `Question` (
  `id` INT NOT NULL,
  `question` VARCHAR(120) NULL,
  `answer` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`fakeAnswers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `FakeAnswers` (
  `question_id` INT NOT NULL,
  `answer` VARCHAR(45),
  PRIMARY KEY (`question_id`),
  CONSTRAINT `fk_fakeAnswers_question`  FOREIGN KEY (`question_id`) REFERENCES `Question` (`id`)
)
    
ENGINE = InnoDB;
