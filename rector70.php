<?php

declare(strict_types=1);

use Rector\Core\ValueObject\PhpVersion;
use Rector\DowngradePhp70\Rector\FunctionLike\DowngradeScalarTypeDeclarationRector as DowngradeScalarTypeDeclarationRectorAlias;
use Rector\DowngradePhp70\Rector\FunctionLike\DowngradeThrowableTypeDeclarationRector;
use Rector\DowngradePhp71\Rector\Array_\SymmetricArrayDestructuringToListRector;
use Rector\DowngradePhp71\Rector\ClassConst\DowngradeClassConstantVisibilityRector;
use Rector\DowngradePhp71\Rector\FunctionLike\DowngradeIterablePseudoTypeDeclarationRector;
use Rector\DowngradePhp71\Rector\FunctionLike\DowngradeNullableTypeDeclarationRector;
use Rector\DowngradePhp71\Rector\FunctionLike\DowngradeVoidTypeDeclarationRector;
use Rector\DowngradePhp71\Rector\String_\DowngradeNegativeStringOffsetToStrlenRector;
use Rector\DowngradePhp71\Rector\TryCatch\DowngradePipeToMultiCatchExceptionRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
	// here we can define, what sets of rules will be applied
	$rectorConfig->paths([
		// autoload specific file
		__DIR__ . '/hexagonal-reviews/src',
	]);

	$services = $rectorConfig->services();
	$services->set(DowngradeIterablePseudoTypeDeclarationRector::class);
	$services->set(DowngradeNullableTypeDeclarationRector::class);
	$services->set(DowngradeVoidTypeDeclarationRector::class);

	$rectorConfig->phpVersion(PhpVersion::PHP_70);

	// skip root namespace classes, like \DateTime or \Exception [default: true]
	$rectorConfig->importShortClasses(false);

	// skip classes used in PHP DocBlocks, like in /** @var \Some\Class */ [default: true]
	$rectorConfig->importNames(true, false);

	$services->set(DowngradeClassConstantVisibilityRector::class);
	$services->set(DowngradePipeToMultiCatchExceptionRector::class);
	$services->set(SymmetricArrayDestructuringToListRector::class);
	$services->set(DowngradeNegativeStringOffsetToStrlenRector::class);

	$services->set(DowngradeScalarTypeDeclarationRectorAlias::class);
	$services->set(DowngradeThrowableTypeDeclarationRector::class);
};
