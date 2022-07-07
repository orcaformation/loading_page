<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(Request $request,MailerInterface $mailer,ManagerRegistry $doctrine): Response
    {
        $customer= new Customer();

        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $customer->setDateRDV(new \DateTime($request->get('dateRDV')));
            $customer->setCreatedAt(new \DateTimeImmutable('now'));
            $entityManager->persist($customer);
            $entityManager->flush();

            try {
                $email = (new Email())
                    ->from($this->getParameter('app.senderEMAIL'))
                    ->to($this->getParameter('app.principalEMAIL'))
                    ->addCc($this->getParameter('app.secondaryEMAIL'))
                    ->subject('Demander une démo gratuite')
                    ->html($this->renderView('email/email.html.twig', [
                            'sender'=>$customer->getFullName(),
                            'email'=>$customer->getEmail(),
                            'ste'=>$customer->getCompany(),
                            'function'=>$customer->getRole(),
                            'phone'=>$customer->getPhone(),
                            'RDV'=>$customer->getDateRDV()->format('Y-m-d H:i:s')
                        ]
                    ));

                $mailer->send($email);
            }catch (Exception $exception){
                //dump($exception);
            }
            try {
                $emailRec = (new Email())
                    ->from($this->getParameter('app.senderEMAIL'))
                    ->to($customer->getEmail())
                    ->subject('Demander une démo gratuite')
                    ->html($this->renderView('email/emailReceiver.html.twig', [
                            'receiver'=>$customer->getFullName(),
                        ]
                    ));
                $mailer->send($emailRec);
            }catch (Exception $exception){
                //dump($exception);
            }

            $this->addFlash('success', 'Votre inscription a bien été prise en compte !');
            return $this->redirectToRoute('app_home');

        }
        return $this->render('home/index.html.twig', [
            'controller_name' => 'Nouveauté Efficom VO',
            'form'=>$form->createView()
        ]);
    }
}
