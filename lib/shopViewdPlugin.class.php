<?php
/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2019-2021
 * @license Webasyst
 */

/**
 * Main plugin class
 */
class shopViewdPlugin extends shopPlugin
{
    /**
     * Handler for frontend_head hook
     *
     * @return string|null
     * @throws waException
     */
    public function hookFrontendHeadProduct(): ?string
    {
        $param = waRequest::param();
        if (($param['module'] !== 'frontend') || ($param['action'] !== 'product')) {
            return null;
        }

        $url = $param['product_url'];

        try {
            $product = (new shopProductModel)->getByField('url', $url);
        } catch (waException $e) {
            return null;
        }

        if (!$product) {
            return null;
        }

        $id = $product['id'];
        $url = wa()->getRouteUrl('shop/frontend/ping', ['plugin' => 'viewd']);
        $static_url = wa()->getAppStaticUrl('shop') . 'plugins/viewd/js/viewd' . (waSystemConfig::isDebug(
            ) ? '' : '.min') . '.js';

        // @formatter:off
        $js = <<<JS
(function (callback) {
    if(document.readyState != 'loading') callback();
    else if (document.addEventListener) document.addEventListener('DOMContentLoaded', callback);
    else document.attachEvent('onreadystatechange', function () {
            if(document.readyState == 'complete') callback();
        })
})(function () {
    var v = new ViewdPlugin($id, 'product');
    v.ping('$url');
});
JS;
        // @formatter:on
        return "<script src=\"$static_url\" defer></script>\n<script>\n$js\n</script>";
    }

    /**
     * Handler for frontend_product hook
     *
     * @param shopProduct|array $product
     * @return array
     */
    public function hookFrontendProduct($product): ?array
    {
        $location = $this->getSettings('frontend_product');
        if ($location == 1) {
            $location = 'block_aux';
        }
        if (empty($location)) {
            return null;
        }

        return [$location => shopViewdPluginViewHelper::productViews($product)];
    }

    /**
     * Handler for backend_product hook
     *
     * @param $product
     * @return array|null
     * @throws waException
     */
    public function hookBackendProduct(&$product): ?array
    {
        if (!is_array($product) && !($product instanceof ArrayAccess)) {
            return null;
        }

        $localized_views_total = _wp('Просмотры за всё время');
        $total_views = (int)ifset($product, 'total_views', 0);

        $js = <<<JS
(function(jq) {
    var row = '<tr><td>$localized_views_total</td><td class="align-right nowrap"><strong class="s-target s-last-month">$total_views</strong><strong class="s-target s-forecast hidden" data-hidden="1">0</strong></td></tr>';
    jq('table.s-report-tabs tbody').append(row);
})(jQuery)
JS;

        return ['toolbar_section' => "<script>\n$js\n</script>"];
    }

    /**
     * @param $params
     * @return string[]
     */
    public function hookBackendProdContent($params): array
    {
        $content_id = ifset($params, 'content_id', '');
        $product = ifset($params, 'product', '');

        try {
            if (($content_id === 'prices') && ($product instanceof shopProduct) && $product->id) {
                $view = wa()->getView();
                $view->assign([
                    'product_id' => (int)$product->id,
                    'views'      => (int)$product->total_views,
                    'header'     => _wp('Количество просмотров карточки товара'),
                    'label'      => _wp('Просмотров карточки товара:'),
                ]);
                $template = $this->path . '/templates/hooks/backend_prod_content.html';
                return ['bottom' => $view->fetch($template)];
            }
        } catch (Exception $e) {
            //do nothing
        }

        return [];
    }
}
