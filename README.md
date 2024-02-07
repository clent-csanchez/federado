# ACESSO FEDERADO - clent/federado

El paquete permite al sistema crear una interfaz de autenticacion a traves del acceso federado de clent

## Instalación

Puedes instalar este paquete a través de Composer. Asegúrate de tener Composer instalado en tu sistema. Para poder descargar este paquete es necesario ejecutar estos pasos previos : 

1. **Edita el Archivo `composer.json`:**
   Abre el archivo `composer.json` en la raíz de tu proyecto Laravel.

2. **Agrega el Repositorio VCS:**
   Dentro del archivo `composer.json`, busca la sección `"repositories"` (si no existe, créala) y agrega un nuevo objeto JSON que represente tu repositorio VCS. Aquí hay un ejemplo:

   ```json
   "repositories": [
       {
           "type": "vcs",
            "url":   "git@github.com:clent-csanchez/federado.git"
       }
   ]

2. **Agrega el paquete a require del proyecto :**
   Dentro del archivo `composer.json`, busca la sección `"require"` (si no existe, créala) y agrega un nuevo objeto JSON que represente el paquete que vas a descargar desde el repo vsc. Aquí hay un ejemplo:
   
   ```json
    "require": {
        "clent/federado" : "dev-master"
    },

3. **Instala el paquete en el proyecto :**
    Para instalar el proyecto, se ejecuta el comando:
```bash
    composer update clent/federado
```
4. **Publica el service provider de paquete :**
    Es necesario publicar el service provider para cargar el archivo de configuracion `federado.php` que sera creado en tu proyecto, necesario para que funcione correctamente. Se publica con el comando : 
    
```bash
    php artisan vendor:publish --provider="Clent\Federado\FederadoServiceProvider"
```
    Es posible que si el archivo federado.php existe no cargue nuevas configuraciones si el paquete es actualizado. En este caso correr el comando :
```bash
    php artisan vendor:publish --provider="Clent\Federado\FederadoServiceProvider" --force
```
4. **Configurar el archivo .env :**
    Es necesario agregar 3 variables de entorno a tu archivo .env que deben coincidir con ciertos valores presentes en campos del acceso federado, las cuales son :

    ```plaintext
        APP_SECRET="clent_acceso_federado.tb_plataforms.secret|clent_acceso_federado.tb_plataforms.name"
        SSO_SECRET="aqui va la contrase;a del accso federeado"
        SSO_PLATAFORM_ID=clent_acceso_federado.tb_plataforms.id
    ```
    Reemplazando por supuesto por sus valores correspondiente segun la convencion escrita anteriormente.

5. **Crear tabla tb_secret_user**
    Copia la estructura de la tabla clent_cotizadores y pegarla en tu BD. los campos son
    ```plaintext
        `id`
        `secret`
        `user_id`
        `timestamps`   
    ```
6. **Dar de alta un Usuario :**.
    Para que el usuario pueda iniciar sesion desde el acceso federado debemos crear un usuario en tb_users del acceso federado. el campo user secret agregar un valor. Ese valor sera agregado en la tabla tb_secret_user en tu proyecto y el campo user_id debe ser el id del usuario que quieres autenticar en tu plataforma con ese secret.

    Enjoy!