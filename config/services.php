<?php

use Rami\ProblemDetailBundle\Exceptions\ProblemDetailResponseException;
use Rami\ProblemDetailBundle\ProblemResponse\ProblemResponse;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function(ContainerConfigurator $configurator): void
{
    $services = $configurator->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->set('problem.response', ProblemResponse::class)->tag('problem_response_tag');
    $services->set('problem.response.exception', ProblemDetailResponseException::class)->tag('problem_response_exception');
};