{{--
    ****************************************************************************
    Scripts de Formulario.
    ____________________________________________________________________________
    Contiene el código javascript usado en el formulario.
    ****************************************************************************

    Este archivo es parte de Books.
    (c) Johan Alvarez <llstarscreamll@hotmail.com>
    Licensed under The MIT License (MIT).

    @package    Books
    @version    0.1
    @author     Johan Alvarez
    @license    The MIT License (MIT)
    @copyright  (c) 2015-2016, Johan Alvarez <llstarscreamll@hotmail.com>
    @link       https://github.com/llstarscreamll
    
    ****************************************************************************
--}}

<script type="text/javascript">

    {{-- Configuración de Bootstrap DateTimePicker --}}
    $('input[name=published_year]').datetimepicker({
        locale: '{{ Lang::locale() }}',
        format: 'YYYY-MM-DD'
    });
    
    {{-- Inicializa el componente SwitchBootstrap --}}
    $(".bootstrap_switch").bootstrapSwitch();
    
    {{-- Inicialización y configuración de Bootbox --}}
    initBootBoxComponent(
        '{{ trans('book.index.modal-default-title') }}',
        '{{ trans('book.index.modal-default-btn-confirmation') }}',
        'btn-primary',
        '{{ trans('core::shared.modal-default-btn-cancel') }}',
        'btn-default'
    );
    
</script>