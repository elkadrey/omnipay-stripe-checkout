<?php

namespace DigiTickets\Stripe\Messages;

use DigiTickets\Stripe\Lib\ComplexTransactionRef;

class CompletePurchaseRequest extends AbstractCheckoutRequest
{
    /**
     * @var string|null
     */
    private $sessionID;

    /**
     * @return string|null
     */
    public function getSessionID()
    {
        return $this->sessionID;
    }

    public function setToAccountID($value)
    {
        $this->setParameter('to_account_id', $value);
    }

    public function getToAccountID()
    {
        return $this->getParameter("to_account_id");
    }


    /**
     * Because we have to pass the session id around, but also save the transaction ref in the same place, they are
     * combined, in JSON format, so this setter has to split them.
     *
     * @param string $value
     */
    public function setTransactionReference($value)
    {
        $this->sessionID = ComplexTransactionRef::buildFromJson($value)->getSessionID();
    }

    public function getData()
    {
        // Just validate the parameters.
        $this->validate('apiKey');

        return null; // The data we need (the session id) is already in the request object.
    }

    public function sendData($data)
    {
        // Retrieve the session that would have been started earlier.
        \Stripe\Stripe::setApiKey($this->getApiKey());
        $account = $this->getToAccountID();
        $session = \Stripe\Checkout\Session::retrieve($this->sessionID,$account ? ['stripe_account' => $account] : null);
        $paymentIntent = \Stripe\PaymentIntent::retrieve($session->payment_intent,$account ? ['stripe_account' => $account] : null);

        return $this->response = new CompletePurchaseResponse($this, ['paymentIntent' => $paymentIntent]);
    }
}
