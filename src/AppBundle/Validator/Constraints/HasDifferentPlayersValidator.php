<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;

class HasDifferentPlayersValidator extends ConstraintValidator
{
  public function validate($object, Constraint $constraint)
  {
	$array = $object->getPlayer()->toArray();
	$number = count($array);
	if($number>6)
	{
		$this->context->buildViolation($constraint->message)
	      ->atPath('player')
	      ->addViolation();
	      return;
	}
    
	return true;
  }
}
