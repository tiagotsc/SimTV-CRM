/*
Navicat MySQL Data Transfer

Source Server         : Mysql - Novo
Source Server Version : 50540
Source Host           : 192.168.140.91:3306
Source Database       : adminti

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2016-04-22 09:51:11
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `cargo`
-- ----------------------------
DROP TABLE IF EXISTS `cargo`;
CREATE TABLE `cargo` (
`cd_cargo`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`nome`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`status`  enum('I','A') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' ,
PRIMARY KEY (`cd_cargo`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=210

;

-- ----------------------------
-- Table structure for `centro_custo`
-- ----------------------------
DROP TABLE IF EXISTS `centro_custo`;
CREATE TABLE `centro_custo` (
`cd_centro_custo`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`cd_departamento`  int(11) UNSIGNED NULL DEFAULT NULL ,
`cd_unidade`  int(11) UNSIGNED NULL DEFAULT NULL ,
`codigo`  smallint(6) NULL DEFAULT NULL ,
PRIMARY KEY (`cd_centro_custo`),
FOREIGN KEY (`cd_unidade`) REFERENCES `unidade` (`cd_unidade`) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (`cd_departamento`) REFERENCES `departamento` (`cd_departamento`) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=114

;

-- ----------------------------
-- Table structure for `chat_dinamica`
-- ----------------------------
DROP TABLE IF EXISTS `chat_dinamica`;
CREATE TABLE `chat_dinamica` (
`cd_chat_dinamica`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`cd_origem`  int(11) NULL DEFAULT NULL ,
`tipo_origem`  enum('grupo','dp','user') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`cd_destino`  int(11) NULL DEFAULT NULL ,
`tipo_destino`  enum('grupo','dp','user') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`local`  int(11) NULL DEFAULT NULL ,
`status_escrita`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`data_abertura`  timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP ,
PRIMARY KEY (`cd_chat_dinamica`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=40

;

-- ----------------------------
-- Table structure for `chat_favoritos`
-- ----------------------------
DROP TABLE IF EXISTS `chat_favoritos`;
CREATE TABLE `chat_favoritos` (
`cd_chat_favoritos`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`cd_adicionado`  int(11) NULL DEFAULT NULL ,
`cd_usuario`  int(11) UNSIGNED NULL DEFAULT NULL ,
PRIMARY KEY (`cd_chat_favoritos`),
FOREIGN KEY (`cd_usuario`) REFERENCES `usuario` (`cd_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=2265

;

-- ----------------------------
-- Table structure for `chat_lidas`
-- ----------------------------
DROP TABLE IF EXISTS `chat_lidas`;
CREATE TABLE `chat_lidas` (
`cd_chat_lidas`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`cd_chat_msg`  int(10) UNSIGNED NULL DEFAULT NULL ,
`cd_destino`  int(11) NULL DEFAULT NULL ,
`local`  int(11) NULL DEFAULT NULL ,
`tipo`  enum('grupo','dp') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`cd_usuario`  int(11) UNSIGNED NULL DEFAULT NULL ,
`status_lida`  enum('N','S') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'N' ,
`data_lida`  timestamp NULL DEFAULT NULL ,
PRIMARY KEY (`cd_chat_lidas`),
FOREIGN KEY (`cd_usuario`) REFERENCES `usuario` (`cd_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (`cd_chat_msg`) REFERENCES `chat_msg` (`cd_chat_msg`) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=440

;

-- ----------------------------
-- Table structure for `chat_msg`
-- ----------------------------
DROP TABLE IF EXISTS `chat_msg`;
CREATE TABLE `chat_msg` (
`cd_chat_msg`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`cd_origem`  int(11) UNSIGNED NULL DEFAULT NULL ,
`tipo_origem`  enum('user','dp','grupo') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`cd_destino`  int(11) NULL DEFAULT NULL ,
`tipo_destino`  enum('user','dp','grupo') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`local`  int(11) NULL DEFAULT NULL ,
`mensagem`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`mensagem_tipo`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`diretorio`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`extensao`  varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`lida`  enum('N','S') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'N' ,
`data_lida`  timestamp NULL DEFAULT NULL ,
`data_envio`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
PRIMARY KEY (`cd_chat_msg`),
FOREIGN KEY (`cd_origem`) REFERENCES `usuario` (`cd_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=441

;

-- ----------------------------
-- Table structure for `DADOS`
-- ----------------------------
DROP TABLE IF EXISTS `DADOS`;
CREATE TABLE `DADOS` (
`matricula`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`nome`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`local`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`cargo`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`admissao`  date NULL DEFAULT NULL ,
`email`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci

;

-- ----------------------------
-- Table structure for `departamento`
-- ----------------------------
DROP TABLE IF EXISTS `departamento`;
CREATE TABLE `departamento` (
`cd_departamento`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`nome_departamento`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`status_departamento`  enum('I','A') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`cd_departamento`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=56

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
AUTO_INCREMENT=29

;

-- ----------------------------
-- Table structure for `log`
-- ----------------------------
DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` (
`cd_log`  bigint(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`cd_usuario`  int(10) UNSIGNED NULL DEFAULT NULL ,
`aplicacao`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`modulo`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`funcao`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`acao`  enum('DELETE','UPDATE','INSERT','INICIA','PROCESSANDO','FINALIZA') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`idAcao`  int(11) NULL DEFAULT NULL ,
`descricao`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`data`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
PRIMARY KEY (`cd_log`),
FOREIGN KEY (`cd_usuario`) REFERENCES `usuario` (`cd_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=1964

;

-- ----------------------------
-- Table structure for `log_arquivo`
-- ----------------------------
DROP TABLE IF EXISTS `log_arquivo`;
CREATE TABLE `log_arquivo` (
`cd_log_arquivo`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`nome`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`localizacao`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`md5file`  varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`data`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
`fonte`  enum('CALLCENTER - ATIVO','SERVIDOR ASTERISK','CALLCENTER - RECEPTIVO','CALLCENTER - RECEPTIVO - 0800','MOVEL','CALLCENTER - RECEPTIVO - 4004') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`permissor`  int(11) NULL DEFAULT NULL ,
PRIMARY KEY (`cd_log_arquivo`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=558

;

-- ----------------------------
-- Table structure for `log_chat_msg`
-- ----------------------------
DROP TABLE IF EXISTS `log_chat_msg`;
CREATE TABLE `log_chat_msg` (
`cd_log_chat_msg`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`cd_chat_msg`  int(11) NULL DEFAULT NULL ,
`cd_origem`  int(11) NULL DEFAULT NULL ,
`tipo_origem`  enum('dp','user') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`cd_destino`  int(11) NULL DEFAULT NULL ,
`tipo_destino`  enum('user','dp') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`local`  int(11) NULL DEFAULT NULL ,
`mensagem`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`mensagem_tipo`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`diretorio`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`extensao`  varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`lida`  enum('N','S') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`data_lida`  timestamp NULL DEFAULT NULL ,
`data_envio`  timestamp NULL DEFAULT NULL ,
PRIMARY KEY (`cd_log_chat_msg`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=107

;

-- ----------------------------
-- Table structure for `log_tarefa_agendada`
-- ----------------------------
DROP TABLE IF EXISTS `log_tarefa_agendada`;
CREATE TABLE `log_tarefa_agendada` (
`cd_log_tarefa_agendada`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`tarefa`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`descricao`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`status`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`data`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
PRIMARY KEY (`cd_log_tarefa_agendada`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=392

;

-- ----------------------------
-- Table structure for `menu`
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
`cd_menu`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`nome_menu`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`pai_menu`  tinyint(11) NULL DEFAULT NULL ,
`link_menu`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`ordem_menu`  tinyint(4) NULL DEFAULT NULL ,
`status_menu`  enum('A','I') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`cd_menu`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=19

;

-- ----------------------------
-- Table structure for `operadora`
-- ----------------------------
DROP TABLE IF EXISTS `operadora`;
CREATE TABLE `operadora` (
`cd_operadora`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`id_operadora`  smallint(11) NULL DEFAULT NULL ,
`nome`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`status`  enum('I','A') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' ,
PRIMARY KEY (`cd_operadora`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=5

;

-- ----------------------------
-- Table structure for `telefonia_acessorio`
-- ----------------------------
DROP TABLE IF EXISTS `telefonia_acessorio`;
CREATE TABLE `telefonia_acessorio` (
`cd_telefonia_acessorio`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`nome`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`status`  enum('I','A') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' ,
PRIMARY KEY (`cd_telefonia_acessorio`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=10

;

-- ----------------------------
-- Table structure for `telefonia_aparelho`
-- ----------------------------
DROP TABLE IF EXISTS `telefonia_aparelho`;
CREATE TABLE `telefonia_aparelho` (
`cd_telefonia_aparelho`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`cd_telefonia_marca`  int(10) UNSIGNED NULL DEFAULT NULL ,
`tipo`  enum('CEL','INT') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'CEL' ,
`nota_fiscal`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`modelo`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`data_inicio`  date NULL DEFAULT NULL ,
`data_fim`  date NULL DEFAULT NULL ,
`status`  enum('Ativo','Estoque','Avariado') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'Estoque' ,
`data_cadastro`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
PRIMARY KEY (`cd_telefonia_aparelho`),
FOREIGN KEY (`cd_telefonia_marca`) REFERENCES `telefonia_marca` (`cd_telefonia_marca`) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=599

;

-- ----------------------------
-- Table structure for `telefonia_cdr`
-- ----------------------------
DROP TABLE IF EXISTS `telefonia_cdr`;
CREATE TABLE `telefonia_cdr` (
`calldate`  datetime NOT NULL ,
`clid`  varchar(80) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`src`  varchar(80) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`dst`  varchar(80) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`dcontext`  varchar(80) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`channel`  varchar(80) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`dstchannel`  varchar(80) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`lastapp`  varchar(80) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`lastdata`  varchar(80) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`duration`  int(11) NOT NULL ,
`billsec`  int(11) NOT NULL ,
`disposition`  varchar(45) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`amaflags`  int(11) NOT NULL ,
`accountcode`  varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`uniqueid`  varchar(32) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`userfield`  varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`did`  varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`recordingfile`  varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`cnum`  varchar(40) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`cnam`  varchar(40) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`outbound_cnum`  varchar(40) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`outbound_cnam`  varchar(40) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`dst_cnam`  varchar(40) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`id`  int(11) NOT NULL AUTO_INCREMENT ,
PRIMARY KEY (`id`)
)
ENGINE=FEDERATED
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci

;

-- ----------------------------
-- Table structure for `telefonia_chamadas`
-- ----------------------------
DROP TABLE IF EXISTS `telefonia_chamadas`;
CREATE TABLE `telefonia_chamadas` (
`cd_telefonia_chamadas`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`cd_log_arquivo`  int(11) UNSIGNED NULL DEFAULT NULL ,
`inicio`  datetime NULL DEFAULT NULL ,
`fim`  datetime NULL DEFAULT NULL ,
`origem`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`destino`  varchar(18) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`duracao`  time NULL DEFAULT NULL ,
`segundos`  int(11) NULL DEFAULT NULL ,
`custo`  decimal(10,5) NULL DEFAULT NULL ,
`tipo`  varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`font`  enum('CALLCENTER - ATIVO','SERVIDOR ASTERISK','CALLCENTER - RECEPTIVO','CALLCENTER - RECEPTIVO - 0800','MOVEL','CALLCENTER - RECEPTIVO - 4004') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`cd_telefonia_chamadas`),
FOREIGN KEY (`cd_log_arquivo`) REFERENCES `log_arquivo` (`cd_log_arquivo`) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=1264898

;

-- ----------------------------
-- Table structure for `telefonia_ddd`
-- ----------------------------
DROP TABLE IF EXISTS `telefonia_ddd`;
CREATE TABLE `telefonia_ddd` (
`cd_telefonia_ddd`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`ddd`  smallint(2) NULL DEFAULT NULL ,
`estado`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`cidade_regiao`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`status`  enum('I','A') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' ,
PRIMARY KEY (`cd_telefonia_ddd`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=68

;

-- ----------------------------
-- Table structure for `telefonia_emprestimo`
-- ----------------------------
DROP TABLE IF EXISTS `telefonia_emprestimo`;
CREATE TABLE `telefonia_emprestimo` (
`cd_telefonia_emprestimo`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`cd_telefonia_aparelho`  int(11) UNSIGNED NOT NULL ,
`cd_usuario`  int(10) UNSIGNED NOT NULL ,
`data_inicio`  date NULL DEFAULT NULL ,
`data_fim`  date NULL DEFAULT NULL ,
`criador_termo`  int(11) NULL DEFAULT NULL ,
`data_criacao_termo`  timestamp NULL DEFAULT NULL ,
`alterador_termo`  int(11) NULL DEFAULT NULL ,
`data_alteracao_termo`  timestamp NULL DEFAULT NULL ,
`data_termo`  date NULL DEFAULT NULL ,
`aceite_termo`  enum('N','S') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`data_aceite_termo`  timestamp NULL DEFAULT NULL ,
`data_cadastro`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
PRIMARY KEY (`cd_telefonia_emprestimo`),
FOREIGN KEY (`cd_telefonia_aparelho`) REFERENCES `telefonia_aparelho` (`cd_telefonia_aparelho`) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (`cd_usuario`) REFERENCES `usuario` (`cd_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=355

;

-- ----------------------------
-- Table structure for `telefonia_emprestimo_acessorio`
-- ----------------------------
DROP TABLE IF EXISTS `telefonia_emprestimo_acessorio`;
CREATE TABLE `telefonia_emprestimo_acessorio` (
`cd_telefonia_emprestimo_acessorio`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`cd_telefonia_emprestimo`  int(11) UNSIGNED NULL DEFAULT NULL ,
`cd_telefonia_acessorio`  int(11) UNSIGNED NULL DEFAULT NULL ,
PRIMARY KEY (`cd_telefonia_emprestimo_acessorio`),
FOREIGN KEY (`cd_telefonia_acessorio`) REFERENCES `telefonia_acessorio` (`cd_telefonia_acessorio`) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (`cd_telefonia_emprestimo`) REFERENCES `telefonia_emprestimo` (`cd_telefonia_emprestimo`) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=128

;

-- ----------------------------
-- Table structure for `telefonia_emprestimo_linha`
-- ----------------------------
DROP TABLE IF EXISTS `telefonia_emprestimo_linha`;
CREATE TABLE `telefonia_emprestimo_linha` (
`cd_telefonia_emprestimo_linha`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`cd_telefonia_emprestimo`  int(10) UNSIGNED NULL DEFAULT NULL ,
`cd_telefonia_linha`  int(11) UNSIGNED NULL DEFAULT NULL ,
PRIMARY KEY (`cd_telefonia_emprestimo_linha`),
FOREIGN KEY (`cd_telefonia_emprestimo`) REFERENCES `telefonia_emprestimo` (`cd_telefonia_emprestimo`) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (`cd_telefonia_linha`) REFERENCES `telefonia_linha` (`cd_telefonia_linha`) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=580

;

-- ----------------------------
-- Table structure for `telefonia_fatura`
-- ----------------------------
DROP TABLE IF EXISTS `telefonia_fatura`;
CREATE TABLE `telefonia_fatura` (
`cd_telefonia_fatura`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`tipo`  enum('D','S','B') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`data`  date NULL DEFAULT NULL ,
`hora`  time NULL DEFAULT NULL ,
`origem`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`destino`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`ddd_origem`  smallint(2) NULL DEFAULT NULL ,
`numero_origem`  int(10) NULL DEFAULT NULL ,
`ddd_destino`  smallint(2) NULL DEFAULT NULL ,
`numero_destino`  int(10) NULL DEFAULT NULL ,
`realizado`  time NULL DEFAULT NULL ,
`tarifado`  time NULL DEFAULT NULL ,
`valor`  float NULL DEFAULT NULL ,
PRIMARY KEY (`cd_telefonia_fatura`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `telefonia_febraban`
-- ----------------------------
DROP TABLE IF EXISTS `telefonia_febraban`;
CREATE TABLE `telefonia_febraban` (
`cd_telefonia_febraban`  bigint(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`tipo`  tinyint(1) NULL DEFAULT NULL ,
`empresa`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`dt_vencimento`  date NULL DEFAULT NULL ,
`dt_emissao`  date NULL DEFAULT NULL ,
`qtd_registros`  bigint(20) NULL DEFAULT NULL ,
`qtd_linhas`  bigint(20) NULL DEFAULT NULL ,
`sinal_total`  char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`valor_total`  float NULL DEFAULT NULL ,
`nrc`  varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`cnl_recurso`  tinyint(5) NULL DEFAULT NULL ,
`cnl_localidade`  tinyint(5) NULL DEFAULT NULL ,
`uf_localidade`  char(2) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`localidade`  varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`ddd`  tinyint(2) NULL DEFAULT NULL ,
`telefone`  int(10) NULL DEFAULT NULL ,
`tipo_servico`  varchar(4) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`desc_tipo_servico`  varchar(35) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`carac_recurso`  varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`dt_ligacao`  date NULL DEFAULT NULL ,
`dt_servico`  date NULL DEFAULT NULL ,
`cod_nac_int`  tinyint(2) NULL DEFAULT NULL ,
`cod_operadora`  tinyint(2) NULL DEFAULT NULL ,
`desc_operadora`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`ddd_destino`  tinyint(2) NULL DEFAULT NULL ,
`telefone_destino`  int(10) NULL DEFAULT NULL ,
`uf_destino`  char(2) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`horario_ligacao`  time NULL DEFAULT NULL ,
`duracao_ligacao`  float NULL DEFAULT NULL ,
`categoria`  char(3) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`desc_categoria`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`grupo_categoria`  char(3) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`desc_grupo_categoria`  varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`tipo_chamada`  char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`desc_horario_tarifario`  varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`degrau_ligacao`  tinyint(2) NULL DEFAULT NULL ,
`sinal_valor_ligacao`  char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`aliquota_valor`  float NULL DEFAULT NULL ,
`valor_ligacao`  float NULL DEFAULT NULL ,
`ini_assinatura`  date NULL DEFAULT NULL ,
`fim_assinatura`  date NULL DEFAULT NULL ,
`ini_servico`  date NULL DEFAULT NULL ,
`fim_servico`  date NULL DEFAULT NULL ,
`uni_servico`  varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`qtd_servico`  int(11) NULL DEFAULT NULL ,
`qtd_consumo`  int(11) NULL DEFAULT NULL ,
`uni_consumo`  varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`sinal_valor_consumo`  char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`valor_consumo`  float NULL DEFAULT NULL ,
`sinal_assinatura`  char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`valor_assinatura`  float NULL DEFAULT NULL ,
`aliquota_porcentagem`  varchar(2) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`sinal_icms`  char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`valor_icms`  float NULL DEFAULT NULL ,
`sinal_valor_outros`  char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`nota_fiscal`  varchar(12) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`sinal_valor_conta`  char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`valor_conta`  float NULL DEFAULT NULL ,
`sinal_valor_total_outros`  char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`valor_total_impostos`  float NULL DEFAULT NULL ,
`cd_log_arquivo`  int(10) UNSIGNED NULL DEFAULT NULL ,
PRIMARY KEY (`cd_telefonia_febraban`),
FOREIGN KEY (`cd_log_arquivo`) REFERENCES `log_arquivo` (`cd_log_arquivo`) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=582889

;

-- ----------------------------
-- Table structure for `telefonia_imei`
-- ----------------------------
DROP TABLE IF EXISTS `telefonia_imei`;
CREATE TABLE `telefonia_imei` (
`imei`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`cd_telefonia_aparelho`  int(10) UNSIGNED NULL DEFAULT NULL ,
FOREIGN KEY (`cd_telefonia_aparelho`) REFERENCES `telefonia_aparelho` (`cd_telefonia_aparelho`) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci

;

-- ----------------------------
-- Table structure for `telefonia_interface`
-- ----------------------------
DROP TABLE IF EXISTS `telefonia_interface`;
CREATE TABLE `telefonia_interface` (
`cd_telefonia_interface`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`cd_departamento`  int(10) UNSIGNED NULL DEFAULT NULL ,
`identificacao`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`nome`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`status`  enum('I','A') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' ,
`data_cadastro`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
PRIMARY KEY (`cd_telefonia_interface`),
FOREIGN KEY (`cd_departamento`) REFERENCES `departamento` (`cd_departamento`) ON DELETE NO ACTION ON UPDATE NO ACTION
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `telefonia_linha`
-- ----------------------------
DROP TABLE IF EXISTS `telefonia_linha`;
CREATE TABLE `telefonia_linha` (
`cd_telefonia_linha`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`cd_telefonia_operadora`  int(11) UNSIGNED NULL DEFAULT NULL ,
`cd_telefonia_plano`  int(11) UNSIGNED NULL DEFAULT NULL ,
`cd_telefonia_ddd`  int(10) UNSIGNED NULL DEFAULT NULL ,
`cd_departamento`  int(10) UNSIGNED NULL DEFAULT NULL ,
`identificacao`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`numero`  int(11) NULL DEFAULT NULL ,
`tipo`  enum('FIXO','MOVEL') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'MOVEL' ,
`status`  enum('I','A','E') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' ,
`data_cadastro`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
PRIMARY KEY (`cd_telefonia_linha`),
FOREIGN KEY (`cd_telefonia_ddd`) REFERENCES `telefonia_ddd` (`cd_telefonia_ddd`) ON DELETE NO ACTION ON UPDATE NO ACTION,
FOREIGN KEY (`cd_departamento`) REFERENCES `departamento` (`cd_departamento`) ON DELETE SET NULL ON UPDATE SET NULL,
FOREIGN KEY (`cd_telefonia_operadora`) REFERENCES `telefonia_operadora` (`cd_telefonia_operadora`) ON DELETE NO ACTION ON UPDATE NO ACTION,
FOREIGN KEY (`cd_telefonia_plano`) REFERENCES `telefonia_plano` (`cd_telefonia_plano`) ON DELETE NO ACTION ON UPDATE NO ACTION
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=839

;

-- ----------------------------
-- Table structure for `telefonia_linha_servico`
-- ----------------------------
DROP TABLE IF EXISTS `telefonia_linha_servico`;
CREATE TABLE `telefonia_linha_servico` (
`cd_telefonia_linha_servico`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`cd_telefonia_linha`  int(11) UNSIGNED NULL DEFAULT NULL ,
`cd_telefonia_servico`  int(11) UNSIGNED NULL DEFAULT NULL ,
`qtd`  int(11) NULL DEFAULT NULL ,
`valor`  float NULL DEFAULT NULL ,
`data_inicio`  date NULL DEFAULT NULL ,
`data_fim`  date NULL DEFAULT NULL ,
PRIMARY KEY (`cd_telefonia_linha_servico`),
FOREIGN KEY (`cd_telefonia_linha`) REFERENCES `telefonia_linha` (`cd_telefonia_linha`) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (`cd_telefonia_servico`) REFERENCES `telefonia_servico` (`cd_telefonia_servico`) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=3123

;

-- ----------------------------
-- Table structure for `telefonia_marca`
-- ----------------------------
DROP TABLE IF EXISTS `telefonia_marca`;
CREATE TABLE `telefonia_marca` (
`cd_telefonia_marca`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`nome`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`status`  enum('I','A') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' ,
PRIMARY KEY (`cd_telefonia_marca`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=61

;

-- ----------------------------
-- Table structure for `telefonia_oferta`
-- ----------------------------
DROP TABLE IF EXISTS `telefonia_oferta`;
CREATE TABLE `telefonia_oferta` (
`cd_telefonia_oferta`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`nome`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`status`  enum('I','A') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' ,
PRIMARY KEY (`cd_telefonia_oferta`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=4

;

-- ----------------------------
-- Table structure for `telefonia_operadora`
-- ----------------------------
DROP TABLE IF EXISTS `telefonia_operadora`;
CREATE TABLE `telefonia_operadora` (
`cd_telefonia_operadora`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`nome`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`status`  enum('I','A') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' ,
`data_cadastro`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
PRIMARY KEY (`cd_telefonia_operadora`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=10

;

-- ----------------------------
-- Table structure for `telefonia_plano`
-- ----------------------------
DROP TABLE IF EXISTS `telefonia_plano`;
CREATE TABLE `telefonia_plano` (
`cd_telefonia_plano`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`cd_telefonia_operadora`  int(11) UNSIGNED NULL DEFAULT NULL ,
`nome`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`sms`  int(11) NULL DEFAULT NULL ,
`voz`  int(11) NULL DEFAULT NULL ,
`dados`  int(11) NULL DEFAULT NULL ,
`tipo_dados`  enum('MB','GB') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`status`  enum('I','A') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' ,
`data_cadastro`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
PRIMARY KEY (`cd_telefonia_plano`),
FOREIGN KEY (`cd_telefonia_operadora`) REFERENCES `telefonia_operadora` (`cd_telefonia_operadora`) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=8

;

-- ----------------------------
-- Table structure for `telefonia_ramal`
-- ----------------------------
DROP TABLE IF EXISTS `telefonia_ramal`;
CREATE TABLE `telefonia_ramal` (
`cd_telefonia_ramal`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`numero`  int(11) NULL DEFAULT NULL ,
`status`  enum('I','A') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' ,
`data_cadastro`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
PRIMARY KEY (`cd_telefonia_ramal`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=57

;

-- ----------------------------
-- Table structure for `telefonia_ramal_usuario`
-- ----------------------------
DROP TABLE IF EXISTS `telefonia_ramal_usuario`;
CREATE TABLE `telefonia_ramal_usuario` (
`cd`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`cd_telefonia_ramal`  int(10) UNSIGNED NULL DEFAULT NULL ,
`cd_usuario`  int(10) UNSIGNED NULL DEFAULT NULL ,
`data_cadastro`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
PRIMARY KEY (`cd`),
FOREIGN KEY (`cd_usuario`) REFERENCES `usuario` (`cd_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (`cd_telefonia_ramal`) REFERENCES `telefonia_ramal` (`cd_telefonia_ramal`) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=75

;

-- ----------------------------
-- Table structure for `telefonia_servico`
-- ----------------------------
DROP TABLE IF EXISTS `telefonia_servico`;
CREATE TABLE `telefonia_servico` (
`cd_telefonia_servico`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`nome`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`descricao`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`qtd`  int(11) NULL DEFAULT NULL ,
`valor`  decimal(10,2) NULL DEFAULT NULL ,
`data_inicio`  date NULL DEFAULT NULL ,
`data_fim`  date NULL DEFAULT NULL ,
`status`  enum('I','A') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' ,
PRIMARY KEY (`cd_telefonia_servico`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=28

;

-- ----------------------------
-- Table structure for `telefonia_tarifa`
-- ----------------------------
DROP TABLE IF EXISTS `telefonia_tarifa`;
CREATE TABLE `telefonia_tarifa` (
`cd_telefonia_tarifa`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`cd_telefonia_plano`  int(10) UNSIGNED NULL DEFAULT NULL ,
`nome`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`valor`  float NULL DEFAULT NULL ,
`data_inicio`  date NULL DEFAULT NULL ,
`data_fim`  date NULL DEFAULT NULL ,
PRIMARY KEY (`cd_telefonia_tarifa`),
FOREIGN KEY (`cd_telefonia_plano`) REFERENCES `telefonia_plano` (`cd_telefonia_plano`) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=160

;

-- ----------------------------
-- Table structure for `tipo_usuario`
-- ----------------------------
DROP TABLE IF EXISTS `tipo_usuario`;
CREATE TABLE `tipo_usuario` (
`cd_tipo_usuario`  int(11) NOT NULL ,
`nome`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`status`  enum('I','A') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' ,
PRIMARY KEY (`cd_tipo_usuario`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci

;

-- ----------------------------
-- Table structure for `unidade`
-- ----------------------------
DROP TABLE IF EXISTS `unidade`;
CREATE TABLE `unidade` (
`cd_unidade`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`permissor`  smallint(6) NULL DEFAULT NULL ,
`sigla`  varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`nome`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`id_servico`  smallint(6) NULL DEFAULT NULL ,
`id_operadora`  smallint(11) NULL DEFAULT NULL ,
`id_cep_aps`  int(11) NULL DEFAULT NULL ,
`nome_servico`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`nome_pacote`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`tecnologia`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`inicio_servico`  date NULL DEFAULT NULL ,
`status`  enum('I','A') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' ,
PRIMARY KEY (`cd_unidade`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=17

;

-- ----------------------------
-- Table structure for `usuario`
-- ----------------------------
DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
`cd_usuario`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`matricula_usuario`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`nome_usuario`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`rg_usuario`  varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`cpf_usuario`  varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`email_usuario`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`login_usuario`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`senha_usuario`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`cd_departamento`  int(11) UNSIGNED NULL DEFAULT NULL ,
`cd_cargo`  int(11) UNSIGNED NULL DEFAULT NULL ,
`ramal_usuario`  char(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`acesso_usuario`  enum('N','S') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'N' ,
`cd_unidade`  int(11) UNSIGNED NULL DEFAULT NULL ,
`cd_estado`  int(11) UNSIGNED NULL DEFAULT NULL ,
`gestor_usuario`  enum('N','S') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'N' ,
`data_admissao_usuario`  date NULL DEFAULT NULL ,
`status_usuario`  enum('I','A') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' ,
`criador_usuario`  int(11) NOT NULL ,
`data_criacao_usuario`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
`atualizador_usuario`  int(11) NULL DEFAULT NULL ,
`data_atualizacao_usuario`  timestamp NULL DEFAULT NULL ,
`logado_usuario`  enum('N','S') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'N' ,
`data_logado_usuario`  timestamp NULL DEFAULT NULL ,
`data_chat_usuario`  timestamp NULL DEFAULT NULL ,
`status_chat_usuario`  enum('ONLINE','OCUPADO','OFFLINE') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'OFFLINE' ,
`tipo_usuario`  enum('USER','DP') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'USER' ,
PRIMARY KEY (`cd_usuario`),
FOREIGN KEY (`cd_cargo`) REFERENCES `cargo` (`cd_cargo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
FOREIGN KEY (`cd_departamento`) REFERENCES `departamento` (`cd_departamento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
FOREIGN KEY (`cd_unidade`) REFERENCES `unidade` (`cd_unidade`) ON DELETE NO ACTION ON UPDATE NO ACTION,
FOREIGN KEY (`cd_estado`) REFERENCES `estado` (`cd_estado`) ON DELETE NO ACTION ON UPDATE NO ACTION
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=4685

;

-- ----------------------------
-- Event structure for `evento_desloga_usuario`
-- ----------------------------
DROP EVENT IF EXISTS `evento_desloga_usuario`;
DELIMITER ;;
CREATE DEFINER=`sistemas`@`%` EVENT `evento_desloga_usuario` ON SCHEDULE EVERY '23:59' HOUR_MINUTE STARTS '2015-12-22 23:59:00' ON COMPLETION NOT PRESERVE DISABLE DO UPDATE adminti.usuario 
		SET logado_usuario = 'N', data_logado_usuario = CURRENT_TIMESTAMP()
	WHERE logado_usuario = 'S'
;;
DELIMITER ;

-- ----------------------------
-- Indexes structure for table cargo
-- ----------------------------
CREATE INDEX `idx_cd_cargo_cargo` ON `cargo`(`cd_cargo`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `cargo`
-- ----------------------------
ALTER TABLE `cargo` AUTO_INCREMENT=210;

-- ----------------------------
-- Indexes structure for table centro_custo
-- ----------------------------
CREATE INDEX `fk_cd_departamento_centro_custo` ON `centro_custo`(`cd_departamento`) USING BTREE ;
CREATE INDEX `fk_cd_unidade_centro_custo` ON `centro_custo`(`cd_unidade`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `centro_custo`
-- ----------------------------
ALTER TABLE `centro_custo` AUTO_INCREMENT=114;

-- ----------------------------
-- Indexes structure for table chat_dinamica
-- ----------------------------
CREATE UNIQUE INDEX `idx_cd_chat_dinamica` ON `chat_dinamica`(`cd_chat_dinamica`) USING BTREE ;
CREATE INDEX `idx_cd_origem_chat_dinamica` ON `chat_dinamica`(`cd_origem`) USING BTREE ;
CREATE INDEX `idx_cd_destino_chat_dinamica` ON `chat_dinamica`(`cd_destino`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `chat_dinamica`
-- ----------------------------
ALTER TABLE `chat_dinamica` AUTO_INCREMENT=40;

-- ----------------------------
-- Indexes structure for table chat_favoritos
-- ----------------------------
CREATE INDEX `idx_cd_usuario_chat_favoritos` ON `chat_favoritos`(`cd_usuario`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `chat_favoritos`
-- ----------------------------
ALTER TABLE `chat_favoritos` AUTO_INCREMENT=2265;

-- ----------------------------
-- Indexes structure for table chat_lidas
-- ----------------------------
CREATE UNIQUE INDEX `idx_cd_chat_lidas` ON `chat_lidas`(`cd_chat_lidas`) USING BTREE ;
CREATE INDEX `fk_cd_chat_msg_chat_lidas` ON `chat_lidas`(`cd_chat_msg`) USING BTREE ;
CREATE INDEX `fk_cd_usuario_chat_lidas` ON `chat_lidas`(`cd_usuario`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `chat_lidas`
-- ----------------------------
ALTER TABLE `chat_lidas` AUTO_INCREMENT=440;

-- ----------------------------
-- Indexes structure for table chat_msg
-- ----------------------------
CREATE INDEX `idx_cd_origem_chat_msg` ON `chat_msg`(`cd_origem`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `chat_msg`
-- ----------------------------
ALTER TABLE `chat_msg` AUTO_INCREMENT=441;

-- ----------------------------
-- Auto increment value for `departamento`
-- ----------------------------
ALTER TABLE `departamento` AUTO_INCREMENT=56;

-- ----------------------------
-- Indexes structure for table estado
-- ----------------------------
CREATE INDEX `idx_cd_estado` ON `estado`(`cd_estado`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `estado`
-- ----------------------------
ALTER TABLE `estado` AUTO_INCREMENT=29;

-- ----------------------------
-- Indexes structure for table log
-- ----------------------------
CREATE INDEX `fk_cd_usuario_log` ON `log`(`cd_usuario`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `log`
-- ----------------------------
ALTER TABLE `log` AUTO_INCREMENT=1964;

-- ----------------------------
-- Indexes structure for table log_arquivo
-- ----------------------------
CREATE UNIQUE INDEX `idx_cd_log_arquivo` ON `log_arquivo`(`cd_log_arquivo`) USING BTREE ;
CREATE INDEX `idx_fonte` ON `log_arquivo`(`fonte`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `log_arquivo`
-- ----------------------------
ALTER TABLE `log_arquivo` AUTO_INCREMENT=558;

-- ----------------------------
-- Indexes structure for table log_chat_msg
-- ----------------------------
CREATE INDEX `fk_cd_chat_msg_log_chat_msg` ON `log_chat_msg`(`cd_chat_msg`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `log_chat_msg`
-- ----------------------------
ALTER TABLE `log_chat_msg` AUTO_INCREMENT=107;

-- ----------------------------
-- Auto increment value for `log_tarefa_agendada`
-- ----------------------------
ALTER TABLE `log_tarefa_agendada` AUTO_INCREMENT=392;

-- ----------------------------
-- Auto increment value for `menu`
-- ----------------------------
ALTER TABLE `menu` AUTO_INCREMENT=19;

-- ----------------------------
-- Auto increment value for `operadora`
-- ----------------------------
ALTER TABLE `operadora` AUTO_INCREMENT=5;

-- ----------------------------
-- Auto increment value for `telefonia_acessorio`
-- ----------------------------
ALTER TABLE `telefonia_acessorio` AUTO_INCREMENT=10;

-- ----------------------------
-- Indexes structure for table telefonia_aparelho
-- ----------------------------
CREATE INDEX `idx_cd_telefonia_aparelho` ON `telefonia_aparelho`(`cd_telefonia_aparelho`) USING BTREE ;
CREATE INDEX `fk_cd_telefonia_marca_telefonia_aparelho` ON `telefonia_aparelho`(`cd_telefonia_marca`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `telefonia_aparelho`
-- ----------------------------
ALTER TABLE `telefonia_aparelho` AUTO_INCREMENT=599;

-- ----------------------------
-- Indexes structure for table telefonia_cdr
-- ----------------------------
CREATE INDEX `dst` ON `telefonia_cdr`(`dst`) ;
CREATE INDEX `accountcode` ON `telefonia_cdr`(`accountcode`) ;
CREATE INDEX `idx_id` ON `telefonia_cdr`(`id`) ;

-- ----------------------------
-- Auto increment value for `telefonia_cdr`
-- ----------------------------
ALTER TABLE `telefonia_cdr` AUTO_INCREMENT=0;

-- ----------------------------
-- Indexes structure for table telefonia_chamadas
-- ----------------------------
CREATE INDEX `fk_cd_arquivo_log_telefonia_chamadas` ON `telefonia_chamadas`(`cd_log_arquivo`) USING BTREE ;
CREATE INDEX `idx_fim_telefonia_chamadas` ON `telefonia_chamadas`(`fim`) USING BTREE ;
CREATE INDEX `idx_tipo_telefonia_chamadas` ON `telefonia_chamadas`(`tipo`) USING BTREE ;
CREATE INDEX `idx_font_telefonia_chamadas` ON `telefonia_chamadas`(`font`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `telefonia_chamadas`
-- ----------------------------
ALTER TABLE `telefonia_chamadas` AUTO_INCREMENT=1264898;

-- ----------------------------
-- Auto increment value for `telefonia_ddd`
-- ----------------------------
ALTER TABLE `telefonia_ddd` AUTO_INCREMENT=68;

-- ----------------------------
-- Indexes structure for table telefonia_emprestimo
-- ----------------------------
CREATE INDEX `idx_cd_telefonia_emprestimo_telefonia_emprestimo` ON `telefonia_emprestimo`(`cd_telefonia_emprestimo`) USING BTREE ;
CREATE INDEX `fk_cd_telefonia_aparelho_telefonia_emprestimo` ON `telefonia_emprestimo`(`cd_telefonia_aparelho`) USING BTREE ;
CREATE INDEX `fk_cd_usuario_telefonia_emprestimo` ON `telefonia_emprestimo`(`cd_usuario`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `telefonia_emprestimo`
-- ----------------------------
ALTER TABLE `telefonia_emprestimo` AUTO_INCREMENT=355;

-- ----------------------------
-- Indexes structure for table telefonia_emprestimo_acessorio
-- ----------------------------
CREATE INDEX `fk_cd_telefonia_emprestimo_telefonia_emprestimo_acessorio` ON `telefonia_emprestimo_acessorio`(`cd_telefonia_emprestimo`) USING BTREE ;
CREATE INDEX `fk_cd_telefonia_acessorio_telefonia_emprestimo_acessorio` ON `telefonia_emprestimo_acessorio`(`cd_telefonia_acessorio`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `telefonia_emprestimo_acessorio`
-- ----------------------------
ALTER TABLE `telefonia_emprestimo_acessorio` AUTO_INCREMENT=128;

-- ----------------------------
-- Indexes structure for table telefonia_emprestimo_linha
-- ----------------------------
CREATE INDEX `fk_cd_telefonia_linha_telefonia_emprestimo_linha` ON `telefonia_emprestimo_linha`(`cd_telefonia_linha`) USING BTREE ;
CREATE INDEX `fk_cd_telefonia_emprestimo_telefonia_emprestimo_linha` ON `telefonia_emprestimo_linha`(`cd_telefonia_emprestimo`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `telefonia_emprestimo_linha`
-- ----------------------------
ALTER TABLE `telefonia_emprestimo_linha` AUTO_INCREMENT=580;

-- ----------------------------
-- Auto increment value for `telefonia_fatura`
-- ----------------------------
ALTER TABLE `telefonia_fatura` AUTO_INCREMENT=1;

-- ----------------------------
-- Indexes structure for table telefonia_febraban
-- ----------------------------
CREATE INDEX `fk_cd_log_arquivo_telefonia_febraban` ON `telefonia_febraban`(`cd_log_arquivo`) USING BTREE ;
CREATE INDEX `idx_numero_telefonia_febraban` ON `telefonia_febraban`(`telefone`) USING BTREE ;
CREATE INDEX `idx_ddd_telefonia_febraban` ON `telefonia_febraban`(`ddd`) USING BTREE ;
CREATE INDEX `idx_dt_emissao_telefonia_febraban` ON `telefonia_febraban`(`dt_emissao`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `telefonia_febraban`
-- ----------------------------
ALTER TABLE `telefonia_febraban` AUTO_INCREMENT=582889;

-- ----------------------------
-- Indexes structure for table telefonia_imei
-- ----------------------------
CREATE UNIQUE INDEX `imei` ON `telefonia_imei`(`imei`) USING BTREE ;
CREATE INDEX `fk_cd_telefonia_aparelho_telefonia_imei` ON `telefonia_imei`(`cd_telefonia_aparelho`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table telefonia_interface
-- ----------------------------
CREATE INDEX `idx_cd_telefonia_interface_telefonia_interface` ON `telefonia_interface`(`cd_telefonia_interface`) USING BTREE ;
CREATE INDEX `fk_cd_departamento_telefonia_interface` ON `telefonia_interface`(`cd_departamento`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `telefonia_interface`
-- ----------------------------
ALTER TABLE `telefonia_interface` AUTO_INCREMENT=1;

-- ----------------------------
-- Indexes structure for table telefonia_linha
-- ----------------------------
CREATE INDEX `idx_cd_telefonia_linha` ON `telefonia_linha`(`cd_telefonia_linha`) USING BTREE ;
CREATE INDEX `fk_cd_departamento_telefonia_linha` ON `telefonia_linha`(`cd_departamento`) USING BTREE ;
CREATE INDEX `fk_cd_telefonia_operadora_telefonia_linha` ON `telefonia_linha`(`cd_telefonia_operadora`) USING BTREE ;
CREATE INDEX `fk_cd_telefonia_plano_telefonia_linha` ON `telefonia_linha`(`cd_telefonia_plano`) USING BTREE ;
CREATE INDEX `fk_cd_telefonia_ddd_telefonia_linha` ON `telefonia_linha`(`cd_telefonia_ddd`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `telefonia_linha`
-- ----------------------------
ALTER TABLE `telefonia_linha` AUTO_INCREMENT=839;

-- ----------------------------
-- Indexes structure for table telefonia_linha_servico
-- ----------------------------
CREATE INDEX `fk_cd_telefonia_linha_telefonia_linha_servico` ON `telefonia_linha_servico`(`cd_telefonia_linha`) USING BTREE ;
CREATE INDEX `fk_cd_telefonia_servico_telefonia_linha_servico` ON `telefonia_linha_servico`(`cd_telefonia_servico`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `telefonia_linha_servico`
-- ----------------------------
ALTER TABLE `telefonia_linha_servico` AUTO_INCREMENT=3123;

-- ----------------------------
-- Indexes structure for table telefonia_marca
-- ----------------------------
CREATE INDEX `idx_cd_telefonia_marca_telefonia_marca` ON `telefonia_marca`(`cd_telefonia_marca`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `telefonia_marca`
-- ----------------------------
ALTER TABLE `telefonia_marca` AUTO_INCREMENT=61;

-- ----------------------------
-- Indexes structure for table telefonia_oferta
-- ----------------------------
CREATE INDEX `idx_cd_telefonia_oferta_telefonia_oferta` ON `telefonia_oferta`(`cd_telefonia_oferta`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `telefonia_oferta`
-- ----------------------------
ALTER TABLE `telefonia_oferta` AUTO_INCREMENT=4;

-- ----------------------------
-- Indexes structure for table telefonia_operadora
-- ----------------------------
CREATE INDEX `idx_cd_telefonia_operadora` ON `telefonia_operadora`(`cd_telefonia_operadora`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `telefonia_operadora`
-- ----------------------------
ALTER TABLE `telefonia_operadora` AUTO_INCREMENT=10;

-- ----------------------------
-- Indexes structure for table telefonia_plano
-- ----------------------------
CREATE INDEX `fk_cd_telefonia_operadora_telefonia_plano` ON `telefonia_plano`(`cd_telefonia_operadora`) USING BTREE ;
CREATE INDEX `idx_cd_telefonia_plano` ON `telefonia_plano`(`cd_telefonia_plano`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `telefonia_plano`
-- ----------------------------
ALTER TABLE `telefonia_plano` AUTO_INCREMENT=8;

-- ----------------------------
-- Auto increment value for `telefonia_ramal`
-- ----------------------------
ALTER TABLE `telefonia_ramal` AUTO_INCREMENT=57;

-- ----------------------------
-- Indexes structure for table telefonia_ramal_usuario
-- ----------------------------
CREATE INDEX `fk_cd_usuario` ON `telefonia_ramal_usuario`(`cd_usuario`) USING BTREE ;
CREATE INDEX `fk_cd_telefonia_ramal` ON `telefonia_ramal_usuario`(`cd_telefonia_ramal`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `telefonia_ramal_usuario`
-- ----------------------------
ALTER TABLE `telefonia_ramal_usuario` AUTO_INCREMENT=75;

-- ----------------------------
-- Auto increment value for `telefonia_servico`
-- ----------------------------
ALTER TABLE `telefonia_servico` AUTO_INCREMENT=28;

-- ----------------------------
-- Indexes structure for table telefonia_tarifa
-- ----------------------------
CREATE INDEX `fk_cd_telefonia_plano_telefonia_tarifa` ON `telefonia_tarifa`(`cd_telefonia_plano`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `telefonia_tarifa`
-- ----------------------------
ALTER TABLE `telefonia_tarifa` AUTO_INCREMENT=160;

-- ----------------------------
-- Indexes structure for table tipo_usuario
-- ----------------------------
CREATE INDEX `idx_cd_tipo_usuario_tipo_usuario` ON `tipo_usuario`(`cd_tipo_usuario`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `unidade`
-- ----------------------------
ALTER TABLE `unidade` AUTO_INCREMENT=17;

-- ----------------------------
-- Indexes structure for table usuario
-- ----------------------------
CREATE UNIQUE INDEX `idx_login_usuario` ON `usuario`(`login_usuario`) USING BTREE ;
CREATE INDEX `fk_cd_departamento` ON `usuario`(`cd_departamento`) USING BTREE ;
CREATE INDEX `fk_usuario_cd_estado` ON `usuario`(`cd_estado`) USING BTREE ;
CREATE INDEX `fk_cd_unidade_usuario` ON `usuario`(`cd_unidade`) USING BTREE ;
CREATE INDEX `fk_cd_cargo_usuario` ON `usuario`(`cd_cargo`) USING BTREE ;
CREATE INDEX `idx_email_usuario` ON `usuario`(`email_usuario`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `usuario`
-- ----------------------------
ALTER TABLE `usuario` AUTO_INCREMENT=4685;
