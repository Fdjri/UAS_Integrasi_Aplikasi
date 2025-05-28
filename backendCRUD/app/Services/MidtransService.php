<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        // Setting konfigurasi Midtrans dari config/midtrans.php atau .env
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = config('midtrans.is_sanitized', true);
        Config::$is3ds = config('midtrans.is_3ds', true);
    }

    /**
     * Buat transaksi pembayaran dan dapatkan token & redirect url
     *
     * @param string|int $orderId         ID unik untuk transaksi/order
     * @param float      $grossAmount      Total pembayaran (dalam IDR)
     * @param array      $customerDetails  Data customer (nama, email, dll)
     * @return object                     Objek transaksi dari Midtrans
     */
    public function createTransaction($orderId, $grossAmount, array $customerDetails = [])
    {
        // Daftar lengkap metode pembayaran yang ingin diaktifkan (sesuaikan jika perlu)
        $enabledPayments = [
            'credit_card',
            'bank_transfer',
            'gopay',
            'shopeepay',
            'bca_klikpay',
            'bca_klikbca',
            'cimb_clicks',
            'danamon_online',
            'indomaret',
            'akulaku',
            'qris',
            'alfamart',
            'bri_epay',
            'mandiri_clickpay',
            'echannel',
            'permata_va',
            'bca_va',
            'bni_va',
            'other_va',
            // tambahkan metode lain jika perlu
        ];

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => $customerDetails,
            'enabled_payments' => $enabledPayments,
        ];

        return Snap::createTransaction($params);
    }
}
