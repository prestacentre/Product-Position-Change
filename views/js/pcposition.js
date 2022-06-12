$(document).ready(function() {
var draggable = $('#label'); //element 


draggable.on('mousedown', function(e){
	var dr = $(this).addClass("drag").css("cursor","move");
	height = dr.outerHeight();
	width = dr.outerWidth();
	max_left = dr.parent().offset().left + dr.parent().width() - dr.width();
	max_top = dr.parent().offset().top + dr.parent().height() - dr.height();
	min_left = dr.parent().offset().left;
	min_top = dr.parent().offset().top;

	ypos = dr.offset().top + height - e.pageY,
	xpos = dr.offset().left + width - e.pageX;
	$(document.body).on('mousemove', function(e){
		var itop = e.pageY + ypos - height;
		var ileft = e.pageX + xpos - width;
		
		if(dr.hasClass("drag")){
			if(itop <= min_top ) { itop = min_top; }
			if(ileft <= min_left ) { ileft = min_left; }
			if(itop >= max_top ) { itop = max_top; }
			if(ileft >= max_left ) { ileft = max_left; }
			dr.offset({ top: itop,left: ileft});
		}
	}).on('mouseup', function(e){
			dr.removeClass("drag");
			createTx();
	});
});

});

function addCss(el,tx,tt,px) {
	var txt = '<span class="first '+el+'">'+tt+'</span>';
	tinyMCE.activeEditor.setContent(txt);
	$("#label").html(txt);
	
}
function radiusSize()
{
	var radiussize = $("#radiussize").val()
	$('#label span').css('border-radius', ''+radiussize+'px');
	createTx();
}
function btSize()
{
	var btsize = $("#btsize").val()
	$('#label span').css('width', ''+btsize+'px');
	createTx();
}
function bthSize()
{
	var bthsize = $("#bthsize").val()
	$('#label span').css('height', ''+bthsize+'px');
	createTx();
}
function paddingLabel()
{
	var plabels = $("#plabels").val()
	$('#label span').css('padding', ''+plabels+'px');
	createTx();
}
function createTx(){
	var aa = $('#label span').attr('style');
	var topPosition = document.getElementById("label").style.top;
	var leftPosition = document.getElementById("label").style.left;
	if (!topPosition)
		topPosition = 0;
	if (!leftPosition)
		leftPosition = 0;
	if (!aa)
		aa = '';
	
	$('#sonuc').html('top: '+ topPosition +'; left:'+ leftPosition +';'+aa);
}
