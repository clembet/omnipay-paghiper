<?php

namespace Omnipay\PagHiper;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\ItemBag;

class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'PagHiper';
    }

    public function getClientId()
    {
        return $this->getParameter('client_id');
    }

    public function setClientId($value)
    {
        return $this->setParameter('client_id', $value);
    }

    public function getClientSecret()
    {
        return $this->getParameter('client_secret');
    }

    public function setClientSecret($value)
    {
        return $this->setParameter('client_secret', $value);
    }

    public function getGrantType()
    {
        return $this->getParameter('grant_type');
    }

    public function setGrantType($value)
    {
        return $this->setParameter('grant_type', $value);
    }

    public function setApiKey($value)
    {
        return $this->setParameter('api_key', $value);
    }

    public function getApiKey()
    {
        return $this->getParameter('api_key');
    }

    public function setApiToken($value)
    {
        return $this->setParameter('api_token', $value);
    }

    public function getApiToken()
    {
        return $this->getParameter('api_token');
    }

    public function setExternalReference($value)
    {
        return $this->setParameter('external_reference', $value);
    }

    public function getExternalReference()
    {
        return $this->getParameter('external_reference');
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PagHiper\Message\PurchaseRequest', $parameters);
    }
    public function requestToken(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PagHiper\Message\TokenRequest', $parameters);
    }
    /**
     * @param  array  $parameters
     * @return \Omnipay\PagHiper\Message\CompletePurchaseRequest
     */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PagHiper\Message\CompletePurchaseRequest', $parameters);
    }

}

?>
