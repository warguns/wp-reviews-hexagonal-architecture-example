<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Rector\DowngradePhp71\Rector\Array_\SymmetricArrayDestructuringToListRector;
use Rector\DowngradePhp71\Rector\ClassConst\DowngradeClassConstantVisibilityRector;
use Rector\DowngradePhp71\Rector\FunctionLike\DowngradeNullableTypeParamDeclarationRector;
use Rector\DowngradePhp71\Rector\FunctionLike\DowngradeNullableTypeReturnDeclarationRector;
use Rector\DowngradePhp71\Rector\FunctionLike\DowngradeVoidTypeReturnDeclarationRector;
use Rector\DowngradePhp71\Rector\String_\DowngradeNegativeStringOffsetToStrlenRector;
use Rector\DowngradePhp71\Rector\TryCatch\DowngradePipeToMultiCatchExceptionRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use Rector\DowngradePhp70\Rector\FunctionLike\DowngradeTypeParamDeclarationRector;
use Rector\DowngradePhp70\Rector\FunctionLike\DowngradeTypeReturnDeclarationRector;

return static function (ContainerConfigurator $containerConfigurator): void {
	// here we can define, what sets of rules will be applied
	$parameters = $containerConfigurator->parameters();
	$parameters->set(Option::AUTOLOAD_PATHS, [
		// autoload specific file
		__DIR__ . '/hexagonal-reviews/src',
	]);
	$services = $containerConfigurator->services();
	$services->set(DowngradeNullableTypeParamDeclarationRector::class);
	$services->set(DowngradeNullableTypeReturnDeclarationRector::class);
	$services->set(DowngradeVoidTypeReturnDeclarationRector::class);

	$parameters = $containerConfigurator->parameters();
	$parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_70);

	// skip root namespace classes, like \DateTime or \Exception [default: true]
	$parameters->set(Option::IMPORT_SHORT_CLASSES, false);

	// skip classes used in PHP DocBlocks, like in /** @var \Some\Class */ [default: true]
	$parameters->set(Option::IMPORT_DOC_BLOCKS, false);

	$services->set(DowngradeClassConstantVisibilityRector::class);
	$services->set(DowngradePipeToMultiCatchExceptionRector::class);
	$services->set(SymmetricArrayDestructuringToListRector::class);
	$services->set(DowngradeNegativeStringOffsetToStrlenRector::class);

	$services->set(DowngradeTypeParamDeclarationRector::class);
	$services->set(DowngradeTypeReturnDeclarationRector::class);
};
