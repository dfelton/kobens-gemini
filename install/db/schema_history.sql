CREATE TABLE IF NOT EXISTS `trade_history_mcusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History MCUSD';

CREATE TABLE IF NOT EXISTS `trade_history_mplusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History MPLUSD';

CREATE TABLE IF NOT EXISTS `trade_history_plausd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History PLAUSD';

CREATE TABLE IF NOT EXISTS `trade_history_rlyusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History RLYUSD';

CREATE TABLE IF NOT EXISTS `trade_history_samousd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History SAMOUSD';

CREATE TABLE IF NOT EXISTS `trade_history_spellusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History SPELLUSD';

CREATE TABLE IF NOT EXISTS `trade_history_qrdousd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History QRDOUSD';

CREATE TABLE IF NOT EXISTS `trade_history_bicousd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History BICOUSD';

CREATE TABLE IF NOT EXISTS `trade_history_dpiusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History DPIUSD';

CREATE TABLE IF NOT EXISTS `trade_history_ernusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History ERNUSD';

CREATE TABLE IF NOT EXISTS `trade_history_eulusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History EULUSD';

CREATE TABLE IF NOT EXISTS `trade_history_fidausd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History FIDAUSD';

CREATE TABLE IF NOT EXISTS `trade_history_fraxusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History FRAXUSD';

CREATE TABLE IF NOT EXISTS `trade_history_fxsusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History FXSUSD';

CREATE TABLE IF NOT EXISTS `trade_history_galusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History GALUSD';

CREATE TABLE IF NOT EXISTS `trade_history_indexusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History INDEXUSD';

CREATE TABLE IF NOT EXISTS `trade_history_iotxusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History IOTXUSD';

CREATE TABLE IF NOT EXISTS `trade_history_kp3rusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History KP3RUSD';

CREATE TABLE IF NOT EXISTS `trade_history_metisusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History METISUSD';

CREATE TABLE IF NOT EXISTS `trade_history_mimusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History MIMUSD';

CREATE TABLE IF NOT EXISTS `trade_history_orcausd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History ORCAUSD';

CREATE TABLE IF NOT EXISTS `trade_history_rayusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History RAYUSD';

CREATE TABLE IF NOT EXISTS `trade_history_rbnusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History RBNUSD';

CREATE TABLE IF NOT EXISTS `trade_history_revvusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History REVVUSD';

CREATE TABLE IF NOT EXISTS `trade_history_sbrusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History SBRUSD';

CREATE TABLE IF NOT EXISTS `trade_history_tokeusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History TOKEUSD';

CREATE TABLE IF NOT EXISTS `trade_history_truusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History TRUUSD';

CREATE TABLE IF NOT EXISTS `trade_history_zbcusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History ZBCUSD';


CREATE TABLE IF NOT EXISTS `trade_history_jamusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History JAMUSD';

CREATE TABLE IF NOT EXISTS `trade_history_imxusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History IMXUSD';

CREATE TABLE IF NOT EXISTS `trade_history_gmtusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History GMTUSD';

CREATE TABLE IF NOT EXISTS `trade_history_gfiusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History GFIUSD';

CREATE TABLE IF NOT EXISTS `trade_history_ensusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History ENSUSD';

CREATE TABLE IF NOT EXISTS `trade_history_cvcusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History CVCUSD';

CREATE TABLE IF NOT EXISTS `trade_history_avaxusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History AVAXUSD';

CREATE TABLE IF NOT EXISTS `trade_history_chzusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History CHZUSD';

CREATE TABLE IF NOT EXISTS `trade_history_dotusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History DOTUSD';

CREATE TABLE IF NOT EXISTS `trade_history_elonusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History ELONUSD';

CREATE TABLE IF NOT EXISTS `trade_history_galausd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History GALAUSD';

CREATE TABLE IF NOT EXISTS `trade_history_solusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History SOLUSD';

CREATE TABLE IF NOT EXISTS `trade_history_rndrusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History RNDRUSD';

CREATE TABLE IF NOT EXISTS `trade_history_ldousd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History LDOUSD';

CREATE TABLE IF NOT EXISTS `trade_history_apeusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History APEUSD';

CREATE TABLE IF NOT EXISTS `trade_history_aliusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History ALIUSD';

CREATE TABLE IF NOT EXISTS `trade_history_atomusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History ATOMUSD';


CREATE TABLE IF NOT EXISTS `trade_history_1inchusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History 1INCHUSD';

CREATE TABLE IF NOT EXISTS `trade_history_aaveusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History AAVEUSD';

CREATE TABLE IF NOT EXISTS `trade_history_alcxusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History ALCXUSD';

CREATE TABLE IF NOT EXISTS `trade_history_ampusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History AMPUSD';

CREATE TABLE IF NOT EXISTS `trade_history_ankrusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History ANKRUSD';

CREATE TABLE IF NOT EXISTS `trade_history_api3usd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History API3USD';

CREATE TABLE IF NOT EXISTS `trade_history_ashusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History ASHUSD';

CREATE TABLE IF NOT EXISTS `trade_history_audiousd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History AUDIOUSD';

CREATE TABLE IF NOT EXISTS `trade_history_axsusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History AXSUSD';

CREATE TABLE IF NOT EXISTS `trade_history_balusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History BALUSD';

CREATE TABLE IF NOT EXISTS `trade_history_batbtc` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History BATBTC';

CREATE TABLE IF NOT EXISTS `trade_history_bateth` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History BATETH';

CREATE TABLE IF NOT EXISTS `trade_history_batusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History BATUSD';

CREATE TABLE IF NOT EXISTS `trade_history_bchbtc` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History BCHBTC';

CREATE TABLE IF NOT EXISTS `trade_history_bcheth` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History BCHETH';

CREATE TABLE IF NOT EXISTS `trade_history_bchusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History BCHUSD';

CREATE TABLE IF NOT EXISTS `trade_history_bntusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History BNTUSD';

CREATE TABLE IF NOT EXISTS `trade_history_bondusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History BONDUSD';

CREATE TABLE IF NOT EXISTS `trade_history_btcdai` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History BTCDAI';

CREATE TABLE IF NOT EXISTS `trade_history_btcusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History BTCUSD';

CREATE TABLE IF NOT EXISTS `trade_history_compusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History COMPUSD';

CREATE TABLE IF NOT EXISTS `trade_history_crvusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History CRVUSD';

CREATE TABLE IF NOT EXISTS `trade_history_ctxusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History CTXUSD';

CREATE TABLE IF NOT EXISTS `trade_history_cubeusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History CUBEUSD';

CREATE TABLE IF NOT EXISTS `trade_history_daiusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History DAIUSD';

CREATE TABLE IF NOT EXISTS `trade_history_dogeusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History DOGEUSD';

CREATE TABLE IF NOT EXISTS `trade_history_enjusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History ENJUSD';

CREATE TABLE IF NOT EXISTS `trade_history_ethbtc` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History ETHBTC';

CREATE TABLE IF NOT EXISTS `trade_history_ethdai` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History ETHDAI';

CREATE TABLE IF NOT EXISTS `trade_history_ethusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History ETHUSD';

CREATE TABLE IF NOT EXISTS `trade_history_fetusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History FETUSD';

CREATE TABLE IF NOT EXISTS `trade_history_filusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History FILUSD';

CREATE TABLE IF NOT EXISTS `trade_history_ftmusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History FTMUSD';

CREATE TABLE IF NOT EXISTS `trade_history_grtusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History GRTUSD';

CREATE TABLE IF NOT EXISTS `trade_history_injusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History INJUSD';

CREATE TABLE IF NOT EXISTS `trade_history_kncusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History KNCUSD';

CREATE TABLE IF NOT EXISTS `trade_history_linkbtc` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History LINKBTC';

CREATE TABLE IF NOT EXISTS `trade_history_linketh` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History LINKETH';

CREATE TABLE IF NOT EXISTS `trade_history_linkusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History LINKUSD';

CREATE TABLE IF NOT EXISTS `trade_history_lptusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History LPTUSD';

CREATE TABLE IF NOT EXISTS `trade_history_lrcusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History LRCUSD';

CREATE TABLE IF NOT EXISTS `trade_history_ltcbch` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History LTCBCH';

CREATE TABLE IF NOT EXISTS `trade_history_ltcbtc` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History LTCBTC';

CREATE TABLE IF NOT EXISTS `trade_history_ltceth` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History LTCETH';

CREATE TABLE IF NOT EXISTS `trade_history_ltcusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History LTCUSD';

CREATE TABLE IF NOT EXISTS `trade_history_lunausd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History LUNAUSD';

CREATE TABLE IF NOT EXISTS `trade_history_manausd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History MANAUSD';

CREATE TABLE IF NOT EXISTS `trade_history_maskusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History MASKUSD';

CREATE TABLE IF NOT EXISTS `trade_history_maticusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History MATICUSD';

CREATE TABLE IF NOT EXISTS `trade_history_mco2usd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History MCO2USD';

CREATE TABLE IF NOT EXISTS `trade_history_mirusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History MIRUSD';

CREATE TABLE IF NOT EXISTS `trade_history_mkrusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History MKRUSD';

CREATE TABLE IF NOT EXISTS `trade_history_nmrusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History NMRUSD';

CREATE TABLE IF NOT EXISTS `trade_history_oxtbtc` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History OXTBTC';

CREATE TABLE IF NOT EXISTS `trade_history_oxteth` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History OXTETH';

CREATE TABLE IF NOT EXISTS `trade_history_oxtusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History OXTUSD';

CREATE TABLE IF NOT EXISTS `trade_history_paxgusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History PAXGUSD';

CREATE TABLE IF NOT EXISTS `trade_history_qntusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History QNTUSD';

CREATE TABLE IF NOT EXISTS `trade_history_radusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History RADUSD';

CREATE TABLE IF NOT EXISTS `trade_history_rareusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History RAREUSD';

CREATE TABLE IF NOT EXISTS `trade_history_renusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History RENUSD';

CREATE TABLE IF NOT EXISTS `trade_history_sandusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History SANDUSD';

CREATE TABLE IF NOT EXISTS `trade_history_shibusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History SHIBUSD';

CREATE TABLE IF NOT EXISTS `trade_history_sklusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History SKLUSD';

CREATE TABLE IF NOT EXISTS `trade_history_slpusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History SLPUSD';

CREATE TABLE IF NOT EXISTS `trade_history_snxusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History SNXUSD';

CREATE TABLE IF NOT EXISTS `trade_history_storjusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History STORJUSD';

CREATE TABLE IF NOT EXISTS `trade_history_sushiusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History SUSHIUSD';

CREATE TABLE IF NOT EXISTS `trade_history_umausd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History UMAUSD';

CREATE TABLE IF NOT EXISTS `trade_history_uniusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History UNIUSD';

CREATE TABLE IF NOT EXISTS `trade_history_ustusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History USTUSD';

CREATE TABLE IF NOT EXISTS `trade_history_xtzusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History XTZUSD';

CREATE TABLE IF NOT EXISTS `trade_history_wcfgusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History WCFGUSD';

CREATE TABLE IF NOT EXISTS `trade_history_yfiusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History YFIUSD';

CREATE TABLE IF NOT EXISTS `trade_history_zecbch` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History ZECBCH';

CREATE TABLE IF NOT EXISTS `trade_history_zecbtc` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History ZECBTC';

CREATE TABLE IF NOT EXISTS `trade_history_zeceth` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History ZECETH';

CREATE TABLE IF NOT EXISTS `trade_history_zecltc` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History ZECLTC';

CREATE TABLE IF NOT EXISTS `trade_history_zecusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History ZECUSD';

CREATE TABLE IF NOT EXISTS `trade_history_zrxusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History ZRXUSD';
