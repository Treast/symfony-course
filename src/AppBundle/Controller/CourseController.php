<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Course;
use AppBundle\Type\CourseType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/courses")
 */
class CourseController extends Controller
{
    /**
     * @Route("/", name="courses")
     */
    public function indexAction()
    {
        $courses = $this->getDoctrine()->getRepository(Course::class)->findAll();
        return $this->render('course/index.html.twig', [
            'courses' => $courses
        ]);
    }

    /**
     * @Route("/edit/{id}", name="course_edit", requirements={"id"="\d+"})
     */
    public function editAction(Request $request, Course $course)
    {
        $thumbnail = $course->getThumbnail();
        $courseForm = $this->createForm(CourseType::class, $course);

        $courseForm->handleRequest($request);

        if($courseForm->isSubmitted() && $courseForm->isValid()) {
            $course = $courseForm->getData();

            if (!is_null($course->getThumbnail())) {
                $file = $course->getThumbnail();
                $filename = uniqid() . '-' . $file->getClientOriginalName();
                $path = $this->getParameter('kernel.root_dir') . '/../web/uploads';
                $file->move($path, $filename);

                $course->setThumbnail('uploads/' . $filename);
            } else {
                $course->setThumbnail($thumbnail);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($course);
            $em->flush();

            $this->addFlash('success', "GG ! The course has been edited.");
            return $this->redirectToRoute('courses');
        }


        return $this->render('course/create.html.twig', [
            'courseForm' => $courseForm->createView()
        ]);
    }

    /**
     * @Route("/delete", name="course_delete", methods={"POST"})
     */
    public function deleteAction(Request $request)
    {
        if (!$course = $this->getDoctrine()->getRepository(Course::class)->find($request->request->get('course_id'))) {
            $this->addFlash('danger', 'This course does not exist.');
        } else {
            $csrfToken = new CsrfToken('delete_course', $request->request->get('csrf_token'));

            if ($this->get('security.csrf.token_manager')->isTokenValid($csrfToken)) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($course);
                $em->flush();
                $this->addFlash('success', "GG ! The course has been deleted.");
            } else {
                $this->addFlash('danger', "Csrf token not valid.");
            }
        }

        return $this->redirectToRoute('courses');
    }

    /**
     * @Route("/create", name="courses_create")
     */
    public function createAction(Request $request)
    {
        $course = new Course();
        $courseForm = $this->createForm(CourseType::class, $course);

        $courseForm->handleRequest($request);

        if($courseForm->isSubmitted() && $courseForm->isValid()) {
            $course = $courseForm->getData();

            $file = $course->getThumbnail();
            $filename = uniqid() . '-' . $file->getClientOriginalName();
            $path = $this->getParameter('kernel.root_dir') . '/../web/uploads';
            $file->move($path, $filename);

            $course->setThumbnail('uploads/' . $filename);

            $em = $this->getDoctrine()->getManager();
            $em->persist($course);
            $em->flush();

            $this->addFlash('success', "GG ! The course has been added.");

            return $this->redirectToRoute('courses');
        }

        return $this->render('course/create.html.twig', [
            'courseForm' => $courseForm->createView()
        ]);
    }

    public function searchAction()
    {
        return $this->render('course/search.html.twig');
    }
}