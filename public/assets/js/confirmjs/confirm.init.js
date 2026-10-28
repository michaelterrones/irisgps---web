$('.confirm-action').confirm({
    title:'Confirmación de acción',
    text:'Esta acción puede ser irreversible, ¡no debería de realizarla sin autorización!<br><strong>¿Está seguro de querer continuar?</strong>',
    cancel: function(button) {
        event.preventDefault();
    },
    confirmButton: 'Si, estoy seguro',
    cancelButton:'No',
	confirmButtonClass: 'btn-success',
	cancelButtonClass: 'btn-default'
});

$('.confirm-submit').confirm({
    title:'Confirmación de acción',
    text:'Esta acción puede ser irreversible, ¡no debería de realizarla sin autorización!<br><strong>¿Está seguro de querer continuar?</strong>',
    confirm: function(button) {
    	$(button).parents('form:first').submit();
    },
    cancel: function(button) {
        event.preventDefault();
    },
    confirmButton: 'Si, estoy seguro',
    cancelButton:'No',
	confirmButtonClass: 'btn-success',
	cancelButtonClass: 'btn-default'
});