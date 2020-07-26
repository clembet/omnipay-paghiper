<?php

namespace Omnipay\PagHiper\Message;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $liveEndpoint = 'https://api.paghiper.com';
    protected $testEndpoint = 'https://api.paghiper.com';

    public function getData()
    {
        $customer = $this->getCustomer();
        $items_data = $this->getItems();

        $items = array();
        if($items_data && (count($items_data) > 0))foreach($items_data as $item)
        {
            $items[] = array('description'=>$item->getName(),
                'quantity'=>$item->getQuantity(),
                'price_cents'=>(int)round(($item->getPrice()*100.0), 0),
                'item_id' => '1');
        }

        $data = array(
            'apiKey' => $this->getApiKey(),
            'token' => $this->getApiToken(),
            'order_id' => $this->getOrderId(), // código interno do lojista para identificar a transacao.
            'payer_email' => $customer['payer_email'],
            'payer_name' => $customer['payer_name'], // nome completo ou razao social
            'payer_cpf_cnpj' => $customer['payer_cpf_cnpj'], // cpf ou cnpj
            'payer_phone' => $customer['payer_phone'], // fixou ou móvel
            'payer_street' => $customer['payer_street'],
            'payer_number' => $customer['payer_number'],
            'payer_complement' => $customer['payer_complement'],
            'payer_district' => $customer['payer_district'],
            'payer_city' => $customer['payer_city'],
            'payer_state' => $customer['payer_state'], // apenas sigla do estado
            'payer_zip_code' => $customer['payer_zip_code'],
            'notification_url' => $this->getNotifyUrl(),
            'discount_cents' => '0', // em centavos
            'shipping_price_cents' => $this->getShippingPrice(), // em centavos
            'shipping_methods' => 'Envio Personalizado',
            'fixed_description' => true,
            'type_bank_slip' => 'boletoA4', // formato do boleto
            'days_due_date' => $this->getDueDays(), // dias para vencimento do boleto
            'open_after_day_due'=>'0',
            'items' => $items,
        );

        return $data;
    }

    public function sendData($data)
    {
        $url = $this->getEndpoint();
        $data=@$data['external_reference'];
        $httpRequest = $this->httpClient->request(
            'POST',
            $url,
            array(
                'Accept' => 'application/json',
                'Accept-Charset' => 'UTF-8',
                'Accept-Encoding' => 'application/json',
                'Content-Type' => 'application/json'
            ),
            $this->toJSON($data)
        );

        $content = $httpRequest->getBody()->getContents();
        print($content);
        $payload = json_decode($content, true);
        print "payload\n";
        print_r($payload);
        return $this->response = $this->createResponse(@$payload['create_request']);


        /*$url = $this->getEndpoint().'/transaction/create/';
        $httpRequest = $this->httpClient->request(
            'POST',
            $url,
            [
                'Content-Type' => 'application/json'
            ],
            $this->toJSON($data)
        );

        $payload =  json_decode($httpRequest->getBody()->getContents(), true);


        return $this->response = new Response($this, $payload);*/

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
        return (int)round((@($this->getParameter('shipping_price')*100.0)), 0);
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
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
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
