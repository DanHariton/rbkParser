<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\AdminPostEditType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_index")
     */
    public function indexAction()
    {
        return $this->render('admin/action/index.html.twig');
    }

    /**
     * @Route("/post/list", name="admin_post_list")
     */
    public function postList(PostRepository $repository)
    {
        return $this->render('admin/action/post/list.html.twig', [
            'posts' => $repository->findAll()
        ]);
    }

    /**
     * @Route("/post/edit/{post}", name="admin_post_edit")
     */
    public function postEdit(Post $post, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(AdminPostEditType::class, $post)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Новость успешно отредактирована');
            return $this->redirectToRoute('admin_post_list');
        }

        return $this->render('admin/action/post/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/post/remove/{post}", name="admin_post_remove")
     */
    public function postRemove(Post $post, EntityManagerInterface $em)
    {
        $em->remove($post);
        $em->flush();
        $this->addFlash('success', 'Новость успешно удалена');
        return $this->redirectToRoute('admin_post_list');
    }
}