
$(document).ready(function() {
	$("div.tagcategory").mouseover(function() {
		$("#"+this.id+" div.pagination.small").show();
    });
    
    $("div.tagcategory").mouseout(function() {
		$("#"+this.id+" div.pagination.small").hide();
    });
    
    (function($){

    	  $.extend({
    	    playSound: function(){
    	      return $("<embed src='"+arguments[0]+"' hidden='true' autostart='true' loop='false' class='playSound'>").appendTo('body');
    	    }
    	  });

    	})(jQuery);
});