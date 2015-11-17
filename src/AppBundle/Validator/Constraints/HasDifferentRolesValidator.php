<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;

class HasDifferentRolesValidator extends ConstraintValidator
{
  public function validate($object, Constraint $constraint)
  {
    if ($object->getRole1() === $object->getRole2() OR
    $object->getRole1() === $object->getRole3() OR
    $object->getRole1() === $object->getRole4() OR
    $object->getRole1() === $object->getRole5()){
      $this->context->buildViolation($constraint->message)
      ->atPath('role_1')
      ->addViolation();
      return;
    }

    if ($object->getRole2() === $object->getRole3() OR
    $object->getRole2() === $object->getRole4() OR
    $object->getRole2() === $object->getRole5()){
      $this->context->buildViolation($constraint->message)
      ->atPath('role_2')
      ->addViolation();
      return;
    }
    if ($object->getRole3() === $object->getRole4() OR
    $object->getRole3() === $object->getRole5()){
      $this->context->buildViolation($constraint->message)
      ->atPath('role_3')
      ->addViolation();
      return;
    }
    if ($object->getRole4() === $object->getRole5()){
      $this->context->buildViolation($constraint->message)
      ->atPath('role_4')
      ->addViolation();
      return;
    }
  }
}
