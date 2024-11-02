# Documentación de la clase Auth

## Descripción general
La clase Auth proporciona funcionalidades para la autenticación y autorización de usuarios en la aplicación.

## Métodos principales

### login($username, $password)
Este método se encarga de autenticar a un usuario con su nombre de usuario y contraseña.

**Parámetros:**
- `$username`: String - El nombre de usuario del usuario que intenta iniciar sesión.
- `$password`: String - La contraseña del usuario.

**Retorno:**
- `boolean`: Devuelve `true` si la autenticación es exitosa, `false` en caso contrario.

**Ejemplo de uso:**

```php
//revise el controlador login para ver su implementación
$auth = new Auth("User", "check");
if ($auth->login($username, $password)) {
// Autenticación exitosa
} else {
    // Autenticación fallida
}
```

El método login ejecuta la clase y el metodo pasados como parámetros en el constructor de la clase Auth.
En el ejemplo anterior se utiliza el modelo User y el método check para autenticar al usuario, los que se ven implementados a continuación:

```php
class User extends Model
{
    public function check($username, $password)
    {
        $user =  $this->findFirst('WHERE username = :username', [':username' => $username]);
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        return false;
    }
}
```

Para finalizar la explicación, el modelo User hace uso de una tabla llamada user, cuya definición se ve a continuación:

```sql
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
)
```

### logout()
Este método cierra la sesión del usuario actual.


### isLoggedIn()
Verifica si hay un usuario autenticado actualmente.

**Retorno:**
- `boolean`: `true` si hay un usuario autenticado, `false` en caso contrario.

**Ejemplo de uso:**

```php
class AdminController extends Controller
{
    /**
     * Método que se ejecuta antes de cualquier acción del controlador
     * Si el usuario no está autenticado, redirige a la página de login
     */
    protected function beforeFilter()
    {
        if (!Auth::isLogged()) {
            $this->redirect('login');
        }
    }
}
```


### getCurrentUser()
Obtiene la información del usuario actualmente autenticado.

**Retorno:**
- `User|null`: Retorna un objeto User si hay un usuario autenticado, o `null` si no lo hay.


## Notas adicionales
- Esta clase utiliza una base de datos para verificar las credenciales de los usuarios.
- La clase se usa en conjunto con el controlador login, en el cual se implementa la lógica de autenticación y el cierre de sesión.
- Para restringir los accesos de forma sencilla se utiliza el controlador AdminController, que sirve para utilizarlo como controlador heredable, permitiendo con ello que cualquier controlador que extienda de él herede los métodos de autenticación.


## Recomendaciones de uso
- Siempre verifica el estado de autenticación antes de permitir acceso a áreas protegidas de la aplicación.
- Utiliza el método `isLoggedIn()` para implementar control de acceso.
- Asegúrate de llamar a `logout()` cuando el usuario desee cerrar sesión.