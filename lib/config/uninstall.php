<?php
$m = new waModel();

try {
    $m->exec('ALTER TABLE `shop_product` DROP COLUMN `total_views`');
} catch (waException $e) {
    waLog::log('Error while uninstall viewd plugin: ' . $e->getMessage() . '(' . $e->getCode() . ')');
}
