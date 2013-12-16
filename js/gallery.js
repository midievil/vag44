var galleryItem = -1;
var galleryItemsCount = -1;
var slideShow = false;
function SetGalleryImage(image) {
    galleryItem = image;
	var fileName = galleryItems[image*1];
		
	$("#imgGalleryMain").attr("src", path + "/" + fileName + ".jpg");
}

function setGellerySubImage(url){
	$("#imgGallery").attr('src', url)
}

function ShowImageGallery(path, withSlideShow) {
	
	slideShow = withSlideShow;

    $("#divGalleryBack").html("<table class='galleryback' id='tblGalleryBack' width='100%' height='100%'><tr><td align='middle'>&nbsp;</td></tr></table");

	var fileName = "";
	
	if(slideShow)
	{
		fileName = galleryItems[galleryItem];	
	}
	else
	{
		fileName = path;
	}
	
    $("#divGallery").html("<table class='gallery' id='tblGallery' width='100%' height='100%' cellspacing='0' cellpadding='0'><tr><td align='middle' height='1px'><img id='imgGallery' class='gallery' src='" + fileName + "' /></td></tr></table");
	
    $("#tblGallery tr:first").before("<tr><td colspan='3' width='100%' onclick='HideImageGallery();'>&nbsp;</td></tr>");    
    $("#tblGallery tr:last td:last").before("<td width='50%' onclick='HideImageGallery();'>&nbsp;</td>");
    $("#tblGallery tr:last td:last").after("<td width='50%' onclick='HideImageGallery();'>&nbsp;</td>");
	
	
    $("#tblGallery tr:last").after("<tr height='30px'><td></td><td class='buttons' id='tdGalleryButtons'></td><td></td></tr>");
    $("#tblGallery tr:last").after("<tr><td colspan='3' width='100%' onclick='HideImageGallery();'>&nbsp;</td></tr>");

    $("#tdGalleryButtons").html(
		"<table width='100%'><tr>"+
			"<td id='tdGalleryCounter'></td>"+
			"<td align='right'>"+
			"<img id='imgGalleryPrev' src='/img/prev.gif' onclick='GalleryPrev(\"" + path + "\");'><img id='imgGalleryNext' src='/img/next.gif' onclick='GalleryNext(\"" + path + "\");'>"+
		"<img class='hand' src='/img/close.gif' onclick='HideImageGallery();'></td></table>");

    SetGalleryButtonsVisibility();
	
    //$("#divGallery").attr('top', 100);
	$("#divGallery").css({ "position": "absolute", "height": $(window).height(), "width": $(window).width(), "top": $(window).scrollTop(), "left": 0 });
    $("#divGalleryBack").css({ "position": "absolute", "height": $(window).height(), "width": $(window).width(), "top": $(window).scrollTop(), "left": 0 });

    $("#divGallery").show();
    $("#divGalleryBack").fadeTo('fast', 0.5);

}

function check_load(image, path) {
    var handler = function() {complete_load(path)};
    image.onload = handler;
}
function complete_load(path) {
    jQuery("#imgGallery").attr("src", path);
    $("#divGallery").fadeTo('slow', 1);
}

function AsyncLoadImage(path) {	
	var im = new Image();
    im.src = path;
    if (im.readyState != "complete") 
	{
		check_load(im, path);
	}
    else
	{
		complete_load(path);
	}
}

function GalleryNext(path) {
	galleryItem = galleryItem+1;

	$("#divGallery").fadeTo('slow', 0.2, 
		function() {
			AsyncLoadImage(galleryItems[galleryItem]);
		} 
	);

	SetGalleryButtonsVisibility();
}

function GalleryPrev(path) {
	galleryItem = galleryItem - 1;

	$("#divGallery").fadeTo('slow', 0.2, 
		function() {
			AsyncLoadImage(galleryItems[galleryItem]);
		} 
	);

	SetGalleryButtonsVisibility();
}

function SetGalleryButtonsVisibility() {
    if(slideShow){
		$("#tdGalleryCounter").html("<a>(" + (galleryItem) + " \u0438\u0437 " + galleryItemsCount + ")</a>");
	}
	
	if(galleryItem > 0 && slideShow)
	{
		$("#imgGalleryPrev").show();
	}
	else
	{
		$("#imgGalleryPrev").hide();
	}    

    if (galleryItem+1 < galleryItems.length && slideShow) {
        $("#imgGalleryNext").show();
    }
    else {
		$("#imgGalleryNext").hide();        
    }
}

function HideImageGallery() {
    $("#divGallery").hide();
    $("#divGalleryBack").hide();
}