<?php

namespace App\Security\Voter;

use App\Entity\Question;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class QuestionVoter extends Voter
{
    protected function supports(string $attribute, $question): bool
    {
        return in_array($attribute, ['MANAGE_QUESTION'])
            && $question instanceof Question;
    }

    protected function voteOnAttribute(string $attribute, $question, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case 'MANAGE_QUESTION':
                return $question->getUser() == $user;
        }

        return false;
    }
}
