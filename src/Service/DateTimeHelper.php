<?php

namespace App\Service;

use DateTime;
use DateTimeInterface;

/**
 * Class DateTimeHelper
 * @package App\Service
 */
class DateTimeHelper
{
    /**
     * @return DateTimeInterface
     */
    public function getCurrentDateTime(): DateTimeInterface
    {
        return new DateTime();
    }
}
