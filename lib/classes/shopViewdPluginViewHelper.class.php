<?php
/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2019-2021
 * @license Webasyst
 */

/**
 * Class shopViewdPluginViewHelper
 */
class shopViewdPluginViewHelper extends waPluginViewHelper
{
    /**
     * @param mixed $product
     * @return string
     */
    public function views($product): string
    {
        $total_views = 0;

        if ($product !== null) {
            if (is_scalar($product) && ($product = (int)$product)) {
                $product = new shopProduct($product);
            }

            if (is_array($product) || (is_object($product) && ($product instanceof ArrayAccess))) {
                $total_views = (int)ifset($product, 'total_views', 0);
            }
        }

        $template_name = 'plugin.viewd.product.html';

        try {
            $file = wa('shop')->getConfig()->getPluginPath('viewd') . "/templates/$template_name";
        } catch (waException $e) {
            return "";
        }

        try {
            if (wa()->getEnv() === 'frontend' && ($theme = waRequest::getTheme())) {
                $theme = new waTheme($theme);
                if ($theme->getFile($template_name)) {
                    $file = $theme->getPath() . "/$template_name";
                }
            }
        } catch (waException $e) {
            // do nothing
        }

        try {
            $view = wa('shop')->getView();
            $view->assign('total_views', $total_views);
            if (!$view->getVars('product')) {
                $view->assign('product', $product);
            }

            waSystem::pushActivePlugin('viewd');
            $result = $view->fetch($file);
            waSystem::popActivePlugin();
        } catch (Exception $e) {
            $result = '';
        }

        return $result;
    }

    /**
     * Обёртка для устаревших версий Shop-Script и уже установленных плагинов
     *
     * @param mixed $product id или объект/массив товара
     * @return string
     */
    public static function productViews($product): string
    {
        try {
            return (new shopViewdPluginViewHelper(wa('shop')->getPlugin('viewd'), 'viewd'))
                ->views($product);
        } catch (waException $e) {
            return "";
        }
    }
}
