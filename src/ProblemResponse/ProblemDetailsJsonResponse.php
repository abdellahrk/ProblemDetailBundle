<?php
/**
* Copyright (c) 2025.
*
 * This file is part of the ProblemDetailBundle.
 *
 * @author Abdel Ramadan <ramadanabdel24@gmail.com>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace Rami\ProblemDetailBundle\ProblemResponse;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Exception\ProblemDetailsJsonResponseException;

/**
 * Represents a JSON response with a Problem Details object.
 *
 * @author Abdellah Ramadan <ramadanabdel24@gmail.com>
 */
class ProblemDetailsJsonResponse extends Response
{
    public function __construct(
        int $status = 500,
        string $type = 'about:blank',
        ?string $title = null,
        ?string $detail = null,
        ?string $instance = null,
        ?array $extensions = [],
    ) {
        parent::__construct();

        $this->statusCode = $status;

        if ($status < 400 || $status > 599) {
            throw new ProblemDetailsJsonResponseException(\sprintf('The status code "%s" is not a valid HTTP Status Code error.', $this->statusCode));
        }

        if ($title && null === $type || null === $title) {
            $title = Response::$statusTexts[$status];
        }

        if (null !== $type) {
            $scheme = parse_url($type, \PHP_URL_SCHEME);
            if (null === $scheme) {
                throw new ProblemDetailsJsonResponseException("Invalid url type: $type.");
            }
        }

        $problemDetails = [
            'type' => $type,
            'title' => $title,
            'detail' => $detail,
            'status' => $status,
            'instance' => $instance,
            ...$extensions,
        ];

        $problemDetails = array_filter($problemDetails, function ($value) {
            return null !== $value;
        });

        $this->headers->set('Content-Type', 'application/problem+json');

        $content = json_encode($problemDetails, \JSON_FORCE_OBJECT | \JSON_PRETTY_PRINT | \JSON_THROW_ON_ERROR);

        $this->setContent($content);
    }
}