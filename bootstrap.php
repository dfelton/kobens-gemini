<?php

require __DIR__.'/vendor/autoload.php';

try {
    $config = \Kobens\Core\Config::getInstance();
    $config->setConfig(__DIR__.'/env/config.xml');
    $config->setRootDir(__DIR__);
    
    $phpSettings = $config->get('php');
    if ($phpSettings) {
        if ($phpSettings->get('timezone')) {
            \date_default_timezone_set((string) $phpSettings->get('timezone'));
        }
    }
    unset ($config);
    
    // Init Exchange Mapper (todo: may not be necessary if we're scrapping kobens-exchange's trade-repeater)
    new \Kobens\Exchange\Exchange\Mapper(['gemini' => \Kobens\Gemini\Exchange::class]);
    
} catch (\Exception $e) {
    echo \sprintf("Initialization Error: %s\n", $e->getMessage());
    exit(1);
}
