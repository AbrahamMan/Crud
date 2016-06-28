<?php
/* @var $gen llstarscreamll\CrudGenerator\Providers\TestsGenerator */
/* @var $fields [] */
/* @var $test [] */
/* @var $request [] */
?>
<?='<?php'?>

return [

    /**
     * Los textos de las vistas como por ejemplo los labels de los campos del fomulario,
     * cabeceras de tablas, etc...
     */
    
    // nombre del módulo
    'module'   => [
        'name'           => '{!!$request->get('plural_entity_name')!!}',
        'short-name'     => '{!!$request->get('plural_entity_name')!!}',
        'name-singular'  => '{!!$request->get('single_entity_name')!!}',
    ],

    // vista index
    'index'    => [
        'name'          => 'Index',
        'btn-trash'     => 'Mover {!!$request->get('plural_entity_name')!!} a Papelera',
        'btn-create'    => 'Crear {!!$request->get('single_entity_name')!!}',
        'table-actions-column'  => 'Acciones',
        'filters-button'        => 'Más Filtros',
@if ($gen->hasDeletedAtColumn($fields))
        'filter-with-trashed-label' => 'Con Reg. Borrados',
        'filter-only-trashed-label' => 'Sólo Reg. Borrados',
        'restore-row-button-label' => 'Restaurar',
@if ($request->has('use_modal_confirmation_on_delete'))
        // valores para la ventana modal de confirmación de acción de botón restaurar registro
        'modal-restore-title' => 'Está seguro?',
        'modal-restore-message' => 'La información de <strong>:item</strong> será <strong>Restaurada</strong>...',
        'modal-restore-btn-confirm-label' => 'Restaurar',
        'modal-restore-btn-confirm-class-name' => 'btn-success',
@else
        'restore-confirm-message' => 'Está seguro? El resgistro será RESTAURADO...',
@endif
@endif
        'search-button'         => 'Buscar',
        'clean-filter'          => 'Limpiar filtros',
        'see-details-button'    => 'Ver detalles',
        'edit-item-button'      => 'Editar registro',
        'delete-item-button'    => 'Borrar registro',
        'no-records-found'      => 'No se encontraron registros...',
        'create-form-modal-title'   => 'Crear Nuevo {!!$request->get('single_entity_name')!!}',
@if ($request->has('use_modal_confirmation_on_delete'))
        // valores para la ventana modal de confirmación de acción del botón eliminar registro
        'modal-delete-title'        => 'Está seguro?',
        'modal-delete-message'       => 'La información de <strong>:item</strong> será <strong>:action</strong>...',
        'modal-delete-btn-confirm-label' => 'Borrar',
        'modal-delete-btn-confirm-class-name' => 'btn-danger',
        // los valores por defecto de las ventanas modales generadas con el componente Bootbox
        'modal-default-title' => 'Está Seguro?',
        'modal-default-btn-confirmation-label' => 'Confirmar',
        'modal-default-btn-confirmation-className' => 'btn-primary',
        'modal-default-btn-cancel-label' => 'Cancelar',
        'modal-default-btn-cancel-className' => 'btn-default',
@else
        'delete-confirm-message' => 'Está seguro? Toda la información será ELIMINADA...',
@endif
@if ($gen->hasDateFields($fields) || $gen->hasDateTimeFields($fields))
        // para el componente Bootstrap dateRangePicker
        'dateRangePicker' => [
            'applyLabel' => 'Aplicar',
            'cancelLabel' => 'Cancelar',
            'fromLabel' => 'Desde',
            'toLabel' => 'Hasta',
            'separator' => ' - ',
            'weekLabel' => 'S',
            'customRangeLabel' => 'Personalizado',
            'daysOfWeek' => "['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi','Sa']",
            'monthNames' => "['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre']",
            'firstDay' => '1',
            // rangos predeterminados
            'range_today' => 'Hoy',
            'range_yesterday' => 'Ayer',
            'range_last_7_days' => 'Últimos 7 días',
            'range_last_30_days' => 'Últimos 30 días',
            'range_this_month' => 'Este mes',
            'range_last_month' => 'Mes pasado',
        ],
@endif
    ],

    // vista create
    'create'    => [
        'name'          => 'Crear',
        'btn-create'    => 'Crear'
    ],

    // vista edit
    'edit'    => [
        'name'          => 'Actualizar',
        'link-access'   => 'Editar',
        'btn-edit'      => 'Actualizar',
    ],

    // vista show
    'show'    => [
        'name'                                      => 'Detalles',
        'long-name'                                 => 'Detalles de {!!$request->get('single_entity_name')!!}',
        'btn-trash'                                 => 'Mover a Papelera',
        'btn-edit'                                  => 'Editar',
        'modal-confirm-trash-title'                 => 'Está Seguro?',
        'modal-confirm-trash-body'                  => 'La información de <strong>:item</strong> será movida a la papelera, sus datos no estarán disponibles...',
        'modal-confirm-trash-btn-confirm'           => 'Confirmar',
        'modal-confirm-trash-btn-cancel'            => 'Cancelar',
    ],
    
    // nombres de los elementos del formulario de creación/edición
    'form-fields' => [
@foreach($fields as $field)
        '{{$field->name}}' => '{!!$gen->getFormFieldName($field->label).($gen->isTheFieldRequired($field) ? ' *' : '')!!}',
@if(strpos($field->validation_rules, 'confirmed'))
        '{{$field->name}}_confirmation' => '{!!$gen->getFormFieldName("Confirmar ".$field->label).($gen->isTheFieldRequired($field) ? ' *' : '')!!}',
@endif
@endforeach
    ],

    // nombres cortos de los elementos del formulario, para la tabla del index
    'form-fields-short-name' => [
@foreach($fields as $field)
        '{{$field->name}}' => '{!!$gen->getFormFieldName($field->label)!!}',
@endforeach
    ],

    // el formulario de búsqueda
    'search_form'   => [
        'find'              => 'Buscar...',
        'btn-search'        => 'Buscar',
        'btn-clean'         => 'Quitar Filtros',
    ],

    // otros mensajes
    'inputs-required-help'  => 'Los campos marcados con <strong>*</strong> son requeridos.'

];