<?php
declare(strict_types=1);

namespace App\Controller\ControlPanel;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/cp", name: "control_panel")]
abstract class AbstractControlPanelController extends SymfonyAbstractController
{

}
