<?php

namespace App\Controller;

use App\Entity\ContactMessage;
use App\Form\ContactType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;
class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request, ManagerRegistry $doctrine): Response
    {
        $contact = new ContactMessage();
        $user = $this->getUser(); // Get logged-in user or null

        if ($user) {
            $contact->setCreatedBy($user);
        }

        $form = $this->createForm(ContactType::class, $contact, [
            'action' => $this->generateUrl('app_contact'),
            'user' => $user,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($contact);
            $em->flush();

            $this->addFlash('success', 'Your message has been sent!');
            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/index.html.twig', [
            'contact_form' => $form->createView(),
        ]);
    }
}
