<?php
$m = new waModel();

try {
    $m->query('SELECT `total_views` FROM `shop_product` WHERE 1=1 LIMIT 1');
} catch (waDbException $exception) {
    try {
        $m->exec('ALTER TABLE `shop_product` ADD `total_views` INT DEFAULT 0 NOT NULL');
    } catch (waException $e) {
        waLog::log('Error installing viewd plugin: ' . $e->getMessage());
    }
} catch (waException $e) {
    waLog::log('Error installing viewd plugin: ' . $e->getMessage());
}
