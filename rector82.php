<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {

	$rectorConfig->parameters();
	$rectorConfig->paths([
		__DIR__ . '/hexagonal-reviews/src',
	]);
	$rectorConfig->sets([SetList::PHP_82]);
};
