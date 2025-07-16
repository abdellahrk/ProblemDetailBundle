<?php

namespace Rami\ProblemDetailBundle;

use Symfony\Component\HttpFoundation\Response;

class ProblemResponse extends Response
{
    public string $type = 'about:blank';
    public string $title = '';
    public string $detail = '';
    public int $status;
    public mixed $instance = null;
    public mixed $trace = null;

    public function __construct(int $status, ?string $title = null, ?string $detail = null)
    {
        $this->status = $status;
        $this->title = $title;
        $this->detail = $detail;
        parent::__construct(content: $this->title, status: $this->status);

        $this->setProblemContent();
    }

    public function setHeaders(): void
    {
        //if ($this->headers->has('Content-Type') && $this->headers->get('Content-Type') !== 'application/problem+json') {
            $this->headers->set('Content-Type', 'application/problem+json');
        //}
    }

    /**
     * @throws \JsonException
     */
    public function setProblemContent(): string
    {
        $problem = [
            'type' => $this->type ?? null,
            'title' => $this->title ?? null,
            'detail' => $this->detail ?? null,
            'status' => $this->status,
            'instance' => $this->instance ?? null,
            'trace' => $this->trace ?? null,
        ];

        $problem = array_filter($problem, function ($value) {
            return $value !== null;
        });

        $this->setHeaders();

        $content = json_encode($problem, JSON_THROW_ON_ERROR|JSON_PRETTY_PRINT);

        return $this->setContent($content);
    }
}