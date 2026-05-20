<?php

declare(strict_types=1);

namespace Devscast\Flexpay;

/**
 * Class Environment.
 * * Définit les environnements de travail (Production ou Sandbox)
 * et centralise la gestion des URLs des différents services Flexpay.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
enum Environment: string
{
    case LIVE = 'prod';
    case SANDBOX = 'dev';

    /**
     * Retourne l'URL pour les paiements par carte (Visa/Mastercard).
     * Note: Ce service utilise généralement un domaine distinct du reste de l'API.
     */
    public function getCardPaymentUrl(): string
    {
        return match ($this) {
            self::LIVE => 'https://cardpayment.flexpay.cd/v1.1/pay',
            self::SANDBOX => 'https://beta-cardpayment.flexpay.cd/v1.1/pay',
        };
    }

    /**
     * Retourne l'URL pour les paiements Mobile Money.
     */
    public function getMobilePaymentUrl(): string
    {
        return sprintf('%s/paymentService', $this->getBaseUrl());
    }

    /**
     * Retourne l'URL de vérification de statut d'une transaction.
     */
    public function getCheckStatusUrl(string $orderNumber): string
    {
        return sprintf('%s/check/%s', $this->getBaseUrl(), $orderNumber);
    }

    /**
     * Retourne l'URL pour les opérations de Payout (Sortie de fonds).
     * Le segment '/merchantPayOutService' est ajouté à la base de l'API.
     */
    public function getPayoutUrl(): string
    {
        return sprintf('%s/merchantPayOutService', $this->getBaseUrl());
    }

    /**
     * Centralise la base de l'API REST selon l'environnement.
     * Utilisé pour Mobile Money, Check Status et Payout.
     */
    private function getBaseUrl(): string
    {
        return match ($this) {
            self::LIVE => 'https://backend.flexpay.cd/api/rest/v1',
            self::SANDBOX => 'https://beta-backend.flexpay.cd/api/rest/v1',
        };
    }
}
