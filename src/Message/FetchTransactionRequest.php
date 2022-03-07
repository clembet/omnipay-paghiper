<?php namespace Omnipay\PagHiper\Message;

class FetchTransactionRequest extends AbstractRequest
{
    protected $resourceBoleto = 'transaction/status/';
    protected $resourcePix = 'invoice/status/';
    protected $requestMethod = 'POST';

    public function getData()
    {
        $this->validate("transactionId");
        return parent::getData();
    }

    public function sendData($data)
    {
        $this->validate('transactionId', "paymentType");

        $url = $this->getEndpoint();
        $method = $this->requestMethod;

        $headers = [
            'Accept' => 'application/json',
            'Accept-Charset' => 'UTF-8',
            'Accept-Encoding' => 'application/json',
            'Content-Type' => 'application/json'
        ];

        $data = [
            'apiKey' => $this->getApiKey(),
            'token' => $this->getApiToken(),
            'transaction_id' => $this->getTransactionID()
        ];

        print_r([$method, $url, $headers, $data]);
        $httpResponse = $this->httpClient->request($method, $url, $headers, $this->toJSON($data));
        $json = $httpResponse->getBody()->getContents();
        $json = @json_decode($json, true);
        return $this->createResponse(@$json["status_request"]);
    }
}
