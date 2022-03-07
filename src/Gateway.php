<?php

namespace Omnipay\PagHiper;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\ItemBag;

/**
 * https://asaasv3.docs.apiary.io/#reference/pix
 * @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface completePurchase(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface authorize(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface capture(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface refund(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface createCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface updateCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface deleteCard(array $options = array())
 */

class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'PagHiper';
    }

    public function setApiKey($value)
    {
        return $this->setParameter('apiKey', $value);
    }

    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }

    public function setApiToken($value)
    {
        return $this->setParameter('apiToken', $value);
    }

    public function getApiToken()
    {
        return $this->getParameter('apiToken');
    }

    public function setExternalReference($value)
    {
        return $this->setParameter('external_reference', $value);
    }

    public function getExternalReference()
    {
        return $this->getParameter('external_reference');
    }

    public function parseResponse($data)
    {
        $request = $this->createRequest('\Omnipay\PagHiper\Message\PurchaseRequest', []);
        return new \Omnipay\PagHiper\Message\Response($request, (array)$data);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PagHiper\Message\PurchaseRequest', $parameters);
    }

    public function void(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PagHiper\Message\VoidRequest', $parameters);
    }

    public function fetchTransaction(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\PagHiper\Message\FetchTransactionRequest', $parameters);
    }
    public function acceptNotification(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\PagHiper\Message\NotificationRequest', $parameters);
    }
}

?>
