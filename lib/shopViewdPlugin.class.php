<?php

class shopViewdPlugin extends shopPlugin
{
    /**
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
        $static_url = wa()->getAppStaticUrl('shop') . 'plugins/viewd/js/viewd.' . (waSystemConfig::isDebug() ? '' : 'min') . '.js';

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

    public function hookFrontendProduct($product)
    {
        return ['block_aux' => shopViewdPluginViewHelper::productViews($product)];
    }
}
