<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class LuckyController extends AbstractController
{
    #[Route('/lucky/number', name: 'lucky_number')]
    public function number(): Response
    {
        $number = random_int(0, 100);
        $url = $this->generateUrl('lucky_number', ['max' => 10]);

        return new Response(
            '<html><body>Lucky number: '.$number.'<br>URL: '.$url.'</body></html>'
        );
    }
}
