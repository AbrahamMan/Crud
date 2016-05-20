<?php

namespace llstarscreamll\CrudGenerator\Providers;

use llstarscreamll\CrudGenerator\Providers\BaseGenerator;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

/**
* 
*/
class TestsGenerator extends BaseGenerator
{
    /**
     * El nombre de la tabla en la base de datos.
     * @var string
     */
    public $table_name;
    
    /**
     * La iformación dada por el usuario.
     * @var Object
     */
    public $request;

    public $msg_success = array();
    public $msg_error = array();

    /**
     * 
     */
    public function __construct($request)
    {
        $this->table_name = $request->get('table_name');
        $this->request = $request;
    }

    /**
     * Genera los tests o pruebas funcionales del CRUD a crear.
     * @return integer|bool
     */
    public function generate()
    {
        // no se ha creado la carpeta para los pageObjects?
        if (! file_exists($this->pageObjectsDir())) {
            // entonces la creo
            mkdir($this->pageObjectsDir(), 0777, true);
        }

        // no se ha creado la carpeta para los tests funcionales?
        if (! file_exists($this->testsDir())) {
            // entonces la creo
            mkdir($this->testsDir(), 0777, true);
        }

        // no se ha creado la carpeta para los archivos de idioma?
        if (! file_exists($this->langDir())) {
            // entonces la creo
            mkdir($this->langDir(), 0777, true);
        }

        ///////////////////////////////////
        // genero los archivos de idioma //
        ///////////////////////////////////
        if (! $this->generateLangFiles()) {
            $this->msg_error[] = "Ocurrió un error generando el archivo de idioma.";
            return false;
        }
        $this->msg_success[] = "Archivo de idioma generado correctamente.";

        /////////////////////////////////////////////////////////
        // genero los archivos para los seeder de los permisos //
        /////////////////////////////////////////////////////////
        if (! $this->generatePermissionsSeederFile()) {
            $this->msg_error[] = "Ocurrió un error generando el seeder de permisos.";
            return false;
        }
        $this->msg_success[] = "Seeder de permisos generado correctamente.";

        ///////////////////////////////////
        // ejecuta composer dumpautoload //
        ///////////////////////////////////
        if (($command = $this->executeComposerDumpAutoload()) === false) {
            $this->msg_error[] = "Ocurrió un error ejecutando composer dumpautoload.";
            return false;
        }
        $this->msg_success[] = "composer dumpautoload exitoso: ".$command;

        // recorro el array de tests que debo crear
        foreach (config('llstarscreamll.CrudGenerator.config.tests') as $test) {

            // genero los page objects
            if (! $this->generatePageObject($test)) {
                $this->msg_error[] = "Ocurrió un error generando el PageObject ".$test.".";
                return false;
            }
            $this->msg_success[] = "PageObject ".$test." generado correctamente.";

            if ($test == 'Base')
                continue;
            
            // genero los tests
            if (! $this->generateFunctionalTests($test)) {
                $this->msg_error[] = "Ocurrió un error generando el Test ".$test.".";
                return false;
            }
            $this->msg_success[] = "Test ".$test." generado correctamente.";

            // genero los tests
            
        }
        //dd($this->msg_success, $this->msg_error);
        
        return true;
    }

    /**
     * Devuleve string del path de los page objects para los tests.
     * @return string
     */
    public function pageObjectsDir()
    {
        return base_path('tests/_support/Page/Functional/'.$this->pageObjectsDirName());
    }

    /**
     * Devuelve el nombre de la carpeta que contiene los page objects de los tests
     * funcionales.
     * @return string
     */
    public function pageObjectsDirName()
    {
        return ucwords(str_plural($this->table_name));
    }

    /**
     * Devuleve string del path de los tests funcionales.
     * @return string
     */
    public function testsDir()
    {
        return base_path('tests/functional/'.$this->pageObjectsDirName());
    }

    /**
     * Devuleve string del path de los archivos de idioma.
     * @return string
     */
    public function langDir()
    {
        return base_path('resources/lang/es/'.$this->getLangAccess());
    }

    /**
     * Devuleve string del path de los seeder.
     * @return string
     */
    public function seedsDir()
    {
        return base_path('database/seeds');
    }

    /**
     * Genera los achivos de los page objects.
     * @return bool
     */
    public function generatePageObject($test)
    {
        $pageObjectFile = $this->pageObjectsDir()."/".$test.".php";

        $content = view($this->templatesDir().'.pageObjects.'.$test, [
            'gen' => $this,
            'fields' => $this->advanceFields($this->request),
            'test' => $test,
            'request' => $this->request
        ]);

        if (file_put_contents($pageObjectFile, $content) === false) {
            return false;
        }

        return true;
    }

    /**
     * Genera los archivos de test del CRUD.
     * @return bool
     */
    public function generateFunctionalTests($test)
    {
        $testFile = $this->testsDir()."/".$test."Cest.php";

        $content = view($this->templatesDir().'.tests.'.$test, [
            'gen' => $this,
            'fields' => $this->advanceFields($this->request),
            'test' => $test,
            'request' => $this->request
        ]);

        if (file_put_contents($testFile, $content) === false) {
            return false;
        }

        return true;
    }

    /**
     * Genera el archivo de idioma del paquete.
     * @return bool
     */
    public function generateLangFiles()
    {
        // genero el archivo views
        $langFile = $this->langDir()."/views.php";

        $content = view($this->templatesDir().'.lang.views', [
            'gen' => $this,
            'fields' => $this->advanceFields($this->request),
            'request' => $this->request
        ]);

        if (file_put_contents($langFile, $content) === false) {
            return false;
        }

        // genero el archivo messages
        $langFile = $this->langDir()."/messages.php";

        $content = view($this->templatesDir().'.lang.messages', [
            'gen' => $this,
            'fields' => $this->advanceFields($this->request),
            'request' => $this->request
        ]);

        if (file_put_contents($langFile, $content) === false) {
            return false;
        }

        // genero el archivo validation
        $langFile = $this->langDir()."/validation.php";

        $content = view($this->templatesDir().'.lang.validation', [
            'gen' => $this,
            'fields' => $this->advanceFields($this->request),
            'request' => $this->request
        ]);

        if (file_put_contents($langFile, $content) === false) {
            return false;
        }

        return true;
    }

    /**
     * Genera el seeder de los permisos del módulo o entidad.
     * @return bool
     */
    public function generatePermissionsSeederFile()
    {
        $seederFile = $this->seedsDir()."/".$this->modelClassName()."PermissionsSeeder.php";

        $content = view($this->templatesDir().'.seeders.module-permissions-seeder', [
            'gen' => $this,
            'fields' => $this->advanceFields($this->request),
            'request' => $this->request
        ]);

        if (file_put_contents($seederFile, $content) === false) {
            return false;
        }

        return true;
    }

    /**
     * Ejecuta el comando compser dumpautoload.
     * @return bool|string
     */
    public function executeComposerDumpAutoload()
    {
        $process = new Process('cd '.base_path().' && composer dumpautoload');
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            //throw new ProcessFailedException($process);
            return false;
        }

        return $process->getOutput();
    }
}