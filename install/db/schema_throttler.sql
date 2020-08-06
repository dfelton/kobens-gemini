
--
-- TODO: This (schema) originates from kobens-core and doesn't belong in the kobens-gemini.
-- Note: Can optionally put throttler into it's own database if desired. See env/config.sample.xml
--
DROP TABLE IF EXISTS `throttler`;
CREATE TABLE `throttler` (
     `id` VARCHAR(255) NOT NULL COMMENT 'Key',
     `max` INT(10) UNSIGNED NOT NULL COMMENT 'Limit',
     `count` INT(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Count',
     `time` INT(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Time',
     PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Throttler';

INSERT INTO `throttler` (`id`,`max`)
VALUES
  ('api.sandbox.gemini.com::public',  2),
  ('api.sandbox.gemini.com::private', 10),
  ('api.gemini.com::public',  2),
  ('api.gemini.com::private', 10)
;


