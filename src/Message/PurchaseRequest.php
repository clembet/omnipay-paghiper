<?php

namespace Omnipay\PagHiper\Message;

class PurchaseRequest extends AbstractRequest
{
    protected $resourceBoleto = 'transaction/create/';
    protected $resourcePix = 'invoice/create/';
    protected $requestMethod = 'POST';

    public function getItemData()
    {
        $data = [];
        $items = $this->getItems();

        if ($items) {
            foreach ($items as $n => $item) {

                $item_array = [];
                $item_array['title'] = $item->getName();
                $item_array['description'] = $item->getDescription();
//                $item_array['category_id'] = $item->getCategoryId();
                $item_array['quantity'] = (int)$item->getQuantity();
                $item_array['currency_id'] = $this->getCurrency();
                $item_array['unit_price'] = (double)($this->formatCurrency($item->getPrice()));

                array_push($data, $item_array);
            }
        }

        return $data;
    }

    public function getData()
    {
        $this->validate("items", "customer", "order_id", "amount", "due_days", "shipping_price", "currency");
        $items = $this->getItemData();
        $customer = $this->getCustomer();
        $items_data = $this->getItems();

        $itemsArr = array();
        if($items_data && (count($items_data) > 0))foreach($items_data as $item)
        {
            $itemsArr[] = array('description'=>$item->getName(),
                'quantity'=>$item->getQuantity(),
                'price_cents'=>(int)round(($item->getPrice()*100.0), 0),
                'item_id' => '1');//utilizar o código 1, caso não queira usar o sku do produto
        }

        $data = [
            'apiKey' => $this->getApiKey(),
            'token' => $this->getApiToken(),
            'order_id' => $this->getOrderId(), // código interno do lojista para identificar a transacao.
            'payer_email' => $customer['payer_email'],
            'payer_name' => $customer['payer_name'], // nome completo ou razao social
            'payer_cpf_cnpj' => $customer['payer_cpf_cnpj'], // cpf ou cnpj
            'payer_phone' => $customer['payer_phone'], // fixou ou móvel
            'shipping_price_cents' => $this->getShippingPrice(), // em centavos
            'shipping_methods' => 'Envio Personalizado',
            'fixed_description' => false,
            'days_due_date' => $this->getDueDays(), // dias para vencimento da  cobrança
            'discount_cents' => '0', // em centavos
            'notification_url' => $this->getNotifyUrl(),
            'items' => $itemsArr,
        ];

        if(strcmp("boleto", strtolower($this->getPaymentType()))==0)
        {
            $data_complemento = [
                'payer_street' => $customer['payer_street'],
                'payer_number' => $customer['payer_number'],
                'payer_complement' => $customer['payer_complement'],
                'payer_district' => $customer['payer_district'],
                'payer_city' => $customer['payer_city'],
                'payer_state' => $customer['payer_state'], // apenas sigla do estado
                'payer_zip_code' => $customer['payer_zip_code'],
                'type_bank_slip' => 'boletoA4', // formato do boleto
                'open_after_day_due' => '0',
            ];

            $data = array_merge($data, $data_complemento);
        }

        return $data;

    }
}

?>
