<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Pizza;
use App\Repository\CategorieRepository;
use App\Repository\PizzaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class PizzaController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('guest/index.html.twig', [
            'controller_name' => 'PizzaController',
        ]);
    }

    #[Route('/categorie/add', name: 'app_categorie_add')]
    public function addCategorie(EntityManagerInterface $entityManager, Request $request): Response
    {
        $name = $request->query->get('name', 'DefaultCategorie');
        $img = $request->query->get('img', 'img/default.jpg');

        $categorie = new Categorie();
        $categorie->setName($name);
        $categorie->setImg($img);
        $entityManager->persist($categorie);
        $entityManager->flush();

        return new Response('Categorie "' . $name . '" toegevoegd met afbeelding: ' . $img);
    }

    #[Route('/pizza/show', name: 'app_pizza_show')]
    public function showPizzas(EntityManagerInterface $entityManager): Response
    {
        $pizzas = $entityManager->getRepository(Pizza::class)->findAll();
        return $this->render('guest/show_pizzas.html.twig', [
            'pizzas' => $pizzas,
        ]);
    }

    #[Route('/categorie/show', name: 'app_categorie_show')]
    public function showCategorie(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager->getRepository(Categorie::class)->findAll();
        return $this->render('guest/show_categorie.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/pizzas/show/{id}', name: 'app_pizzas_show')]
    public function showPizzasByCategorie(CategorieRepository $categorieRepository, int $id): Response
    {
        $categorie = $categorieRepository->find($id);

        if (!$categorie) {
            throw $this->createNotFoundException('Categorie niet gevonden');
        }

        $pizzas = $categorie->getPizzas();
        return $this->render('guest/show_pizza.html.twig', [
            'categorie' => $categorie,
            'pizzas' => $pizzas,
        ]);
    }

    #[Route('/vleespizzas', name: 'app_vleespizzas')]
    public function showVleesPizzas(VleespizzaRepository $vleespizzaRepository): Response
    {
        // Haal alle vlees-pizza's op uit de "vleespizzas" tabel
        $vleespizzas = $vleespizzaRepository->findAll();

        return $this->render('guest/show_pizza.html.twig', [
            'vleespizzas' => $vleespizzas, // Geef de vlees-pizza's door aan de Twig-template
        ]);

    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $username = $request->request->get('username');
            $password = $request->request->get('password');
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $user = new User(); // Zorg voor een bestaande User entity
            $user->setUsername($username);
            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/register.html.twig');
    }

    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('Deze actie wordt afgehandeld door Symfony via security.yaml.');
    }
}