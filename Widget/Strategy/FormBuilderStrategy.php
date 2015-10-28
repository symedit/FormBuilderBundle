<?php

/*
 * This file is part of the SymEdit package.
 *
 * (c) Craig Blanchette <craig.blanchette@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SymEdit\Bundle\FormBuilderBundle\Widget\Strategy;

use Sylius\Component\Resource\Repository\RepositoryInterface;
use SymEdit\Bundle\FormBuilderBundle\Form\FormBuilderFactoryInterface;
use SymEdit\Bundle\WidgetBundle\Model\WidgetInterface;
use SymEdit\Bundle\WidgetBundle\Widget\Strategy\AbstractWidgetStrategy;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormBuilderStrategy extends AbstractWidgetStrategy
{
    protected $repository;
    protected $factory;

    public function __construct(RepositoryInterface $repository, FormBuilderFactoryInterface $factory)
    {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    public function execute(WidgetInterface $widget)
    {
        $model = $this->repository->findOneBy(array(
            'id' => $widget->getOption('form_builder_id'),
        ));

        $form = $this->factory->build($model);

        return $this->render($widget, array(
            'form' => $form->createView(),
        ));
    }

    public function buildForm(FormBuilderInterface $builder)
    {
        $builder
            ->add('form_builder_id', 'entity_property', array(
                'class' => $this->repository->getClassName(),
                'property' => 'legend',
                'property_value' => 'id',
                'label' => 'Form to display',
            ))
        ;
    }

    public function getDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'template' => '@SymEdit/Widget/FormBuilder/form.html.twig',
        ));
    }

    public function getCacheOptions(WidgetInterface $widget)
    {
        return array(
            'private' => true,
        );
    }

    public function getDescription()
    {
        return 'form_builder.form_builder';
    }

    public function getName()
    {
        return 'form_builder';
    }
}
