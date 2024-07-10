<?php

declare(strict_types=1);

namespace Luminarix\JSONL\Exceptions;

use Exception;

/**
 * The exception that is thrown when an object cannot be encoded to a JSONL row.
 */
class FailedToEncodeException extends Exception {}
