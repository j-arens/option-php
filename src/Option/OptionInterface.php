<?php

declare(strict_types=1);

namespace O\Option;

interface OptionInterface
{
    /**
     * Returns true if the Option is an instance of Some.
     *
     * @return bool
     */
    public function isSome(): bool;

    /**
     * Returns true if the Option is an instance of None.
     *
     * @return bool
     */
    public function isNone(): bool;

    /**
     * Moves the value v out of the Option<T> if it is Some(v).
     * Throws an OptionException if the Option<T> is None.
     *
     * @return mixed
     */
    public function unwrap();

    /**
     * Returns the contained value or a default.
     *
     * @param mixed $fallback
     * @return mixed
     */
    public function unwrapOr($fallback);

    /**
     * Returns the contained value or computes it from the given function.
     *
     * @param callable $op
     * @return mixed
     */
    public function unwrapOrElse(callable $op);

    /**
     * Maps an Option<T> to Option<U> by applying a function to a contained value.
     *
     * @param callable(mixed): mixed $op
     * @return OptionInterface
     */
    public function map(callable $op): OptionInterface;

    /**
     * Applies a function to the contained value (if any), or returns the provided default (if not).
     *
     * @param mixed $fallback
     * @param callable(mixed): mixed $op
     * @return mixed
     */
    public function mapOr($fallback, callable $op);

    /**
     * Applies a function to the contained value (if any), or computes a default (if not).
     *
     * @param callable(): mixed $fallback
     * @param callable(mixed): mixed $op
     * @return mixed
     */
    public function mapOrElse(callable $fallback, callable $op);

    /**
     * Returns None if the opt is None, otherwise returns optb.
     *
     * @param OptionInterface $optb
     * @return OptionInterface
     */
    public function and(OptionInterface $optb): OptionInterface;

    /**
     * Returns None if the opt is None, otherwise calls op with the wrapped value and returns the result.
     *
     * @param callable(mixed): OptionInterface $op
     * @return OptionInterface
     */
    public function andThen(callable $op): OptionInterface;

    /**
     * Returns None if the opt is None, otherwise calls predicate with the wrapped value and returns:
     * - Some(t) if predicate returns truthy (where t is the wrapped value), and
     * - None if predicate returns falsey.
     *
     * @param callable(mixed): mixed $op
     * @return OptionInterface
     */
    public function filter(callable $op): OptionInterface;

    /**
     * Returns the opt if it contains a value, otherwise returns optb.
     *
     * @param OptionInterface $optb
     * @return OptionInterface
     */
    public function or(OptionInterface $optb): OptionInterface;

    /**
     * Returns the opt if it contains a value, otherwise calls op and returns the result.
     *
     * @param callable(): OptionInterface $op
     * @return OptionInterface
     */
    public function orElse(callable $op): OptionInterface;

    /**
     * Returns Some if exactly one of self, optb is Some, otherwise returns None.
     *
     * @param OptionInterface $optb
     * @return OptionInterface
     */
    public function xor(OptionInterface $optb): OptionInterface;

    /**
     * Converts from Option<Option<T>> to Option<T>. Flattening once only removes one level of nesting.
     *
     * @return OptionInterface
     */
    public function flatten(): OptionInterface;
}
