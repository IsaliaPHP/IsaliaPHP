# La Historia de IsaliaPHP

## Introducción
IsaliaPHP es un framework PHP que nació como un proyecto personal para resolver problemas usando el paradigma de la Programación Orientada a Objetos, y los principios MVC (Modelo Vista Controlador), DRY (Don't Repeat Yourself / No te repitas) y Convention over Configuration (Convención sobre configuración).
Su creación data de marzo de 2016, y desde entonces ha sido evolucionando para resolver problemas de manera sencilla y eficiente.

Soy Nelson Rojas, creador de este proyecto. Estudié para ser Ingeniero de Ejecución en Computación e Informática en la Universidad Católica del Maule de la ciudad de Talca, Chile. Aunque originalmente quería ser electricista. Luego cambié de rumbo sin saber en qué iba a entrar. Nunca había escrito un programa, no sabía de lenguajes de programación ni de lógica. Todo lo aprendí por mi cuenta y con la ayuda de los libros y mis compañeros de laboratorio.

Me gusta trabajar con los computadores, y para mí, la programación es una especie de habilidad en la que aprendes a comunicarte con la máquina para que haga cosas que tú le dices (o que te piden que haga). 

Aprendí sobre programación web por mi cuenta el año 1999. Los cursos de la universidad no contemplaban el tema, pero la biblioteca tenía algunos libros sobre ASP (Asp Clásico).
Por aquellos años también existía PHP 3, pero no se dieron las cosas para participar con PHP sino hasta la versión 4. Yo trabajaba principalmente como desarrollador de escritorio, y programaba en Visual Basic 6 y con VB .NET. Tengo un amigo que insistía con crear un proyecto web, así que me di a la labor de buscar opciones que no fueran tan tediosas como lo eran las aplicaciones de escritorio. Hice una primera versión de la aplicación web con PHP 4 y Dreamweaver, pero el código resultante fue bastante feo, y no cumplía con los principios de simplicidad, eficiencia y facilidad de uso.

Luego empecé a ver si podía llevarlo a ASP .NET, pero no logré resolver el problema del hosting, así que se desechó esa idea.

Luego me encontré con la versión 1 de Ruby on Rails, y me enamoré de la simplicidad y eficiencia de la misma.

Fue entonces que empecé a abrazar la idea de que crear aplicaciones no tenía por qué ser un dolor de cabeza. Hice cuatro charlas sobre Ruby On Rails, creé un par de aplicaciones para usarlas desde Windows, pero para implementarlo en web, en un hosting real, no fue una tarea sencilla.

Nacieron entonces frameworks en PHP que se inspiraron en Ruby On Rails, y uno de ellos fue CakePHP. Hice un par de aplicaciones con ese framework, pero los cambios entre la versión 1 y 2 fueron bastante fuertes, y no fue sencillo ni grato tener que aprender a usarlo de nuevo.

En un cambio de trabajo, me presentaron KumbiaPHP, y me enamoré de su simplicidad y facilidad de uso. Eso me permitió crear aplicaciones web de manera sencilla y eficiente. Ahí conocí a su comunidad, creadores, y colaboré en diferentes temas, como la documentación, y la escritura de artículos. También estaba atento al soporte para los usuarios nuevos, y con muchos de ellos pude compartir algunos videos que hice para que pudieran entender mejor el framework.

Hice unas cuantas colaboraciones en KumbiaPHP, con colegas de Colombia, Ecuador, Perú, Argentina, España y Chile. También participé en una charla sobre el framework entre ex alumnos de la universidad.

Pero yo quería crear mi propio framework, y no un fork de KumbiaPHP. Quería crear algo nuevo, algo que me permitiera expresar mi manera de ver la programación.

Una de mis frases favoritas es: No hay magia de por medio para lograr tus objetivos, es trabajo duro, tiempo y constancia.

## Orígenes y motivación
La creación de IsaliaPHP surgió de la necesidad de resolver problemas específicos en proyectos personales, y de la búsqueda de una manera más sencilla y eficiente de realizar tareas repetitivas. Los principios que sustentan el framework vienen de la herencia de experiencias previas con Ruby on Rails, CakePHP y KumbiaPHP. Pero quería otro enfoque, una herramienta que me permitiera crear aplicaciones web de manera más sencilla y eficiente, y que luego pudiera exponer para que otros la pudieran usar. 

Sé que suena a "repetir la rueda", pero quería crear algo propio, algo a lo que pudiera dedicarle tiempo y esfuerzo, y que pudiera ser de utilidad para la comunidad.

Desde la creación del framework, he estado trabajando en el desarrollo de aplicaciones web, y en la actualidad, IsaliaPHP se encuentra en uso en varios proyectos, tanto personales como de clientes.


## Desarrollo inicial
Leer el libro Modern PHP, de Josh Lockhart, me inspiró a crear un framework que fuera moderno, sencillo y fácil de usar. Las primeras versiones de IsaliaPHP tenían las estructuras con espacios de nombres, y usaban el autoload de PHP basado en PSR-0. A mi gusto cumplía con los principios del libro, y me permitía crear aplicaciones web de manera sencilla y eficiente. Comencé usando NotORM para el manejo de las bases de datos, y el uso de consultas preparadas.

Luego de un análisis durante este año 2024, decidí dirigir la mirada hacia personas con menos conocimientos técnicos, estudiantes y recién iniciados, así que el proyecto tomó un giro, y comencé a trabajar en una estructura que fuera más sencilla y fácil de entender, y que pudiera ser asimilada por personas que recién comenzaban a programar.

En términos de desafíos iniciales, lo primero fue trabajar en la gestión de las peticiones HTTP, y la forma en que se gestionaban las rutas, y se podía acceder a ellas de manera sencilla y eficiente. Para eso se usa el patrón de diseño Front Controller, y el encargado de dicha labor es un simple archivo php llamado index.php que está alojado en el directorio public.

Luego se definió una clase encargada del acceso y manipulación de datos en la base de datos. La clase Db es una clase sencilla, que permite usar SQL nativo, basándose en las bondades de PDO para realizar consultas seguras y parametrizadas.

Encima de Db está la clase Model, que permite definir modelos de datos, y que luego pueden ser usados en las vistas o controladores. La clase Model es un wrapper de la clase Db, y permite definir los métodos para acceder a los datos, eliminando mayormente la escritura de consultas SQL.

Gestionar las versiones del código fue un desafío, y se usó Git para ello. Como herencia de KumbiaPHP, se  usó el sistema de análisis de código Scrutinizer. Fue clave para mantener un estándar de calidad. Soy más bien usuario que experto de PHP, así que las recomendaciones de Scrutinizer fueron fundamentales para la evolución del framework y la calidad de su código.

## Evolución y crecimiento
La evolución del framework ha sido constante, y ha ido tomando forma según recomendaciones para la mejora de la experiencia de uso y la disminución de la curva de aprendizaje.

Aunque inicialmente el framework sería mucho más básico, pensar en herramientas de generación de código, como Scaffolding, la consola interactiva (que sirve para crear controladores, modelos y vistas), las clases Db y Model, helpers como Html y Form, y finalmente el SqlBuilder, fueron claves para la evolución del framework.

También se han agregado componentes para la Autenticación y la gestión de archivos adjuntos, y se ha trabajado en la mejora de la seguridad del framework.

## Anécdotas
La primera anécdota es que el nombre IsaliaPHP, viene de los nombres de mi esposa e hija. Isabel y Emilia respectivamente. De la primera se extrajo ISA, y de la segunda LIA lo que nos da Isalia. El PHP del fondo es porque está escrito en PHP y para seguir la línea de CakePHP y KumbiaPHP.

Otra anécdota interesante es que una versión de IsaliaPHP sería completamente en español, con nombres de clases y métodos en español, pero la idea fue abandonada por temas de compatibilidad y a causa de que los lenguajes de programación están escritos principalmente en inglés.


## Filosofía y principios de diseño
La filosofía de IsaliaPHP es sencilla, y se basa en la simplicidad, la eficiencia y la facilidad de uso. Se busca que el framework sea fácil de entender, de usar y de mantener.
Ha sido desarrollado bajo los principios de la Programación Orientada a Objetos, MVC (Modelo Vista Controlador), DRY (Don't Repeat Yourself / No te repitas) y Convention over Configuration (Convención sobre configuración).

IsaliaPHP no es perfecto, y no se pretende serlo, pero si es sencillo, fácil de usar y eficiente, cuestión que viene de la mano del uso de PHP como lenguaje de programación.

Otro elemento importante es que la documentación del framework está completamente en español, y se busca que sea clara y fácil de entender y por lo tanto accesible a usuarios de habla hispana.

## Comunidad y contribuciones
En términos de comunidad, IsaliaPHP es un proyecto que recién comienza a ver la luz, y se espera que sea de utilidad para la comunidad de desarrolladores, estudiantes, profesionales y entusiastas de la programación.

El hecho que su nombre provenga de dos nombres de mujeres, me hacen pensar en la inclusión y la diversidad, y es un recordatorio de que la programación no es solo para hombres, y que todas las personas pueden aprender y contribuir a este maravilloso mundo de la programación.

Con ayuda de herramientas de inteligencia artificial se ha creado un podcast en el cual se habla sobre el framework, y se comparte información sobre el mismo, aunque los episodios están en idioma inglés por el momento.

## Casos de uso y adopción
Diferentes versiones de IsaliaPHP han sido usadas en proyectos personales, y en proyectos de clientes.

## Futuro y visión
En términos de futuro, la idea es promover el uso de IsaliaPHP en charlas, talleres, cursos y en general en la comunidad, y así poder ir creciendo de manera armónica y sostenible. También se espera que puedan crearse una serie de video tutoriales para que puedan ser usados como material de estudio. Aunque debo confesar que una de mis ideas iniciales era comenzar con un grupo de mujeres entusiastas, que quisieran aprender a programar y crear sus propios proyectos, y que puedan ser mentoras y mentorados a la vez. Lo mismo me pasa con la juventud relegada, que no sabe sobre la programación y las oportunidades que ello puede brindar.

## Conclusión
IsaliaPHP, es un pequeño proyecto personal, creado en Talca, una ciudad del sur de Chile. IsaliaPHP busca ser una herramienta de utilidad para la comunidad de desarrolladores, estudiantes, profesionales y entusiastas de la programación. 

Si es posible que con el framework alguien pueda aprender y crear sus propios proyectos, ayudar a su comunidad o servir como herramienta de trabajo y con ello obtener ingresos, me daré por satisfecho.

Con cariño, 
Nelson Rojas Núñez, 
creador de IsaliaPHP.
