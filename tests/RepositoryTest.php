<?php

use PHPUnit\Framework\TestCase;

class User {
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }
}


class RepositoryTest extends TestCase
{
 
    public function testRepository()
    {
        $repository = Repository::get(User::class, 1);
        $this->assertInstanceOf(User::class, $repository);
    }
}
