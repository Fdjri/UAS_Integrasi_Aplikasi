<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        // Set konfigurasi Midtrans dari config/midtrans.php
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = config('midtrans.is_sanitized', true);
        Config::$is3ds = config('midtrans.is_3ds', true);
    }

    /**
     * Membuat transaksi pembayaran Snap dan mendapatkan token & redirect URL.
     *
     * @param string|int $orderId          ID unik transaksi/order
     * @param float      $grossAmount      Total pembayaran dalam IDR
     * @param array      $customerDetails  Data customer (nama, email, dll)
     * @param array      $additionalParams Parameter tambahan opsional (misal auto_capture, expiry, enabled_payments)
     * @return object                      Objek transaksi dari Midtrans Snap API
     */
    public function createTransaction($orderId, $grossAmount, array $customerDetails = [], array $additionalParams = [])
    {
        // Default metode pembayaran yang aktif
        $defaultEnabledPayments = [
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
        ];

        // Bangun parameter dasar transaksi
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => $customerDetails,
            'enabled_payments' => $defaultEnabledPayments,
        ];

        // Override / merge dengan param tambahan jika ada
        if (!empty($additionalParams)) {
            $params = array_replace_recursive($params, $additionalParams);
        }

        // Buat transaksi menggunakan Snap API
        return Snap::createTransaction($params);
    }
}
