<?php
/*************************************************************************************/
/*                                                                                   */
/*      Thelia	                                                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*	email : info@thelia.net                                                      */
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
/*	    along with this program. If not, see <http://www.gnu.org/licenses/>.     */
/*                                                                                   */
/*************************************************************************************/

namespace Thelia\Action;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Core\Event\ActionEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Form\BaseForm;
use Thelia\Form\CustomerCreation;
use Thelia\Log\Tlog;

class Customer implements EventSubscriberInterface
{

    public function create(ActionEvent $event)
    {

        $event->getDispatcher()->dispatch(TheliaEvents::BEFORE_CREATECUSTOMER, $event);

        $request = $event->getRequest();

        $customerForm = new CustomerCreation($request);

        $form = $customerForm->getForm();


        if ($request->isMethod("post")) {
            $form->bind($request);

            if ($form->isValid()) {
                $data = $form->getData();
                $customer = new \Thelia\Model\Customer();
                try {
                    $customer->createOrUpdate(
                        $data["title"],
                        $data["firstname"],
                        $data["lastname"],
                        $data["address1"],
                        $data["address2"],
                        $data["address3"],
                        $data["phone"],
                        $data["cellphone"],
                        $data["zipcode"],
                        $data["country"],
                        $data["email"],
                        $data["password"]
                    );
                } catch (\PropelException $e) {
                    Tlog::getInstance()->error(sprintf('error during creating customer on action/createCustomer with message "%s"', $e->getMessage()));
                }

                echo "ok"; exit;
            } else {

                $event->setFormError($form);
            }
        }

        $event->getDispatcher()->dispatch(TheliaEvents::AFTER_CREATECUSTOMER, $event);
    }

    public function modify(ActionEvent $event)
    {

    }

    public function modifyPassword(ActionEvent $event)
    {

    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array(
            "action.createCustomer" => array("create", 128),
            "action.modifyCustomer" => array("modify", 128)
        );
    }

}
