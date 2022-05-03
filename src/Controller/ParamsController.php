<?php

namespace Svc\ParamBundle\Controller;

use Doctrine\DBAL\Exception\TableNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Svc\ParamBundle\Entity\Params;
use Svc\ParamBundle\Form\ParamsType;
use Svc\ParamBundle\Repository\ParamsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ParamsController extends AbstractController
{
  public function index(ParamsRepository $paramsRepository): Response
  {
    try {
      $params = $paramsRepository->findAll();
    } catch (TableNotFoundException) {
      //      $this->addFlash('danger', 'Table "params" not found.');
      $params = null;
    }

    return $this->render('@SvcParam/params/index.html.twig', [
      'params' => $params,
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

    return $this->renderForm('@SvcParam/params/edit.html.twig', [
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

    return $this->redirectToRoute('params_index');
  }
}
