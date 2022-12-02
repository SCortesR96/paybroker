<?php

namespace App\Traits;

use Exception;
use App\Enums\LogChannelEnum;
use Illuminate\Support\Facades\Log;

trait LogError
{
    /**
     * Record the error in a file for the model and send the error's code
     * It logs the error and returns a message to the user
     *
     * @param Exception e The exception that was thrown.
     * @param LogChannelEnum The channel to log the error to [Auth | User | Mail | File].
     * @param string location The location of the error.
     *
     * @return string A string with the error message.
     */
    function getErrorMessage(Exception $e, LogChannelEnum $channel, string $location): string
    {
        $errorCode = $channel->value . "-" . now()->timestamp;
        $errorClientMessage = "Ha ocurrido un error.\r\nContacte con un administrador, su código para una solución es: $errorCode";
        $errorLogMessage = "Error: $errorCode ($location):  \r\n" . $e->getMessage() . PHP_EOL;

        Log::channel($channel->value)->error($errorLogMessage);

        return $errorClientMessage;
    }
}
