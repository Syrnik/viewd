<?php
/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2026
 * @license Webasyst
 */

class shopViewdPluginBackendSetController extends waJsonController
{
    public function execute()
    {
        if (!wa()->getUser()->get('is_user')) {
            throw new waException('Access denied', 403);
        }

        $product_id = (int)waRequest::post('product_id', 0);
        $views = (int)waRequest::post('views', -1);

        if (!$product_id || $views < 0) {
            $this->setError(_wp('Invalid parameters'));
            return;
        }

        $model = new shopProductModel();
        if (!$model->getById($product_id)) {
            $this->setError(_wp('Product not found'));
            return;
        }

        $model->updateById($product_id, ['total_views' => $views]);

        $this->response['views'] = $views;
    }
}
