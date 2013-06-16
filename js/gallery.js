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