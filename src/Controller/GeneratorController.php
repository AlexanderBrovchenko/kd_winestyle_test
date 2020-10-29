<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\Image;
use App\Entity\Size;

class GeneratorController extends AbstractController
{
    /**
     * @Route("/generator.php/{name}/{sizecode}")
     */
    public function generate(string $name, string $sizecode)
    {
        $fullname = null;
        $name_for_cache = 'Cache/' . $name . '_' . $sizecode . '.jpg';
        if (!file_exists($name_for_cache)) {
            foreach (glob('Gallery/' . $name . '.*') as $file)
                $fullname = $file;
            $sizeObj = $this->getDoctrine()
                ->getRepository(Size::class)
                ->findOneByCode($sizecode);
            $image = new Image($fullname);
            $height = $sizeObj->getHeight();
            $width = $sizeObj->getWidth();
            $image->scale_to_fit($width, $height);
            $image->save('Cache/', $name . '_' . $sizecode, 'JPG');
        }
        $image = new Image($name_for_cache);
        return $image->output();
    }
}