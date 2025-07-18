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

use Rami\ProblemDetailBundle\Exceptions\ProblemDetailResponseException;
use Symfony\Component\HttpFoundation\Response;

class ProblemResponse extends Response
{

    /**
     * @param int $status the HTTP status code for the response
     *
     * @param string|null $type the URI of the problem type. If not set, the default value is "about:blank".
     *
     * @param string|null $title the human-readable explanation specific to this occurrence of the problem. If $type is empty, i.e. "about:blank", the default HTTP status text is used e.g., unauthorized.
     *
     * @param string|null $detail a human-readable explanation specific to this occurrence of the problem. If not set, the default value is "Unknown error".
     *
     * @param string|null $instance a URI that identifies the specific occurrence of the problem. If not set, the default value is null.
     *
     * @param array[] $extensions a JSON array containing references to the source of the error, optionally including any of the following members:
     *
     * - file: The filename of the source file that produced the error.
     * - line: The line number in the source file at which the error occurred.
     *
     * @throws ProblemDetailResponseException
     * @throws \JsonException
     */
    public function __construct(
        public int $status,
        public ?string $type = null,
        public ?string $title = null,
        public ?string $detail = null,
        public ?string $instance = null,
        public array $extensions = [],
    )
    {
        parent::__construct(status: $this->status);

        $this->title = $this->title && $this->type === null ? Response::$statusTexts[$this->status] : $this->title;

        $this->setProblemContent();
    }

    public function setHeaders(): void
    {
        $this->headers->set('Content-Type', 'application/problem+json');
        $this->headers->set('Content-Language', $this->headers->get('Content-Language'));
    }

    /**
     * @throws ProblemDetailResponseException
     */
    public function checkErrorStatus(): void
    {
        if (!($this->status >= 400) || !($this->status < 600)) {
            throw new ProblemDetailResponseException("Not a valid HTTP error: $this->status");
        }
    }

    public function setProblemContent(): string
    {
        $this->checkErrorStatus();

        if (null !== $this->type) {
            $scheme = parse_url($this->type, PHP_URL_SCHEME);
            if (null === $scheme) {
                throw new ProblemDetailResponseException("Invalid url type: $this->type");
            }
        }

        $problem = [
            'type' => $this->type ?? 'about:blank',
            'title' =>  $this->title ?? Response::$statusTexts[$this->status],
            'detail' => $this->detail ?? null,
            'status' => $this->status,
            'instance' => $this->instance ?? null,
            ...$this->extensions
        ];

        $problem = array_filter($problem, function ($value) {
            return $value !== null;
        });

        $this->setHeaders();

        $content = json_encode($problem, JSON_THROW_ON_ERROR|JSON_PRETTY_PRINT);

        return $this->setContent($content);
    }
}