<?php

namespace DigiTickets\Stripe\Messages;

use Omnipay\Common\Message\AbstractRequest;

abstract class AbstractCheckoutRequest extends AbstractRequest
{
    /**
     * Get the gateway API Key (the "secret key").
     *
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->getParameter('apiKey');
    }

    /**
     * Set the gateway API Key.
     *
     * @return AbstractRequest provides a fluent interface.
     */
    public function setApiKey($value): AbstractRequest
    {
        return $this->setParameter('apiKey', $value);
    }

    public function setMethods($value)
    {
        $this->setParameter('methods', $value);
    }

    public function getMethods()
    {
        $methods = $this->getParameter("methods");
        return !empty($methods) && !is_array($methods) ? [$methods] : $methods;
    }

    public function setToAccountID($value)
    {
        $this->setParameter('to_account_id', $value);
    }

    public function getToAccountID()
    {
        return $this->getParameter("to_account_id");
    }
}
