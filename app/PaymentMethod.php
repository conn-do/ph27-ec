<?php

namespace App;

enum PaymentMethod: string
{
    case CreditCard = 'credit_card';
    case MobilePayment = 'mobile_payment';
    case ConvenienceStore = 'convenience_store';
    case BankTransfer = 'bank_transfer';
    case CashOnDelivery = 'cash_on_delivery';

    /**
     * @return list<self>
     */
    public static function availableForCheckout(): array
    {
        return [
            self::CreditCard,
            self::MobilePayment,
            self::ConvenienceStore,
            self::BankTransfer,
        ];
    }

    public function label(): string
    {
        return match ($this) {
            self::CreditCard => 'クレジットカード',
            self::MobilePayment => 'スマホ決済',
            self::ConvenienceStore => 'コンビニ払い',
            self::BankTransfer => '銀行振込',
            self::CashOnDelivery => '代金引換',
        };
    }

    /**
     * @return list<string>
     */
    public function badgeLabels(): array
    {
        return match ($this) {
            self::CreditCard => ['VISA', 'MC', 'JCB', '+3'],
            self::MobilePayment => ['QR'],
            self::ConvenienceStore => ['FamilyMart', 'LAWSON', '7-ELEVEN'],
            self::BankTransfer => ['振込'],
            self::CashOnDelivery => ['代引'],
        };
    }
}
