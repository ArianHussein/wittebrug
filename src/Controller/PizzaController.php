<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Pizza;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Attribute\Route;

final class PizzaController extends AbstractController
{
    #[Route('/guest', name: 'app_guest')]
    public function index(): Response
    {
        return $this->render('guest/index.html.twig', [
            'controller_name' => 'PizzaController',
        ]);
    }

    #[Route('/categorie/add', name: 'app_categorie_add')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $name = $request->query->get('name', 'sales'); // Default naar 'sales'
        $img = $request->query->get('img', 'img/default.jpg'); // Default image

        $categorie = new Categorie();
        $categorie->setName($name);
        $categorie->setImg($img);
        $entityManager->persist($categorie);
        $entityManager->flush();

        return new Response('Categorie toegevoegd met afbeelding: ' . $img);
    }

    #[Route('/pizza/show', name: 'app_pizza_show')]
    public function show(EntityManagerInterface $entityManager): Response
    {
        $pizzas = $entityManager->getRepository(Pizza::class)->findAll();
        return $this->render('guest/show_pizzas.html.twig', ['pizzas' => $pizzas]);
    }

    #[Route('/categorie/show', name: 'app_categorie_show')]
    public function showCategorie(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager->getRepository(Categorie::class)->findAll();

        return $this->render('guest/show_categorie.html.twig', ['categories' => $categories]);
    }

    #[Route('/pizzas/show/{id}', name: 'app_pizzas_show')]
    public function showPizzas(CategorieRepository $categorieRepository, int $id): Response
    {
        $categorie = $categorieRepository->find($id);

        if (!$categorie) {
            throw $this->createNotFoundException('Categorie niet gevonden');
        }

        return $this->render('guest/show_pizza.html.twig', ['categorie' => $categorie]);
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Registratielogica (placeholder, voeg hier formulierverwerking toe)
        if ($request->isMethod('POST')) {
            $username = $request->request->get('username');
            $password = $request->request->get('password');
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Opslaan van een nieuwe gebruiker (bijv. User entity)
            $user = new User(); // Zorg ervoor dat de User entity bestaat
            $user->setUsername($username);
            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/register.html.twig');
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Symfony regelt de logout automatisch via security.yaml
        throw new \LogicException('Dit moet worden geconfigureerd in security.yaml.');
    }

    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Haalt eventuele laatste fout op bij inloggen
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
}