<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Service\Slugger;
use Simplex\Framework;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class SpamCheckerTest extends TestCase
{
    public function testSomething(): void
    {
        $titre = "Un Super Article";
        $slug = new Slugger;
        $titreslug = $slug->slugify($titre, '-');
        $this->assertEquals($titreslug, "un-super-article");
    }
}
