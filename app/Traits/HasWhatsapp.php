<?php

namespace App\Traits;

trait HasWhatsapp
{
    /**
     * Genera l'URL per WhatsApp pulendo il numero e aggiungendo il prefisso 39.
     *
     * @param string $message Il messaggio preimpostato (opzionale)
     * @param string $columnName Il nome della colonna nel DB (default: 'phone')
     * @return string|null
     */
    public function getWhatsappUrl(string $message = '', string $columnName = 'phone'): ?string
    {
        // Recupera il numero dal modello usando la colonna specificata
        $number = $this->{$columnName};

        if (empty($number)) {
            return null;
        }

        // 1. Rimuove tutto tranne i numeri
        $cleanNumber = preg_replace('/[^0-9]/', '', $number);

        // 2. Logica Prefisso Italia
        // Se non inizia con 39, lo aggiunge.
        if (!str_starts_with($cleanNumber, '39')) {
            $cleanNumber = '39' . $cleanNumber;
        }

        // 3. Costruzione URL
        $baseUrl = "https://wa.me/{$cleanNumber}";

        if (!empty($message)) {
            $baseUrl .= "?text=" . rawurlencode($message);
        }

        return $baseUrl;
    }
}
