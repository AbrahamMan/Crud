<?php
/* @var $gen llstarscreamll\CrudGenerator\Providers\TestsGenerator */
/* @var $fields [] */
/* @var $test [] */
/* @var $request [] */
?>
<?='<?php'?>

return [

    /**
     * Los campos y/o mensajes de validación del formulario del módulo.
     */

    /**
     * Los atributos del módulo.
     */
    'attributes' => [
        @foreach($fields as $field)
        '{{$field->name}}' => '{!!$field->label!!}',
        @endforeach
    ],

];