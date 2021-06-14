<?php

namespace Domains\Context\PlayersInformation\Domain\Model\DataSource;

class SourceTypeEnum
{

    public const YARDI_API_V1 = 'YARDI_API_V1';

    public const XERO_API_V1 = 'XERO_API_V1';

    public const YARDI_EXPORT_EXCEL = 'YARDI_EXPORT_EXCEL';

    public const TYPES = [self::YARDI_API_V1, self::XERO_API_V1, self::YARDI_EXPORT_EXCEL];

}