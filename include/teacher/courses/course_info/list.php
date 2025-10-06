<?php
$curs_info = $coursecls->get_courseinfo(CURS_ID);

$info_id = ($curs_info ? $curs_info['id'] : '');
echo'
<table class="table border table-nowrap">
	<tbody>
		<tr>
			<th width="15%">Course Code</th>
			<td width="35%"><span class="badge badge-soft-primary">'.$curs['curs_code'].'</span></td>
			<th width="15%">Credit Hours</th>
			<td width="35%">'.$curs['curs_credit_hours'].' (Theory: '.$curs['cur_credithours_theory'].', Practical: '.$curs['cur_credithours_practical'].')</td>
		</tr>
		<tr>
			<th>Title</th>
			<td colspan="3">'.$curs['curs_name'].'</td>
		</tr>
		<tr>
			<th>Pre-requisite</th>
			<td colspan="3">'.(!empty($curs['pre_requisite_name']) ? $curs['pre_requisite_name'] : '<span class="badge badge-soft-warning">No</span>').'</td>
		</tr>
		<tr>
			<td colspan="6">
				'.($curs['duration'] == 0 ? '<h6 class="text-danger text-center">Admin Need to update course duration for percentage</h6>' : '').'
				<div class="card bg-light overflow-hidden">
					<div class="card-body">
						<div class="d-flex">
							<div class="flex-grow-1">
								<h6 class="mb-0"><b class="text-success">'.intval($percent).'%</b> Completed</h6>
							</div>
							<div class="flex-shrink-0">
								<h6 class="mb-0">'.$remaining.' '.get_CourseWise($curs['curs_wise']).' left</h6>
							</div>
						</div>
					</div>
					<div class="progress bg-soft-success rounded-0">
						<div class="progress-bar bg-success" role="progressbar" style="width: '.intval($percent).'%" aria-valuenow="'.intval($percent).'" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
				</div>
			</td>
		</tr>
	</tbody>
</table>
<div class="card mb-3">
	<div class="card-header alert-dark p-2">
		<div class="d-flex align-items-center">
			<h5 class="card-title mb-0 flex-grow-1"><i class="ri-file-paper-2-fill align-bottom me-1"></i>Course Introduction / Description</h5>
			<div class="flex-shrink-0">';
				$btn = (empty($curs_info['introduction']) ? 'add' : 'edit');
				$modal = (empty($curs_info['introduction']) ? 'primary' : 'info');
				echo'
				<a class="btn btn-'.$modal.' btn-xs" onclick="showAjaxModalZoom(\'include/modals/courses/course_info/introduction.php?id='.$curs['curs_id'].'&view=course_info&info_id='.$info_id.'\');"><i class="ri-'.$btn.'-circle-line align-bottom me-1"></i>'.ucfirst($btn).'</a>
			</div>
		</div>
	</div>
	<div class="card-body border">
		'.(!empty($curs_info['introduction']) ? html_entity_decode(html_entity_decode($curs_info['introduction'])) : 'No Record Found').'
	</div>
</div>
<div class="card mb-3">
	<div class="card-header alert-dark p-2">
		<div class="d-flex align-items-center">
			<h5 class="card-title mb-0 flex-grow-1"><i class="ri-file-paper-2-fill align-bottom me-1"></i>Course Objectives</h5>
			<div class="flex-shrink-0">';
				$btn = (empty($curs_info['objectives']) ? 'add' : 'edit');
				$modal = (empty($curs_info['objectives']) ? 'primary' : 'info');
				echo'
				<a class="btn btn-'.$modal.' btn-xs" onclick="showAjaxModalZoom(\'include/modals/courses/course_info/objectives.php?id='.$curs['curs_id'].'&view=course_info&info_id='.$info_id.'\');"><i class="ri-'.$btn.'-circle-line align-bottom me-1"></i>'.ucfirst($btn).'</a>
			</div>
		</div>
	</div>
	<div class="card-body border">
		'.(!empty($curs_info['objectives']) ? html_entity_decode(html_entity_decode($curs_info['objectives'])) : 'No Record Found').'
	</div>
</div>
<div class="card mb-3">
	<div class="card-header alert-dark p-2">
		<div class="d-flex align-items-center">
			<h5 class="card-title mb-0 flex-grow-1"><i class="ri-file-paper-2-fill align-bottom me-1"></i>Learning Outcomes</h5>
			<div class="flex-shrink-0">';
				$btn = (empty($curs_info['outcomes']) ? 'add' : 'edit');
				$modal = (empty($curs_info['outcomes']) ? 'primary' : 'info');
				echo'
				<a class="btn btn-'.$modal.' btn-xs" onclick="showAjaxModalZoom(\'include/modals/courses/course_info/outcomes.php?id='.$curs['curs_id'].'&view=course_info&info_id='.$info_id.'\');"><i class="ri-'.$btn.'-circle-line align-bottom me-1"></i>'.ucfirst($btn).'</a>
			</div>
		</div>
	</div>
	<div class="card-body border">
		'.(!empty($curs_info['outcomes']) ? html_entity_decode(html_entity_decode($curs_info['outcomes'])) : 'No Record Found').'
	</div>
</div>
<div class="card mb-3">
	<div class="card-header alert-dark p-2">
		<div class="d-flex align-items-center">
			<h5 class="card-title mb-0 flex-grow-1"><i class="ri-file-paper-2-fill align-bottom me-1"></i>Teaching & Learning Strategies</h5>
			<div class="flex-shrink-0">';
				$btn = (empty($curs_info['strategies']) ? 'add' : 'edit');
				$modal = (empty($curs_info['strategies']) ? 'primary' : 'info');
				echo'
				<a class="btn btn-'.$modal.' btn-xs" onclick="showAjaxModalZoom(\'include/modals/courses/course_info/strategies.php?id='.$curs['curs_id'].'&view=course_info&info_id='.$info_id.'\');"><i class="ri-'.$btn.'-circle-line align-bottom me-1"></i>'.ucfirst($btn).'</a>
			</div>
		</div>
	</div>
	<div class="card-body border">
		'.(!empty($curs_info['strategies']) ? html_entity_decode(html_entity_decode($curs_info['strategies'])) : 'No Record Found').'
	</div>
</div>
<div class="card mb-3">
	<div class="card-header alert-dark p-2">
		<div class="d-flex align-items-center">
			<h5 class="card-title mb-0 flex-grow-1"><i class="ri-file-paper-2-fill align-bottom me-1"></i>Soft Skills & Personal Effectiveness</h5>
			<div class="flex-shrink-0">';
				$btn = (empty($curs_info['effectiveness']) ? 'add' : 'edit');
				$modal = (empty($curs_info['effectiveness']) ? 'primary' : 'info');
				echo'
				<a class="btn btn-'.$modal.' btn-xs" onclick="showAjaxModalZoom(\'include/modals/courses/course_info/effectiveness.php?id='.$curs['curs_id'].'&view=course_info&info_id='.$info_id.'\');"><i class="ri-'.$btn.'-circle-line align-bottom me-1"></i>'.ucfirst($btn).'</a>
			</div>
		</div>
	</div>
	<div class="card-body border">
		'.(!empty($curs_info['effectiveness']) ? html_entity_decode(html_entity_decode($curs_info['effectiveness'])) : 'No Record Found').'
	</div>
</div>
<div class="card mb-3">
	<div class="card-header alert-dark p-2">
		<div class="d-flex align-items-center">
			<h5 class="card-title mb-0 flex-grow-1"><i class="ri-file-paper-2-fill align-bottom me-1"></i>Course Outline</h5>
			<div class="flex-shrink-0">';
				$btn = (empty($curs_info['outlines']) ? 'add' : 'edit');
				$modal = (empty($curs_info['outlines']) ? 'primary' : 'info');
				echo'
				<a class="btn btn-'.$modal.' btn-xs" onclick="showAjaxModalZoom(\'include/modals/courses/course_info/outlines.php?id='.$curs['curs_id'].'&view=course_info&info_id='.$info_id.'\');"><i class="ri-'.$btn.'-circle-line align-bottom me-1"></i>'.ucfirst($btn).'</a>
			</div>
		</div>
	</div>
	<div class="card-body border">
		'.(!empty($curs_info['outlines']) ? html_entity_decode(html_entity_decode($curs_info['outlines'])) : 'No Record Found').'
	</div>
</div>
<div class="card mb-3">
	<div class="card-header alert-dark p-2">
		<h5 class="card-title mb-0 flex-grow-1"><i class="ri-file-paper-2-fill align-bottom me-1"></i>Course References</h5>
	</div>
	<div class="card-body border">
		'.(!empty($curs['curs_references']) ? html_entity_decode(html_entity_decode($curs['curs_references'])) : 'No Record Found').'
	</div>
</div>';
