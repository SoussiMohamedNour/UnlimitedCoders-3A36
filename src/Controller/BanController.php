<?php
use App\Form\UtilisateurType;
use App\Service\banService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BanController extends AbstractController
{
    /**
     * @Route("/user/{id}/ban", name="ban_user")
     */
    public function banUser(banService $userService, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(UtilisateurType::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $userService->banUser($user);

        return $this->redirectToRoute('app_utilisateur_index');
    }
}
