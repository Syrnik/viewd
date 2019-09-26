<?php

class shopViewdPlugin extends shopPlugin
{
    /**
     * Handler for frontend_head hook
     *
     * @return string|null
     */
    public function hookFrontendHeadProduct()
    {
        $param = waRequest::param();
//        waLog::dump($param);
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
        $static_url = wa()->getAppStaticUrl('shop') . 'plugins/viewd/js/viewd' . (waSystemConfig::isDebug() ? '' : '.min') . '.js';

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
    public function hookFrontendProduct($product)
    {
        if(!$this->getSettings('frontend_product')) {
            return null;
        }

        return ['block_aux' => shopViewdPluginViewHelper::productViews($product)];
    }

    /**
     * Handler for backend_product hook
     *
     * @param $product
     * @return array|null
     */
    public function hookBackendProduct(&$product)
    {
        if(!is_array($product) && !($product instanceof ArrayAccess)) {
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

        return ['toolbar_section'=>"<script>\n$js\n</script>"];
    }
}
