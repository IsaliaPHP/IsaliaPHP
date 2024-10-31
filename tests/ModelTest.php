<?php

use PHPUnit\Framework\TestCase;

class Usuarios extends Model
{
    protected $_table_name = "usuarios";
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
        $this->model->setTableName("usuarios");
        $this->assertEquals("usuarios", $this->model->getTableName());
    }

    public function testCreateUser()
    {
        $this->model->setTableName("usuarios");
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
        $this->model->setTableName("usuarios");
        $this->model->load([
            "nombre" => "Juan",
            "email" => "juan@example.com"
        ]);
        $this->model->save();

        $usuario = $this->model->findById(1);
        $this->assertEquals("Juan", $usuario->nombre);
        $this->assertEquals("juan@example.com", $usuario->email);
    }

    public function testFindAll()
    {
        $this->model->setTableName("usuarios");
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

        $usuarios = $this->model->findAll();
        $this->assertEquals(2, count($usuarios));

    }

    public function testFindAllCondition()
    {
        $this->model->setTableName("usuarios");
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

        $usuarios = $this->model->findAll("WHERE nombre = 'Juan'");
        $this->assertEquals(1, count($usuarios));

    }

    public function testFindFirst()
    {
        $this->model->setTableName("usuarios");
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

        $usuario = $this->model->findFirst();
        $this->assertEquals("Juan", $usuario->nombre);
        $this->assertEquals("juan@example.com", $usuario->email);
    }

    public function testFindFirstCondition()
    {
        $this->model->setTableName("usuarios");
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

        $usuario = $this->model->findFirst("WHERE nombre = 'Luisa'");
        $this->assertEquals("Luisa", $usuario->nombre);
        $this->assertEquals("luisa@example.com", $usuario->email);
    }


    public function testFindFirstConditionNoHydrate()
    {
        $this->model->setTableName("usuarios");
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

        $usuario = $this->model->findFirst("WHERE nombre = 'Pepe'");
        $this->assertNull($usuario);
    }

    public function testUpdateUser()
    {
        $this->model->setTableName("usuarios");
        $this->model->load([
            "nombre" => "Juan",
            "email" => "juan@example.com"
        ]);
        $this->model->create();

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
        $this->model->setTableName("usuarios");
        $this->model->load([
            "nombre" => "Juan",
            "email" => "juan@example.com"
        ]);
        $this->model->create();

        $this->model->update([
            "nombre" => "Juancho"
        ], "WHERE id = 1");

        $usuario = $this->model->findById(1);
        $this->assertEquals("Juancho", $usuario->nombre);
    }

    public function testUpdateUserNoData()
    {
        $this->model->setTableName("usuarios");
        
        $result = $this->model->update();

        $this->assertFalse($result);
    }

    public function testFindBySQL()
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
        $usuarios = $this->model->findBySQL("SELECT * FROM usuarios");
        $this->assertEquals(2, count($usuarios));
    }

    public function testDeleteUser()
    {
        
        $this->model->load([
            "nombre" => "Juan",
            "email" => "juan@example.com"
        ]);
        $this->model->create();
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
        
        $this->model->load([
            "nombre" => "Juan",
            "email" => "juan@example.com"
        ]);
        $this->model->create();

        $this->model->deleteAll("WHERE nombre = 'Juan'");
        
        $usuario = $this->model->findById(1);
        $this->assertNull($usuario);
    }

}
