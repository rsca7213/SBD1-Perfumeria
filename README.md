# Sistema OLTP de Perfumes

### Proyecto realizado para la materia Sistemas de Bases de Datos 1 que consiste en un sistema OLTP. 
El proyecto contiene documentación sobre prototipos de interfaces, prototipos de reportes, procesos de negocio, diagramas de actividades, modelo logico relacional (tablas)y un modelo Entidad-Relación.
#### Lenguajes, Frameworks y Programas utilizados:
- HTML5
- CSS3
- Laravel 7 (PHP)
- Vue.js
- Bootstrap 4
- PostgreSQL 12.1
- Java
- Adobe XD
- JasperSoft Studio (para diseñar reportes)
- JasperSoft Server (para publicar reportes)

#### Funcionalidades del sistema:
- Modulo de "Registro y Evaluación de Proveedores" en el que cada empresa productora debe tener apoyo para la toma de desición sobre proveedores de ingredientes a contratar a través de la implementación de un sistema de evaluaciones iniciales y anuales utilizando criterios (ubicación geográfica, costos y alternativas de envío, alternativas de pago y cumplimiento de pedidos) colocados en una formula de evaluación por la empresa productora.
- Modulo de contratos (generación y renovaciones) en el que se acuerdan los ingredientes contratados con su precio, metodos de envío y metodos de pago con una duración de 1 año por contrato.
- Modulo de compras, en el cual se realizan pedidos de ingredientes en contratos previamente acordados y se selecciona el metodo de envio y pago a utilizar, luego se debe poder registrar los pagos correspondientes del pedido.
- Modulo de recomendación de perfumes, en el cual se le pregunta al usuario una cantidad de 8 criterios y a través de sus respuestas se recomiendan los perfumes mas compatibles con el usuario.
#### Usuarios del sistema:
- **Consumidor,** utiliza el modulo de recomendador de perfumes.
- **Productor de perfumes**, utiliza los modulos de evaluaciones, contratos y compras.
- **Proveedor de ingredientes**, utiliza los modulos de contratos y compras.
### Requisitos:
- Java JDK 14+ o Java JRE 8+ (Necesario para JasperSoft Studio y JasperSoft Server)
- JasperSoft Studio 6+
- JasperSoft Server 7.8+
- PHP 7.25+ (Necesario para Laravel)
- Laravel 7
- PostgreSQL 12.1+
- Node.js y NPM
- Composer 1.10+
### Instrucciones de Instalación:
- Crear una base de datos SQL con PostgreSQL denominada "Perfumes"
- Crear un usuario en PostgreSQL con el nombre "postgres" y contraseña "postgres"
- Ejecutar los archivos en la carpeta "Codigo/Scripts SQL" en PostgreSQL utilizando \i
- Instalar todas las dependencias del proyecto utilizando composer install (para PHP) y npm install (para JS)
- Compilar archivos JavaScript utilizando npm run serve
- Iniciar servidor local utilizando "php artisan serve"
- Acceder a la pagina a través del enlace "localhost:8000"
