<?php

declare(strict_types=1);

namespace O\Option;

class Option implements OptionInterface
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * Option constructor
     *
     * @param mixed $value
     */
    public function __construct($value)
    {
        if (!$this->isSome() && !$this->isNone()) {
            throw OptionException::illegalInstantiation();
        }
        $this->value = $value;
    }

    /**
     * Returns the default instance of Option.
     *
     * @return OptionInterface
     */
    public static function default(): OptionInterface
    {
        return new None();
    }

    /**
     * {@inheritdoc}
     */
    public function isSome(): bool
    {
        return $this instanceof Some;
    }

    /**
     * {@inheritdoc}
     */
    public function isNone(): bool
    {
        return $this instanceof None;
    }

    /**
     * {@inheritdoc}
     */
    public function unwrap()
    {
        if ($this->isSome()) {
            return $this->value;
        }
        throw OptionException::illegalUnwrap();
    }

    /**
     * {@inheritdoc}
     */
    public function unwrapOr($fallback)
    {
        if ($this->isSome()) {
            return $this->unwrap();
        }
        return $fallback;
    }

    /**
     * {@inheritdoc}
     */
    public function unwrapOrElse(callable $op)
    {
        if ($this->isSome()) {
            return $this->unwrap();
        }
        return $op();
    }

    /**
     * {@inheritdoc}
     */
    public function map(callable $op): OptionInterface
    {
        if ($this->isSome()) {
            return new Some($op($this->unwrap()));
        }
        return new None();
    }

    /**
     * {@inheritdoc}
     */
    public function mapOr($fallback, callable $op)
    {
        if ($this->isSome()) {
            return $op($this->unwrap());
        }
        return $fallback;
    }

    /**
     * {@inheritdoc}
     */
    public function mapOrElse(callable $fallback, callable $op)
    {
        if ($this->isSome()) {
            return $op($this->unwrap());
        }
        return $fallback();
    }

    /**
     * {@inheritdoc}
     */
    public function and(OptionInterface $optb): OptionInterface
    {
        if ($this->isSome()) {
            return $optb;
        }
        return new None();
    }

    /**
     * {@inheritdoc}
     */
    public function andThen(callable $op): OptionInterface
    {
        if ($this->isSome()) {
            return $op($this->unwrap());
        }
        return new None();
    }

    /**
     * {@inheritdoc}
     */
    public function filter(callable $op): OptionInterface
    {
        return $this->andThen(function ($val) use ($op) {
            return $op($val) ? $this : new None();
        });
    }

    /**
     * {@inheritdoc}
     */
    public function or(OptionInterface $optb): OptionInterface
    {
        if ($this->isSome()) {
            return $this;
        }
        return $optb;
    }

    /**
     * {@inheritdoc}
     */
    public function orElse(callable $op): OptionInterface
    {
        if ($this->isSome()) {
            return $this;
        }
        return $op();
    }

    /**
     * {@inheritdoc}
     */
    public function xor(OptionInterface $optb): OptionInterface
    {
        if ($this->isSome()) {
            if ($optb->isSome()) {
                return new None();
            }
            return $this;
        }
        if ($optb->isSome()) {
            return $optb;
        }
        return new None();
    }

    /**
     * {@inheritdoc}
     */
    public function flatten(): OptionInterface
    {
        return $this->mapOrElse(
            function () {
                return new None();
            },
            function ($opt) {
                return $opt instanceof Some ? $opt : $this;
            },
        );
    }
}
