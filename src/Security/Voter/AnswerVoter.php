<?php

namespace App\Security\Voter;

use App\Entity\Answer;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class AnswerVoter extends Voter
{
    protected function supports(string $attribute, $answer): bool
    {
        return in_array($attribute, ['MANAGE_ANSWER'])
            && $answer instanceof Answer;
    }

    protected function voteOnAttribute(string $attribute, $answer, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case 'MANAGE_ANSWER':
                return $answer->getUser() == $user;
        }

        return false;
    }
}
