<?php
/*************************************************************************************/
/*                                                                                   */
/*      Thelia	                                                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : info@thelia.net                                                      */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      This program is free software; you can redistribute it and/or modify         */
/*      it under the terms of the GNU General Public License as published by         */
/*      the Free Software Foundation; either version 3 of the License                */
/*                                                                                   */
/*      This program is distributed in the hope that it will be useful,              */
/*      but WITHOUT ANY WARRANTY; without even the implied warranty of               */
/*      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                */
/*      GNU General Public License for more details.                                 */
/*                                                                                   */
/*      You should have received a copy of the GNU General Public License            */
/*	    along with this program. If not, see <http://www.gnu.org/licenses/>.         */
/*                                                                                   */
/*************************************************************************************/

namespace CmCIC;

use Propel\Runtime\Connection\ConnectionInterface;
use Thelia\Model\ModuleImageQuery;
use Thelia\Model\Order;
use Thelia\Module\BaseModule;
use Thelia\Module\PaymentModuleInterface;
use Thelia\Tools\Redirect;
use Thelia\Tools\URL;

class CmCIC extends BaseModule implements  PaymentModuleInterface
{
    const JSON_CONFIG_PATH = "/Config/config.json";
    const ORDER_NOT_PAID = "not_paid";
    const ORDER_PAID_ID = 2;
    const ORDER_CANCELLED = "canceled";

    /**
     *
     * This method is call on Payment loop.
     *
     * If you return true, the payment method will de display
     * If you return false, the payment method will not be display
     *
     * @return boolean
     */
    public function isValidPayment()
    {
        // TODO: Implement isValidPayment() method.
    }

    public function postActivation(ConnectionInterface $con = null)
    {
        /* insert the images from image folder if first module activation */
        $module = $this->getModuleModel();
        if(ModuleImageQuery::create()->filterByModule($module)->count() == 0) {
            $this->deployImageFolder($module, sprintf('%s/images', __DIR__), $con);
        }

        /* set module title */
        $this->setTitle(
            $module,
            array(
                "en_US" => "CB",
                "fr_FR" => "CB",
            )
        );
    }
    /**
     * @return mixed
     */
    public function pay(Order $order)
    {
        Redirect::exec(URL::getInstance()->absoluteUrl("/cmcic/bankservice/").$order->getId());
    }

    public function getRequest() {
        return $this->container->get('request');
    }

    public function getCode()
    {
        return 'CmCIC';
    }
}