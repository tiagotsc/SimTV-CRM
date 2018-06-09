/*
Navicat MySQL Data Transfer

Source Server         : Mysql - Novo
Source Server Version : 50540
Source Host           : 192.168.140.91:3306
Source Database       : atendimento

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2016-04-22 09:53:14
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `acesso_atendente`
-- ----------------------------
DROP TABLE IF EXISTS `acesso_atendente`;
CREATE TABLE `acesso_atendente` (
`cd_acesso_atendente`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`tipo_registro`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`data_hora`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
`navegador`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`ip`  varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`descricao`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`cd_usuario`  int(10) UNSIGNED NOT NULL ,
PRIMARY KEY (`cd_acesso_atendente`),
FOREIGN KEY (`cd_usuario`) REFERENCES `adminti`.`usuario` (`cd_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=457

;

-- ----------------------------
-- Table structure for `atendimento`
-- ----------------------------
DROP TABLE IF EXISTS `atendimento`;
CREATE TABLE `atendimento` (
`cd_atendimento`  bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`protocolo_atendimento`  varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`senha_atendimento`  int(11) NULL DEFAULT NULL ,
`nome_atendimento`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`descricao_atendimento`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`resolucao_atendimento`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`cd_atendente`  int(11) NULL DEFAULT NULL ,
`cd_resolvedor`  int(11) NULL DEFAULT NULL ,
`data_chegada_atendimento`  timestamp NULL DEFAULT NULL ,
`data_inicio_atendimento`  timestamp NULL DEFAULT NULL ,
`data_fim_atendimento`  timestamp NULL DEFAULT NULL ,
`data_resolucao_atendimento`  timestamp NULL DEFAULT NULL ,
`cd_motivo`  int(11) UNSIGNED NULL DEFAULT NULL ,
`cd_tipo_atendimento`  int(11) UNSIGNED NULL DEFAULT NULL ,
`cd_categoria`  int(11) UNSIGNED NULL DEFAULT NULL ,
`cd_local`  int(11) UNSIGNED NULL DEFAULT NULL ,
`cd_guiche`  int(11) UNSIGNED NULL DEFAULT NULL ,
`cd_cliente`  int(11) NULL DEFAULT NULL ,
`cd_status_atendimento`  int(11) UNSIGNED NULL DEFAULT NULL ,
PRIMARY KEY (`cd_atendimento`),
FOREIGN KEY (`cd_categoria`) REFERENCES `categoria` (`cd_categoria`) ON DELETE NO ACTION ON UPDATE NO ACTION,
FOREIGN KEY (`cd_cliente`) REFERENCES `cliente` (`cd_cliente`) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (`cd_guiche`) REFERENCES `guiche` (`cd_guiche`) ON DELETE NO ACTION ON UPDATE NO ACTION,
FOREIGN KEY (`cd_local`) REFERENCES `local` (`cd_local`) ON DELETE NO ACTION ON UPDATE NO ACTION,
FOREIGN KEY (`cd_motivo`) REFERENCES `motivo` (`cd_motivo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
FOREIGN KEY (`cd_status_atendimento`) REFERENCES `status_atendimento` (`cd_status_atendimento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
FOREIGN KEY (`cd_tipo_atendimento`) REFERENCES `tipo_atendimento` (`cd_tipo_atendimento`) ON DELETE NO ACTION ON UPDATE NO ACTION
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=447

;

-- ----------------------------
-- Table structure for `categ_tipo_atend`
-- ----------------------------
DROP TABLE IF EXISTS `categ_tipo_atend`;
CREATE TABLE `categ_tipo_atend` (
`cd_categ_tipo_atend`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`cd_tipo_atendimento`  int(10) UNSIGNED NULL DEFAULT NULL ,
`cd_categoria`  int(10) UNSIGNED NULL DEFAULT NULL ,
PRIMARY KEY (`cd_categ_tipo_atend`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=20

;

-- ----------------------------
-- Table structure for `categoria`
-- ----------------------------
DROP TABLE IF EXISTS `categoria`;
CREATE TABLE `categoria` (
`cd_categoria`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`nome_categoria`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`status_categoria`  enum('A','I') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' ,
PRIMARY KEY (`cd_categoria`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=7

;

-- ----------------------------
-- Table structure for `ci_sessions`
-- ----------------------------
DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE `ci_sessions` (
`session_id`  varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' ,
`ip_address`  varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' ,
`user_agent`  varchar(254) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`last_activity`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`user_data`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
PRIMARY KEY (`session_id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci

;

-- ----------------------------
-- Table structure for `cliente`
-- ----------------------------
DROP TABLE IF EXISTS `cliente`;
CREATE TABLE `cliente` (
`cd_cliente`  int(11) NOT NULL AUTO_INCREMENT ,
`numero_cliente`  varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`cpf_cnpj_cliente`  varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`nome_cliente`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`endereco_cliente`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`num_end_cliente`  varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`comp_end_cliente`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`bairro_cliente`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`cep_cliente`  varchar(9) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`telefone_cliente`  varchar(9) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`celular_cliente`  varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`tipo_cliente`  varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`status_cliente`  enum('A','I') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' ,
`cd_estado`  int(11) UNSIGNED NOT NULL ,
PRIMARY KEY (`cd_cliente`),
FOREIGN KEY (`cd_estado`) REFERENCES `estado` (`cd_estado`) ON DELETE NO ACTION ON UPDATE NO ACTION
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `config_usuario`
-- ----------------------------
DROP TABLE IF EXISTS `config_usuario`;
CREATE TABLE `config_usuario` (
`cd_config_usuario`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`cd_usuario`  int(11) UNSIGNED NOT NULL ,
`cd_perfil`  int(11) UNSIGNED NULL DEFAULT NULL ,
`cd_guiche`  int(11) UNSIGNED NULL DEFAULT NULL ,
`cd_local`  int(11) UNSIGNED NULL DEFAULT NULL ,
`atendente_usuario`  enum('N','S') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`online_usuario`  enum('N','S') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`status_config_usuario`  enum('I','A') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'A' ,
PRIMARY KEY (`cd_config_usuario`),
FOREIGN KEY (`cd_perfil`) REFERENCES `perfil` (`cd_perfil`) ON DELETE SET NULL ON UPDATE SET NULL,
FOREIGN KEY (`cd_usuario`) REFERENCES `adminti`.`usuario` (`cd_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (`cd_guiche`) REFERENCES `guiche` (`cd_guiche`) ON DELETE NO ACTION ON UPDATE NO ACTION,
FOREIGN KEY (`cd_local`) REFERENCES `local` (`cd_local`) ON DELETE NO ACTION ON UPDATE NO ACTION
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=4101

;

-- ----------------------------
-- Table structure for `departamento`
-- ----------------------------
DROP TABLE IF EXISTS `departamento`;
CREATE TABLE `departamento` (
`cd_departamento`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`nome_departamento`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`status_departamento`  enum('A','I') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' ,
PRIMARY KEY (`cd_departamento`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=36

;

-- ----------------------------
-- Table structure for `estado`
-- ----------------------------
DROP TABLE IF EXISTS `estado`;
CREATE TABLE `estado` (
`cd_estado`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`nome_estado`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`sigla_estado`  char(2) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`cd_estado`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=28

;

-- ----------------------------
-- Table structure for `grupo_resolucao`
-- ----------------------------
DROP TABLE IF EXISTS `grupo_resolucao`;
CREATE TABLE `grupo_resolucao` (
`cd_grupo_resolucao`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`nome_grupo_resolucao`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`status_grupo_resolucao`  varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`cd_grupo_resolucao`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=3

;

-- ----------------------------
-- Table structure for `grupo_resolucao_motivo`
-- ----------------------------
DROP TABLE IF EXISTS `grupo_resolucao_motivo`;
CREATE TABLE `grupo_resolucao_motivo` (
`cd_grupo_resolucao_motivo`  int(11) NOT NULL AUTO_INCREMENT ,
`cd_grupo_resolucao`  int(11) UNSIGNED NOT NULL ,
`cd_motivo`  int(11) UNSIGNED NOT NULL ,
PRIMARY KEY (`cd_grupo_resolucao_motivo`, `cd_grupo_resolucao`, `cd_motivo`),
FOREIGN KEY (`cd_grupo_resolucao`) REFERENCES `grupo_resolucao` (`cd_grupo_resolucao`) ON DELETE NO ACTION ON UPDATE NO ACTION,
FOREIGN KEY (`cd_motivo`) REFERENCES `motivo` (`cd_motivo`) ON DELETE NO ACTION ON UPDATE NO ACTION
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=80

;

-- ----------------------------
-- Table structure for `grupo_resolucao_usuario`
-- ----------------------------
DROP TABLE IF EXISTS `grupo_resolucao_usuario`;
CREATE TABLE `grupo_resolucao_usuario` (
`cd_grupo_resolucao_usuario`  int(11) NOT NULL AUTO_INCREMENT ,
`cd_usuario`  int(11) UNSIGNED NOT NULL ,
`cd_grupo_resolucao`  int(11) UNSIGNED NOT NULL ,
PRIMARY KEY (`cd_grupo_resolucao_usuario`, `cd_usuario`, `cd_grupo_resolucao`),
FOREIGN KEY (`cd_grupo_resolucao`) REFERENCES `grupo_resolucao` (`cd_grupo_resolucao`) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (`cd_usuario`) REFERENCES `adminti`.`usuario` (`cd_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=41

;

-- ----------------------------
-- Table structure for `guiche`
-- ----------------------------
DROP TABLE IF EXISTS `guiche`;
CREATE TABLE `guiche` (
`cd_guiche`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`nome_guiche`  varchar(4) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`status_guiche`  enum('A','I') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' ,
PRIMARY KEY (`cd_guiche`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=4

;

-- ----------------------------
-- Table structure for `local`
-- ----------------------------
DROP TABLE IF EXISTS `local`;
CREATE TABLE `local` (
`cd_local`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`nome_local`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`cd_municipio`  int(11) UNSIGNED NULL DEFAULT NULL ,
`impressora_local`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`desc_impressora_local`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`status_local`  enum('A','I') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`cd_local`),
FOREIGN KEY (`cd_municipio`) REFERENCES `municipio` (`cd_municipio`) ON DELETE NO ACTION ON UPDATE NO ACTION
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=2

;

-- ----------------------------
-- Table structure for `menu`
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
`cd_menu`  int(11) NOT NULL AUTO_INCREMENT ,
`nome_menu`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`pai_menu`  tinyint(4) NULL DEFAULT NULL ,
`link_menu`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`ordem_menu`  int(11) NULL DEFAULT NULL ,
`cd_permissao`  int(11) UNSIGNED NOT NULL ,
`status_menu`  enum('A','I') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' ,
PRIMARY KEY (`cd_menu`, `cd_permissao`),
FOREIGN KEY (`cd_permissao`) REFERENCES `permissao` (`cd_permissao`) ON DELETE NO ACTION ON UPDATE NO ACTION
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=25

;

-- ----------------------------
-- Table structure for `motivo`
-- ----------------------------
DROP TABLE IF EXISTS `motivo`;
CREATE TABLE `motivo` (
`cd_motivo`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`nome_motivo`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`cd_categoria`  int(11) UNSIGNED NULL DEFAULT NULL ,
`cd_tipo_atendimento`  int(11) UNSIGNED NULL DEFAULT NULL ,
`prazo_motivo`  int(11) NULL DEFAULT NULL ,
`status_motivo`  enum('A','I') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' ,
PRIMARY KEY (`cd_motivo`),
FOREIGN KEY (`cd_categoria`) REFERENCES `categoria` (`cd_categoria`) ON DELETE NO ACTION ON UPDATE NO ACTION,
FOREIGN KEY (`cd_tipo_atendimento`) REFERENCES `tipo_atendimento` (`cd_tipo_atendimento`) ON DELETE NO ACTION ON UPDATE NO ACTION
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=82

;

-- ----------------------------
-- Table structure for `municipio`
-- ----------------------------
DROP TABLE IF EXISTS `municipio`;
CREATE TABLE `municipio` (
`cd_municipio`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`nome_municipio`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`timezone_municipio`  varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`ddd_municipio`  char(2) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`cd_estado`  int(11) UNSIGNED NOT NULL ,
PRIMARY KEY (`cd_municipio`, `cd_estado`),
FOREIGN KEY (`cd_estado`) REFERENCES `estado` (`cd_estado`) ON DELETE NO ACTION ON UPDATE NO ACTION
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
COMMENT='	'
AUTO_INCREMENT=16

;

-- ----------------------------
-- Table structure for `parametro`
-- ----------------------------
DROP TABLE IF EXISTS `parametro`;
CREATE TABLE `parametro` (
`cd_parametro`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`nome_parametro`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`legenda_parametro`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`mascara_parametro`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`campo_parametro`  varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`variavel_parametro`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`tipo_parametro`  enum('NUMERO','LETRA','MOEDA','DATA','PORCETAGEM') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`status_parametro`  enum('I','A') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' ,
PRIMARY KEY (`cd_parametro`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=15

;

-- ----------------------------
-- Table structure for `perfil`
-- ----------------------------
DROP TABLE IF EXISTS `perfil`;
CREATE TABLE `perfil` (
`cd_perfil`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`nome_perfil`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`data_criacao_perfil`  timestamp NULL DEFAULT NULL ,
`data_atualizacao_perfil`  timestamp NULL DEFAULT NULL ,
`criador_perfil`  int(11) NULL DEFAULT NULL ,
`atualizador_perfil`  int(11) NULL DEFAULT NULL ,
`status_perfil`  enum('A','I') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' ,
PRIMARY KEY (`cd_perfil`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=3

;

-- ----------------------------
-- Table structure for `permissao`
-- ----------------------------
DROP TABLE IF EXISTS `permissao`;
CREATE TABLE `permissao` (
`cd_permissao`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`nome_permissao`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`descricao_permissao`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`pai_permissao`  int(11) NULL DEFAULT NULL ,
`ordem_permissao`  int(11) NULL DEFAULT NULL ,
`status_permissao`  enum('A','I') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' ,
PRIMARY KEY (`cd_permissao`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=45

;

-- ----------------------------
-- Table structure for `permissao_perfil`
-- ----------------------------
DROP TABLE IF EXISTS `permissao_perfil`;
CREATE TABLE `permissao_perfil` (
`cd_permissao_perfil`  int(11) NOT NULL AUTO_INCREMENT ,
`cd_perfil`  int(11) UNSIGNED NOT NULL ,
`cd_permissao`  int(11) UNSIGNED NOT NULL ,
PRIMARY KEY (`cd_permissao_perfil`, `cd_perfil`, `cd_permissao`),
FOREIGN KEY (`cd_perfil`) REFERENCES `perfil` (`cd_perfil`) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (`cd_permissao`) REFERENCES `permissao` (`cd_permissao`) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=698

;

-- ----------------------------
-- Table structure for `relatorio`
-- ----------------------------
DROP TABLE IF EXISTS `relatorio`;
CREATE TABLE `relatorio` (
`cd_relatorio`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`nome_relatorio`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`descricao_relatorio`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`descricao_parametro_relatorio`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`cd_departamento`  int(11) UNSIGNED NULL DEFAULT NULL ,
`banco_relatorio`  varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`query_relatorio`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`criador_relatorio`  int(11) NULL DEFAULT NULL ,
`data_criacao_relatorio`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
`atualizador_relatorio`  int(11) NULL DEFAULT NULL ,
`data_atualizacao_relatorio`  timestamp NULL DEFAULT NULL ,
`cd_permissao`  int(11) UNSIGNED NULL DEFAULT NULL ,
`status_relatorio`  enum('I','A') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`cd_relatorio`),
FOREIGN KEY (`cd_departamento`) REFERENCES `departamento` (`cd_departamento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
FOREIGN KEY (`cd_permissao`) REFERENCES `permissao` (`cd_permissao`) ON DELETE NO ACTION ON UPDATE NO ACTION
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=101

;

-- ----------------------------
-- Table structure for `relatorio_acesso`
-- ----------------------------
DROP TABLE IF EXISTS `relatorio_acesso`;
CREATE TABLE `relatorio_acesso` (
`cd_relatorio_acesso`  bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
`cd_relatorio`  int(11) UNSIGNED NULL DEFAULT NULL ,
`cd_usuario`  int(10) UNSIGNED NULL DEFAULT NULL ,
`data_relatorio_acesso`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
PRIMARY KEY (`cd_relatorio_acesso`),
FOREIGN KEY (`cd_relatorio`) REFERENCES `relatorio` (`cd_relatorio`) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (`cd_usuario`) REFERENCES `adminti`.`usuario` (`cd_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=2905

;

-- ----------------------------
-- Table structure for `relatorio_parametro`
-- ----------------------------
DROP TABLE IF EXISTS `relatorio_parametro`;
CREATE TABLE `relatorio_parametro` (
`cd_relatorio_parametro`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`cd_relatorio`  int(10) UNSIGNED NULL DEFAULT NULL ,
`cd_parametro`  int(10) UNSIGNED NULL DEFAULT NULL ,
`nome_relatorio_parametro`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`cd_relatorio_parametro`),
FOREIGN KEY (`cd_parametro`) REFERENCES `parametro` (`cd_parametro`) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (`cd_relatorio`) REFERENCES `relatorio` (`cd_relatorio`) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `status_atendimento`
-- ----------------------------
DROP TABLE IF EXISTS `status_atendimento`;
CREATE TABLE `status_atendimento` (
`cd_status_atendimento`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`nome_status_atendimento`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`cd_status_atendimento`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=7

;

-- ----------------------------
-- Table structure for `tipo_atendimento`
-- ----------------------------
DROP TABLE IF EXISTS `tipo_atendimento`;
CREATE TABLE `tipo_atendimento` (
`cd_tipo_atendimento`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`nome_tipo_atendimento`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`status_tipo_atendimento`  enum('A','I') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' ,
PRIMARY KEY (`cd_tipo_atendimento`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=7

;

-- ----------------------------
-- Table structure for `usuario`
-- ----------------------------
DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
`cd_usuario`  int(11) NOT NULL AUTO_INCREMENT ,
`nome_usuario`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`email_usuario`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`login_usuario`  varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`senha_usuario`  varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`data_criacao_usuario`  timestamp NULL DEFAULT NULL ,
`data_atualizacao_usuario`  timestamp NULL DEFAULT NULL ,
`criador_usuario`  int(11) NULL DEFAULT NULL ,
`atualizador_usuario`  int(11) NULL DEFAULT NULL ,
`ramal_usuario`  char(4) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`atendente_usuario`  enum('S','N') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`status_usuario`  enum('A','I') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' ,
`cd_perfil`  int(11) UNSIGNED NOT NULL ,
`cd_departamento`  int(11) UNSIGNED NOT NULL ,
`cd_local`  int(11) UNSIGNED NOT NULL ,
`cd_estado`  int(11) UNSIGNED NOT NULL ,
`online_usuario`  enum('N','S') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'N' ,
`cd_guiche`  int(11) UNSIGNED NULL DEFAULT NULL ,
PRIMARY KEY (`cd_usuario`, `cd_perfil`, `cd_departamento`),
FOREIGN KEY (`cd_estado`) REFERENCES `estado` (`cd_estado`) ON DELETE NO ACTION ON UPDATE NO ACTION,
FOREIGN KEY (`cd_guiche`) REFERENCES `guiche` (`cd_guiche`) ON DELETE NO ACTION ON UPDATE NO ACTION,
FOREIGN KEY (`cd_departamento`) REFERENCES `departamento` (`cd_departamento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
FOREIGN KEY (`cd_local`) REFERENCES `local` (`cd_local`) ON DELETE NO ACTION ON UPDATE NO ACTION,
FOREIGN KEY (`cd_perfil`) REFERENCES `perfil` (`cd_perfil`) ON DELETE NO ACTION ON UPDATE NO ACTION
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=12

;

-- ----------------------------
-- Event structure for `event_desloga_atendente`
-- ----------------------------
DROP EVENT IF EXISTS `event_desloga_atendente`;
DELIMITER ;;
CREATE DEFINER=`sistemas`@`%` EVENT `event_desloga_atendente` ON SCHEDULE EVERY '22:0' HOUR_MINUTE STARTS '2015-06-08 14:35:00' ON COMPLETION NOT PRESERVE DISABLE DO INSERT INTO acesso_atendente (cd_usuario, descricao)
		SELECT cd_usuario, 'Deslogado pelo rotina' FROM config_usuario WHERE online_usuario = 'S'
;;
DELIMITER ;

-- ----------------------------
-- Indexes structure for table acesso_atendente
-- ----------------------------
CREATE INDEX `fk_acesso_atendente_usuario1_idx` ON `acesso_atendente`(`cd_usuario`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `acesso_atendente`
-- ----------------------------
ALTER TABLE `acesso_atendente` AUTO_INCREMENT=457;

-- ----------------------------
-- Indexes structure for table atendimento
-- ----------------------------
CREATE INDEX `fk_atendimento_motivo1_idx` ON `atendimento`(`cd_motivo`) USING BTREE ;
CREATE INDEX `fk_atendimento_tipo_atendimento1_idx` ON `atendimento`(`cd_tipo_atendimento`) USING BTREE ;
CREATE INDEX `fk_atendimento_categoria1_idx` ON `atendimento`(`cd_categoria`) USING BTREE ;
CREATE INDEX `fk_atendimento_local1_idx` ON `atendimento`(`cd_local`) USING BTREE ;
CREATE INDEX `fk_atendimento_guiche1_idx` ON `atendimento`(`cd_guiche`) USING BTREE ;
CREATE INDEX `fk_atendimento_cliente1_idx` ON `atendimento`(`cd_cliente`) USING BTREE ;
CREATE INDEX `fk_atendimento_status_atendimento1_idx` ON `atendimento`(`cd_status_atendimento`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `atendimento`
-- ----------------------------
ALTER TABLE `atendimento` AUTO_INCREMENT=447;

-- ----------------------------
-- Auto increment value for `categ_tipo_atend`
-- ----------------------------
ALTER TABLE `categ_tipo_atend` AUTO_INCREMENT=20;

-- ----------------------------
-- Indexes structure for table categoria
-- ----------------------------
CREATE INDEX `cd_categoria` ON `categoria`(`cd_categoria`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `categoria`
-- ----------------------------
ALTER TABLE `categoria` AUTO_INCREMENT=7;

-- ----------------------------
-- Indexes structure for table ci_sessions
-- ----------------------------
CREATE INDEX `last_activity_idx` ON `ci_sessions`(`last_activity`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table cliente
-- ----------------------------
CREATE INDEX `fk_cliente_estado1_idx` ON `cliente`(`cd_estado`) USING BTREE ;
CREATE INDEX `cd_cliente` ON `cliente`(`cd_cliente`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `cliente`
-- ----------------------------
ALTER TABLE `cliente` AUTO_INCREMENT=1;

-- ----------------------------
-- Indexes structure for table config_usuario
-- ----------------------------
CREATE INDEX `fk_cd_usuario_config_usuario` ON `config_usuario`(`cd_usuario`) USING BTREE ;
CREATE INDEX `fk_cd_perfil_config_usuario` ON `config_usuario`(`cd_perfil`) USING BTREE ;
CREATE INDEX `fk_config_usuario_local` ON `config_usuario`(`cd_local`) USING BTREE ;
CREATE INDEX `fk_config_usuario_guiche` ON `config_usuario`(`cd_guiche`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `config_usuario`
-- ----------------------------
ALTER TABLE `config_usuario` AUTO_INCREMENT=4101;

-- ----------------------------
-- Indexes structure for table departamento
-- ----------------------------
CREATE INDEX `idx_cd_departamento` ON `departamento`(`cd_departamento`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `departamento`
-- ----------------------------
ALTER TABLE `departamento` AUTO_INCREMENT=36;

-- ----------------------------
-- Indexes structure for table estado
-- ----------------------------
CREATE INDEX `idx_cd_estado` ON `estado`(`cd_estado`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `estado`
-- ----------------------------
ALTER TABLE `estado` AUTO_INCREMENT=28;

-- ----------------------------
-- Indexes structure for table grupo_resolucao
-- ----------------------------
CREATE INDEX `idx_cd_grupo_resolucao` ON `grupo_resolucao`(`cd_grupo_resolucao`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `grupo_resolucao`
-- ----------------------------
ALTER TABLE `grupo_resolucao` AUTO_INCREMENT=3;

-- ----------------------------
-- Indexes structure for table grupo_resolucao_motivo
-- ----------------------------
CREATE INDEX `fk_grupo_resolucao_motivo_grupo_resolucao1_idx` ON `grupo_resolucao_motivo`(`cd_grupo_resolucao`) USING BTREE ;
CREATE INDEX `fk_grupo_resolucao_motivo_motivo1_idx` ON `grupo_resolucao_motivo`(`cd_motivo`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `grupo_resolucao_motivo`
-- ----------------------------
ALTER TABLE `grupo_resolucao_motivo` AUTO_INCREMENT=80;

-- ----------------------------
-- Indexes structure for table grupo_resolucao_usuario
-- ----------------------------
CREATE INDEX `fk_usuario_grupo_resolucao_usuario1_idx` ON `grupo_resolucao_usuario`(`cd_usuario`) USING BTREE ;
CREATE INDEX `fk_usuario_grupo_resolucao_grupo_resolucao1_idx` ON `grupo_resolucao_usuario`(`cd_grupo_resolucao`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `grupo_resolucao_usuario`
-- ----------------------------
ALTER TABLE `grupo_resolucao_usuario` AUTO_INCREMENT=41;

-- ----------------------------
-- Indexes structure for table guiche
-- ----------------------------
CREATE INDEX `idx_cd_guiche` ON `guiche`(`cd_guiche`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `guiche`
-- ----------------------------
ALTER TABLE `guiche` AUTO_INCREMENT=4;

-- ----------------------------
-- Indexes structure for table local
-- ----------------------------
CREATE INDEX `fk_local_municipio` ON `local`(`cd_municipio`) USING BTREE ;
CREATE INDEX `idx_cd_local` ON `local`(`cd_local`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `local`
-- ----------------------------
ALTER TABLE `local` AUTO_INCREMENT=2;

-- ----------------------------
-- Indexes structure for table menu
-- ----------------------------
CREATE INDEX `fk_menu_permissao1_idx` ON `menu`(`cd_permissao`) USING BTREE ;
CREATE INDEX `idx_cd_menu` ON `menu`(`cd_menu`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `menu`
-- ----------------------------
ALTER TABLE `menu` AUTO_INCREMENT=25;

-- ----------------------------
-- Indexes structure for table motivo
-- ----------------------------
CREATE INDEX `idx_cd_motivo` ON `motivo`(`cd_motivo`) USING BTREE ;
CREATE INDEX `idx_cd_categoria` ON `motivo`(`cd_categoria`) USING BTREE ;
CREATE INDEX `fk_cd_tipo_atendimento_motivo` ON `motivo`(`cd_tipo_atendimento`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `motivo`
-- ----------------------------
ALTER TABLE `motivo` AUTO_INCREMENT=82;

-- ----------------------------
-- Indexes structure for table municipio
-- ----------------------------
CREATE INDEX `fk_municipio_estado1_idx` ON `municipio`(`cd_estado`) USING BTREE ;
CREATE INDEX `cd_municipio` ON `municipio`(`cd_municipio`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `municipio`
-- ----------------------------
ALTER TABLE `municipio` AUTO_INCREMENT=16;

-- ----------------------------
-- Auto increment value for `parametro`
-- ----------------------------
ALTER TABLE `parametro` AUTO_INCREMENT=15;

-- ----------------------------
-- Indexes structure for table perfil
-- ----------------------------
CREATE INDEX `idx_cd_perfil` ON `perfil`(`cd_perfil`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `perfil`
-- ----------------------------
ALTER TABLE `perfil` AUTO_INCREMENT=3;

-- ----------------------------
-- Indexes structure for table permissao
-- ----------------------------
CREATE INDEX `idx_cd_permissao` ON `permissao`(`cd_permissao`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `permissao`
-- ----------------------------
ALTER TABLE `permissao` AUTO_INCREMENT=45;

-- ----------------------------
-- Indexes structure for table permissao_perfil
-- ----------------------------
CREATE INDEX `fk_permissao_perfil_perfil1_idx` ON `permissao_perfil`(`cd_perfil`) USING BTREE ;
CREATE INDEX `fk_permissao_perfil_permissao1_idx` ON `permissao_perfil`(`cd_permissao`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `permissao_perfil`
-- ----------------------------
ALTER TABLE `permissao_perfil` AUTO_INCREMENT=698;

-- ----------------------------
-- Indexes structure for table relatorio
-- ----------------------------
CREATE INDEX `fk_cd_departamento_relatorio` ON `relatorio`(`cd_departamento`) USING BTREE ;
CREATE INDEX `fk_cd_permissao_relatorio` ON `relatorio`(`cd_permissao`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `relatorio`
-- ----------------------------
ALTER TABLE `relatorio` AUTO_INCREMENT=101;

-- ----------------------------
-- Indexes structure for table relatorio_acesso
-- ----------------------------
CREATE INDEX `fk_cd_relatorio_relatorio_acesso` ON `relatorio_acesso`(`cd_relatorio`) USING BTREE ;
CREATE INDEX `fk_cd_usuario_relatorio_acesso` ON `relatorio_acesso`(`cd_usuario`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `relatorio_acesso`
-- ----------------------------
ALTER TABLE `relatorio_acesso` AUTO_INCREMENT=2905;

-- ----------------------------
-- Indexes structure for table relatorio_parametro
-- ----------------------------
CREATE INDEX `fk_cd_relatorio_relatorio_parametro` ON `relatorio_parametro`(`cd_relatorio`) USING BTREE ;
CREATE INDEX `fk_cd_parametro_relatorio_parametro` ON `relatorio_parametro`(`cd_parametro`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `relatorio_parametro`
-- ----------------------------
ALTER TABLE `relatorio_parametro` AUTO_INCREMENT=1;

-- ----------------------------
-- Indexes structure for table status_atendimento
-- ----------------------------
CREATE INDEX `idx_cd_status_atendimento` ON `status_atendimento`(`cd_status_atendimento`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `status_atendimento`
-- ----------------------------
ALTER TABLE `status_atendimento` AUTO_INCREMENT=7;

-- ----------------------------
-- Indexes structure for table tipo_atendimento
-- ----------------------------
CREATE INDEX `idx_cd_tipo_atendimento` ON `tipo_atendimento`(`cd_tipo_atendimento`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `tipo_atendimento`
-- ----------------------------
ALTER TABLE `tipo_atendimento` AUTO_INCREMENT=7;

-- ----------------------------
-- Indexes structure for table usuario
-- ----------------------------
CREATE UNIQUE INDEX `email_usuario_UNIQUE` ON `usuario`(`email_usuario`) USING BTREE ;
CREATE INDEX `fk_usuario_perfil1_idx` ON `usuario`(`cd_perfil`) USING BTREE ;
CREATE INDEX `fk_usuario_departamento1_idx` ON `usuario`(`cd_departamento`) USING BTREE ;
CREATE INDEX `fk_usuario_local1_idx` ON `usuario`(`cd_local`) USING BTREE ;
CREATE INDEX `fk_usuario_estado1_idx` ON `usuario`(`cd_estado`) USING BTREE ;
CREATE INDEX `cd_usuario` ON `usuario`(`cd_usuario`) USING BTREE ;
CREATE INDEX `fk_usuario_cd_guiche` ON `usuario`(`cd_guiche`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `usuario`
-- ----------------------------
ALTER TABLE `usuario` AUTO_INCREMENT=12;
