<?php

namespace App\Enum;

enum RecurrenceTypeEnum: string
{
    case DAILY = 'daily';
    case WEEKLY = 'weekly';
    case MONTHLY = 'monthly';
}
