# Option

This is a partial port of the [Option](https://doc.rust-lang.org/std/option/) type from [Rust](https://www.rust-lang.org/). Most of the functionality is here but I skipped porting some of the more Rust-specific methods that don't really make sense in a PHP context.

## Usage

Basic usage is the same as in Rust.

```php
use O\Option\{Some, None, OptionInterface};

// basic setting and getting of values
$greeting = new Some('hey there');
$name = new None();

echo $greeting->unwrap(); // echos 'hey there'
echo $name->unwrap(); // throws an OptionException
echo $name->unwrapOr('unknown'); // echos 'unknown'

function divide(int $x, int $y): OptionInterface
{
  if (y === 0) {
    return new None();
  }
  return new Some(x / y);
}

divide(1, 0); // None
divide(1, 1); // Some(1)
```

## Linting

```
$ composer lint
```

## Analysing

```
$ composer analyse
```

## Testing

```
$ composer test:unit
```
