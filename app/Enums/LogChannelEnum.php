<?php

namespace App\Enums;

enum LogChannelEnum: string
{
    case AUTH       = 'Auth';
    case FILE       = 'File';
    case MAIL       = 'Mail';
    case USER       = 'User';
    case VALIDTION  = 'Validation';

    // CUSTOMS
}
