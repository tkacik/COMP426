/*************************************
This Javascript was created by
T. J. Tkacik and Kevin Guo for the 
Final Project of COMP 426-f13
**************************************/

$(document).ready(function () {
	$('#courseSearch .searchBar').on('submit', courseSearch);
	
	$('#courseSearch .courseList').on('click', 'input', null, addSection);
	
	$('#myCourses .courseList').on('click', 'input', null, dropSection);

	update();
	
});

var courseSearch = function(e){
	var dept = $('input.dept').val();
	var numSel = $('select[name="numSel"]').val();
	var cnum = $('input.cnum').val();
	var ge = $('select[name="ge"]').val();
	var instructor = $('input.instructor').val();
	var semester = $('select[name="semester"]').val();
	var honors = $('input.honors').is(':checked') ? 1 : 0;
	var lab = $('input.lab').is(':checked') ? 1 : 0;
	
	alert(dept+numSel+cnum+ge+instructor+semester+honors+lab);
	
	e.preventDefault();
	 
};

var addSection = function(e){
	
};

var dropSection = function(e){
	
};

var update = function(e){
	
};