/*************************************
This Javascript was created by
T. J. Tkacik and Kevin Guo for the 
Final Project of COMP 426-f13
**************************************/

var url_base = "http://wwwp.cs.unc.edu/Courses/comp426-f13/kmp/lec23/server-side";
var userID = 0;

//Array of IDs of sections returned by search
var searches = [0, 1, 2, 3, 4, 5];
//Array of IDs of sections
var mySections = [0, 1, 2];


$(document).ready(function () {
	$('#courseSearch .searchBar').on('submit', courseSearch);
	
	$('#courseSearch .courseList').on('click', 'input', null, addSection);
	
	$('#myCourses .courseList').on('click', 'input', null, dropSection);

	updatePage();
	
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
	var ge = $('select[name="ge"]').val();
	var instructor = $('input.instructor').val();
	var semester = $('select[name="semester"]').val();
	var honors = $('input.honors').is(':checked') ? 1 : 0;
	var lab = $('input.lab').is(':checked') ? 1 : 0;
	
	alert(dept+numSel+cnum+ge+instructor+semester+honors+lab);
	
	var resultsList = $('#courseSearch table.courseList tbody').empty();
	//TODO get search results and put into searches
	for(var i=0; i<searches.length; i++){
		$.ajax(url_base + "/sections.php/" + searches[i], {
			type: "GET",
			dataType: "json",
			data: 	"{dept: " + 	dept +			"," +
					"equals: " + 	numSel +		"," +
					"cnum: " +		cnum +			"," +
					"ge: " + 		ge + 			"," +
					"prof: " + 		instructor + 	"," +
					"semester: " + 	semester +		"," +
					"honors: " + 	honors + 		"," +
					"lab: " + 		lab +			"}",
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
					alert(status);
				}});
	}
	 
};

var addSection = function(e){
	alert("TODO");
};

var dropSection = function(e){
	alert("TODO");
};

var updatePage = function(e){
	updateMyCourses();
	//updateGERequirements();
};

var updateMyCourses = function(){
	var myCourseList = $('#myCourses table.courseList tbody').empty();
	var historyList = $('#courseHistory table.courseList tbody').empty();
	$.ajax(url_base + "/sections.php", {
		type: "GET",
		dataType: "json",
		data: "{student: " + userID +"}",
		success: function(data, status, jqXHR) {
			mySections = data;
	}});
	for(var i=0; i<mySections.length; i++){
		$.ajax(url_base + "/sections.php/" + mySections[i], {
			type: "GET",
			dataType: "json",
			success: function(data, status, jqXHR) {
				if (data.grade = "current")
					myCourseList.append(
							$('<tr></tr>').attr('data-section', mySections[i])
								.append($('<td></td>').html(data.dept))
								.append($('<td></td>').html(data.cnum))
								.append($('<td></td>').html(data.days))
								.append($('<td></td>').html(data.time_slot))
								.append($('<td></td>').html(data.location))
								.append($('<td></td>').html(data.prof))
								.append($('<td></td>').append('<input type="submit" value="Drop">'))
					);
				else 
					historyList.append(
							$('<tr></tr>').attr('data-section',courseHistory[i])
								.append($('<td></td>').html(data.semester))
								.append($('<td></td>').html(data.dept))
								.append($('<td></td>').html(data.cnum))
								.append($('<td></td>').html(data.grade))
								.append($('<td></td>').html(data.days))
								.append($('<td></td>').html(data.semester))
								.append($('<td></td>').html(data.prof))
					);
			}});
	}
};

var updateGERequirements = function(){
	
};
