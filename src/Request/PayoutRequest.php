<?php

declare(strict_types=1);

namespace Devscast\Flexpay\Request;

use Devscast\Flexpay\Data\Currency;
use Devscast\Flexpay\Data\Type;
use Override;
use Webmozart\Assert\Assert;

/**
 * Class PayoutRequest.
 * * Cette classe gère les demandes de Payout (paiement vers un client).
 * Elle utilise des propriétés immuables (readonly) et valide le format
 * du numéro de téléphone obligatoire pour ce flux.
 * * @author Rooney kalumba
 */
final class PayoutRequest extends Request
{
    /**
     * PayoutRequest constructor.
     * * @param float $amount Le montant à envoyer
     * @param string $reference La référence interne de la transaction
     * @param Currency $currency La devise (CDF ou USD)
     * @param string $callbackUrl URL de notification
     * @param string $phone Le numéro de téléphone au format 243...
     * @param Type $type Le type de payout (Défaut: MOBILE)
     */
    public function __construct(
        float $amount,
        string $reference,
        Currency $currency,
        string $callbackUrl,
        public readonly string $phone,
        public readonly Type $type = Type::MOBILE,
    ) {
        // Validation stricte du format du numéro de téléphone (Ex: 243000000000)
        Assert::length($this->phone, 12, 'The phone number should be 12 characters long, eg: 243123456789');

        parent::__construct(
            amount: $amount,
            reference: $reference,
            currency: $currency,
            callbackUrl: $callbackUrl
        );
    }

    /**
     * Génère le corps de la requête pour l'API Flexpay.
     * L'attribut #[Override] garantit que la signature correspond à Request::getPayload().
     * * @return array<string, float|string|null|int>
     * @return array<string, float|string|null|int>
     */
    #[Override]
    public function getPayload(): array
    {
        return [
            'merchant' => $this->merchant,
            'type' => $this->type->value,
            'reference' => $this->reference,
            'phone' => $this->phone,
            'amount' => $this->amount,
            'currency' => $this->currency->value,
            'callbackUrl' => $this->callbackUrl,
        ];
    }
}
