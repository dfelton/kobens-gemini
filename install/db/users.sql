
-- gemini_prod 
CREATE USER gemini_prod@'%' IDENTIFIED BY 'FOO_BAR';
GRANT INSERT, UPDATE, SELECT ON gemini_prod.* TO gemini_prod@'%';

-- gemini_throttler
CREATE USER gemini_throttler@'%' IDENTIFIED BY 'FOO_BAR';
GRANT SELECT ON gemini_throttler.throttler TO gemini_throttler@'%';
GRANT UPDATE (count, time) ON gemini_throttler.throttler TO gemini_throttler@'%';

