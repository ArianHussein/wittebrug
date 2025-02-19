<?php

namespace App\Controller;

use App\Entity\Department;
use App\Entity\Employee;
use App\Repository\DepartmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GuestController extends AbstractController
{
    #[Route('/guest', name: 'app_guest')]
    public function index(): Response
    {
        return $this->render('guest/index.html.twig', [
            'controller_name' => 'GuestController',
        ]);
    }

    #[Route('/department/add', name: 'app_department_add')]
    public function add(EntityManagerInterface $entityManager): Response
    {
        $department = new Department();
        $department->setName("sales");
        //dd($department);
        $entityManager->persist($department);
        $entityManager->flush();
        return new Response('Department added');
    }

    #[Route('/employee/show', name: 'app_employee_show')]
    public function show(EntityManagerInterface $entityManager): Response
    {
        $employees=$entityManager->getRepository(Employee::class)->findAll();
        //dd($employees);
        return $this->render('guest/show.html.twig', ['employees'=>$employees]);
    }

    #[Route('/department/show', name: 'app_department_show')]
    public function showDepartment(EntityManagerInterface $entityManager): Response
    {
        $departments=$entityManager->getRepository(Department::class)->findAll();

        return $this->render('guest/show_department.html.twig', ['departments'=>$departments]);
    }

    #[Route('/employees/show/{id}', name: 'app_employees_show')]
    function showEmployees(DepartmentRepository $departmentRepository, int $id ) {
        $department=$departmentRepository->find($id);
        //dd($department);
        return $this->render('guest/show_employees.html.twig', ['department'=>$department]);

    }

}
