<?php

namespace App\Controller;
use App\Entity\Task;
use App\Form\Type\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    #[Route('/task/new', name: 'task_new')]
    public function new(Request $request): Response
    {
        $task = new Task();
        // $task->setTask('Write a blog post');
        // $task->setDueDate(new \DateTime('tomorrow'));

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $task = $form->getData();
            return $this->redirectToRoute('task_success');
        }

        return $this->renderForm('task/form.html.twig', [
            'form' => $form,
            'pageTitle' => "Nouvelle tâche",
        ]);
    }

    #[Route('/task/success', name: 'task_success')]
    public function taskSuccess(): Response
    {
        return $this->render('task/success.html.twig', [
            'success' => "Votre tâche a bien été enregistrée !",
            'pageTitle' => "Félicitations !",
        ]);
    }
}