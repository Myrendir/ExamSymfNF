<?php

namespace App\Controller;

use App\entity\Film;
use App\Form\FilmType;
use App\Repository\FilmRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class FilmsController
 * @Route("/films", name="hello")
 * @package App\Controller
 */
class FilmsController extends AbstractController
{

    /**
     * @return Response
     */
    public function index()
    {
        return $this->render('films/film.html.twig');

    }

    /**
     * @Route("/add", methods={"POST"}, name="add_film")
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function addFilm(Request $request,
                            ValidatorInterface $validator, EntityManagerInterface $em): JsonResponse
    {
        $film = new Film();
        $data = json_decode($request->getContent(), true);
        $req = $this->createForm(FilmType::class, $film);
        $req->submit($data);
        $valid = $validator->validate($film, null, 'Film');
        if (count($valid) !== 0) {
            foreach ($valid as $error) {
                return new JsonResponse($error->getMessage(), Response::HTTP_BAD_REQUEST);
            }
        }
        $em->persist($film);
        $em->flush();
        return new JsonResponse('Film ajouté avec succès', 200);
    }

    /**
     * @Route("/", methods={"GET"}, name="all_films")
     * @param FilmRepository $filmsRepository
     * @return JsonResponse
     */
    public function getAll(FilmRepository $filmsRepository): JsonResponse
    {
        $res = $filmsRepository->getFilms();
        return new JsonResponse($res, Response::HTTP_OK);
    }

    /**
     * @Route("/all", name="front_films", methods={"GET"})
     * @param Request $request
     * @param FilmRepository $filmRepository
     * @return Response
     */
    public function getAllFront(Request $request, FilmRepository $filmRepository): Response
    {
        $filter = [];
        $em = $this->getDoctrine()->getManager();
        $metaData = $em->getClassMetadata(Film::class)->getFieldNames();
        foreach ($metaData as $value) {
            if ($request->query->get($value)) {
                $filter[$value] = $request->query->get($value);
            }
        }
        return $this->render('films/film.html.twig', [
            'Film' => $filter
        ]);
    }

    /**
     * @Route("/{id}", methods={"GET"}, name="get_one_film_id")
     * @param int $id
     * @param FilmRepository $filmsRepository
     * @return JsonResponse
     */
    public function getFilm(int $id, FilmRepository $filmsRepository): JsonResponse
    {
        $res = $filmsRepository->getById($id);
        return new JsonResponse($res, Response::HTTP_OK);
    }

    /**
     * @Route("/parId/{id}", name="frontbydid", methods={"GET"})
     * @param int $id
     * @param Request $request
     * @param FilmRepository $filmRepository
     * @return Response
     */
    public function getIdFront(int $id, Request $request, FilmRepository $filmRepository): Response
    {

        $res = $filmRepository->getFilm($id);
        return $this->render('films/one.html.twig', [
            'Film' => $res
        ]);
    }
}
