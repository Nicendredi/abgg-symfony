<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class HasDifferentPlayers extends Constraint
{
    public $message = 'Vous devez choisir un joueur au minimum, sept au maximum.';

    public function validatedBy()
    {
        return 'has_different_players';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
