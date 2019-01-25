function joinTeam(team_id){
	$('#join-team').val(team_id);
}
$('#join-form').on('submit', function() {
	$('#join-submit').button('loading');
	var postdata = $(this).serialize();
	$.xpost($(this).attr('action'), postdata, function(code, message) {
		if(code == 0) {
			alert(message);
			$('#join-submit').button('Good!');
			setTimeout(function() {
				window.location.reload();
			}, 500);
		} else {
			alert(message);
			$('#join-submit').button('reset');
		}
	});
	return false;
});
