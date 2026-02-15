<?php

namespace App\Services\Payment\Contracts;

interface PaymentGatewayInterface
{
    public function createCustomer(array $data);

    public function createSubscription(array $data);

    public function createCheckoutSession(array $data);

    public function cancelSubscription(string $subscriptionId);

    public function retrieveSubscription(string $subscriptionId);

    public function updateSubscription(string $subscriptionId, array $data);

    public function createInvoice(array $data);

    public function retrieveInvoice(string $invoiceId);
}
