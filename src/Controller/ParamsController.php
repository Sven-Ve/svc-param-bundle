<?php

namespace Svc\ParamBundle\Controller;

use Svc\ParamBundle\Entity\Params;
use Svc\ParamBundle\Form\ParamsType;
use Svc\ParamBundle\Repository\ParamsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/params/{_locale}", requirements={"_locale": "%app.supported_locales%"})
 * @IsGranted("ROLE_SUPER_ADMIN")
 */
class ParamsController extends AbstractController
{
  /**
   * @Route("/", name="params_index", methods={"GET"})
   */
  public function index(ParamsRepository $paramsRepository): Response
  {
    return $this->render('params/index.html.twig', [
      'params' => $paramsRepository->findAll(),
    ]);
  }


  /**
   * @Route("/{id}/edit", name="params_edit", methods={"GET","POST"})
   */
  public function edit(Request $request, Params $param): Response
  {
    $form = $this->createForm(ParamsType::class, $param, ['dataType' => $param->getParamType()]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->getDoctrine()->getManager()->flush();

      return $this->redirectToRoute('params_index');
    }

    return $this->render('params/edit.html.twig', [
      'param' => $param,
      'form' => $form->createView(),
    ]);
  }

  /**
   * @Route("/{id}", name="params_delete", methods={"POST"})
   */
  public function delete(Request $request, Params $param): Response
  {
    if ($this->isCsrfTokenValid('delete' . $param->getId(), $request->request->get('_token'))) {
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->remove($param);
      $entityManager->flush();
    }

    return $this->redirectToRoute('params_index');
  }
}
