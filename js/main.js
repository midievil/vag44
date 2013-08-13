
$(document).ready(function() {
	$("div.tagcategory").mouseover(function() {
		$("#"+this.id+" div.pagination.small").show();
    });
    
    $("div.tagcategory").mouseout(function() {
		$("#"+this.id+" div.pagination.small").hide();
    });
});