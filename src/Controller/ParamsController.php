<?php

namespace Svc\ParamBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Svc\ParamBundle\Entity\Params;
use Svc\ParamBundle\Form\ParamsType;
use Svc\ParamBundle\Repository\ParamsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ParamsController extends AbstractController
{
  public function __construct(private bool $enableDeleteParam)
  {
  }

  public function index(ParamsRepository $paramsRepository): Response
  {
    try {
      $params = $paramsRepository->findAll();
    } catch (\Exception) {
      try {
        $this->addFlash('danger', 'Table "params" not found or wrong structure.');
      } catch (\Exception) {
      }
      $params = null;
    }

    return $this->render('@SvcParam/params/index.html.twig', [
      'params' => $params,
      'enableDeleteParam' => $this->enableDeleteParam,
    ]);
  }

  public function edit(Request $request, Params $param, EntityManagerInterface $entityManager): Response
  {
    $form = $this->createForm(ParamsType::class, $param, ['dataType' => $param->getParamType()]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->flush();

      return $this->redirectToRoute('svc_param_index');
    }

    return $this->render('@SvcParam/params/edit.html.twig', [
      'param' => $param,
      'form' => $form,
    ]);
  }

  public function delete(Request $request, Params $param, EntityManagerInterface $entityManager): Response
  {
    if ($this->isCsrfTokenValid('delete' . $param->getId(), $request->request->get('_token'))) {
      $entityManager->remove($param);
      $entityManager->flush();
    }

    return $this->redirectToRoute('svc_param_index');
  }
}
