<?php

namespace Rami\ProblemDetailBundle;

use Symfony\Component\HttpFoundation\Response;

class ProblemResponse extends Response
{
    public string $type = 'about:blank';
    public string $title = '';
    public string $detail = '';
    public int $status;
    public mixed $instance;
    public mixed $trace;

    public function __construct(int $status, string $title = '')
    {
        $this->status = $status;
        parent::__construct(content: $this->title, status: $this->status);

        $this->setProblemContent();
    }

    public function setHeaders(): void
    {
        if ($this->headers->has('Content-Type') && $this->headers->get('Content-Type') !== 'application/problem+json') {
            $this->headers->set('Content-Type', 'application/problem+json');
        }
    }

    /**
     * @throws \JsonException
     */
    public function setProblemContent(): string
    {
        $problem = [
            'type' => $this->type,
            'title' => $this->title,
            'detail' => $this->detail,
            'status' => $this->status,
            'instance' => $this->instance,
            'trace' => $this->trace,
        ];

        $this->setHeaders();

        $content = json_encode($problem, JSON_THROW_ON_ERROR|JSON_PRETTY_PRINT);

        return $this->setContent($content);
    }
}