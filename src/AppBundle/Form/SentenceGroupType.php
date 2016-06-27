<?php

namespace AppBundle\Form;

use AppBundle\Service\SentenceService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SentenceGroupType extends AbstractType
{
    /** @var SentenceService **/
    private $sentenceService;

    /**
     * @param SentenceService $sentenceService
     */
    public function __construct(SentenceService $sentenceService = null)
    {
        $this->sentenceService = $sentenceService ?: new SentenceService();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Save'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getName()
    {
        return 'app_bundle_sentence_group';
    }
}
