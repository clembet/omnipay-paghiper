<?php namespace Omnipay\PagHiper\Message;

/**
 *
 * <code>
 *   // Do a refund transaction on the gateway
 *   $transaction = $gateway->void(array(
 *       'transactionId'     => $transactionCode,
 *       'paymentType'       => "Boleto"
 *   ));
 *
 *   $response = $transaction->send();
 *   if ($response->isSuccessful()) {
 *   }
 * </code>
 */

class VoidRequest extends AbstractRequest
{
    protected $resourceBoleto = 'transaction/cancel/';
    protected $resourcePix = 'invoice/cancel/';
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
            "status" => "canceled",
            'transaction_id' => $this->getTransactionID()
        ];

        $httpResponse = $this->httpClient->request($method, $url, $headers, $this->toJSON($data));
        $json = $httpResponse->getBody()->getContents();
        $json = @json_decode($json, true);
        return $this->createResponse($json["cancellation_request"]);
    }
}
