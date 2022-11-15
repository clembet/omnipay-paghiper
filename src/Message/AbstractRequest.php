<?php

namespace Omnipay\PagHiper\Message;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $liveEndpointBoleto = 'https://api.paghiper.com';
    protected $testEndpointBoleto = 'https://api.paghiper.com';
    protected $liveEndpointPix = 'https://pix.paghiper.com';
    protected $testEndpointPix = 'https://pix.paghiper.com';
    protected $resourceBoleto = 'transaction/create/';
    protected $resourcePix = 'invoice/create/';
    protected $requestMethod = 'POST';

    public function getData()
    {
        $this->validate("paymentType");
        return [];
    }

    public function sendData($data)
    {
        $this->validate("paymentType");

        $url = $this->getEndpoint();
        $method = $this->requestMethod;
        
        $headers = [
            'Accept' => 'application/json',
            'Accept-Charset' => 'UTF-8',
            'Accept-Encoding' => 'application/json',
            'Content-Type' => 'application/json'
        ];
        
        $httpRequest = $this->httpClient->request($method, $url, $headers, $this->toJSON($data));

        $content = $httpRequest->getBody()->getContents();
        $payload = json_decode($content, true);
        return $this->createResponse(@$payload[(strcmp("boleto", strtolower($this->getPaymentType()))==0)?'create_request':'pix_create_request']);
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

    public function setOrderId($value)
    {
        return $this->setParameter('order_id', $value);
    }
    public function getOrderId()
    {
        return $this->getParameter('order_id');
    }

    public function setDueDays($value)
    {
        return $this->setParameter('due_days', $value);
    }

    public function getDueDays()
    {
        return $this->getParameter('due_days');
    }

    public function setShippingPrice($value)
    {
        return $this->setParameter('shipping_price', $value);
    }

    public function getShippingPrice()
    {
        return $this->getParameter('shipping_price');
    }

    public function getTransactionID()
    {
        return $this->getParameter('transactionId');
    }

    public function setTransactionID($value)
    {
        return $this->setParameter('transactionId', $value);
    }

    public function getPaymentType()
    {
        return $this->getParameter('paymentType');
    }

    public function setPaymentType($value)
    {
        $this->setParameter('paymentType', $value);
    }

    /**
     * Get Customer Data
     *
     * @return array customer data
     */
    public function getCustomer()
    {
        return $this->getParameter('customer');
    }

    /**
     * Set Customer data
     *
     * @param array $value
     * @return AbstractRequest provides a fluent interface.
     */
    public function setCustomer($value)
    {
        return $this->setParameter('customer', $value);
    }

    protected function getEndpoint()
    {
        switch(strtolower($this->getPaymentType()))
        {
            case "boleto":
                return $this->getTestMode() ? ($this->testEndpointBoleto . '/'.$this->resourceBoleto) : ($this->liveEndpointBoleto . '/'.$this->resourceBoleto);
            break;

            case "pix":
                return $this->getTestMode() ? ($this->testEndpointPix . '/'.$this->resourcePix) : ($this->liveEndpointPix . '/'.$this->resourcePix);
                break;

            default:
                return $this->getTestMode() ? ($this->testEndpointBoleto . '/'.$this->resourceBoleto) : ($this->liveEndpointBoleto . '/'.$this->resourceBoleto);
        }

    }

    protected function createResponse($data)
    {
        return $this->response = new Response($this, $data);
    }

    public function toJSON($data, $options = 0)
    {
        if (version_compare(phpversion(), '5.4.0', '>=') === true) {
            return json_encode($data, $options | 64);
        }
        return str_replace('\\/', '/', json_encode($data, $options));
    }

}

?>
