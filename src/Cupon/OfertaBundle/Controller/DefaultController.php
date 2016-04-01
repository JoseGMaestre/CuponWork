<?php

/*
 * (c) Javier Eguiluz <javier.eguiluz@gmail.com>
 *
 * Este archivo pertenece a la aplicación de prueba Cupon.
 * El código fuente de la aplicación incluye un archivo llamado LICENSE
 * con toda la información sobre el copyright y la licencia.
 */

namespace Cupon\OfertaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {
	/**
	 * Muestra la portada del sitio web
	 *
	 * @param string $ciudad El slug de la ciudad activa en la aplicación
	 */
	public function portadaAction($ciudad) {
		$em = $this->getDoctrine()->getManager();
		$oferta = $em->getRepository('OfertaBundle:Oferta')->findOfertaDelDia($ciudad);
		//var_dump($oferta);

		if (!$oferta) {
			throw $this->createNotFoundException('No se ha encontrado ninguna oferta del día en la ciudad seleccionada');
		}

		$respuesta = $this->render('OfertaBundle:Default:portada.html.twig', array(
			'oferta' => $oferta,
		));
		$respuesta->setSharedMaxAge(60);

		return $respuesta;
	}

	/**
	 * Muestra la página de detalle de la oferta indicada
	 *
	 * @param string $ciudad El slug de la ciudad a la que pertenece la oferta
	 * @param string $slug   El slug de la oferta (el mismo slug se puede dar en dos o más ciudades diferentes)
	 */
	public function ofertaAction($ciudad, $slug) {
		$em = $this->getDoctrine()->getManager();
		$oferta = $em->getRepository('OfertaBundle:Oferta')
			->findOferta($ciudad, $slug);
		$relacionadas = $em->getRepository('OfertaBundle:Oferta')
			->findRelacionadas($ciudad);
		return $this->render('OfertaBundle:Default:detalle.html.twig', array(
			'oferta' => $oferta,
			'relacionadas' => $relacionadas,
		));
	}

}
