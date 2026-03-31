<?php include 'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline">
		<div class="card-header">
			<h3 class="card-title">Evaluation List</h3>
			<div class="card-tools">
				<a class="btn btn-sm btn-default btn-flat border-primary" href="./index.php?page=evaluation_status">
					<i class="fa fa-list"></i> Open Evaluation Status
				</a>
			</div>
		</div>
		<div class="card-body">
			<table class="table table-hover table-bordered" id="list">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>Student</th>
						<th>Section</th>
						<th>Subject</th>
						<th>Faculty</th>
						<th class="text-center">Performance Average</th>
						<th class="text-center">Date Evaluated</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$current_academic = $conn->query("SELECT id FROM academic_list WHERE is_default = 1")->fetch_assoc();
					$academic_id = $current_academic ? $current_academic['id'] : 0;

					$qry = $conn->query("SELECT e.id,
						CONCAT(s.lastname, ', ', s.firstname, ' ', COALESCE(NULLIF(s.middlename,''),'')) AS student_name,
						COALESCE(c.section, 'N/A') AS section,
						sub.subject AS subject_name,
						CONCAT(f.lastname, ', ', f.firstname, ' ', COALESCE(NULLIF(f.middlename,''),'')) AS faculty_name,
						AVG(a.rate) AS avg_rate,
						e.date_taken
					FROM evaluation_list e
					INNER JOIN evaluation_answers a ON a.evaluation_id = e.id
					INNER JOIN student_list s ON s.id = e.student_id
					LEFT JOIN class_list c ON c.id = s.class_id
					INNER JOIN subject_list sub ON sub.id = e.subject_id
					INNER JOIN faculty_list f ON f.id = e.faculty_id
					WHERE e.academic_id = $academic_id
					GROUP BY e.id
					ORDER BY e.date_taken DESC");

					while ($row = $qry->fetch_assoc()):
						$performance_average = ((float) $row['avg_rate'] / 5) * 100;
						?>
						<tr>
							<td class="text-center"><?php echo $i++ ?></td>
							<td><b><?php echo ucwords($row['student_name']) ?></b></td>
							<td><?php echo htmlspecialchars($row['section']) ?></td>
							<td><?php echo htmlspecialchars($row['subject_name']) ?></td>
							<td><?php echo ucwords($row['faculty_name']) ?></td>
							<td class="text-center"><b><?php echo number_format($performance_average, 2) . '%' ?></b></td>
							<td class="text-center"><?php echo date('M d, Y h:i A', strtotime($row['date_taken'])) ?></td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<style>
	.overlay {
		background: rgba(0, 0, 0, 0.6);
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
	}

	.card,
	.card-body {
		color: black;
		background: transparent !important;
		backdrop-filter: blur(10px);
		-webkit-backdrop-filter: blur(10px);
		border-radius: 15px;
		border: none;
	}
</style>
<script>
	$(document).ready(function () {
		$('#list').dataTable()
	})
</script>