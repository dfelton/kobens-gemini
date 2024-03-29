
CREATE TABLE IF NOT EXISTS `taxes_lqtyusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - LQTYUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_lqtyusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - LQTYUSD Sell Log';

CREATE TABLE IF NOT EXISTS `taxes_mcusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - MCUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_mcusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - MCUSD Sell Log';

CREATE TABLE IF NOT EXISTS `taxes_mplusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - MPLUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_mplusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - MPLUSD Sell Log';

CREATE TABLE IF NOT EXISTS `taxes_plausd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - PLAUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_plausd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - PLAUSD Sell Log';

CREATE TABLE IF NOT EXISTS `taxes_rlyusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - RLYUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_rlyusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - RLYUSD Sell Log';

CREATE TABLE IF NOT EXISTS `taxes_samousd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - SAMOUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_samousd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - SAMOUSD Sell Log';

CREATE TABLE IF NOT EXISTS `taxes_spellusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - SPELLUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_spellusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - SPELLUSD Sell Log';

CREATE TABLE IF NOT EXISTS `taxes_qrdousd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - QRDOUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_qrdousd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - QRDOUSD Sell Log';

-- CREATE TABLE IF NOT EXISTS `taxes_bicousd_buy_log` (
--    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
--   `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
--    PRIMARY KEY (`tid`)
-- ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - BICOUSD Buy Log';

-- CREATE TABLE IF NOT EXISTS `taxes_bicousd_sell_log` (
--    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
--    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
--    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
--    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
--    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
--    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
--    PRIMARY KEY (`sell_tid`, `buy_tid`)
-- ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - BICOUSD Sell Log';

-- CREATE TABLE IF NOT EXISTS `taxes_dpiusd_buy_log` (
--    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
--    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
--    PRIMARY KEY (`tid`)
-- ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - DPIUSD Buy Log';

-- CREATE TABLE IF NOT EXISTS `taxes_dpiusd_sell_log` (
--    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
--    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
--    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
--    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
--    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
--    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
--     PRIMARY KEY (`sell_tid`, `buy_tid`)
-- ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - DPIUSD Sell Log';

CREATE TABLE IF NOT EXISTS `taxes_ernusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ERNUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_ernusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ERNUSD Sell Log';

CREATE TABLE IF NOT EXISTS `taxes_eulusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - EULUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_eulusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - EULUSD Sell Log';

-- CREATE TABLE IF NOT EXISTS `taxes_fidausd_buy_log` (
--    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
--    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
--    PRIMARY KEY (`tid`)
-- ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - FIDAUSD Buy Log';

-- CREATE TABLE IF NOT EXISTS `taxes_fidausd_sell_log` (
--    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
--    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
--    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
--    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
--    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
--    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
--    PRIMARY KEY (`sell_tid`, `buy_tid`)
-- ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - FIDAUSD Sell Log';

CREATE TABLE IF NOT EXISTS `taxes_fraxusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - FRAXUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_fraxusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - FRAXUSD Sell Log';

CREATE TABLE IF NOT EXISTS `taxes_fxsusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - FXSUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_fxsusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - FXSUSD Sell Log';

CREATE TABLE IF NOT EXISTS `taxes_galusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - GALUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_galusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - GALUSD Sell Log';

CREATE TABLE IF NOT EXISTS `taxes_indexusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - INDEXUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_indexusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - INDEXUSD Sell Log';

CREATE TABLE IF NOT EXISTS `taxes_iotxusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - IOTXUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_iotxusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - IOTXUSD Sell Log';

CREATE TABLE IF NOT EXISTS `taxes_kp3rusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - KP3RUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_kp3rusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - KP3RUSD Sell Log';

CREATE TABLE IF NOT EXISTS `taxes_metisusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - METISUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_metisusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - METISUSD Sell Log';

-- CREATE TABLE IF NOT EXISTS `taxes_mimusd_buy_log` (
--    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
--    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
--    PRIMARY KEY (`tid`)
-- ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - MIMUSD Buy Log';

-- CREATE TABLE IF NOT EXISTS `taxes_mimusd_sell_log` (
--    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
--    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
--    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
--    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
--    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
--    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
--    PRIMARY KEY (`sell_tid`, `buy_tid`)
-- ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - MIMUSD Sell Log';

CREATE TABLE IF NOT EXISTS `taxes_orcausd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ORCAUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_orcausd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ORCAUSD Sell Log';

CREATE TABLE IF NOT EXISTS `taxes_rayusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - RAYUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_rayusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - RAYUSD Sell Log';

CREATE TABLE IF NOT EXISTS `taxes_rbnusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - RBNUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_rbnusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - RBNUSD Sell Log';

-- CREATE TABLE IF NOT EXISTS `taxes_revvusd_buy_log` (
--    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
--    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
--    PRIMARY KEY (`tid`)
-- ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - REVVUSD Buy Log';

-- CREATE TABLE IF NOT EXISTS `taxes_revvusd_sell_log` (
--    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
--    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
--    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
--    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
--    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
--    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
--    PRIMARY KEY (`sell_tid`, `buy_tid`)
-- ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - REVVUSD Sell Log';

-- CREATE TABLE IF NOT EXISTS `taxes_sbrusd_buy_log` (
--    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
--    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
--    PRIMARY KEY (`tid`)
-- ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - SBRUSD Buy Log';

-- CREATE TABLE IF NOT EXISTS `taxes_sbrusd_sell_log` (
--    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
--    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
--    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
--    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
--    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
--    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
--    PRIMARY KEY (`sell_tid`, `buy_tid`)
-- ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - SBRUSD Sell Log';

CREATE TABLE IF NOT EXISTS `taxes_tokeusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - TOKEUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_tokeusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - TOKEUSD Sell Log';

-- CREATE TABLE IF NOT EXISTS `taxes_truusd_buy_log` (
--    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
--    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
--    PRIMARY KEY (`tid`)
-- ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - TRUUSD Buy Log';

-- CREATE TABLE IF NOT EXISTS `taxes_truusd_sell_log` (
--    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
--    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
--    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
--    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
--    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
--    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
--    PRIMARY KEY (`sell_tid`, `buy_tid`)
-- ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - TRUUSD Sell Log';

CREATE TABLE IF NOT EXISTS `taxes_zbcusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ZBCUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_zbcusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ZBCUSD Sell Log';

CREATE TABLE IF NOT EXISTS `taxes_jamusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - JAMUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_jamusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - JAMUSD Sell Log';

CREATE TABLE IF NOT EXISTS `taxes_imxusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - IMXUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_imxusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - IMXUSD Sell Log';

CREATE TABLE IF NOT EXISTS `taxes_gmtusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - GTMUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_gmtusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - GTMUSD Sell Log';

CREATE TABLE IF NOT EXISTS `taxes_gfiusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - GFIUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_gfiusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - GFIUSD Sell Log';

CREATE TABLE IF NOT EXISTS `taxes_ensusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ENSUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_ensusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ENSUSD Sell Log';

CREATE TABLE IF NOT EXISTS `taxes_cvcusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - CVCUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_cvcusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - CVCUSD Sell Log';

CREATE TABLE IF NOT EXISTS `taxes_avaxusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - AVAXUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_avaxusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - AVAXUSD Sell Log';

CREATE TABLE IF NOT EXISTS `taxes_chzusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - CHZUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_chzusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - CHZUSD Sell Log';

CREATE TABLE IF NOT EXISTS `taxes_dotusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - DOTUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_dotusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - DOTUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_elonusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ELONUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_elonusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ELONUSD Sell Log';

CREATE TABLE IF NOT EXISTS `taxes_galausd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - GALAUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_galausd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - GALAUSD Sell Log';

CREATE TABLE IF NOT EXISTS `taxes_solusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - SOLUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_solusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - SOLUSD Sell Log';

CREATE TABLE IF NOT EXISTS `taxes_rndrusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - RNDRUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_rndrusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - RNDRUSD Sell Log';

CREATE TABLE IF NOT EXISTS `taxes_ldousd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - LDOUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_ldousd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - LDOUSD Sell Log';

CREATE TABLE IF NOT EXISTS `taxes_apeusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - APEUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_apeusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - APEUSD Sell Log';

CREATE TABLE IF NOT EXISTS `taxes_aliusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ALIUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_aliusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ALIUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_atomusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ATOMUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_atomusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ATOMUSD Sell Log';



CREATE TABLE IF NOT EXISTS `taxes_1inchusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - 1INCHUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_1inchusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - 1INCHUSD Sell Log';




CREATE TABLE IF NOT EXISTS `taxes_aaveusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - AAVEUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_aaveusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - AAVEUSD Sell Log';



CREATE TABLE IF NOT EXISTS `taxes_ashusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ASHUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_ashusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ASHUSD Sell Log';




CREATE TABLE IF NOT EXISTS `taxes_alcxusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ALCXUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_alcxusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ALCXUSD Sell Log';



CREATE TABLE IF NOT EXISTS `taxes_ampusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - AMPUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_ampusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - AMPUSD Sell Log';



CREATE TABLE IF NOT EXISTS `taxes_ankrusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ANKRUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_ankrusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ANKRUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_api3usd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - API3USD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_api3usd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - API3USD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_audiousd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - AUDIOUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_audiousd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - AUDIOUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_axsusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - AXSUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_axsusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - AXSUSD Sell Log';



CREATE TABLE IF NOT EXISTS `taxes_balusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - BALUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_balusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - BALUSD Sell Log';



CREATE TABLE IF NOT EXISTS `taxes_batusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - BATUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_batusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - BATUSD Sell Log';



CREATE TABLE IF NOT EXISTS `taxes_bchusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - BCHUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_bchusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - BCHUSD Sell Log';



CREATE TABLE IF NOT EXISTS `taxes_bntusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - BNTUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_bntusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - BNTUSD Sell Log';



CREATE TABLE IF NOT EXISTS `taxes_bondusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - BONDUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_bondusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - BONDUSD Sell Log';



CREATE TABLE IF NOT EXISTS `taxes_btcusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - BTCUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_btcusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - BTCUSD Sell Log';



CREATE TABLE IF NOT EXISTS `taxes_compusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - COMPUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_compusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - COMPUSD Sell Log';



CREATE TABLE IF NOT EXISTS `taxes_crvusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - CRVUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_crvusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - CRVUSD Sell Log';




CREATE TABLE IF NOT EXISTS `taxes_ctxusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - CTXUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_ctxusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - CTXUSD Sell Log';




CREATE TABLE IF NOT EXISTS `taxes_cubeusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - CUBEUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_cubeusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - CUBEUSD Sell Log';




CREATE TABLE IF NOT EXISTS `taxes_daiusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - DAIUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_daiusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - DAIUSD Sell Log';




CREATE TABLE IF NOT EXISTS `taxes_dogeusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - DOGEUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_dogeusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - DOGEUSD Sell Log';




CREATE TABLE IF NOT EXISTS `taxes_enjusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ENJUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_enjusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ENJUSD Sell Log';



CREATE TABLE IF NOT EXISTS `taxes_ethusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ETHUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_ethusd_sell_log` (
        `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
        `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
        `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
        `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
        `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
        `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ETHUSD Sell Log';



CREATE TABLE IF NOT EXISTS `taxes_fetusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - FETUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_fetusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - FETUSD Sell Log';



CREATE TABLE IF NOT EXISTS `taxes_filusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - FILUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_filusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - FILUSD Sell Log';



CREATE TABLE IF NOT EXISTS `taxes_ftmusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - FTMUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_ftmusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - FTMUSD Sell Log';



CREATE TABLE IF NOT EXISTS `taxes_grtusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - GRTUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_grtusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - GRTUSD Sell Log';



CREATE TABLE IF NOT EXISTS `taxes_injusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - INJUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_injusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - INJUSD Sell Log';



CREATE TABLE IF NOT EXISTS `taxes_kncusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - KNCUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_kncusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - KNCUSD Sell Log';



CREATE TABLE IF NOT EXISTS `taxes_linkusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - LINKUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_linkusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - LINKUSD Sell Log';




CREATE TABLE IF NOT EXISTS `taxes_lptusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - LPTUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_lptusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - LPTUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_lunausd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - LUNAUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_lunausd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - LUNAUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_lrcusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - LRCUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_lrcusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - LRCUSD Sell Log';




CREATE TABLE IF NOT EXISTS `taxes_ltcusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - LTCUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_ltcusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - LTCUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_manausd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - MANAUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_manausd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - MANAUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_maskusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - MASKUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_maskusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - MASKUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_maticusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - MATICUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_maticusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - MATICUSD Sell Log';



CREATE TABLE IF NOT EXISTS `taxes_mco2usd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - MCO2USD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_mco2usd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - MCO2USD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_mirusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - MIRUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_mirusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - MIRUSD Sell Log';




CREATE TABLE IF NOT EXISTS `taxes_mkrusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - MKRUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_mkrusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - MKRUSD Sell Log';



CREATE TABLE IF NOT EXISTS `taxes_nmrusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - NMRUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_nmrusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - NMRUSD Sell Log';



CREATE TABLE IF NOT EXISTS `taxes_oxtusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - OXTUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_oxtusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - OXTUSD Sell Log';





CREATE TABLE IF NOT EXISTS `taxes_paxgusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - PAXGUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_paxgusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - PAXGUSD Sell Log';



CREATE TABLE IF NOT EXISTS `taxes_qntusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - QNTUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_qntusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - QNTUSD Sell Log';



CREATE TABLE IF NOT EXISTS `taxes_radusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - RADUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_radusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - RADUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_rareusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - RAREUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_rareusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - RAREUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_renusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - RENUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_renusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - RENUSD Sell Log';





CREATE TABLE IF NOT EXISTS `taxes_sandusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - SANDUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_sandusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - SANDUSD Sell Log';



CREATE TABLE IF NOT EXISTS `taxes_shibusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - SHIBUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_shibusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - SHIBUSD Sell Log';



CREATE TABLE IF NOT EXISTS `taxes_sklusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - SKLUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_sklusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - SKLUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_slpusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - SLPUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_slpusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - SLPUSD Sell Log';



CREATE TABLE IF NOT EXISTS `taxes_snxusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - SNXUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_snxusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - SNXUSD Sell Log';





CREATE TABLE IF NOT EXISTS `taxes_storjusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - STORJUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_storjusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - STORJUSD Sell Log';



CREATE TABLE IF NOT EXISTS `taxes_sushiusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - SUSHIUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_sushiusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - SUSHIUSD Sell Log';



CREATE TABLE IF NOT EXISTS `taxes_umausd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - UMAUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_umausd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - UMAUSD Sell Log';




CREATE TABLE IF NOT EXISTS `taxes_uniusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - UNIUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_uniusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - UNIUSD Sell Log';



CREATE TABLE IF NOT EXISTS `taxes_ustusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - USTUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_ustusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - USTUSD Sell Log';



CREATE TABLE IF NOT EXISTS `taxes_wcfgusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - WCFGUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_wcfgusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - WCFGUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_xtzusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - XTZUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_xtzusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - XTZUSD Sell Log';



CREATE TABLE IF NOT EXISTS `taxes_yfiusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - YFIUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_yfiusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - YFIUSD Sell Log';





CREATE TABLE IF NOT EXISTS `taxes_zecusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ZECUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_zecusd_sell_log` (
        `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
        `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
        `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
        `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
        `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
        `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ZECUSD Sell Log';





CREATE TABLE IF NOT EXISTS `taxes_zrxusd_buy_log` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ZRXUSD Buy Log';

CREATE TABLE IF NOT EXISTS `taxes_zrxusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ZRXUSD Sell Log';

-- CREATE TABLE IF NOT EXISTS `taxes_form8949` (
--    `id` BIGINT(13) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
--    `tid` BIGINT(10) UNSIGNED NOT NULL COMMENT 'Transaction ID',
--    `description` VARCHAR(50) NOT NULL COMMENT 'Description of Property',
--    `date_acquired` TIMESTAMP NOT NULL COMMENT 'Date Acquired',
--    `date_sold` TIMESTAMP NOT NULL COMMENT 'Date Sold',
--    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
--    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
--    `gain_or_loss` VARCHAR(50) NOT NULL COMMENT 'Gain or (loss)',
--    `created_at` TIMESTAMP(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) COMMENT 'Created At',
--    PRIMARY KEY (`id`)
-- ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - Form 8949';
