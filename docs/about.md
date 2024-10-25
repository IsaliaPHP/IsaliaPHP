# La Historia de IsaliaPHP

## Introducción
IsaliaPHP es un framework PHP que nació como un proyecto personal para resolver problemas usando el paradigma de la Programación Orientada a Objetos, y los principios MVC (Modelo Vista Controlador), DRY (Don't Repeat Yourself / No te repitas) y Convention over Configuration (Convención sobre configuración).
Su creación data de marzo de 2016, y desde entonces ha sido evolucionando para resolver problemas de manera sencilla y eficiente.
Su creador es Nelson Rojas Núñez, Ingeniero de Ejecución en Computación e Informática de la Universidad Católica del Maule de la ciudad de Talca, Chile. Nelson es un apasionado de la programación y la tecnología, y su objetivo es crear herramientas que faciliten la vida de los desarrolladores. Ha participado en la comunidad educativa presentando charlas sobre diferentes temas de programación y compartiendo conocimiento en relación a herramientas Open Source. Su paso por la universidad fue entre los años 1996 y 2002, y trabajó en ese tiempo como ayudante de asignaturas de base de datos e ingeniería de software. Eso le valió para dedicarse a la docencia, y a la programación. Sin tener idea de programación, se convirtió en alguien a quién preguntarle sobre soluciones a problemas.
No hay magia de por medio, es trabajo duro, tiempo y constancia.
Trabaja como desarrollador de software desde el año 2003 y ha trabajado en el rubro del retail (supermercados), financiero (factoring), educación (universidad, institutos y centros de formación técnica), workflow (automatización de procesos), compliance (cumplimiento de normativas) y actualmente como desarrolloador frontend (remoto) para una empresa de desarrollo de software de la ciudad de Santiago de Chile. Sigue viviendo en la ciudad de Talca, su ciudad natal.

## Orígenes y motivación
La creación de IsaliaPHP surgió de la necesidad de resolver problemas específicos en proyectos personales, y de la búsqueda de una manera más sencilla y eficiente de realizar tareas repetitivas. Los principios que sustentan el framework vienen de la herencia de experiencias previas con Ruby on Rails, CakePHP y KumbiaPHP. En este último framework, he colaborado en temas de guías para usuarios nuevos, escritura de artículos y documentación. Pero quería otro enfoque, una herramienta que me permitiera crear aplicaciones web de manera más sencilla y eficiente, y que luego pudiera exponer para que otros la pudieran usar. Sé que suena a repetir la rueda, pero quería crear algo propio, algo que me permitiera expresar mi manera de ver la programación.

Desde ya creación del framework, he estado trabajando en el desarrollo de aplicaciones web, y en la actualidad, se encuentra en uso en varios proyectos, tanto personales como de clientes.


## Desarrollo inicial
Luego de leer el libro Modern PHP, de Josh Lockhart, me inspiró a crear un framework que fuera moderno, sencillo y fácil de usar. Las primeras versiones de IsaliaPHP, tenían las estructuras con espacios de nombres, y usaban el autoload de PHP. A mi gusto cumplía con los principios del libro, y me permitía crear aplicaciones web de manera sencilla y eficiente.
Pero luego de un análisis, y de dirigir la mirada hacia personas con menos conocimientos técnicos, estudiantes y recién iniciados, el proyecto tomó un giro, y se comenzó a trabajar en una estructura que fuera más sencilla y fácil de entender, y que pudiera ser asimilada por personas que recién comenzaban a programar.

En términos de desafíos iniciales, lo primero fue trabajar en la gestión de las peticiones HTTP, y la forma en que se gestionaban las rutas, y se podía acceder a ellas de manera sencilla y eficiente. Para eso se usa el patrón de diseño Front Controller y el encargado de dicha labor es un simple archivo php llamado index.php que está alojado en el directorio public.

Otros temas complejos fueron el uso de un sistema de gestión de versiones (Git), y la forma en que se podía mantener el historial de cambios, y que los commits fueran representativos de las funcionalidades agregadas. Como herencia de KumbiaPHP, el uso de un sistema de análisis de código como Scrutinizer, para mantener un estándar de calidad fueron claves para el desarrollo. Soy más bien usuario que experto de PHP, así que las recomendaciones de Scrutinizer fueron fundamentales para la evolución del framework y la calidad de su código.

## Evolución y crecimiento
La evolución del framework ha sido constante, y ha ido tomando formas según recomendaciones para la mejora de la experiencia de uso y la disminución de la curva de aprendizaje.
Aunque inicialmente el framework sería mucho más básico, pensar en herramientas de generación de código, como scaffolding, la consola interactiva, el ORM, helpers como Html y Form, y finalmente el SqlBuilder, fueron claves para la evolución del framework.
También se han agregado componentes para la Autenticación y la gestión de archivos adjuntos, y se ha trabajado en la mejora de la seguridad del framework.

## Anécdotas
La primera anécdota es que el nombre IsaliaPHP, viene del nombre de la esposa de Nelson, Isabel y el nombre de su hija Emilia.
Otra anécdota interesante es que una versión de IsaliaPHP sería completamente en español, con nombres de clases y métodos en español, pero la idea fue abandonada por temas de compatibilidad y a causa que los lenguajes de programación están escritos principalmente en inglés.


## Filosofía y principios de diseño
La filosofía de IsaliaPHP es sencilla, y se basa en la simplicidad, la eficiencia y la facilidad de uso. Se busca que el framework sea fácil de entender, de usar y de mantener.
Ha sido desarrollado usando los principios de la Programación Orientada a Objetos, y los principios MVC (Modelo Vista Controlador), DRY (Don't Repeat Yourself / No te repitas) y Convention over Configuration (Convención sobre configuración).

IsaliaPHP no es perfecto, y no se pretende serlo, pero si es sencillo, fácil de usar y eficiente, cuestión que viene de la mano del uso de PHP como lenguaje de programación.

Otro elemento importante es que la documentación del framework está completamente en español, y se busca que sea clara y fácil de entender.

## Comunidad y contribuciones
En términos de comunidad, IsaliaPHP es un proyecto que recién comienza a ver la luz, y se espera que sea de utilidad para la comunidad de desarrolladores, estudiantes, profesionales y entusiastas de la programación.
El hecho que su nombre provenga de dos nombres de mujeres, me hacen pensar en la inclusión y la diversidad, y es un recordatorio de que la programación no es solo para hombres, y que todas las personas pueden aprender y contribuir a este maravilloso mundo de la programación.
Con ayuda de herramientas se ha creado un podcast en el cual se habla sobre el framework, y se comparte información sobre el mismo, aunque los episodios están en idioma inglés por el momento.

## Casos de uso y adopción
Diferentes versiones de IsaliaPHP han sido usadas en proyectos personales, y en proyectos de clientes.

## Futuro y visión
En términos de futuro, la idea es promover el uso de IsaliaPHP en charlas, talleres, cursos y en general en la comunidad, y así poder ir creciendo de manera armónica y sostenible. También se espera que puedan crearse una serie de video tutotiales para que puedan ser usados como material de estudio.

## Conclusión
IsaliaPHP, es un pequeño proyecto personal, creado en una cuidad del sur de Chile, que busca ser una herramienta de utilidad para la comunidad de desarrolladores, estudiantes, profesionales y entusiastas de la programación. 
Si es posible que con el framework alguien pueda aprender y crear sus propios proyectos, ayudar a su comunidad o servir como herramienta de trabajo y con ello obtener ingresos, me daré por satisfecho.

Con cariño, 
Nelson Rojas Núñez, 
creador de IsaliaPHP.
