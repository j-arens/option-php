<?php

declare(strict_types=1);

namespace O\Option;

use Exception;

class OptionException extends Exception
{
    /**
     * Returns an OptionException with illegal instantiation message.
     *
     * @return OptionException
     */
    public static function illegalInstantiation(): OptionException
    {
        return new OptionException('Option must be an instance of Some or None');
    }

    /**
     * Returns an OptionException with illegal unwrap message.
     */
    public static function illegalUnwrap(): OptionException
    {
        return new OptionException('tried to unwrap on None');
    }

    /**
     * Returns an OptionException with none constructed with args message.
     */
    public static function noneConstructedWithArgs(): OptionException
    {
        return new OptionException('None should not be constructed with any arugments');
    }
}
