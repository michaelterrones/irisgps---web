<script type="text/javascript">
	$(document).ready(function() {
	    $('#all-elements-datatable').DataTable({
	    		"aaSorting": [],
	            responsive: true,
	            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
	            "language": {
	            	"url": '{{ asset("assets/json/DataTableSpanishCustom.json") }}'
		        }
	    });
	});
</script>