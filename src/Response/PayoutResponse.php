<?php

declare(strict_types=1);

namespace Devscast\Flexpay\Response;

use Devscast\Flexpay\Data\Status;

/**
 * Class PayoutResponse.
 * * Représente la réponse suite à une demande de Payout (versement vers un client).
 * Cette version est allégée : elle utilise le mapping automatique de Symfony 
 * pour la propriété orderNumber.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class PayoutResponse extends FlexpayResponse
{
    /**
     * PayoutResponse constructor.
     * * @param Status $code Le code de statut de la réponse (200, 400, etc.)
     * @param string $message Le message descriptif renvoyé par l'API
     * @param string|null $orderNumber Le numéro de commande généré par Flexpay
     */
    public function __construct(
        public Status $code,
        public string $message = '',
        public ?string $orderNumber = null,
    ) {
    }
}