<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Store\Product;
use App\Form\ContactType;
use App\Mailer\ContactMailer;
use App\Repository\Store\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

final class MainController extends AbstractController
{
    public function __construct(
        private ContactMailer $mailer,
        private EntityManagerInterface $em,
        private ProductRepository $productRepository,
    ) {
    }

    #[Route('/', name: 'main_homepage', methods: ['GET'])]
    public function homepage(): Response
    {
        return $this->render('main/homepage.html.twig', [
            'lastCreatedProducts' => $this->productRepository->findLastCreatedProducts(),
            'mostPopularProducts' => $this->productRepository->findMostPopularProducts(),
        ]);
    }
    
    #[Route('/presentation', name: 'main_presentation', methods: ['GET'])]
    public function presentation(): Response
    {
        return $this->render('main/presentation.html.twig');
    }

    #[Route('/contact', name: 'main_contact', methods: ['GET', 'POST'])]
    public function contact(Request $request): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($contact);
            $this->em->flush();

            $this->mailer->send($contact);
            
            $this->addFlash('success', 'Merci, votre demande de contact a bien été prise en compte !');
            
            return $this->redirectToRoute('main_contact');
        }
        
        return $this->render('main/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
