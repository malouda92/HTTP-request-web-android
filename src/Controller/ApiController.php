<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/user/post/", name="api", methods={"POST"})
     */
    public function index(Request $request, EntityManagerInterface $manager, SerializerInterface $serializer, UserPasswordEncoderInterface $encoder)
    {
        $content = $request->getContent();
        $newUser = $serializer->deserialize($content, User::class, 'json');
        if($newUser)
        {
            $hash = $encoder->encodePassword($newUser, $newUser->getPassword());
            $newUser->setPassword($hash);
            $manager->persist($newUser);
            $manager->flush();
        }
        return $this->json(array(
            'message'=>'user created'
        ), 201);
    }
}
