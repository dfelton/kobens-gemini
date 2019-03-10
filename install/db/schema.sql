-- Statuses:
--   NEW
--   BUY_PLACED
--   BUY_FILLED
--   SELL_PLACED
--   COMPLETE
--   CANCELLED

CREATE TABLE `trade_repeater` (
  `trade_repeater_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Trade Repeater Id',
  `exchange` varchar(25) NOT NULL COMMENT 'Exchange',
  `symbol` varchar(12) NOT NULL COMMENT 'Symbol',
  `status` varchar(24) NOT NULL DEFAULT 'NEW' COMMENT 'Status',
  `auto_buy` tinyint(1)  NOT NULL DEFAULT 0 COMMENT 'Auto Buy',
  `auto_sell` tinyint(1)  NOT NULL DEFAULT 0 COMMENT 'Auto Sell',
  `order_id` int(10) unsigned COMMENT 'Order Id',
  `buy_quote_price` varchar(50) NOT NULL COMMENT 'Buy Quote Price',
  `buy_base_amount` varchar(50) NOT NULL COMMENT 'Buy Base Amount',
  `sell_quote_price` varchar(50) NOT NULL COMMENT 'Sell Quote Price',
  `sell_base_amount` varchar(50) NOT NULL COMMENT 'Sell Base Amount',
  `completions` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'Completions', 
  PRIMARY KEY (`trade_repeater_id`),
  KEY `IDX_ORDER_ID` (`order_id`),
  KEY `IDX_STATUS` (`status`),
  KEY `IDX_AUTO_BUY` (`auto_buy`),
  KEY `IDX_AUTO_SELL` (`auto_sell`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Trade Repeater'