<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/** @var ClassLoader $loader */
$loader = require __DIR__.'/../vendor/autoload.php';
// 'PHPPdf' => __DIR__.'/../vendor/PHPPdf/lib',
// 'Imagine' => array(__DIR__.'/../vendor/PHPPdf/lib', __DIR__.'/../vendor/PHPPdf/lib/vendor/Imagine/lib'),
// 'Zend' => __DIR__.'/../vendor/PHPPdf/lib/vendor/Zend/library',
// 'ZendPdf' => __DIR__.'/../vendor/PHPPdf/lib/vendor/ZendPdf/library',

AnnotationRegistry::registerLoader([$loader, 'loadClass']);

return $loader;