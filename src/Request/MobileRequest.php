<?php

declare(strict_types=1);

namespace Devscast\Flexpay\Request;

use Devscast\Flexpay\Data\Currency;
use Devscast\Flexpay\Data\Type;
use Override;
use Webmozart\Assert\Assert;

/**
 * Class MobileRequest.
 * * Cette classe gère les requêtes de paiement via Mobile Money.
 * Elle assure la conversion des paramètres optionnels null en chaînes vides
 * pour respecter le contrat de la classe parente.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class MobileRequest extends Request
{
    /**
     * MobileRequest constructor.
     * * @param float $amount Le montant de la transaction
     * @param string $reference La référence unique
     * @param Currency $currency La devise (CDF ou USD)
     * @param string $callbackUrl L'URL de notification (Webhook)
     * @param string $phone Le numéro de téléphone (12 caractères)
     * @param Type $type Le type de paiement (Défaut: MOBILE)
     * @param string|null $description Description optionnelle
     * @param string|null $approveUrl URL de redirection après succès
     * @param string|null $cancelUrl URL de redirection après annulation
     * @param string|null $declineUrl URL de redirection après échec
     */
    public function __construct(
        float $amount,
        string $reference,
        Currency $currency,
        string $callbackUrl,
        public readonly string $phone,
        public readonly Type $type = Type::MOBILE,
        ?string $description = null,
        ?string $approveUrl = null,
        ?string $cancelUrl = null,
        ?string $declineUrl = null
    ) {
        // Validation du format du numéro de téléphone
        Assert::length($this->phone, 12, 'The phone number should be 12 characters long, eg: 243123456789');

        // Appel au parent en convertissant les nulls en chaînes vides ('')
        // pour éviter le TypeError avec Request::__construct
        parent::__construct(
            amount: $amount,
            reference: $reference,
            currency: $currency,
            callbackUrl: $callbackUrl,
            description: $description ?? '',
            approveUrl: $approveUrl ?? '',
            cancelUrl: $cancelUrl ?? '',
            declineUrl: $declineUrl ?? ''
        );
    }

    /**
     * Génère le payload pour l'API Mobile Money de Flexpay.
     * * @return array<string, float|string|int|null>
     * @return array<string, float|string|int|null>
     */
    #[Override]
    public function getPayload(): array
    {
        return [
            'phone' => $this->phone,
            'type' => $this->type->value,
            'amount' => $this->amount,
            'merchant' => $this->merchant,
            'reference' => $this->reference,
            'currency' => $this->currency->value,
            'callbackUrl' => $this->callbackUrl,
            'description' => $this->description,
            'approveUrl' => $this->approveUrl,
            'cancelUrl' => $this->cancelUrl,
            'declineUrl' => $this->declineUrl,
        ];
    }
}
