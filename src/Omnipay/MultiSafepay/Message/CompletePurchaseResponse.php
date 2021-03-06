<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\MultiSafepay\Message;

class CompletePurchaseResponse extends AbstractResponse
{
    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return isset($this->data->ewallet->status) && 'completed' === (string) $this->data->ewallet->status;
    }

    /**
     * {@inheritdoc}
     */
    public function getTransactionReference()
    {
        return isset($this->data->transaction->id) ? (string) $this->data->transaction->id : null;
    }

    /**
     * Is the payment created, but uncompleted?
     *
     * @return boolean
     */
    public function isInitialized()
    {
        return isset($this->data->ewallet->status) && 'initialized' === (string) $this->data->ewallet->status;
    }

    /**
     * Is the payment created, but not yet exempted (credit cards)?
     *
     * @return boolean
     */
    public function isUncleared()
    {
        return isset($this->data->ewallet->status) && 'uncleared' === (string) $this->data->ewallet->status;
    }

    /**
     * Is the payment cancelled?
     *
     * @return boolean
     */
    public function isCancelled()
    {
        return isset($this->data->ewallet->status) && 'void' === (string) $this->data->ewallet->status;
    }

    /**
     * Is the payment rejected?
     *
     * @return boolean
     */
    public function isRejected()
    {
        return isset($this->data->ewallet->status) && 'declined' === (string) $this->data->ewallet->status;
    }

    /**
     * Is the payment refunded?
     *
     * @return boolean
     */
    public function isRefunded()
    {
        return isset($this->data->ewallet->status) && 'refunded' === (string) $this->data->ewallet->status;
    }

    /**
     * Is the payment expired?
     *
     * @return boolean
     */
    public function isExpired()
    {
        return isset($this->data->ewallet->status) && 'expired' === (string) $this->data->ewallet->status;
    }

    /**
     * Get raw payment status.
     *
     * @return null|string
     */
    public function getPaymentStatus()
    {
        return isset($this->data->ewallet->status) ? (string) $this->data->ewallet->status : null;
    }
}
