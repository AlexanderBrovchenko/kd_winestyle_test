<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Mobile_Detect;

use App\Entity\Size;

class DemoController extends AbstractController
{
    /**
     * @Route("/demo.php")
     */
    public function show()
    {
        $detect = new Mobile_Detect();
        $methodname = "getAll";
        if ($detect->isMobile())
            $methodname .= "Formobile";
        else if (!$detect->isTablet())
            $methodname .= "Fordesktop";
        $sizeRepository = $this->getDoctrine()
            ->getRepository(Size::class);
        if (empty($sizes = $sizeRepository->$methodname())) {
            $this->fillSizeData();
            $sizes = $sizeRepository->$methodname();
        }
        $filenames = array();
        $possibles = array('GIF', 'JPG', 'PNG', 'WBMP', 'XBM', 'WEBP');
        foreach (glob('Gallery/*.*') as $filename) {
            $extention = pathinfo($filename, PATHINFO_EXTENSION);
            if (in_array(strtoupper($extention), $possibles))
                $filenames[] = basename($filename, "." . $extention);
        }
        return $this->render('base.html.twig', [
            'filenames' => $filenames,
            'sizes' => $sizes
        ]);
    }

    private function fillSizeData()
    {
        $data = array(
            array('Code' => 'mic', 'Width' => 150, 'Height' => 150, 'Fordesktop' => false, 'Formobile' => true),
            array('Code' => 'min', 'Width' => 320, 'Height' => 240, 'Fordesktop' => true, 'Formobile' => true),
            array('Code' => 'med', 'Width' => 640, 'Height' => 480, 'Fordesktop' => true, 'Formobile' => true),
            array('Code' => 'big', 'Width' => 800, 'Height' => 600, 'Fordesktop' => true, 'Formobile' => false),
        );
        foreach($data as $row)
        {
            $entityManager = $this->getDoctrine()->getManager();

            $size = new Size();
            foreach($row as $key => $value)
            {
                $methodName = 'set' . $key;
                $size->$methodName($value);
            }
            $entityManager->persist($size);
            $entityManager->flush();
        }
    }
}