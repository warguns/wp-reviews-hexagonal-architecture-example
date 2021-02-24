<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
	// here we can define, what sets of rules will be applied
	$parameters = $containerConfigurator->parameters();
	$parameters->set(Option::AUTOLOAD_PATHS, [
		// autoload specific file
		__DIR__ . '/hexagonal-reviews/src',
	]);
	$parameters->set(Option::SETS, [SetList::PHP_74]);
};
