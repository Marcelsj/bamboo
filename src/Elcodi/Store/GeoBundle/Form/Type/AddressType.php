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

namespace Elcodi\Store\GeoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Elcodi\Component\Core\Factory\Traits\FactoryTrait;

/**
 * Class AddressType
 */
class AddressType extends AbstractType
{
    use FactoryTrait;

    /**
     * Default form options
     *
     * @param OptionsResolverInterface $resolver
     *
     * @return array With the options
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'empty_data' => function () {
                $this
                    ->factory
                    ->create();
            },
            'data_class' => $this
                ->factory
                ->getEntityNamespace(),
        ]);
    }

    /**
     * Buildform function
     *
     * @param FormBuilderInterface $builder the formBuilder
     * @param array                $options the options for this form
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('city', 'hidden', [
                'required' => true,
            ])
            ->add('name', 'text', [
                'required' => true,
                'label'    => 'store.address.form.fields.name.label',
            ])
            ->add('recipientName', 'text', [
                'required' => true,
                'label'    => 'store.address.form.fields.recipientName.label',
            ])
            ->add('recipientSurname', 'text', [
                'required' => true,
                'label'    => 'store.address.form.fields.recipientSurname.label',
            ])
            ->add('address', 'text', [
                'required' => true,
                'label'    => 'store.address.form.fields.address.label',
            ])
            ->add('addressMore', 'text', [
                'required' => false,
                'label'    => 'store.address.form.fields.addressMore.label',
            ])
            ->add('postalcode', 'text', [
                'required' => true,
                'label'    => 'store.address.form.fields.postalcode.label',
            ])
            ->add('phone', 'text', [
                'required' => true,
                'label'    => 'store.address.form.fields.phone.label',
            ])
            ->add('mobile', 'text', [
                'required' => false,
                'label'    => 'store.address.form.fields.mobile.label',
            ])
            ->add('comments', 'textarea', [
                'required' => false,
                'label'    => 'store.address.form.fields.comments.label',
            ])
            ->add('send', 'submit', [
                'label' => 'store.address.form.fields.send.label',
            ]);
    }

    /**
     * Return unique name for this form
     *
     * @return string
     */
    public function getName()
    {
        return 'store_geo_form_type_address';
    }
}
