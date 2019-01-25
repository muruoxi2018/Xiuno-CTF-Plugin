$("#form").on("submit", function() {
		var r=confirm("是否确定创建团队？创建团队后即无法加入其它团队");
		if(r == true){
			$("#submit").button("loading");
			var postdata = $(this).serialize();
			$.xpost(xn.url("ctf-team_found"), postdata, function(code, message) {
				if(code == 0) {
					alert(message);
					$("#submit").button("Good!");
					setTimeout(function() {
						window.location.href= xn.url("ctf");
					}, 500);
				} else {
					alert(message);
					$("#submit").button("reset");
				}
			});
			return false;
		}})
	$(".img_button").click(function(){
		$("#avatarfile").trigger("click");
	})
	$("#portrait").click(function(){
		$("#avatarfile").trigger("click");
	})
	$("#avatarfile").click(function(){
		var fileinput = this;
		var url = "ctf-team_found.htm";
		var up = new FileUploader(fileinput, url);
		up.onprogress = function(file, percent) {
			$("#avatar_progress").show().son("div").width(percent+"%");
		}
		up.onerror = function(file, e) {
			var json = xn.json_decode(e.target.response);
			var err = json && json.message ? json.message : e.target.response;
			$(this).alert(err); return;
		}
		up.oncomplete = function(code, files) {
			$("#isavatar").val(1);
		}
		up.onselected = function(files) {
			var file = files[0];
			$(".img_button").hide();
			$("#portrait").show();
			$("#portrait").srcLocalFile(file);
			if(!/^image/.test(file.type) || !/(.jpg|.jpeg|.gif|.png|.bmp)$/i.test(file.type)) {
				$(this).alert("只允许上传jpg、jpeg、gif、png格式的图片"); return;
			}
			up.start();
		}
		up.onabort = function(file, e) {}
		up.init();
	})