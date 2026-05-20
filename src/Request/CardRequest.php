<?php

declare(strict_types=1);

namespace Devscast\Flexpay\Request;

use Devscast\Flexpay\Data\Currency;
use Override;
use Webmozart\Assert\Assert;

/**
 * Class CardRequest.
 * * Cette classe gère les requêtes de paiement par carte (Visa/Mastercard).
 * Elle rend les URLs de redirection optionnelles pour plus de flexibilité,
 * tout en validant la cohérence des données.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class CardRequest extends Request
{
    /**
     * CardRequest constructor.
     * * @param float $amount Le montant de la transaction
     * @param string $reference La référence unique (max 25 caractères)
     * @param Currency $currency La devise (CDF ou USD)
     * @param string $callbackUrl L'URL de notification (Webhook)
     * @param string $description Description de l'achat
     * @param string $approveUrl URL de retour après succès
     * @param string $cancelUrl URL de retour après annulation
     * @param string $declineUrl URL de retour après échec
     * @param string $homeUrl URL de retour à l'accueil du site
     */
    public function __construct(
        float $amount,
        string $reference,
        Currency $currency,
        string $callbackUrl,
        string $description = '',
        string $approveUrl = '',
        string $cancelUrl = '',
        string $declineUrl = '',
        public string $homeUrl = '',
    ) {
        // Validation de la référence (contrainte API Flexpay)
        Assert::lengthBetween($reference, 1, 25, 'The reference must be between 1 and 25 characters');

        // On ne valide que si les champs ne sont pas vides (souplesse)
        // Mais on garde la structure métier cohérente
        parent::__construct(
            amount: $amount,
            reference: $reference,
            currency: $currency,
            callbackUrl: $callbackUrl,
            description: $description,
            approveUrl: $approveUrl,
            cancelUrl: $cancelUrl,
            declineUrl: $declineUrl
        );
    }

    /**
     * Génère le payload pour l'API Card Payment.
     * Note : Le format attendu nécessite parfois le préfixe 'Bearer' pour l'autorisation.
     * * @return array<string, float|string|null>
     * @return array<string, float|string|null>
     */
    #[Override]
    public function getPayload(): array
    {
        return [
            'amount' => $this->amount,
            'merchant' => $this->merchant,
            'authorization' => sprintf('Bearer %s', $this->authorization),
            'reference' => $this->reference,
            'currency' => $this->currency->value,
            'description' => $this->description,
            'callback_url' => $this->callbackUrl,
            'approve_url' => $this->approveUrl,
            'cancel_url' => $this->cancelUrl,
            'decline_url' => $this->declineUrl,
            'home_url' => $this->homeUrl,
        ];
    }
}
