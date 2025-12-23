<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector;

return RectorConfig::configure()
  ->withPaths([
    __DIR__ . "/src",
    __DIR__ . "/tests",
  ])
  ->withSkip([
    AddOverrideAttributeToOverriddenMethodsRector::class
  ])
  ->withRules([
    TypedPropertyFromStrictConstructorRector::class
  ])
  ->withPreparedSets(
    deadCode: true,
    codeQuality: true,
    typeDeclarations: true,
    privatization: true,
    earlyReturn: true,
    strictBooleans: true,
  )
  ->withPhpSets();
