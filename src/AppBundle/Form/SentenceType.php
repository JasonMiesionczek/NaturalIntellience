<?php

namespace AppBundle\Form;

use AppBundle\Service\SentenceService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SentenceType extends AbstractType
{
    /** @var SentenceService **/
    private $sentenceService;

    /**
     *
     * @param SentenceService $sentenceService [description]
     */
    public function __construct(SentenceService $sentenceService = null)
    {
        $this->sentenceService = $sentenceService ?: new SentenceService();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextType::class)
            ->add('groups', 'entity', array(
                'class' => 'AppBundle:SentenceGroup',
                'property' => 'name',
                'multiple' => true
            ))
            ->add('save', SubmitType::class, array('label' => 'Save'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getName()
    {
        return 'app_bundle_sentence_type';
    }
}
