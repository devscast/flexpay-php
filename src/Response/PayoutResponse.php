<?php

declare(strict_types=1);

namespace Devscast\Flexpay\Response;

use Devscast\Flexpay\Data\Status;
use Symfony\Component\Serializer\Attribute\SerializedName;

/**
 * Class PayoutResponse.
 * * Représente la réponse suite à une demande de Payout (versement vers un client).
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class PayoutResponse extends FlexpayResponse
{
    public function __construct(
        public Status $code,
        public string $message = '',
        
        #[SerializedName('orderNumber')]
        public ?string $orderNumber = null,
    ) {
    }
}