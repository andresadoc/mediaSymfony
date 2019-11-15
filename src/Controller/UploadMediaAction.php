<?php


namespace App\Controller;

use App\Entity\Advertisements;
use App\Form\AdvertisementType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UploadMediaAction
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var LoggerInterface $logger
     */
    private $logger;

    public function __construct(FormFactoryInterface $formFactory,
                                EntityManagerInterface $entityManager,
                                ValidatorInterface $validator,
                                TokenStorageInterface $tokenStorage,
                                LoggerInterface $logger)
    {
        $this -> formFactory = $formFactory;
        $this -> entityManager = $entityManager;
        $this -> validator = $validator;
        $this -> tokenStorage = $tokenStorage;
        $this -> logger = $logger;
    }

    public function __invoke(Request $request)
    {
        $advertisement = new Advertisements();
        $form = $this->formFactory->create(AdvertisementType::class, $advertisement);
        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid()){
            $advertisement -> setCreator($this->tokenStorage->getToken()->getUser());
            $this->entityManager->persist($advertisement);

            if($advertisement -> validateByMediaType()){
                $this->entityManager->flush();
                $advertisement -> setFile(null);

                return $advertisement;
            }
        }

        throw new ValidatorException($this->validator->validate($advertisement));
    }
}