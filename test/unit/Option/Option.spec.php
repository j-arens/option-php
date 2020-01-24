<?php

use O\Option\{Option, Some, None, OptionException};

describe('Option', function () {
    describe('creating an instance of Option', function () {
        it('throws if instantiated directly', function () {
            $fn = function () {
                new Option('');
            };
            expect($fn)->toThrow(OptionException::illegalInstantiation());
        });
    });

    describe('::default', function () {
        it('creates a new instance of None', function () {
            expect(Option::default())->toBeAnInstanceOf(None::class);
        });
    });

    describe('->isSome', function () {
        it('should return true for instances of Some', function () {
            expect((new Some(''))->isSome())->toBe(true);
        });

        it('should return false for instance of None', function () {
            expect((new None())->isSome())->toBe(false);
        });
    });

    describe('->isNone', function () {
        it('should return true for instances of None', function () {
            expect((new None())->isNone())->toBe(true);
        });

        it('should return false for instances of Some', function () {
            expect((new Some(''))->isNone())->toBe(false);
        });
    });

    describe('->unwrap', function () {
        it('returns the value contained within a Some', function () {
            $opt = new Some('foo');
            expect($opt->unwrap())->toBe('foo');
        });

        it('throws if called on an instance of None', function () {
            $fn = function () {
                $opt = new None();
                $opt->unwrap();
            };
            expect($fn)->toThrow(OptionException::illegalUnwrap());
        });
    });

    describe('->unwrapOr', function () {
        it('returns the unwrapped value within a Some', function () {
            $opt = new Some('foo');
            expect($opt->unwrapOr(''))->toBe('foo');
        });

        it('returns the fallback value if called on an instance of None', function () {
            $opt = new None();
            expect($opt->unwrapOr('foo'))->toBe('foo');
        });
    });

    describe('->unwrapOrElse', function () {
        it('returns the unwrapped value within a Some', function () {
            $opt = new Some('foo');
            $fb = function () {
                return '';
            };
            expect($opt->unwrapOrElse($fb))->toBe('foo');
        });

        it('calls and returns the fallback function if called on an instance of None', function () {
            $opt = new None();
            $fb = function () {
                return 'foo';
            };
            expect($opt->unwrapOrElse($fb))->toBe('foo');
        });
    });

    describe('->map', function () {
        it('returns a new instance of Some if called on an instance of Some', function () {
            $opta = new Some('foo');
            $optb = $opta->map(function ($x) {
                return $x;
            });
            expect($optb->isSome())->toBe(true);
        });

        it('returns an instance of Some containing the product of the given function', function () {
            $opta = new Some('foo');
            $optb = $opta->map(function ($x) {
                return $x . 'bar';
            });
            expect($optb->unwrap())->toBe('foobar');
        });

        it('returns a new instance of None if called on an instance of None', function () {
            $opta = new None();
            $optb = $opta->map(function ($x) {
                return $x;
            });
            expect($opta->isNone())->toBe(true);
        });
    });

    describe('->mapOr', function () {
        it('returns the product of the given function if called on an instance of Some', function () {
            $opt = new Some('foo');
            $result = $opt->mapOr('baz', function ($x) {
                return $x . 'bar';
            });
            expect($result)->toBe('foobar');
        });

        it('returns the given fallback value if called on an instance of None', function () {
            $opt = new None();
            $result = $opt->mapOr('baz', function ($x) {
                return $x . 'bar';
            });
            expect($result)->toBe('baz');
        });
    });

    describe('->mapOrElse', function () {
        it('returns the product of the given function if called on an instance of Some', function () {
            $opt = new Some('foo');
            $some = function (string $value) {
                return $value . 'bar';
            };
            $none = function () {
                return 'baz';
            };
            expect($opt->mapOrElse($none, $some))->toBe('foobar');
        });

        it('returns the product of the given fallback function if called on an instance of None', function () {
            $opt = new None();
            $some = function (string $value) {
                return $value . 'bar';
            };
            $none = function () {
                return 'baz';
            };
            expect($opt->mapOrElse($none, $some))->toBe('baz');
        });
    });

    describe('->and', function () {
        it('returns the given opt if called on an instance of Some', function () {
            $opta = new Some('foo');
            $optb = new Some('bar');
            expect($opta->and($optb))->toBe($optb);
        });

        it('returns a new instance of None if called on an instance of None', function () {
            $opta = new None();
            $optb = new Some('foo');
            expect($opta->and($optb))->toBeAnInstanceOf(None::class);
        });
    });

    describe('->andThen', function () {
        it('return the opt returned from the given function if called on an instance of Some', function () {
            $opta = new Some('foo');
            $optb = $opta->andThen(function (string $word) {
                return new Some($word . 'bar');
            });
            expect($optb->isSome())->toBe(true);
            expect($optb->unwrap())->toBe('foobar');
        });

        it('returns a new instance of None if called on an instance of None', function () {
            $opta = new None();
            $optb = $opta->andThen(function ($x) {
                return new Some($x);
            });
            expect($optb->isNone())->toBe(true);
        });
    });

    describe('->filter', function () {
        it('returns the instance if its type is Some and the given function returns true', function () {
            $opta = new Some('foo');
            $optb = $opta->filter(function ($x) {
                return $x === 'foo';
            });
            expect($optb)->toBe($opta);
        });

        it('returns a new instance of None if called on an instance of Some and the given function returns false', function () {
            $opta = new Some('foo');
            $optb = $opta->filter(function ($x) {
                return $x === 'bar';
            });
            expect($optb->isNone())->toBe(true);
        });

        it('returns a new instance of None if called on an instance of None', function () {
            $opta = new None();
            $optb = $opta->filter(function ($x) {
                return $x === $x;
            });
            expect($optb->isNone())->toBe(true);
        });
    });

    describe('->or', function () {
        it('returns the instance if its type is Some', function () {
            $opta = new Some('foo');
            $optb = new Some('bar');
            $optc = $opta->or($optb);
            expect($optc)->toBe($opta);
        });

        it('returns the given opt if called on an instance of None', function () {
            $opta = new None();
            $optb = new Some('foo');
            $optc = $opta->or($optb);
            expect($optc)->toBe($optb);
        });
    });

    describe('->orElse', function () {
        it('returns the instance of its type is Some', function () {
            $opta = new Some('foo');
            $optb = $opta->orElse(function () {
                return new Some('bar');
            });
            expect($optb)->toBe($opta);
        });

        it('returns the opt returned by the given function if called on an instance of None', function () {
            $opta = new None();
            $optb = new Some('foo');
            $optc = $opta->orElse(function () use ($optb) {
                return $optb;
            });
            expect($optb)->toBe($optc);
        });
    });

    describe('->xor', function () {
        it('returns None if the instance is Some and the given opt is Some', function () {
            $opta = new Some('foo');
            $optb = new Some('bar');
            expect($opta->xor($optb)->isNone())->toBe(true);
        });

        it('returns the instance if its type is Some and the given opt is None', function () {
            $opta = new Some('foo');
            $optb = new None();
            $optc = $opta->xor($optb);
            expect($optc)->toBe($opta);
        });

        it('returns the given opt if its type is Some and the current instance is None', function () {
            $opta = new None();
            $optb = new Some('foo');
            $optc = $opta->xor($optb);
            expect($optc)->toBe($optb);
        });

        it('returns a new instance of None if the current instance is None and the given opt is None', function () {
            $opta = new None();
            $optb = new None();
            $optc = $opta->xor($optb);
            expect($optc->isNone())->toBe(true);
            expect($optc)->not->toBe($opta);
            expect($optc)->not->toBe($optb);
        });
    });

    describe('->flatten', function () {
        it('unwraps Somes containing a Some and returns the unwrapped Some', function () {
            $opta = new Some(new Some('foo'));
            $optb = $opta->flatten();
            expect($optb->unwrap())->toBe('foo');
        });

        it('returns the instance if the wrapped value is not an instance of Some', function () {
            $opta = new Some('foo');
            $optb = $opta->flatten();
            expect($optb)->toBe($opta);
        });

        it('returns a new instance of None if the current instance is None', function () {
            $opta = new None();
            $optb = $opta->flatten();
            expect($optb->isNone())->toBe(true);
            expect($optb)->not->toBe($opta);
        });
    });
});
