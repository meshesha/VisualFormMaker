# VisualFormMaker
VisualFormMaker is web application based on [laravel](https://github.com/laravel/laravel) framework and [jQuery.formbuilder](https://formbuilder.online/) that allowing you to build and manage simple html forms using simple drag-and-drop action.

## Requirements
  * web server ( like iis , apache)
  * php > 7. ^7.2.5
  * database : sqlite, mysql, pgsql, sqlsrv (tested in MySQL version 8)

## installation
### download
#### composer:
    composer create-project meshesha/visual-form-maker visualformmaker --no-dev
#### manually
    download from github releases [VisualFormMaker](https://github.com/meshesha/VisualFormMaker/releases).
### install
    * copy visualformmaker folder to the root of your web server.
    * setup web server:
        - iis :
            - [setup iis and php](https://php.iis.net/).

            Open Internet Information Services (IIS) Manager. Right click on the server and select Add Website. Fill out the form as follows:
            Site name: visualformmaker
            Application pool: DefaultAppPool
            Physical path: C:\inetpub\wwwroot\visualformmaker\public.

             - Storage folder Permissions:
                In File Explorer, right click on the storage folder in C:\inetpub\wwwroot\visualformmaker and select Properties. Under the Security tab, grant full control of the storage folder to IUSR.

            * The rewrite rule definitions in the web.config require the [URL Rewite](https://www.iis.net/downloads/microsoft/url-rewrite) extension. For easy installation, use the Free Web Platform Installer.
            [more details...](https://jimfrenette.com/2016/09/laravel-iis-windows-install/).

        - [Setting up a Laravel project on Windows with XAMPP / WAMP](https://medium.com/@insidert/setting-up-laravel-project-on-windows-2aa7e4f080da).

        - apache: [Steps for configuring Laravel on Apache HTTP Server](https://phpraxis.wordpress.com/2016/08/02/steps-for-configuring-laravel-on-apache-http-server/)

    * setup database server and create database called 'visualformmaker'.
        - note: this step is optional in MySQL.
    * open web browser and go to to application link (e.g.: http://localhost/visualformmaker).
    * start installation process.
    
## first time login:
    email: admin@localhost
    password: admin

## usage
    * soon ..

## Acknowledgments
This project inspired by my previous project [SimplePhpFormBuilder](https://github.com/meshesha/SimplePhpFormBuilder) and [Laravel Form Builder Package](https://github.com/jazmy/laravel-formbuilder).

