# AttachmentManager y UploadController

## Clase AttachmentManager

La clase `AttachmentManager` es responsable de manejar las operaciones relacionadas con los archivos adjuntos en la aplicación.

### Características principales:

1. **Gestión de almacenamiento**: Maneja el almacenamiento de archivos, ya sea en el sistema de archivos local o en un servicio de almacenamiento en la nube.

2. **Generación de nombres únicos**: Crea nombres de archivo únicos para evitar conflictos al guardar archivos.

3. **Validación de archivos**: Verifica el tipo y tamaño de los archivos antes de almacenarlos.

4. **Recuperación de archivos**: Proporciona métodos para recuperar archivos almacenados.

5. **Eliminación de archivos**: Permite eliminar archivos adjuntos cuando ya no son necesarios.

## Controlador UploadController

El `UploadController` es el punto de entrada para las operaciones de carga de archivos en la aplicación.

### Funcionamiento en conjunto:

1. **Recepción de solicitudes**: El `UploadController` recibe las solicitudes HTTP para cargar archivos.

2. **Validación inicial**: Realiza una validación básica de la solicitud y los datos recibidos.

3. **Delegación al AttachmentManager**: Utiliza los métodos de `AttachmentManager` para procesar y almacenar los archivos recibidos.

4. **Manejo de respuestas**: Genera respuestas apropiadas para el cliente, incluyendo URLs de acceso a los archivos cargados o mensajes de error.

5. **Gestión de errores**: Maneja excepciones que puedan ocurrir durante el proceso de carga y almacenamiento.

### Ejemplo de flujo:

1. El cliente envía una solicitud POST al `UploadController` con un archivo adjunto.
2. El `UploadController` valida la solicitud y extrae el archivo.
3. Se llama al método apropiado de `AttachmentManager` para procesar y almacenar el archivo.
4. `AttachmentManager` genera un nombre único, valida el archivo y lo almacena.
5. `UploadController` recibe la confirmación de `AttachmentManager` y envía una respuesta al cliente.

Esta combinación permite una separación clara de responsabilidades: el controlador maneja la lógica, mientras que `AttachmentManager` se encarga de los detalles de almacenamiento y gestión de archivos.

## Notas adicionales
La clase AttachmentManager utiliza algunas configuraciones desde la clase Config `app/libs/config.php` para determinar la ubicación, tamaño máximo y el tipo de archivo que se puede subir.

```php
    /**
     * Directorio para almacenar los archivos adjuntos
     */
    const UPLOAD_DIR = ROOT . DS . 'public' . DS . 'files';
    
    /**
     * Tamaño máximo del archivo en bytes (2MB)
     */
    const MAX_UPLOAD_FILE_SIZE = 2097152;

    /**
     * Tipos de archivos permitidos
     */
    const ALLOWED_FILE_TYPES = ['image/jpeg', 'image/png', 'application/pdf'];
```

Es necesario indicar que el tamaño máximo de archivo depende también de la configuración del servidor web (en el archivo php.ini llamada `upload_max_filesize`), en caso de que se supere este límite, el servidor web rechazará la subida del archivo.

Es importante recordar que `upload_max_filesize` trabaja en conjunto con otras directivas como `post_max_size`, que también deberías considerar ajustar si estás aumentando el tamaño máximo de subida de archivos.