-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema controledb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema controledb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `controledb` DEFAULT CHARACTER SET utf8 ;
USE `controledb` ;

-- -----------------------------------------------------
-- Table `controledb`.`usuarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `controledb`.`usuarios` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `usuario` VARCHAR(30) NOT NULL,
  `senha` VARCHAR(32) NOT NULL,
  `senha_original` VARCHAR(20) NOT NULL,
  `nivel` VARCHAR(20) NOT NULL,
  `ativo` BIT(1) NOT NULL DEFAULT b'1',
  `avatar` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `controledb`.`eventos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `controledb`.`eventos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `data_evento` TIMESTAMP NOT NULL,
  `capacidade` INT(11) NOT NULL,
  `ativo` BIT(1) NULL DEFAULT NULL,
  `usuarios_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`, `usuarios_id`),
  INDEX `fk_eventos_usuarios_idx` (`usuarios_id` ASC) VISIBLE,
  CONSTRAINT `fk_eventos_usuarios`
    FOREIGN KEY (`usuarios_id`)
    REFERENCES `controledb`.`usuarios` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8;

USE `controledb` ;

-- -----------------------------------------------------
-- procedure sp_event_insert
-- -----------------------------------------------------

DELIMITER $$
USE `controledb`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_event_insert`(
`:nome` varchar(45),
`:data_evento` timestamp,
`:capacidade` int(11),
`:usuarios_id` int(11)
)
BEGIN
insert into eventos (nome, data_evento, capacidade, ativo, usuarios_id)
values (`:nome`, `:data_evento`, `:capacidade`,1,`:usuarios_id`);
select * from eventos where id = (select @@identity);
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure sp_user_insert
-- -----------------------------------------------------

DELIMITER $$
USE `controledb`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_user_insert`(
`:nome` varchar(45),
`:usuario` varchar(30),
`:senha` varchar(32),
`:nivel` varchar(20),
`:avatar` varchar(45)
)
BEGIN
insert into usuarios (nome, usuario, senha, senha_original, nivel, ativo, avatar)
values (`:nome`, `:usuario`, `:senha`, `:senha`, `:nivel`,1,`:avatar`);
select * from usuarios where id = (select @@identity);
END$$

DELIMITER ;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
