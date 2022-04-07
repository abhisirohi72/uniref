<script src="tablefilter/tablefilter.js"></script>
<script data-config>
    var filtersConfig = {
        base_path: 'tablefilter/',
        paging: false,

        remember_grid_values: false,
        remember_page_number: false,
        remember_page_length: false,
        alternate_rows: false,
        btn_reset: true,
        rows_counter: true,
        loader: false,

        status_bar: true,

        extensions:[{
            name: 'sort',
			<?php if($filename=="view-fsr-request-job.php"){?>
				types: [
                    'number', 'string', 'string',
                    'string','string', 'string', 
					'string', 'string'
                ]
			<?php }elseif($filename=="view-request-job.php"){?>
				types: [
                    'number', 'string', 'string',
                    'string','string', 'string', 
					'string', 'string','string',
					'string','string','number', 'string','number'
                ]
			<?php }elseif($filename=="view_customer.php"){?>
				types: [
                    'number', 'string', 'number',
                    'string','number', 'number', 
					'number', 'string','string',
					'string','string','string'
                ]
			<?php }elseif($filename=="customer_history.php"){?>
				types: [
                    'number', 'string', 'number',
                    'string','string', 'number', 
					'string'
                ]
			<?php }elseif($filename=="customer_notification.php"){?>
				types: [
                    'number', 'string', 'string',
                    'string','string'
                ]
			<?php }elseif($filename=="customer_rating.php"){?>
				types: [
                    'number', 'string', 'string',
                    'string','string','string','string','string','string','string'
                ]
			<?php }elseif($filename=="view_technicians.php"){?>
				types: [
                    'number', 'string', 'number',
                    'string','number','string','string','string','string','string'
                ]
			<?php }elseif($filename=="technicians_leave_request.php"){?>
				types: [
                    'number', 'string', 'string',
                    'string','string','string'
                ]
			<?php }elseif($filename=="technicians_notification.php"){?>
				types: [
                    'number', 'string', 'string',
                    'string','string'
                ]
			<?php }elseif($filename=="technicians_job_history.php"){?>
				types: [
                    'number', 'string', 'string',
                    'string','string','number', 'string'
                ]
			<?php }elseif($filename=="technicians_attendance.php"){?>
				types: [
                    'number', 'string', 'number',
                    'string','string','number', 'string','string','string','string','string','string'
                ]
			<?php }elseif($filename=="technicians_extra_expense.php"){?>
				types: [
                    'number', 'string', 'number',
                    'string','string', 'string','string','string'
                ]
			<?php }?>	
        }]
    };

    var tf = new TableFilter('filtertable', filtersConfig);
    tf.init();

</script>