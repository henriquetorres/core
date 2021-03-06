<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace Thelia\ImportExport\Export\Type;

use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Thelia\ImportExport\Export\AbstractExport;
use Thelia\Model\Currency;
use Thelia\Model\CustomerQuery;
use Thelia\Model\Map\AddressTableMap;
use Thelia\Model\Map\CountryI18nTableMap;
use Thelia\Model\Map\CustomerTableMap;
use Thelia\Model\Map\NewsletterTableMap;
use Thelia\Model\OrderQuery;

/**
 * Class CustomerExport
 * @author Benjamin Perche <bperche@openstudio.fr>
 * @author Jérôme Billiras <jbilliras@openstudio.fr>
 */
class CustomerExport extends AbstractExport
{
    const FILE_NAME = 'customer';

    protected $orderAndAliases = [
        CustomerTableMap::COL_REF => 'ref',
        'title_TITLE' => 'title',
        CustomerTableMap::COL_LASTNAME => 'last_name',
        CustomerTableMap::COL_FIRSTNAME => 'first_name',
        CustomerTableMap::COL_EMAIL => 'email',
        CustomerTableMap::COL_DISCOUNT => 'discount',
        'newsletter_IS_REGISTRED' => 'is_registered_to_newsletter',
        CustomerTableMap::COL_CREATED_AT => 'sign_up_date',
        'order_TOTAL' => 'total_orders',
        'last_order_AMOUNT' => 'last_order_amount',
        'last_order_DATE' => 'last_order_date',
        'address_LABEL' => 'label',
        'address_TITLE' => 'address_title',
        'address_FIRST_NAME' => 'address_first_name',
        'address_LAST_NAME' => 'address_last_name',
        'address_COMPANY' => 'company',
        'address_ADDRESS1' => 'address1',
        'address_ADDRESS2' => 'address2',
        'address_ADDRESS3' => 'address3',
        'address_ZIPCODE' => 'zipcode',
        'address_CITY' => 'city',
        'address_COUNTRY' => 'country',
        'address_PHONE' => 'phone',
        'address_CELLPHONE' => 'cellphone'
    ];

    protected function getData()
    {
        $locale = $this->language->getLocale();

        /**
         * This first query get each customer info and addresses.
         */
        $newsletterJoin = new Join(CustomerTableMap::COL_EMAIL, NewsletterTableMap::COL_EMAIL, Criteria::LEFT_JOIN);

        $query = new CustomerQuery;
        $results = $query
            ->useCustomerTitleQuery('customer_title_')
                ->useCustomerTitleI18nQuery('customer_title_i18n_')
                    ->filterByLocale($locale)
                    ->addAsColumn('title_TITLE', 'customer_title_i18n_.SHORT')
                    ->endUse()
                ->endUse()
            ->useAddressQuery()
                ->useCountryQuery()
                    ->useCountryI18nQuery()
                        ->filterByLocale($locale)
                        ->addAsColumn('address_COUNTRY', CountryI18nTableMap::COL_TITLE)
                        ->endUse()
                    ->endUse()
                    ->useCustomerTitleQuery('address_title')
                    ->useCustomerTitleI18nQuery('address_title_i18n')
                        ->filterByLocale($locale)
                        ->addAsColumn('address_TITLE', 'address_title_i18n.SHORT')
                    ->endUse()
                ->endUse()
                ->filterByIsDefault(true)
                ->addAsColumn('address_LABEL', AddressTableMap::COL_LABEL)
                ->addAsColumn('address_FIRST_NAME', AddressTableMap::COL_FIRSTNAME)
                ->addAsColumn('address_LAST_NAME', AddressTableMap::COL_LASTNAME)
                ->addAsColumn('address_COMPANY', AddressTableMap::COL_COMPANY)
                ->addAsColumn('address_ADDRESS1', AddressTableMap::COL_ADDRESS1)
                ->addAsColumn('address_ADDRESS2', AddressTableMap::COL_ADDRESS2)
                ->addAsColumn('address_ADDRESS3', AddressTableMap::COL_ADDRESS3)
                ->addAsColumn('address_ZIPCODE', AddressTableMap::COL_ZIPCODE)
                ->addAsColumn('address_CITY', AddressTableMap::COL_CITY)
                ->addAsColumn('address_PHONE', AddressTableMap::COL_PHONE)
                ->addAsColumn('address_CELLPHONE', AddressTableMap::COL_CELLPHONE)
            ->endUse()
            ->addJoinObject($newsletterJoin)
            ->addAsColumn('newsletter_IS_REGISTRED', 'IF(NOT ISNULL('.NewsletterTableMap::COL_EMAIL.'),1,0)')
            ->select([
                CustomerTableMap::COL_ID,
                CustomerTableMap::COL_REF,
                CustomerTableMap::COL_LASTNAME,
                CustomerTableMap::COL_FIRSTNAME,
                CustomerTableMap::COL_EMAIL,
                CustomerTableMap::COL_DISCOUNT,
                CustomerTableMap::COL_CREATED_AT,
                'title_TITLE',
                'address_TITLE',
                'address_LABEL',
                'address_COMPANY',
                'address_FIRST_NAME',
                'address_LAST_NAME',
                'address_ADDRESS1',
                'address_ADDRESS2',
                'address_ADDRESS3',
                'address_ZIPCODE',
                'address_CITY',
                'address_COUNTRY',
                'address_PHONE',
                'address_CELLPHONE',
                'newsletter_IS_REGISTRED',
            ])
            ->orderById()
            ->find()
            ->toArray()
        ;

        /**
         * Then get the orders
         */
        $orders = OrderQuery::create()
            ->useCustomerQuery()
                ->orderById()
                ->endUse()
            ->find()
        ;

        /**
         * And add them info the array
         */
        $orders->rewind();

        $arrayLength = count($results);

        $previousCustomerId = null;

        for ($i = 0; $i < $arrayLength; ++$i) {
            $currentCustomer = &$results[$i];

            $currentCustomerId = $currentCustomer[CustomerTableMap::COL_ID];
            unset($currentCustomer[CustomerTableMap::COL_ID]);

            if ($currentCustomerId === $previousCustomerId) {
                $currentCustomer["title_TITLE"] = "";
                $currentCustomer[CustomerTableMap::COL_LASTNAME] = "";
                $currentCustomer[CustomerTableMap::COL_FIRSTNAME] = "";
                $currentCustomer[CustomerTableMap::COL_EMAIL] = "";
                $currentCustomer["address_COMPANY"] = "";
                $currentCustomer["newsletter_IS_REGISTRED"] = "";
                $currentCustomer[CustomerTableMap::COL_CREATED_AT] = "";
                $currentCustomer[CustomerTableMap::COL_DISCOUNT] = "";

                $currentCustomer += [
                    "order_TOTAL" => "",
                    "last_order_AMOUNT" => "",
                    "last_order_DATE" => "",
                ];
            } else {
                /**
                 * Reformat created_at date
                 */
                $date = $currentCustomer[CustomerTableMap::COL_CREATED_AT];
                $dateTime = new \DateTime($date);
                $currentCustomer[CustomerTableMap::COL_CREATED_AT] = $dateTime->format($this->language->getDatetimeFormat());

                /**
                 * Then compute everything about the orders
                 */
                $total = 0;
                $lastOrderAmount = 0;
                $lastOrderDate = null;
                $lastOrder = null;
                $lastOrderCurrencyCode = null;
                $lastOrderId = 0;

                $defaultCurrency = Currency::getDefaultCurrency();
                $defaultCurrencyCode = $defaultCurrency
                    ->getCode()
                ;

                if (empty($defaultCurrencyCode)) {
                    $defaultCurrencyCode = $defaultCurrency
                        ->getCode()
                    ;
                }

                $formattedDate = null;

                /** @var \Thelia\Model\Order $currentOrder */
                while (false !== $currentOrder = $orders->current()) {
                    if ($currentCustomerId != $currentOrder->getCustomerId()) {
                        break;
                    }

                    $amount = $currentOrder->getTotalAmount($tax);
                    if (0 < $rate = $currentOrder->getCurrencyRate()) {
                        $amount = round($amount / $rate, 2);
                    }

                    $total += $amount;

                    /** @var \DateTime $date */
                    $date = $currentOrder->getCreatedAt();

                    if (null === $lastOrderDate || ($date >= $lastOrderDate && $lastOrderId < $currentOrder->getId())) {
                        $lastOrder = $currentOrder;
                        $lastOrderDate = $date;
                        $lastOrderId = $currentOrder->getId();
                    }

                    $orders->next();
                }

                if ($lastOrderDate !== null) {
                    $formattedDate = $lastOrderDate->format($this->language->getDatetimeFormat());

                    $orderCurrency = $lastOrder->getCurrency();
                    $lastOrderCurrencyCode = $orderCurrency
                        ->getCode()
                    ;

                    if (empty($lastOrderCurrencyCode)) {
                        $lastOrderCurrencyCode = $orderCurrency
                            ->getCode()
                        ;
                    }

                    $lastOrderAmount = $lastOrder->getTotalAmount($tax_);
                }

                $currentCustomer += [
                    "order_TOTAL" => $total . " " . $defaultCurrencyCode,
                    "last_order_AMOUNT" => $lastOrderAmount === 0 ? "" : $lastOrderAmount . " " . $lastOrderCurrencyCode,
                    "last_order_DATE" => $formattedDate,
                ];
            }

            $previousCustomerId = $currentCustomerId;
        }

        return $results;
    }
}
