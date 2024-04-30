<?php

namespace Luminarix\JSONL;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Str;
use RuntimeException;

class JSONL
{
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
     */
    public function parseToDto(string $filePath, string $dtoClass): LazyCollection
    {
        return $this->parseLinesToDto($this->readLines($filePath), $dtoClass);
    }

    public function encode(array|Collection $objects): string
    {
        return $this->encodeLines($objects);
    }

    public function encodeFromDto(array|Collection $dtos): string
    {
        return $this->encodeLinesFromDto($dtos);
    }

    public function writeFromDto(string $filePath, array|Collection $dtos): void
    {
        File::put($filePath, $this->encodeFromDto($dtos));
    }

    public function write(string $filePath, array|Collection $objects): void
    {
        File::put($filePath, $this->encode($objects));
    }

    protected function readLines(string $filePath): LazyCollection
    {
        return LazyCollection::make(static function () use ($filePath) {
            $handle = fopen($filePath, 'rb');

            if ($handle === false) {
                throw new RuntimeException("Failed to open file: {$filePath}");
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

    protected function parseLines(LazyCollection $lines): LazyCollection
    {
        return $lines
            ->reject(static fn ($line) => $line->isEmpty())
            ->map(static function ($line) {
                $object = json_decode($line, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new RuntimeException("Invalid JSON: {$line}");
                }

                return $object;
            });
    }

    protected function parseLinesToDto(LazyCollection $lines, string $dtoClass): LazyCollection
    {
        return $lines
            ->reject(static fn ($line) => $line->isEmpty())
            ->map(static function ($line) use ($dtoClass) {
                $data = json_decode($line, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new RuntimeException("Invalid JSON: {$line}");
                }

                return new $dtoClass(...$data);
            });
    }

    protected function encodeLines(array|Collection $objects): string
    {
        return LazyCollection::make($objects)
            ->map(static function ($object) {
                $json = json_encode($object, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new RuntimeException('JSON encoding failed: ' . json_last_error_msg());
                }

                return $json;
            })
            ->implode("\n");
    }

    protected function encodeLinesFromDto(array|Collection $dtos): string
    {
        return LazyCollection::make($dtos)
            ->map(static function ($dto) {
                $data = get_object_vars($dto);
                $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new RuntimeException('JSON encoding failed: ' . json_last_error_msg());
                }

                return $json;
            })
            ->implode("\n");
    }
}
