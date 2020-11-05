<?php

namespace Tests\Morebec\Orkestra\Normalization;

use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Normalization\ObjectNormalizer;
use PHPUnit\Framework\TestCase;

class ObjectNormalizerTest extends TestCase
{
    public function testDenormalize(): void
    {
        $normalizer = new ObjectNormalizer();

        $headers = new DomainMessageHeaders([
            'hello' => 'world',
            'foo' => 'bar',
        ]);

        $data = $normalizer->normalize($headers);

        $denormalizedHeaders = $normalizer->denormalize($data, DomainMessageHeaders::class);

        $this->assertEquals($headers, $denormalizedHeaders);
    }

    public function testNormalize(): void
    {
        $normalizer = new ObjectNormalizer();

        $headers = new DomainMessageHeaders([
            'hello' => 'world',
            'foo' => 'bar',
        ]);

        $data = $normalizer->normalize($headers);

        $this->assertEquals([
            'hello' => 'world',
            'foo' => 'bar',
        ], $data);
    }
}
