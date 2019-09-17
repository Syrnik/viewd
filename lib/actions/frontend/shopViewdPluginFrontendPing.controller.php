<?php
/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2019
 * @license
 */

class shopViewdPluginFrontendPingController extends waJsonController
{
    /**
     * @throws waException
     */
    public function execute()
    {
        if (waRequest::getMethod() !== 'post') {
            throw new waException('Not allowed', 405);
        }

        $content_type = strtolower(waRequest::server('CONTENT_TYPE'));
        if ($content_type !== 'application/json') {
            throw new waException('Invalid request headers', 400);
        }

        $user = wa()->getUser();

        if ($user->get('is_user')) {
            return;
        }

        $data = file_get_contents('php://input');
        try {
            $data = waUtils::jsonDecode($data, true);
        } catch (waException $e) {
            throw new waException('Invalid request data', 400);
        }

        $type = filter_var(
            strtolower(trim(ifset($data, 'type', ''))),
            FILTER_VALIDATE_REGEXP,
            ['options' => ['regexp' => '/^[a-z]+$/']]
        );
        $id = (int)ifset($data, 'id', 0);

        if (!$type || !$id) {
            throw new waException('Invalid request data', 400);
        }

        $last_viewed = (int)$this->getStorage()->get("shop/viewd_plugin/$type");
        if ($id !== $last_viewed) {
            $this->getStorage()->set("shop/viewd_plugin/$type", $id);
        } else {
            $this->setError(_wp('Дублирующийся просмотр'));
            return;
        }

        $this->getStorage()->close();

        try {
            switch ($type) {
                case 'product':
                    (new shopProductModel)->exec("UPDATE `shop_product` SET `total_views` = `total_views` + 1 WHERE `id`=i:id", ['id' => $id]);
                    break;
            }
        } catch (waException $e) {
            waLog::log(_wp('Ошибка обновления количества просмотров плагином viewd у ' . $type . ' с ID=') . $id);
        }
    }
}