<?php

namespace App\Tests\Unit\Service;

use App\Service\DateTimeHelper;
use App\Tests\Feature\Api\BaseApiTest;
use DateTimeInterface;

/**
 * Class DateTimeHelperTest
 * @package App\Tests\Unit\Service
 */
class DateTimeHelperTest extends BaseApiTest
{
    /**
     * @var DateTimeHelper
     */
    private DateTimeHelper $dateTimeHelper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dateTimeHelper = new DateTimeHelper();
    }

    public function testGetCurrentDateTime(): void
    {
        $time = $this->dateTimeHelper->getCurrentDateTime();

        self::assertInstanceOf(DateTimeInterface::class, $time);
    }
}
