<?php
interface PaymentStrategyInterface {
    public function processPayment(float $amount): bool;
    public function getPaymentMethodName(): string;
}

class PromptPayPayment implements PaymentStrategyInterface {
    private string $phoneNumber;

    public function __construct(string $phoneNumber = "081-234-5678") {
        $this->phoneNumber = $phoneNumber;
    }

    public function processPayment(float $amount): bool {
        return $amount > 0;
    }

    public function getPaymentMethodName(): string {
        return "พร้อมเพย์ (PromptPay - " . $this->phoneNumber . ")";
    }
}

class CreditCardPayment implements PaymentStrategyInterface {
    private string $cardNumber;

    public function __construct(string $cardNumber) {
        $this->cardNumber = substr($cardNumber, -4);
    }

    public function processPayment(float $amount): bool {
        return $amount > 0;
    }

    public function getPaymentMethodName(): string {
        return "บัตรเครดิต/เดบิต (ลงท้ายด้วย " . $this->cardNumber . ")";
    }
}
