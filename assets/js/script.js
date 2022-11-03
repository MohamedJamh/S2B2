var id_input = document.getElementById('task-id');
var title_input = document.getElementById('task-title');
var type_inputs = document.querySelectorAll('input[type="radio"]');
var priority_input = document.getElementById('task-priority');
var status_input = document.getElementById('task-status');
var date_input = document.getElementById('task-date');
var description_input = document.getElementById('task-description');


var delBtn = document.getElementById('task-delete-btn');
var upBtn = document.getElementById('task-update-btn');
var svBtn = document.getElementById('task-save-btn');

document.getElementById('add-task-btn').addEventListener('click',function(){
	delBtn.style.display = "none";
	upBtn.style.display = "none";
	svBtn.style.display = "block";
})

function printDataOnModal(id){

	delBtn.style.display = "block";
	upBtn.style.display = "block";
	svBtn.style.display = "none";
	
	let title = document.getElementById(id).querySelector('.task-body .task-title').innerHTML;
	let date = document.getElementById(id).querySelector('.task-body .task-details .task-date').innerHTML.slice(15);
	let description = document.getElementById(id).querySelector('.task-body .task-details .task-description').getAttribute('title');
	let status = document.getElementById(id).querySelector('.task-body .task-details .task-status').getAttribute('data-id-status');
	let priority = document.getElementById(id).querySelector('.task-body .task-features .task-priority').getAttribute('data-id-priority');
	let type = document.getElementById(id).querySelector('.task-body .task-features .task-type').getAttribute('data-id-type');

	id_input.value = id;
	title_input.value = title;
	priority_input.value = priority;
	status_input.value = status;
	description_input.value = description;
	date_input.value = date;
	

	for(t of type_inputs){
		if(t.value == type){
			t.checked = true;
		}
	}

}
function clearModal(){
	let formMoadl = document.getElementById('form-task');
	formMoadl.reset();
}


