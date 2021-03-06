<?php

/*
 * This file is part of the Elcodi package.
 *
 * Copyright (c) 2014-2015 Elcodi.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @author Aldo Chiecchia <zimage@tiscali.it>
 * @author Elcodi Team <tech@elcodi.com>
 */

namespace Elcodi\Common\ConfigurationBundle\Resolver;

use Mmoreram\ControllerExtraBundle\Annotation\Abstracts\Annotation;
use Mmoreram\ControllerExtraBundle\Resolver\Interfaces\AnnotationResolverInterface;
use ReflectionMethod;
use Symfony\Component\HttpFoundation\Request;

use Elcodi\Common\ConfigurationBundle\Annotation\Configuration as AnnotationConfiguration;
use Elcodi\Component\Configuration\Exception\ConfigurationParameterNotFoundException;
use Elcodi\Component\Configuration\Services\ConfigurationManager;

/**
 * ConfigurationResolver
 *
 * Resolve Configuration annotations
 */
class ConfigurationResolver implements AnnotationResolverInterface
{
    /**
     * @var ConfigurationManager
     *
     * Configuration manager
     */
    protected $configurationManager;

    /**
     * Construct method
     *
     * @param $configurationManager ConfigurationManager
     */
    public function __construct(ConfigurationManager $configurationManager)
    {
        $this->configurationManager = $configurationManager;
    }

    /**
     * Specific annotation evaluation
     *
     * @param Request          $request    Request
     * @param Annotation       $annotation Annotation
     * @param ReflectionMethod $method     Method
     *
     * @return $this self Object
     *
     * @throws ConfigurationParameterNotFoundException
     */
    public function evaluateAnnotation(
        Request $request,
        Annotation $annotation,
        ReflectionMethod $method
    ) {
        if ($annotation instanceof AnnotationConfiguration) {
            $value = $this->resolveValue($annotation);

            $request
                ->attributes
                ->set($annotation->name, $value);
        }

        return $this;
    }

    /**
     * Resolve configuration value from annotation
     *
     * @param AnnotationConfiguration $annotation
     *
     * @throws ConfigurationParameterNotFoundException
     *
     * @return mixed
     */
    protected function resolveValue(AnnotationConfiguration $annotation)
    {
        return $this
            ->configurationManager
            ->get(
                $annotation->key,
                $annotation->default
            );
    }
}
