<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Rami\ProblemDetailBundle\ProblemResponse;
return static function(ContainerConfigurator $configurator): void
{
    $services = $configurator->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->set('problem.response', ProblemResponse::class)->tag('problem_response_tag');
};