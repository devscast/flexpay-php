<?php

declare(strict_types=1);

namespace Devscast\Flexpay\Data;

enum Type: int
{
    case MOBILE = 1;
    case CARD = 2;
}
