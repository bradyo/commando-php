<?php
namespace Commando;

interface Module
{
    public function bootstrap();

    public function getRoutes();
}