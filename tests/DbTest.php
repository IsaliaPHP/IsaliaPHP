<?php

use PHPUnit\Framework\TestCase;

class DbTest extends TestCase
{
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
    }

    protected function tearDown(): void
    {
        // Limpiar después de cada prueba
        Db::execute("DROP TABLE IF EXISTS usuarios");
    }

    public function testFindById()
    {
        // Insertamos un usuario de prueba
        Db::insert("usuarios", [
            "nombre" => "Juan",
            "email" => "juan@example.com"
        ]);

        // Simulamos obtener un usuario
        $usuario = Db::findFirst('SELECT * FROM usuarios WHERE id = 1');

        // Verificamos que el usuario se obtenga correctamente
        $this->assertEquals('Juan', $usuario['nombre']);
        $this->assertEquals('juan@example.com', $usuario['email']);
    }

    public function testUserNotFound()
    {
        // Probamos buscar un usuario que no existe
        $usuario = Db::findFirst('SELECT * FROM usuarios WHERE id = 999');
        
        // Verificamos que el resultado esté vacío
        $this->assertEmpty($usuario);
    }

    public function testInsert()
    {
        // Insertar dos usuarios
        Db::insert("usuarios", [
            "nombre" => "Lucia",
            "email" => "lucia@example.com"
        ]);
        Db::insert("usuarios", [
            "nombre" => "Juan",
            "email" => "juan@example.com"
        ]);

         // Probar select simple
         $usuarios = Db::findAll("SELECT * FROM usuarios");
         $this->assertCount(2, $usuarios);
    }

    public function testUpdate()
    {
        // Insertar un usuario
        Db::insert("usuarios", [
            "nombre" => "Juan",
            "email" => "juan@example.com"
        ]);

        // Actualizar el usuario
        Db::update("usuarios", [
            "nombre" => "Juan Carlos",
            "email" => "juan@example.com"
        ], "WHERE id = 1");

        // Buscar el usuario actualizado
        $usuario = Db::findFirst("SELECT * FROM usuarios WHERE id = 1");
        $this->assertEquals("Juan Carlos", $usuario['nombre']);
    }

    public function testDelete()
    {
        // Insertar un usuario
        Db::insert("usuarios", [
            "nombre" => "Juan",
            "email" => "juan@example.com"
        ]);

        // Eliminar el usuario
        Db::delete("usuarios", "WHERE id = 1"); 

        // Buscar el usuario eliminado
        $usuario = Db::findFirst("SELECT * FROM usuarios WHERE id = 1");
        $this->assertEmpty($usuario);
    }

    // public function testInvalidConnection()
    // {
    //     Db::setEnv('invalid');
    //     $usuarios = Db::findAll("SELECT * FROM usuarios");
    //     //$this->expectException(Exception::class);
    //     $this->expectExceptionMessage('Unable to connect to the database');
    // }

    public function testGetScalar()
    {
        $count = Db::getScalar("SELECT COUNT(*) FROM usuarios");
        $this->assertEquals(0, $count);
    }

    public function testCloseConnection()
    {
        // First make a connection by executing a query
        Db::findAll("SELECT * FROM usuarios");
        
        // Use reflection to access private method and property
        $reflection = new ReflectionClass(Db::class);
        
        $closeMethod = $reflection->getMethod('_closeConnection');
        $closeMethod->setAccessible(true);
        
        $connectionProperty = $reflection->getProperty('_connection');
        $connectionProperty->setAccessible(true);
        
        // Call the private close method
        $closeMethod->invoke(null);
        
        // Verify connection is null
        $this->assertNull($connectionProperty->getValue());
    }

    public function testDestructor()
    {
        $reflection = new ReflectionClass(Db::class);
        $instance = $reflection->newInstanceWithoutConstructor();
        
        // Use reflection to verify connection is null after destructor
        $connectionProperty = $reflection->getProperty('_connection');
        $connectionProperty->setAccessible(true);
        
        $destructor = $reflection->getMethod('__destruct');
        $destructor->setAccessible(true);
        $destructor->invoke($instance); 
        
        $this->assertNull($connectionProperty->getValue());
    }

    public function testTransaction()
    {
        Db::beginTransaction(); 
        Db::insert("usuarios", [
            "nombre" => "Juan",
            "email" => "juan@example.com"
        ]);
        Db::commit();

        $usuario = Db::findFirst("SELECT * FROM usuarios WHERE id = 1");
        $this->assertEquals('Juan', $usuario['nombre']);
        $this->assertEquals('juan@example.com', $usuario['email']);
    }

    public function testTransactionRollback()
    {
        Db::beginTransaction(); 
        Db::insert("usuarios", [
            "nombre" => "Juan",
            "email" => "juan@example.com"
        ]);
        Db::rollback();

        $usuario = Db::findFirst("SELECT * FROM usuarios WHERE id = 1");
        $this->assertEmpty($usuario);   
    }

    public function testGetConfig()
    {
        $config = Db::getConfig();
        $this->assertArrayHasKey('dsn', $config);
    }

//     public function testConnnectionException()
//     {
//         // Aseguramos que el entorno esté configurado correctamente
//         Db::setEnv('invalid');
//
//
//         // Llamamos al método que debería lanzar la excepción
//         $reflection = new ReflectionClass('Db');
//         $connect = $reflection->getMethod('connect');
//         $connect->setAccessible(true);
//         $connect->invoke(null);
//
//         // Esperamos que el método estático lance una excepción
//         $this->expectException(Exception::class);
//         $this->expectExceptionMessage('Unable to connect to the database');
//
//     }
    
}
