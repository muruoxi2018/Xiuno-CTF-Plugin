$('.tabs_item').click(function(){
	$(this).addClass("is-active").siblings().removeClass("is-active");
	$(".tab_pane").eq($(this).index()).show().siblings().hide();
	if($(this).index()==0){
		$('.modify').show();
	}else{
		$('.modify').hide();
	}
})