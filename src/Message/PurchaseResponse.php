<?php namespace Omnipay\PagHiper\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    protected $transaction_id = NULL;

    public function isSuccessful()
    {
        $data = $this->getData();
        if((@$data['http_code']*1)==201)
        {
            $this->setTransactionReference(@$data['transaction_id']);
            return true;
        }
        else
            return false;
    }

    /**
     * Redirect for the Payment URL
     * @return boolean
     */
    public function isRedirect()
    {
        return isset($this->data->init_point) && $this->data->init_point;
    }


    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectData()
    {
        return null;
    }

    public function getRedirectUrl()
    {
        if ($this->isRedirect()) {
            return $this->data->init_point;
        }
    }

    public function getBoleto()
    {
        $data = $this->getData();
        $boleto = array();
        $boleto['boleto_url'] = @$data['bank_slip']['url_slip'];
        $boleto['boleto_url_pdf'] = @$data['bank_slip']['url_slip_pdf'];
        $boleto['boleto_barcode'] = @$data['bank_slip']['digitable_line'];
        $boleto['boleto_expiration_date'] = @$data['due_date'];
        $boleto['boleto_valor'] = (@$data['value_cents']*1.0)/100.0;
        $boleto['boleto_transaction_id'] = @$data['transaction_id'];
        @$this->setTransactionReference(@$data['transaction_id']);

        return $boleto;
    }

    public function setTransactionReference($transaction_id)
    {
        $this->transaction_id = $transaction_id;
    }
}

?>
