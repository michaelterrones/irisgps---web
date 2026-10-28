$(document).ready(function() {
    $('#all-elements-datatable').DataTable({
    		"aaSorting": [],
            responsive: true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
            "language": {
            	"url": '{{ HTML::script("assets/json/DataTableSpanishCustom.json") }}'
	        }
    });
});