<?php

/*
 * This file is part of the RichModelFormsBundle package.
 *
 * (c) Christian Flothmann <christian.flothmann@sensiolabs.de>
 * (c) Christopher Hertel <christopher.hertel@sensiolabs.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SensioLabs\RichModelForms\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\TypedReference;

/**
 * @author Christian Flothmann <christian.flothmann@sensiolabs.de>
 */
final class RegisterExceptionHandlersPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('sensiolabs.rich_model_forms.exception_handler.registry')) {
            return;
        }

        $exceptionHandlerRegistry = $container->getDefinition('sensiolabs.rich_model_forms.exception_handler.registry');

        $exceptionHandlers = [];
        $strategies = [];

        foreach ($container->findTaggedServiceIds('sensiolabs.rich_model_forms.exception_handler') as $id => $tag) {
            $class = $container->getParameterBag()->resolveValue($container->getDefinition($id)->getClass());
            $exceptionHandlers[$id] = new TypedReference($id, $class);

            foreach ($tag as $attributes) {
                $strategies[$attributes['strategy']] = $id;
            }
        }

        $exceptionHandlersLocator = ServiceLocatorTagPass::register($container, $exceptionHandlers);
        $exceptionHandlerRegistry->setArgument('$container', $exceptionHandlersLocator);
        $exceptionHandlerRegistry->setArgument('$strategies', $strategies);
    }
}
