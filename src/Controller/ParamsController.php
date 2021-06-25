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
 * @IsGranted("ROLE_ADMIN")
 */
class ParamsController extends AbstractController
{
  public function index(ParamsRepository $paramsRepository): Response
  {
    return $this->render('@SvcParam/params/index.html.twig', [
      'params' => $paramsRepository->findAll(),
    ]);
  }


  public function edit(Request $request, Params $param): Response
  {
    $form = $this->createForm(ParamsType::class, $param, ['dataType' => $param->getParamType()]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->getDoctrine()->getManager()->flush();

      return $this->redirectToRoute('svc_param_index');
    }

    return $this->renderForm('@SvcParam/params/edit.html.twig', [
      'param' => $param,
      'form' => $form,
    ]);
  }

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
