<?php

namespace App\Enums;

enum LogChannelEnum: string
{
    case AUTH       = 'Auth';
    case USER       = 'User';
    case VALIDTION  = 'Validation';
    case PAYMENT    = 'Payment';

    // CUSTOMS
}
