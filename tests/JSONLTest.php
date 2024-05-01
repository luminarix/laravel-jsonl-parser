<?php

use Luminarix\JSONL\Facades\JSONL;
use Luminarix\JSONL\Tests\classes\ExampleDTO;

$datasetPath = 'tests/datasets/example.jsonl';
$outputPath = 'tests/datasets/output.jsonl';

dataset('example dataset', [
    'example dataset' => [
        fn () => [
            'array' => [
                ['name' => 'Gilbert', 'wins' => [['straight', '7♣'], ['one pair', '10♥']]],
                ['name' => 'Alexa', 'wins' => [['two pair', '4♠'], ['two pair', '9♠']]],
                ['name' => 'May', 'wins' => []],
                ['name' => 'Deloise', 'wins' => [['three of a kind', '5♣']]],
            ],
            'jsonl' => '{"name":"Gilbert","wins":[["straight","7♣"],["one pair","10♥"]]}
{"name":"Alexa","wins":[["two pair","4♠"],["two pair","9♠"]]}
{"name":"May","wins":[]}
{"name":"Deloise","wins":[["three of a kind","5♣"]]}',
        ],
    ],
]);

afterEach(function () use ($outputPath) {
    if (file_exists($outputPath)) {
        unlink($outputPath);
    }
});

it('can read JSONL file', function (array $dataset) use ($datasetPath) {
    $decoded = JSONL::parse($datasetPath);

    foreach ($decoded as $index => $line) {
        expect($line)->toBe($dataset['array'][$index]);
    }
})->with('example dataset');

it('can write JSONL file', function () use ($datasetPath, $outputPath) {
    $dataset = JSONL::parse($datasetPath);

    JSONL::write($outputPath, $dataset);

    expect(file_exists($outputPath))->toBeTrue()
        ->and(JSONL::parse($outputPath))->toEqual($dataset);
});

it('can parse JSONL to DTO', function (array $dataset) use ($datasetPath) {
    $decoded = JSONL::parseToDto($datasetPath, ExampleDTO::class);

    foreach ($decoded as $index => $line) {
        expect($line)->toBeInstanceOf(ExampleDTO::class)
            ->and($line->toArray())->toEqual($dataset['array'][$index]);
    }
})->with('example dataset');

it('can write DTO to JSONL file', function () use ($datasetPath, $outputPath) {
    $dataset = JSONL::parseToDto($datasetPath, ExampleDTO::class);

    JSONL::writeFromDto($outputPath, $dataset);

    expect(file_exists($outputPath))->toBeTrue()
        ->and(JSONL::parseToDto($outputPath, ExampleDTO::class))->toEqual($dataset);
});

it('can encode JSONL from DTO', function (array $dataset) use ($datasetPath) {
    $decoded = JSONL::parseToDto($datasetPath, ExampleDTO::class);

    expect(JSONL::encodeFromDto($decoded))->toBe($dataset['jsonl']);
})->with('example dataset');

it('can encode JSONL from array', function (array $dataset) use ($datasetPath) {
    $decoded = JSONL::parse($datasetPath);

    expect(JSONL::encode($decoded))->toBe($dataset['jsonl']);
})->with('example dataset');
