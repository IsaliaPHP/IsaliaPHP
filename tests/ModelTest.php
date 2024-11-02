<?php

use PHPUnit\Framework\TestCase;

class Usuarios extends Model
{
    
}

class ModelTest extends TestCase
{
    protected $model;
    protected function setUp(): void
    {
        // Cargar la configuración de pruebas
        Db::setEnv('testing');
        // Crear tabla de prueba
        Db::execute("
            CREATE TABLE IF NOT EXISTS usuarios (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nombre TEXT NOT NULL,
                email TEXT NOT NULL
            )
        ");

        $this->model = new Usuarios();
    }

    protected function tearDown(): void
    {
        // Limpiar después de cada prueba
        Db::execute("DROP TABLE IF EXISTS usuarios");
    }

    public function testSetAndGetTableName()
    {
        $this->assertEquals("usuarios", $this->model->getTableName());
    }

    public function testCreateUser()
    {
        $this->model->load([
            "nombre" => "Juan",
            "email" => "juan@example.com"
        ]);
        $this->model->create();

        $usuario = $this->model->findById(1);
        $this->assertEquals("Juan", $usuario->nombre);
        $this->assertEquals("juan@example.com", $usuario->email);
    }

    public function testAlternativeCreateUser()
    {
        $this->model->load([
            "nombre" => "Juan",
            "email" => "juan@example.com"
        ]);
        $this->model->save();

        $usuario = $this->model->findById(1);
        $this->assertEquals("Juan", $usuario->nombre);
        $this->assertEquals("juan@example.com", $usuario->email);
    }


    private function createUsers()
    {
        $this->model->load([
            "nombre" => "Juan",
            "email" => "juan@example.com"
        ]);
        $this->model->create();

        $this->model->load([
            "nombre" => "Luisa",
            "email" => "luisa@example.com"
        ]);
        $this->model->create();
    }

    public function testFindAll()
    {
        $this->createUsers();

        $this->model = new Usuarios();
        $usuarios = $this->model->findAll();
        $this->assertEquals(2, count($usuarios));

    }

    public function testFindAllCondition()
    {
        $this->createUsers();

        $usuarios = $this->model->findAll("nombre = 'Juan'");
        $this->assertEquals(1, count($usuarios));

    }

    public function testFindFirst()
    {
        $this->createUsers();

        $usuario = $this->model->findFirst();
        $this->assertEquals("Juan", $usuario->nombre);
        $this->assertEquals("juan@example.com", $usuario->email);
    }

    public function testFindFirstCondition()
    {
        $this->createUsers();

        $usuario = $this->model->findFirst("nombre = 'Luisa'");
        $this->assertEquals("Luisa", $usuario->nombre);
        $this->assertEquals("luisa@example.com", $usuario->email);
    }


    public function testFindFirstConditionNoHydrate()
    {
        $this->createUsers();

        $usuario = $this->model->findFirst("nombre = 'Pepe'");
        $this->assertNull($usuario);
    }

    public function testUpdateUser()
    {
        $this->createUsers();

        $usuario = $this->model->findById(1);
        $usuario->nombre = "Juancho";
        $usuario->update();

        $usuario = $this->model->findById(1);
        $this->assertEquals("Juancho", $usuario->nombre);

        $usuario->nombre = "Juan Pedro";
        $usuario->save();

        $usuario = $this->model->findById(1);
        $this->assertEquals("Juan Pedro", $usuario->nombre);
    }

    public function testUpdateUserAlternative()
    {
        $this->createUsers();

        $this->model->update([
            "nombre" => "Juancho"
        ], " id = 1");

        $usuario = $this->model->findById(1);
        $this->assertEquals("Juancho", $usuario->nombre);
    }

    public function testUpdateUserNoData()
    {
        $result = $this->model->update();

        $this->assertFalse($result);
    }

    public function testFindBySQL()
    {
        $this->createUsers();
        
        $usuarios = $this->model->findBySQL("SELECT * FROM usuarios");
        $this->assertEquals(2, count($usuarios));
    }

    public function testDeleteUser()
    {
        $this->createUsers();
        $usuario = $this->model->findById(1);

        $usuario->delete();

        $usuario = $this->model->findById(1);
        $this->assertNull($usuario);
    }

    public function testDeleteUserNoId()
    {
        $result = $this->model->delete();
        $this->assertFalse($result);
    }

    public function testDeleteAll()
    {
        $this->createUsers();

        $this->model->deleteAll(" nombre = 'Juan'");
        
        $usuario = $this->model->findById(1);
        $this->assertNull($usuario);
    }

    public function testCall()
    {
        $this->createUsers();
        
        $users = $this->model
            ->select(['nombre', 'email'])
            ->orderBy('nombre', 'DESC')
            ->findAll();

        $this->assertEquals(2, count($users));
    }

    public function testCallNonExistentMethod()
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Method nonExistentMethod does not exist');

        $this->model->nonExistentMethod();
    }

    public function testExecute()
    {
        $this->createUsers();
        $users = $this->model
            ->select(['id', 'nombre', 'email'])
            ->orderBy('nombre', 'DESC')
            ->execute();
        $this->assertEquals(2, count($users));
    }

    public function testSetTableName()
    {
        $this->model->setTableName('usuarios2');
        $this->assertEquals('usuarios2', $this->model->getTableName());
    }

    public function testPaginate()
    {
        $this->createUsers();
        $users = $this->model->paginate(1, 1);
        $this->assertNotNull($users);
    }

    public function testParameters()
    {
        
        $this->model->setParameters([":id" => 1]);
        $this->assertEquals([":id" => 1], $this->model->getParameters());
    }

    public function testToSQL()
    {
        $sql = $this->model->__toString();
        $this->assertEquals("SELECT * FROM usuarios", $sql);
    }


}
