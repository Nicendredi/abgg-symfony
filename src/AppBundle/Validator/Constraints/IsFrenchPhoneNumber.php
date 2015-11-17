<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsFrenchPhoneNumber extends Constraint
{
    public $message = 'Ce numéro "%string%" ne semble pas être un numéro français. Il doit ressembler à ceci : +33XXXXXXXXX';
}
