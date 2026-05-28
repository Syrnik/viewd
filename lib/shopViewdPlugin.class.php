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
        if (!$this->getSettings('frontend_product')) {
            return null;
        }

        return ['block_aux' => shopViewdPluginViewHelper::productViews($product)];
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
                $views = (int)$product->total_views;
                if ($views) {
                    $views = _wp("Просмотров карточки товара: ") . "<b>$views</b>";
                } else {
                    $views = _wp("Просмотров карточки товара пока не было");
                }
                $header = _wp("Количество просмотров карточки товара");
                $content = <<<HTML
<div class="s-content-section s-content-section__viewd">
    <div class="s-section-header">
        <h4 class="s-title">$header</h4>
    </div>
    <div class="s-section-body">
        <p>$views</p>
    </div>
</div>
HTML;
                return ['bottom' => $content];
            }
        } catch (Exception $e) {
            //do nothing
        }

        return [];
    }
}
