<?php

declare(strict_types=1);

namespace O\Option;

class None extends Option
{
    /**
     * None constructor
     */
    public function __construct()
    {
        if (func_num_args()) {
            throw OptionException::noneConstructedWithArgs();
        }
        parent::__construct(null);
    }
}
