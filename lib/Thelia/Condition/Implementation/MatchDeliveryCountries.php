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

namespace Thelia\Condition\Implementation;

use Thelia\Exception\UnmatchableConditionException;

/**
 * Check a Checkout against its Product number
 *
 * @package Condition
 * @author  Franck Allimant <franck@cqfdev.fr>
 *
 */
class MatchDeliveryCountries extends AbstractMatchCountries
{
    /**
     * @inheritdoc
     */
    public function getServiceId()
    {
        return 'thelia.condition.match_delivery_countries';
    }

    /**
     * @inheritdoc
     */
    public function isMatching()
    {
        if (null === $customer = $this->facade->getCustomer()) {
            throw new UnmatchableConditionException();
        }

        if (null === $deliveryAddress = $this->facade->getDeliveryAddress()) {
            throw new UnmatchableConditionException();
        }

        return $this->conditionValidator->variableOpComparison(
            $deliveryAddress->getCountryId(),
            $this->operators[self::COUNTRIES_LIST],
            $this->values[self::COUNTRIES_LIST]
        );
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->translator->trans(
            'Delivery country',
            []
        );
    }

    /**
     * @inheritdoc
     */
    public function getToolTip()
    {
        $toolTip = $this->translator->trans(
            'The coupon applies to the selected delivery countries',
            []
        );

        return $toolTip;
    }

    protected function getSummaryLabel($cntryStrList, $i18nOperator)
    {
        return $this->translator->trans(
            'Only if order shipping country is %op% <strong>%countries_list%</strong>',
            [
                '%countries_list%' => $cntryStrList,
                '%op%' => $i18nOperator
            ]
        );
    }

    protected function getFormLabel()
    {
        return $this->translator->trans(
            'Delivery country is',
            []
        );
    }
}
