# MUSEON
## Sistema de gestión de colecciones y piezas de museo

Museon es una aplicación web desarrollada para administrar la información de un museo y organizar sus piezas u objetos. Permite registrar objetos, clasificarlos mediante atributos y etiquetas, ubicarlos en salas y administrar los usuarios que trabajan con el sistema.

## Autores

- Kevin Atencio
- Gabriel Benítez
- Joaquín García
- Sebastián Zalazar

## Contexto académico

Museon fue desarrollado con fines educativos para el **Instituto Superior Gaspar L. Benavento**, en el marco de la **Tecnicatura Superior en Análisis y Desarrollo de Software** (3.er año).

---

## 1. Tecnologías utilizadas

- **Framework:** CodeIgniter 4.6.1.
- **Lenguaje del servidor:** PHP 8.1 o superior.
- **Base de datos:** MySQL.
- **Conexión a MySQL:** extensión MySQLi de PHP.
- **Interfaz:** HTML, CSS y JavaScript.
- **Bibliotecas y recursos de interfaz:**
  - jQuery 3.7.1.
  - Bootstrap 5.3.8.
  - Bootstrap Icons.
  - DataTables 2.3.8 y Buttons 3.2.6.
  - JSZip 3.10.1 y pdfmake 0.2.7, utilizados por las herramientas de exportación de tablas.
  - vanillajs-datepicker 1.3.4, para la selección de fechas.
  - Font Awesome y Google Fonts.
- **Gestión de versiones:** Git.

Parte de las bibliotecas se carga desde CDN, por lo que el navegador necesita conexión a Internet para obtener esos recursos.

## 2. Funcionalidades principales

### Gestión de objetos
- Alta, consulta, edición y baja lógica de objetos del museo.
- Registro de código, denominación y descripción.
- Asociación de atributos y etiquetas.
- Asignación de ubicación desde el formulario de la pieza o desde las salas.
- Visualización de la ficha identitaria del objeto.
- Gestión de imágenes y archivos asociados a las piezas.
- Papelera y recuperación de registros cuando la opción está disponible.

### Gestión de atributos
- Alta, listado, edición y baja lógica de atributos.
- Tipos de datos contemplados: texto, numérico, archivo y fecha.
- Los atributos numéricos pueden incluir una unidad de medida.
- Los valores de los atributos se asocian a cada objeto.

### Gestión de etiquetas
- Creación y administración de etiquetas.
- Asociación de etiquetas con los objetos para facilitar su clasificación.
- Listado y papelera de etiquetas.

### Gestión de salas
- Administración de salas y espacios del museo.
- Organización jerárquica mediante salas principales y salas dependientes.
- Asignación y retiro de objetos de una ubicación.

### Usuarios, roles y permisos
- Administración de usuarios: alta, listado, edición y baja lógica.
- La instalación inicial incluye el rol Administrador; los demás roles se crean desde la opción «Otros...» al registrar usuarios.
- Creación de roles al dar de alta usuarios, con permisos por sección; solo se pueden otorgar permisos propios y únicamente un administrador puede asignar el rol Administrador. Crear, editar o eliminar requiere también el permiso Ver en esa sección; los cambios de rol se aplican en la siguiente petición.
- Cada rol puede tener acceso a la fecha de alta por sección y al historial de actividad. El historial solo muestra registros de las secciones que el usuario puede consultar; las fechas de los eventos del historial son independientes de la fecha de alta del listado.
- Al editar un usuario se pueden personalizar sus permisos sin modificar los de otras personas con el mismo rol. Solo se pueden otorgar permisos propios; el rol Administrador no admite personalización. Al cambiar de rol se restablecen los permisos del nuevo rol, salvo que se vuelvan a personalizar.
- La copia de seguridad contiene todos los datos y solo está disponible para administradores.
- Configuración de los datos del usuario que inició sesión.
- Posibilidad de actualizar la contraseña desde la configuración personal.
- Opción para mostrar u ocultar la contraseña al iniciar sesión y al crear un usuario.
- Control de acceso mediante roles y permisos para los módulos principales.

### Actividad y auditoría
- Consulta de actividad por tipo de registro, combinable con filtros por campo y búsqueda exacta por ID de pieza.
- Al pasar el cursor sobre una acción o tipo de registro se muestra una breve explicación.
- La base de datos contempla eventos de alta, baja, modificación y recuperación para las entidades auditadas.

### Copias de seguridad
- Se incorporó una función para generar y descargar una copia de seguridad comprimida en ZIP.
- La copia incluye una exportación SQL de las tablas y los archivos e imágenes almacenados en `public/file/` y `public/imagenes/objetos/`.
- La generación de ZIP requiere que la extensión `zip` de PHP esté habilitada.
- La operación está protegida para usuarios con sesión iniciada.

## 3. Requisitos previos

Para ejecutar el proyecto en un entorno local se necesita:

- XAMPP para Windows o Linux, con Apache, MySQL/MariaDB y PHP.
- PHP 8.1 o superior.
- Composer.
- Git, para clonar el repositorio.
- Las extensiones PHP:
  - `intl`
  - `mbstring`
  - `mysqli`
  - `zip` (necesaria para la función de copias de seguridad).

También se recomienda verificar que Apache y MySQL estén iniciados antes de ingresar al sistema.

## 4. Obtener el proyecto desde Git

Repositorio:

[https://github.com/joaquingarcia-grc/museon](https://github.com/joaquingarcia-grc/museon)

El proyecto puede descargarse clonando el repositorio desde una terminal.

```bash
git clone https://github.com/joaquingarcia-grc/museon.git
```

El comando crea una carpeta llamada `museon` con los archivos del sistema.

## 5. Instalación local en Windows con XAMPP

1. Instalar XAMPP y Git.
2. Iniciar los servicios **Apache** y **MySQL** desde el panel de control de XAMPP.
3. Abrir una terminal y ubicarse en la carpeta `htdocs`:

   ```bat
   cd C:\xampp\htdocs
   ```

4. Clonar el repositorio:

   ```bash
   git clone https://github.com/joaquingarcia-grc/museon.git
   ```

5. Ingresar a la carpeta del proyecto:

   ```bat
   cd museon
   ```

6. Instalar las dependencias de PHP:

   ```bash
   composer install
   ```

7. Abrir phpMyAdmin desde `http://localhost/phpmyadmin`.
8. Importar el archivo `museo.sql`, ubicado en la raíz del proyecto. El script crea la base de datos `museo` y sus tablas.
   En una instalación existente, ejecutar `php spark migrate` para agregar los permisos nuevos sin perder los roles existentes.
9. Revisar la configuración de conexión a la base de datos en `app/Config/Database.php`. La configuración incluida utiliza:
   - Servidor: `localhost`
   - Usuario: `root`
   - Contraseña: vacía
   - Base de datos: `museo`
   - Puerto: `3306`

   Si la instalación local utiliza otras credenciales o un puerto diferente, modificar esos valores antes de iniciar el sistema.

10. Verificar la URL base en `app/Config/App.php`. Para la instalación indicada, el valor debe corresponder a:

    ```php
    public string $baseURL = 'http://localhost/museon/public/';
    ```

11. Abrir el sistema en el navegador:

    `http://localhost/museon/public/`

## 6. Instalación local en Linux con XAMPP

1. Instalar XAMPP para Linux, Git y Composer.
2. Iniciar XAMPP:

   ```bash
   sudo /opt/lampp/lampp start
   ```

3. Clonar el repositorio dentro del directorio `htdocs` de XAMPP:

   ```bash
   cd /opt/lampp/htdocs
   sudo git clone https://github.com/joaquingarcia-grc/museon.git
   ```

4. Ingresar a la carpeta del proyecto:

   ```bash
   cd /opt/lampp/htdocs/museon
   ```

5. Instalar las dependencias:

   ```bash
   composer install
   ```

6. Abrir phpMyAdmin en `http://localhost/phpmyadmin` e importar el archivo `museo.sql`.
7. Revisar los datos de conexión en `app/Config/Database.php`. Deben coincidir con la configuración de MySQL/MariaDB de XAMPP.
8. Comprobar que `app/Config/App.php` tenga la URL base correspondiente:

   ```php
   public string $baseURL = 'http://localhost/museon/public/';
   ```

9. Asegurarse de que Apache pueda escribir en los directorios que el sistema utiliza para archivos, imágenes y datos temporales, especialmente `writable/` y las carpetas de archivos de `public/`.
10. Abrir en el navegador:

    `http://localhost/museon/public/`

> **Nota:** las rutas de instalación pueden variar si XAMPP fue instalado en una ubicación diferente. En ese caso, adaptar los comandos y la URL a la ubicación real.

## 7. Configuración de la extensión ZIP en PHP

La función de copias de seguridad utiliza la clase `ZipArchive`. Si la extensión ZIP no está habilitada, el sistema no podrá generar el archivo comprimido.

### Windows (XAMPP)

1. Abrir el archivo `php.ini` correspondiente a la instalación de XAMPP. Desde el panel de XAMPP se puede abrir mediante el botón **Config** de Apache y seleccionar `php.ini`.
2. Buscar la línea:

   ```ini
   ;extension=zip
   ```

3. Si aparece comentada, quitar el punto y coma inicial:

   ```ini
   extension=zip
   ```

4. Guardar los cambios.
5. Reiniciar Apache desde el panel de XAMPP.

### Linux (XAMPP)

1. Abrir el archivo de configuración de PHP de XAMPP, habitualmente:

   ```bash
   sudo nano /opt/lampp/etc/php.ini
   ```

2. Buscar `extension=zip`. Si está comentada, habilitarla quitando el carácter `;` inicial. Si la extensión no está disponible en esa instalación, será necesario instalar o habilitar el módulo ZIP compatible con la versión de PHP utilizada.
3. Guardar el archivo y reiniciar XAMPP:

   ```bash
   sudo /opt/lampp/lampp restart
   ```

Para comprobar que PHP reconoce la extensión, se puede ejecutar:

```bash
php -m
```

En la lista debe aparecer `zip`. Es importante comprobar la configuración del mismo PHP que utiliza Apache, ya que el PHP de la terminal puede ser distinto.

## 8. Base de datos

El archivo `museo.sql` contiene la estructura de la base de datos y crea la base `museo`. Entre sus tablas principales se encuentran:

- `museos`
- `usuarios`
- `roles`
- `rol_permiso`
- `objetos`
- `atributos`
- `etiquetas`
- `salas`
- `objeto_atributos`
- `objeto_etiquetas`
- `piezas_archivos`
- `piezas_imagenes`
- `auditoria`

### Registro inicial obligatorio

El SQL incluye un `INSERT` de referencia en la tabla `museos`. Este registro es necesario para que el sistema disponga de los datos básicos del museo que utiliza la interfaz.

Además, el SQL incluye un `INSERT` mínimo en la tabla `salas`: una sala raíz sin sala padre (`id_pertenece` en `null`), que representa al museo como ubicación base. Este registro es necesario para poder crear el anidamiento de las demás salas, ya que todas se organizan a partir de él.

Los datos incluidos son de ejemplo y pueden modificarse luego desde la base de datos según corresponda.

**Importante:** el SQL incluye ese registro inicial del museo, pero no crea automáticamente usuarios ni credenciales de acceso. La preparación de los roles, permisos y del primer usuario debe resolverse para cada instalación antes de poder iniciar sesión. No se incluyen usuarios ni contraseñas de prueba en este repositorio.

## 9. Copias de seguridad

La función de copia de seguridad realiza las siguientes tareas:

1. Comprueba que exista una sesión iniciada.
2. Verifica que PHP tenga disponible `ZipArchive`.
3. Recorre las tablas de la base de datos y genera un archivo `base_de_datos.sql` con las estructuras y los registros.
4. Incorpora al ZIP los archivos de `public/file/` y las imágenes de `public/imagenes/objetos/`, si esos directorios existen.
5. Prepara la descarga del archivo con un nombre que incluye la fecha.

El archivo ZIP se genera temporalmente dentro de `writable/`. Se recomienda guardar la copia descargada en un lugar seguro y comprobar periódicamente que pueda abrirse.

## 10. Estructura general del proyecto

```text
museon/
├── app/
│   ├── Config/          # Configuración de la aplicación y rutas
│   ├── Controllers/     # Controladores de los módulos
│   ├── Models/          # Modelos y acceso a datos
│   └── Views/           # Vistas de la interfaz
├── public/
│   ├── imagenes/        # Recursos e imágenes
│   └── vendor/          # CSS y JavaScript del proyecto
├── system/              # Código del framework CodeIgniter
├── tests/               # Archivos de pruebas
├── writable/            # Archivos temporales y copias de seguridad
├── museo.sql            # Estructura y datos iniciales de la base
├── composer.json        # Dependencias PHP
└── README.md            # Documentación del proyecto
```

## 11. Consideraciones

- Antes de utilizar el sistema, verificar la conexión a MySQL y la URL base.
- No publicar contraseñas ni credenciales reales en el repositorio.
- Para utilizar la copia de seguridad, habilitar la extensión PHP `zip`.
- El registro incluido en `museo.sql` es de referencia y debe adecuarse a los datos reales del museo.
- El repositorio no proporciona credenciales de acceso predeterminadas.
- En un entorno de producción se deben revisar las configuraciones de seguridad, permisos de carpetas, errores y credenciales antes de publicar la aplicación.

## Equipo y contribuciones

Según los documentos de trabajo y el historial entregados por el equipo:

- **Kevin Atencio:** desarrollo de los módulos de objetos y atributos, relaciones con etiquetas y atributos, ficha identitaria imprimible, tipos de datos y unidades de medida.
- **Gabriel Benítez:** mejoras visuales del inicio de sesión y panel administrativo, estilos de tablas y botones, ajustes de navegación y presentación.
- **Joaquin Garcia:** trabajo registrado en validaciones, JavaScript, peticiones AJAX, buscador, correcciones e implementación progresiva del sistema de roles y permisos.

El historial también registra tareas de integración y fusiones entre ramas realizadas por el equipo.

## Licencia

Este proyecto se distribuye bajo la licencia **MIT**.

Esto permite utilizar, copiar, modificar, distribuir y sublicenciar el software, siempre y cuando se conserve el aviso de copyright y el texto de la licencia original.

Para consultar los términos completos, revisar el archivo [`LICENSE`](LICENSE) incluido en la raíz del repositorio.

---
**Museon — Sistema de gestión de colecciones de museo.**