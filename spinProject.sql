/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 5.5.5-10.4.18-MariaDB : Database - database
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`database` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `database`;

/*Table structure for table `clientes` */

DROP TABLE IF EXISTS `clientes`;

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  `cpf` char(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=207 DEFAULT CHARSET=utf8 COMMENT='Tabela que registra os clientes cadastrados';

/*Table structure for table `itens_pedido` */

DROP TABLE IF EXISTS `itens_pedido`;

CREATE TABLE `itens_pedido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idPedido` int(11) DEFAULT NULL,
  `idProduto` int(11) DEFAULT NULL,
  `quantidade` int(11) DEFAULT NULL,
  `desconto` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Produto` (`idProduto`),
  KEY `Pedido` (`idPedido`),
  CONSTRAINT `Pedido` FOREIGN KEY (`idPedido`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Produto` FOREIGN KEY (`idProduto`) REFERENCES `produtos` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

/*Table structure for table `pedidos` */

DROP TABLE IF EXISTS `pedidos`;

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Campo Número Pedido',
  `idCliente` int(11) DEFAULT NULL,
  `dataPedido` datetime DEFAULT NULL,
  `idUsuario` int(11) DEFAULT NULL,
  `situacao` int(11) DEFAULT 0 COMMENT '0 - Em aberto / 1 - Pago / 2 - Cancelado',
  PRIMARY KEY (`id`),
  KEY `Cliente` (`idCliente`),
  KEY `Usuario` (`idUsuario`),
  CONSTRAINT `Cliente` FOREIGN KEY (`idCliente`) REFERENCES `clientes` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `Usuario` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

/*Table structure for table `produtos` */

DROP TABLE IF EXISTS `produtos`;

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  `codigoBarras` char(10) DEFAULT NULL,
  `valorUnitario` double DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Table structure for table `sessoes` */

DROP TABLE IF EXISTS `sessoes`;

CREATE TABLE `sessoes` (
  `userId` int(11) DEFAULT NULL,
  `hash` varchar(255) DEFAULT NULL,
  `dateTime` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabela que salva as sessões dos usuários logados.';

/*Table structure for table `usuarios` */

DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `senha` text DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `admin` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Table structure for table `viewpedidos` */

DROP TABLE IF EXISTS `viewpedidos`;

/*!50001 DROP VIEW IF EXISTS `viewpedidos` */;
/*!50001 DROP TABLE IF EXISTS `viewpedidos` */;

/*!50001 CREATE TABLE  `viewpedidos`(
 `id` int(11) ,
 `vendedor` varchar(255) ,
 `cliente` varchar(255) ,
 `data` datetime ,
 `valor` varbinary(23) ,
 `situacao` int(11) 
)*/;

/*Table structure for table `view_itens_pedido` */

DROP TABLE IF EXISTS `view_itens_pedido`;

/*!50001 DROP VIEW IF EXISTS `view_itens_pedido` */;
/*!50001 DROP TABLE IF EXISTS `view_itens_pedido` */;

/*!50001 CREATE TABLE  `view_itens_pedido`(
 `id` int(11) ,
 `idPedido` int(11) ,
 `produto` varchar(255) ,
 `valorUnitario` double ,
 `quantidade` int(11) ,
 `desconto` double 
)*/;

/*View structure for view viewpedidos */

/*!50001 DROP TABLE IF EXISTS `viewpedidos` */;
/*!50001 DROP VIEW IF EXISTS `viewpedidos` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `viewpedidos` AS select `ped`.`id` AS `id`,`usu`.`nome` AS `vendedor`,`cli`.`nome` AS `cliente`,`ped`.`dataPedido` AS `data`,(select ifnull(sum(`itens`.`quantidade` * `prod`.`valorUnitario` - `itens`.`desconto`),'00.0') from (`itens_pedido` `itens` left join `produtos` `prod` on(`itens`.`idProduto` = `prod`.`id`)) where `itens`.`idPedido` = `ped`.`id`) AS `valor`,`ped`.`situacao` AS `situacao` from ((`pedidos` `ped` left join `usuarios` `usu` on(`ped`.`idUsuario` = `usu`.`id`)) left join `clientes` `cli` on(`ped`.`idCliente` = `cli`.`id`)) */;

/*View structure for view view_itens_pedido */

/*!50001 DROP TABLE IF EXISTS `view_itens_pedido` */;
/*!50001 DROP VIEW IF EXISTS `view_itens_pedido` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_itens_pedido` AS (select `item`.`id` AS `id`,`item`.`idPedido` AS `idPedido`,`prod`.`nome` AS `produto`,`prod`.`valorUnitario` AS `valorUnitario`,`item`.`quantidade` AS `quantidade`,`item`.`desconto` AS `desconto` from (`itens_pedido` `item` left join `produtos` `prod` on(`item`.`idProduto` = `prod`.`id`))) */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
