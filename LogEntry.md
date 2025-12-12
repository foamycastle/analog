# LogEntry

A flexible and immutable log entry class for the Foamycastle\Analog logging system.

## Overview

`LogEntry` represents a single log entry with support for customizable formatting, context parameters, and PSR-3 compatible log levels. It uses a template-based approach where placeholders in the format string are replaced with values from the context array.

## Features

- **Immutable Design**: All setter methods return new instances, ensuring thread-safety and predictable behavior
- **Template-based Formatting**: Use `%placeholder%` syntax in format strings
- **Dynamic Context Resolution**: Supports static values, callables, and closures in context
- **Built-in Parameters**: Automatically provides `datetime`, `level`, and `message` in context
- **PSR-3 Compatible**: Works with standard log levels (DEBUG, INFO, WARNING, ERROR, etc.)
- **Stringable**: Implements `\Stringable` interface for easy casting to string

## Constructor

```php
public function __construct(
    private readonly string    $format,
    private readonly string    $message,
    private readonly ?LogLevel $level = null,
    private array              $context = [],
    private readonly ?DateTime $dateTime = null,
)
```

### Parameters

- **$format** (string): Template string with `%placeholder%` syntax for variable substitution
- **$message** (string): The log message content
- **$level** (?LogLevel): PSR-3 log level (DEBUG, INFO, NOTICE, WARNING, ERROR, CRITICAL, ALERT, EMERGENCY)
- **$context** (array): Additional context data for placeholder replacement
- **$dateTime** (?DateTime): Timestamp for the log entry (defaults to current time)

## Built-in Context Parameters

Every LogEntry automatically includes these parameters:

- **%datetime%**: Formatted as 'Y-m-d H:i:s'
- **%level%**: The log level value (e.g., 'error', 'info')
- **%message%**: The log message

## Methods

### Immutable Setters

All setter methods return a new `LogEntry` instance:

- **setFormat(string $format): self** - Create entry with new format string
- **setMessage(string $message): self** - Create entry with new message
- **setParams(array $params): self** - Create entry with new context parameters
- **setDateTime(DateTime $dateTime): self** - Create entry with new timestamp
- **setLevel(LogLevel $level): self** - Create entry with new log level

### String Conversion

- **__toString(): string** - Compiles and returns the formatted log entry

## Usage Examples

### Basic Usage

```php
use Foamycastle\Analog\LogEntry;
use Foamycastle\Analog\LogLevel;

$entry = new LogEntry(
    format: '[%datetime%] %level%: %message%',
    message: 'User login successful',
    level: LogLevel::INFO
);

echo $entry;
// Output: [2025-12-11 20:15:30] info: User login successful
```

### Custom Context Parameters

```php
$entry = new LogEntry(
    format: '[%datetime%] %level%: %message% - User: %username%, IP: %ip%',
    message: 'Authentication attempt',
    level: LogLevel::WARNING,
    context: [
        'username' => 'john_doe',
        'ip' => '192.168.1.100'
    ]
);

echo $entry;
// Output: [2025-12-11 20:15:30] warning: Authentication attempt - User: john_doe, IP: 192.168.1.100
```

### Using Callables in Context

```php
$entry = new LogEntry(
    format: '[%datetime%] Memory: %memory% - %message%',
    message: 'Process completed',
    level: LogLevel::DEBUG,
    context: [
        'memory' => fn() => memory_get_usage(true) / 1024 / 1024 . 'MB'
    ]
);

echo $entry;
// Output: [2025-12-11 20:15:30] Memory: 2.5MB - Process completed
```

### Immutable Updates

```php
$baseEntry = new LogEntry(
    format: '[%datetime%] %level%: %message%',
    message: 'Original message',
    level: LogLevel::INFO
);

// Create new entry with different level
$errorEntry = $baseEntry->setLevel(LogLevel::ERROR);
$errorEntry = $errorEntry->setMessage('Error occurred');

// $baseEntry remains unchanged
echo $baseEntry; // [2025-12-11 20:15:30] info: Original message
echo $errorEntry; // [2025-12-11 20:15:30] error: Error occurred
```

### Custom Date Format

```php
$customDate = new DateTime('2025-01-01 12:00:00');

$entry = new LogEntry(
    format: '%message% at %datetime%',
    message: 'Happy New Year',
    level: LogLevel::INFO,
    dateTime: $customDate
);

echo $entry;
// Output: Happy New Year at 2025-01-01 12:00:00
```

## How It Works

1. **Parameter Matching**: The constructor scans the format string for `%placeholder%` patterns
2. **Context Merging**: Built-in parameters (datetime, level, message) are merged with custom context
3. **Parameter Resolution**: When converted to string:
   - Callables are executed and their return values are used
   - Closures are invoked
   - Static values are used as-is
4. **Compilation**: Placeholders in the format string are replaced with resolved values

## Implementation Notes

### Context Value Types

The context array supports three types of values:

1. **Static values**: Used directly (strings, numbers, etc.)
2. **Callables**: Executed at compile time, return value is used
3. **Closures**: Invoked at compile time

### DateTime Handling

If no DateTime is provided, the entry uses the current time at construction. The datetime context parameter uses a callable to format the date as needed.

### Performance Considerations

- Parameter resolution is lazy - values are only computed when the entry is converted to a string
- Callables in context allow for expensive operations (like memory usage) to be deferred until needed
- The immutable design means creating variations has a small memory overhead

## Related Classes

- **LogLevel**: Enum defining PSR-3 compatible log levels

## Author

Aaron Sollman (unclepong@gmail.com)
Created: 12/11/25
