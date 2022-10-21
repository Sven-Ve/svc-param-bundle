<?php

namespace Svc\ParamBundle\Form;

use Svc\ParamBundle\Entity\Params;
use Svc\ParamBundle\Enum\ParamType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParamsType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    if ($options['dataType'] == ParamType::BOOL) {
      $builder
        ->add('ValueBool', CheckboxType::class, [
          'required' => false,
          'label' => 'Value',
        ]);
    } elseif ($options['dataType'] == ParamType::DATETIME) {
      $builder
        ->add('ValueDateTime', DateTimeType::class, [
          'date_widget' => 'single_text',
          'time_widget' => 'single_text',
          'label' => 'Date & Time',
        ]);
    } elseif ($options['dataType'] == ParamType::DATE) {
      $builder
        ->add('ValueDate', DateType::class, [
          'widget' => 'single_text',
          'label' => 'Date',
        ]);
    } elseif ($options['dataType'] == ParamType::INTEGER) {
      $builder
        ->add('Value', IntegerType::class, [
          'attr' => ['autofocus' => true],
          'label' => 'Integer',
        ]);
    } else {
      $builder
        ->add('Value', null, [
          'required' => false,
          'label' => 'Value',
          'attr' => ['autofocus' => true],
        ]);
    }
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      'data_class' => Params::class,
      'dataType' => null,
      'translation_domain' => 'ParamBundle',
    ]);
  }
}
