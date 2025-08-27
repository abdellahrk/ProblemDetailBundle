<?php

/*
 * Copyright (c) 2025.
 *
 * This file is part of the ProblemDetailBundle.
 *
 * @author Abdel Ramadan <ramadanabdel24@gmail.com>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace Symfony\Component\HttpFoundation\Tests;

use PHPUnit\Framework\TestCase;
use Rami\ProblemDetailBundle\ProblemResponse\ProblemDetailsJsonResponse;

/**
 * @author Abdellah Ramadan <ramadanabdel24@gmail.com>
 */
class ProblemDetailsJsonResponseTest extends TestCase
{
    public function testNewProblemWithNoParams()
    {
        $problemDetails = new ProblemDetailsJsonResponse();
        $this->assertSame(500, $problemDetails->getStatusCode());

        $this->assertSame('about:blank', json_decode($problemDetails->getContent(), true)['type']);

        $this->assertSame('application/problem+json', $problemDetails->headers->get('Content-Type'));
        $this->assertSame('Internal Server Error', json_decode($problemDetails->getContent(), true)['title']);
    }

    public function testStatusCode()
    {
        $problemDetails = new ProblemDetailsJsonResponse(404);
        $this->assertSame(404, $problemDetails->getStatusCode());
    }

    public function testNewProblemWithParams()
    {
        $problemDetails = new ProblemDetailsJsonResponse(401, 'https://example.com/not-found-docs', 'Unauthorized', 'No access to this resource');

        $this->assertSame(401, json_decode($problemDetails->getContent(), true)['status']);
        $this->assertSame('Unauthorized', json_decode($problemDetails->getContent(), true)['title']);
        $this->assertSame('No access to this resource', json_decode($problemDetails->getContent(), true)['detail']);
        $this->assertSame('https://example.com/not-found-docs', json_decode($problemDetails->getContent(), true)['type']);
        $this->assertSame('application/problem+json', $problemDetails->headers->get('Content-Type'));
    }

    public function testEmptyTitle()
    {
        $problemDetails = new ProblemDetailsJsonResponse(402);
        $this->assertNotNull(json_decode($problemDetails->getContent(), true)['title']);
        $this->assertSame('Payment Required', json_decode($problemDetails->getContent(), true)['title']);
    }

    public function testExtensions()
    {
        $problemDetails = new ProblemDetailsJsonResponse(500, extensions: ['foo' => 'bar']);

        $this->assertArrayHasKey('foo', json_decode($problemDetails->getContent(), true));

        $problemDetails = new ProblemDetailsJsonResponse(400, extensions: ['foo' => 'bar', 'baz' => ['bar' => 'foo']]);
        $this->assertIsArray(json_decode($problemDetails->getContent(), true)['baz']);
    }

    public function testInstance()
    {
        $problemDetails = new ProblemDetailsJsonResponse(400, instance: 'article/5');
        $this->assertIsString(json_decode($problemDetails->getContent(), true)['instance']);
    }
}
