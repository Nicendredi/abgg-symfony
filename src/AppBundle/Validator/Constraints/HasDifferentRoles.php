<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class HasDifferentRoles extends Constraint
{
    public $message = 'Vous devez choisir des roles différent pour chacun des 5 choix.';

    public function validatedBy()
    {
        return 'has_different_roles';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
