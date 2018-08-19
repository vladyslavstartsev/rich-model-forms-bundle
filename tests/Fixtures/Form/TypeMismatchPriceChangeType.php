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

namespace SensioLabs\RichModelForms\Tests\Fixtures\Form;

use SensioLabs\RichModelForms\Tests\Fixtures\Model\Price;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

class TypeMismatchPriceChangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $price = $builder->create('price', IntegerType::class)
            ->addViewTransformer(new CallbackTransformer(
                function ($value) {
                    if (!$value instanceof Price) {
                        return null;
                    }

                    return $value->amount();
                },
                function ($value) {
                    return $value;
                }
            ), true);

        $builder->add($price);
    }
}
