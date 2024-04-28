
const DASHBOARD = (function(){

	let thisDashboard = {};

	thisDashboard.generateSchedule = function()
	{
	    let formData = new FormData();
	    formData.set("txt_timeTableHeader", $('#txt_timeTableHeader').val());
	    formData.set("slc_academicPeriod", $('#slc_academicPeriod').val());

	   	let arrDays = [];

	    $('#academic_period_id input[type="checkbox"]:checked').each(function(){
	       console.log(this.value);
	       arrDays.push(this.value);
	    });

	    formData.set("arrDays", JSON.stringify(arrDays));

	    $('#resource-modal').modal('hide');
	    $('#div_progressBarContainer').prop('hidden',false);
	    $('#resource-add-button').prop('hidden',true);

	    $.ajax({
	      /* DashboardController->generateSchedule() */
    	headers: {
    	                            'X-CSRF-TOKEN': `${$('input[name="_token"]').val()}`,
    	                        },
	      url : `${baseUrl}public/generate-schedule`,
	      method : 'post',
	      dataType: 'json',
	      processData: false, // important
	      contentType: false, // important
	      data : formData,
	      success : function(result)
	      {
	        	setTimeout(function(){
	        	 	$('#div_progressBar').css('width','25%');
	        	}, 1000);
	        	setTimeout(function(){
	        	 	$('#div_progressBar').css('width','50%');
	        	}, 2000);

	        	setTimeout(function(){
	        	 	$('#div_progressBar').css('width','75%');
	        	}, 3000);

	        	setTimeout(function(){
	        	 	$('#div_progressBar').css('width','100%');
	        	}, 4000);

	        	setTimeout(function(){
	        		$('#div_progressBarContainer').prop('hidden',true);
	        		$('#resource-add-button').prop('hidden',false);
	        		$('#div_progressBar').css('width','0%');

	        		DASHBOARD.loadSchedules();
	        	}, 5000);
	      }
		});	
		
	}

	thisDashboard.loadSchedules = function()
	{
		$.ajax({
			/* RoleController->loadRoles() */
			url : `${baseUrl}public/load-schedules`,
			method : 'get',
			dataType: 'json',
			success : function(data)
			{
				console.log(data);
				let tbody = '';
				data.forEach(function(value,key){
					tbody += `<tr>
			                    <td id="table-bordered">${value['name']}</td>
			                    <td id="table-bordered">${value['academic_period']}</td>
			                    <td id="table-bordered">
			                        <button class="btn btn-sm btn-success" onclick="DASHBOARD.printSchedule(${value['id']})"><i class="fa fa-print"></i></button>
			                    </td>
			                    <td>
			                        <button class="btn btn-sm btn-danger" onclick="DASHBOARD.deleteSchedule(${value['id']})"><i class="bi bi-trash3"></i></button>
			                    </td>
			                </tr>`;
				});

				$('#tbl_schedules tbody').html('');
				$('#tbl_schedules tbody').html(tbody);
			}
		});
	}

	thisDashboard.printSchedule = function(scheduleId)
	{
		$("#iframe_printSchedule").contents().find("body").html("<center><i>Loading, please wait...</i></center>").css('background-color','white');

		$('#iframe_printSchedule').prop('src',`${baseUrl}public/print-schedule/${scheduleId}`);

		$('#modal_printPreviewSchedule').modal('show');
	}

	thisDashboard.deleteSchedule = function(scheduleId)
	{
		if(confirm('Please confirm!'))
		{
			$.ajax({
				/* RoleController->deleteSchedule() */
				url : `${baseUrl}public/delete-schedule`,
				method : 'get',
				dataType: 'json',
				data: {scheduleId : scheduleId},
				success : function(data)
				{
					
					DASHBOARD.loadSchedules();
				}
			});
		}
	}

	return thisDashboard; 

})();