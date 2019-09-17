<?php
/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2019
 * @license
 */

class shopViewdPluginViewHelper
{
    public static function productViews($product)
    {
        $total_views = 0;

        if($product !== null) {
            if (is_scalar($product) && ($product = (int)$product)) {
                $product = new shopProduct($product);
            }

            if (is_array($product) || (is_object($product) && ($product instanceof ArrayAccess))) {
                $total_views = (int)ifset($product, 'total_views', 0);
            }
        }

        $template_name = 'plugin.viewd.product.html';

        $file = wa('shop')->getConfig()->getPluginPath('viewd') ."/templates/$template_name";

        if(wa()->getEnv() === 'frontend' && ($theme = waRequest::getTheme())) {
            $theme = new waTheme($theme);
            if($theme->getFile($template_name)) {
                $file = $theme->getPath() . "/$template_name";
            }
        }

        $view = wa('shop')->getView();
        $view->assign('total_views', $total_views);
        if(!$view->getVars('product')) {
            $view->assign('product', $product);
        }

        waSystem::pushActivePlugin('viewd');
        $result = $view->fetch($file);
        waSystem::popActivePlugin();
        return $result;

    }
}