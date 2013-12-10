/*************************************
This Javascript was created by
T. J. Tkacik and Kevin Guo for the 
Final Project of COMP 426-f13
**************************************/

var url_base = "http://wwwp.cs.unc.edu/Courses/comp426-f13/tkacik/project";
var userID = 123456789;

$(document).ready(function () {
	$('#courseSearch .searchBar').on('submit', courseSearch);
	
	$('#courseSearch .courseList').on('click', 'input', null, addSection);
	
	$('#myCourses .courseList').on('click', 'input', null, dropSection);

	updateMyCourses();
	
});

var courseSearch = function(e){
	e.preventDefault();
	var dept = $('input.dept').val();
	var numSel = $('select[name="numSel"]').val();
		switch(numSel){
			case '>': numSel = 1; break;
			case '=': numSel = 0; break;
			case '<': numSel = -1;
		}
	var cnum = $('input.cnum').val();
	var instructor = $('input.instructor').val();
	var honors = $('input.honors').is(':checked') ? 1 : 0;
	var lab = $('input.lab').is(':checked') ? 1 : 0;
	
	var searches = {};

	$.ajax(url_base + "/sections.php", {
		type: "GET",
		dataType: "json",
		data: 	"dept=" + 		dept +			"&" +
				"equals=" + 	numSel +		"&" +
				"cnum=" +		cnum +			"&" +
				"prof=" + 		instructor + 	"&" +
				"honors=" + 	honors + 		"&" +
				"lab=" + 		lab,
		success: function(data, status, jqXHR) {
			searches = data;
		},
		error: function(jqXHR, status, error) {
			alert("ERROR");
		}});
	
	var resultsList = $('#courseSearch table.courseList tbody').empty();
	for(var i=0; i<searches.length; i++){
		$.ajax(url_base + "/sections.php/" + searches[i], {
			type: "GET",
			dataType: "json",
			success: function(data, status, jqXHR) {
				if (data.grade = "current")
				resultsList.append(
						$('<tr></tr>').attr('data-section', searches[i])
							.append($('<td></td>').html(data.dept))
							.append($('<td></td>').html(data.cnum))
							.append($('<td></td>').html(data.days))
							.append($('<td></td>').html(data.time_slot))
							.append($('<td></td>').html(data.location))
							.append($('<td></td>').html(data.prof))
							.append($('<td></td>').append('<input type="submit" value="Drop">'))
				)},
				error: function(jqXHR, status, error) {
					alert("ERROR");
				}});
	}
	 
};

var updateMyCourses = function(){
	var myCourseList = $('#myCourses table.courseList tbody').empty();
	var historyList = $('#courseHistory table.courseList tbody').empty();
	var mySections = {};

	$.ajax(url_base + "/sections.php", {
		type: "GET",
		dataType: "json",
		data: {student: userID},
		async: false
		}).done(function(data, status, jqXHR) {
			mySections = data;
		});
	for(var i=0; i<mySections.length; i++){
		$.ajax(url_base + "/sections.php/" + mySections[i], {
			type: "GET",
			dataType: "json",
			async: false
			}).done(function(data, status, jqXHR) {
				myCourseList.append(
						$('<tr></tr>').attr('data-section', mySections[i])
							.append($('<td></td>').html(data.dept))
							.append($('<td></td>').html(data.cnum))
							.append($('<td></td>').html(data.days))
							.append($('<td></td>').html(data.time_slot))
							.append($('<td></td>').html(data.location))
							.append($('<td></td>').html(data.prof))
							.append($('<td></td>').append('<input type="submit" value="Drop">'))
				)});
	}
};

/*else 
historyList.append(
		$('<tr></tr>').attr('data-section',courseHistory[i])
			.append($('<td></td>').html(data.dept))
			.append($('<td></td>').html(data.cnum))
			.append($('<td></td>').html(data.grade))
			.append($('<td></td>').html(data.days))
			.append($('<td></td>').html(data.time_slot))
			.append($('<td></td>').html(data.prof))
);*/

var addSection = function(e){
	/*var sectionID = $(e).parent('tr').attr('data-section');
	var url = url_base + "/sections.php/" + userID + "/" sectionID;
	$.ajax(url, {type: "POST", data: "enroll=true"});
	*/updateMyCourses();
};

var dropSection = function(e){
	/*var sectionID = $(e).parent('tr').attr('data-section');
	var url = url_base + "/sections.php/" + userID + "/" sectionID;
	$.ajax(url, {type: "GET", data: "delete=true"});
	*/
	updateMyCourses();
};

