<?php

namespace WidgetBundle\Service;

use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Validator\ValidatorBuilder;

use WidgetBundle\Entity\UsernameNotFoundException;
use WidgetBundle\Service\InvalidArgumentException;
use WidgetBundle\Entity\UserRepository;
use Doctrine\ORM\EntityManager;

class WidgetService {

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var ValidatorBuilder
     */

    protected $validatorBuilder;

    /**
     * @param UserRepository $userRepository
     * @param ValidatorBuilder $validator
     */

    public function __construct(UserRepository $userRepository, ValidatorBuilder $validator)
    {
        $this->userRepository   = $userRepository;
        $this->validatorBuilder = $validator;

    }

    public function validateWidgetParams($userHash, $width, $height, $bgColor, $textColor)
    {
        $values = array(
            'user_hash'  => $userHash,
            'width'      => $width,
            'height'     => $height,
            'bg_color'   => $bgColor,
            'text_color' => $textColor
        );

        //validation snippet
        $constraint = new Collection(array(
                'user_hash'  => new Uuid(array('strict' => false)),
                'user_hash'  => new NotBlank(),
                'width'      => new Range(array("min" => 100, "max" => 500)),
                'height'     => new Range(array("min" => 100, "max" => 500)),
                'bg_color'   => new Regex('/#(?:[0-9a-fA-F]{6})/'),
                'text_color' => new Regex('/#(?:[0-9a-fA-F]{6})/'),
            ));

            $violationList = $this->validatorBuilder->getValidator()->validateValue($values, $constraint);

            $errors = array();

            foreach ($violationList as $violation) {
                $field = preg_replace('/\[|\]/', "", $violation->getPropertyPath());
                $error = $violation->getMessage();
                $errors[$field] = $error;


            }
            if ($errors)
            {
                throw new InvalidArgumentException(json_encode($errors));
            }

    }

    public function renderWidget($userHash, $width, $height, $bgColor, $textColor)
    {
        $this->validateWidgetParams($userHash, $width, $height, $bgColor, $textColor);

        $user = $this->userRepository->findActiveUserByHash($userHash);

        if (!is_null($user)) {
            $text = rand(1, 100) . "%";

            $widget = new Widget(
                $width,
                $height,
                $bgColor,
                $textColor,
                $text
            );

            return $widget->render();
        } else {
            throw new UsernameNotFoundException('User not found.');
        }
    }


}