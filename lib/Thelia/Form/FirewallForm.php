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
namespace Thelia\Form;
use Symfony\Component\HttpFoundation\Request;
use Thelia\Model\ConfigQuery;
use Thelia\Model\FormFirewall;
use Thelia\Model\FormFirewallQuery;

/**
 * Class FirewallForm
 * @package Thelia\Form
 * @author Benjamin Perche <bperche@openstudio.fr>
 */
abstract class FirewallForm extends BaseForm
{
    /**
     * Those values are for a "normal" security policy
     */
    const DEFAULT_TIME_TO_WAIT = 1;
    const DEFAULT_ATTEMPTS = 6;

    /** @var  \Thelia\Model\FormFirewall */
    protected $firewallInstance;

    public function __construct(Request $request, $type = "form", $data = array(), $options = array())
    {
        $this->firewallInstance = FormFirewallQuery::create()
            ->filterByFormName($this->getName())
            ->filterByIpAddress($this->request->getClientIp())
            ->findOne()
        ;
        parent::__construct($request, $type, $data, $options);
    }

    public function isFirewallOk()
    {

        if ($this->isFirewallActive() && null !== $firewallRow = &$this->firewallInstance) {
            /** @var \DateTime $lastRequestDateTime */
            $lastRequestDateTime = $firewallRow->getUpdatedAt();

            $lastRequestTimestamp = $lastRequestDateTime->getTimestamp();

            /**
             * Get the last request execution time in hour.
             */
            $lastRequest = (time() - $lastRequestTimestamp) / 3600;

            if ($lastRequest > $this->getConfigTime()) {
                $firewallRow->resetAttempts();
            }

            if ($firewallRow->getAttempts() < $this->getConfigAttempts()) {
                $firewallRow->incrementAttempts();
            } else {
                /** Set updated_at at NOW() */
                $firewallRow->save();

                return false;
            }
        } else {
            $this->firewallInstance = $firewallRow = (new FormFirewall())
                ->setIpAddress($this->request->getClientIp())
                ->setFormName($this->getName())
            ;
            $firewallRow->save();

        }

        return true;
    }

    /**
     * @return int
     *
     * The time (in hours) to wait if the attempts have been exceeded
     */
    public function getConfigTime()
    {
        return ConfigQuery::read("form_firewall_time_to_wait", static::DEFAULT_TIME_TO_WAIT);
    }

    /**
     * @return int
     *
     * The number of allowed attempts
     */
    public function getConfigAttempts()
    {
        return ConfigQuery::read("form_firewall_attempts", static::DEFAULT_ATTEMPTS);
    }

    public function isFirewallActive()
    {
        return ConfigQuery::read("form_firewall_active", true);
    }
}