<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;

class HasDifferentRolesValidator extends ConstraintValidator
{
  public function validate($object, Constraint $constraint)
  {
	return true;
  }
}
