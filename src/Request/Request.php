<?php

declare(strict_types=1);

namespace Devscast\Flexpay\Request;

use Devscast\Flexpay\Credential;
use Devscast\Flexpay\Data\Currency;
use Webmozart\Assert\Assert;

/**
 * Class Request
 * * Classe de base abstraite pour toutes les requêtes de l'API Flexpay.
 * Elle centralise les informations communes à chaque transaction.
 * * @author bernard-ng <bernard@devscast.tech>
 */
abstract class Request
{
    /**
     * ID du marchand fourni par Flexpay
     */
    public ?string $merchant = null;

    /**
     * Jeton d'autorisation (Token)
     */
    public ?string $authorization = null;

    /**
     * Constructeur de base.
     * * @param float $amount Montant de la transaction (doit être > 0)
     * @param string $reference Référence unique de la transaction
     * @param Currency $currency Devise (CDF ou USD)
     * @param string $callbackUrl URL de notification (Webhook)
     * @param string $description Description optionnelle
     * @param string $approveUrl URL de retour après succès
     * @param string $cancelUrl URL de retour après annulation
     * @param string $declineUrl URL de retour après échec
     */
    public function __construct(
        public readonly float $amount,
        public readonly string $reference,
        public readonly Currency $currency,
        public readonly string $callbackUrl,
        public readonly string $description = '',
        public readonly string $approveUrl = '',
        public readonly string $cancelUrl = '',
        public readonly string $declineUrl = '',
    ) {
        // Validations de base communes à tous les flux
        Assert::greaterThan($this->amount, 0, 'The transaction amount should be greater than 0');
        Assert::notEmpty($this->reference, 'The transaction reference is mandatory');
        Assert::notEmpty($this->callbackUrl, 'The callback (webhook) url must be provided');
    }

    /**
     * Définit les informations d'authentification de manière centralisée.
     * * @internal Cette méthode est utilisée par le Provider pour injecter les credentials.
     */
    public function setCredential(Credential $credential): void
    {
        $this->merchant = $credential->merchant;
        $this->authorization = $credential->token;
    }

    /**
     * Chaque type de requête doit implémenter sa propre logique de génération de payload.
     */
    abstract public function getPayload(): array;
}
