<?php

namespace Luminarix\JSONL;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Str;
use Luminarix\JSONL\Exceptions\FailedToEncodeException;
use Luminarix\JSONL\Exceptions\FailedToOpenFileException;
use Luminarix\JSONL\Exceptions\FailedToWriteFileException;
use Luminarix\JSONL\Exceptions\InvalidDtoClassException;
use Luminarix\JSONL\Exceptions\InvalidRowException;

class JSONL
{
    /**
     * @throws FailedToEncodeException If encoding fails.
     */
    public function encode(array|Collection|LazyCollection $objects): string
    {
        return $this->encodeLines($objects);
    }

    /**
     * @throws FailedToEncodeException If encoding fails.
     */
    public function encodeFromDto(array|Collection|LazyCollection $dtos): string
    {
        return $this->encodeLines($dtos, true);
    }

    /**
     * @throws FailedToOpenFileException If <code>$filePath</code> cannot be opened.
     * @throws InvalidRowException If a row is invalid.
     */
    public function parse(string $filePath): LazyCollection
    {
        return $this->parseLines($this->readLines($filePath));
    }

    /**
     * Parse JSON Lines into DTOs.
     *
     * @template T
     *
     * @param  class-string<T>  $dtoClass
     * @return LazyCollection<int, T>
     *
     * @throws FailedToOpenFileException If <code>$filePath</code> cannot be opened.
     * @throws InvalidRowException If a row is invalid.
     * @throws InvalidDtoClassException If <code>$dtoClass</code> doesn't exist.
     */
    public function parseToDto(string $filePath, string $dtoClass): LazyCollection
    {
        return $this->parseLines($this->readLines($filePath), $dtoClass);
    }

    /**
     * @throws FailedToEncodeException If encoding fails.
     * @throws FailedToWriteFileException If writing fails.
     */
    public function writeFromDto(string $filePath, array|Collection|LazyCollection $dtos, bool $lock = false): void
    {
        $result = File::put($filePath, $this->encodeFromDto($dtos), $lock);

        if ($result === false) {
            throw new FailedToWriteFileException("Failed to write file: {$filePath}");
        }
    }

    /**
     * @throws FailedToEncodeException If encoding fails.
     * @throws FailedToWriteFileException If writing fails.
     */
    public function write(string $filePath, array|Collection|LazyCollection $objects, bool $lock = false): void
    {
        $result = File::put($filePath, $this->encode($objects), $lock);

        if ($result === false) {
            throw new FailedToWriteFileException("Failed to write file: {$filePath}");
        }
    }

    protected function encodeLines(array|Collection|LazyCollection $objects, bool $dto = false): string
    {
        if (!$objects instanceof LazyCollection) {
            $objects = LazyCollection::make($objects);
        }

        return $objects
            ->map(static function ($object) use ($dto) {
                if ($dto) {
                    if (!is_object($object)) {
                        throw new FailedToEncodeException('DTO must be an object');
                    }

                    $object = get_object_vars($object);
                }

                $json = json_encode($object, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new FailedToEncodeException('JSON encoding failed: ' . json_last_error_msg());
                }

                return $json;
            })
            ->implode("\n");
    }

    protected function parseLines(LazyCollection $lines, ?string $dtoClass = null): LazyCollection
    {
        return $lines
            ->reject(static fn ($line) => $line->isEmpty())
            ->map(static function ($line) use ($dtoClass) {
                /** @var string $line */
                $object = json_decode($line, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new InvalidRowException("Invalid row: {$line}");
                }

                if ($dtoClass !== null) {
                    if (!class_exists($dtoClass)) {
                        throw new InvalidDtoClassException("Invalid DTO class: {$dtoClass}");
                    }

                    return new $dtoClass(...$object);
                }

                return $object;
            });
    }

    protected function readLines(string $filePath): LazyCollection
    {
        return LazyCollection::make(static function () use ($filePath) {
            $handle = fopen($filePath, 'rb');

            if ($handle === false) {
                throw new FailedToOpenFileException("Failed to open file: {$filePath}");
            }

            try {
                while (($line = fgets($handle)) !== false) {
                    yield Str::of($line)->trim();
                }
            } finally {
                fclose($handle);
            }
        });
    }
}
