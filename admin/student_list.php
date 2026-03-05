	<?php include 'db_connect.php'; ?>
	<div class="col-lg-12">
		<div class="card card-outline ">
			<div class="card-header">
				<div class="card-tools">
					<a class="btn btn-block btn-sm btn-default btn-flat border-primary"
						href="./index.php?page=new_student"><i class="fa fa-plus"></i> Add New Student</a>
				</div>
			</div>
			<div class="card-body">
				<!-- Filter Section -->
				<div class="row mb-3">
					<div class="col-md-3">
						<label>Filter by Course:</label>
						<select id="filter_curriculum" class="form-control form-control-sm">
							<option value="">All Courses</option>
							<?php
							$curriculum_qry = $conn->query("SELECT DISTINCT curriculum FROM class_list ORDER BY curriculum ASC");
							while ($row = $curriculum_qry->fetch_assoc()):
								?>
								<option value="<?php echo $row['curriculum'] ?>"><?php echo $row['curriculum'] ?></option>
							<?php endwhile; ?>
						</select>
					</div>
					<div class="col-md-3">
						<label>Filter by Year:</label>
						<select id="filter_level" class="form-control form-control-sm">
							<option value="">All Years</option>
							<?php
							$level_qry = $conn->query("SELECT DISTINCT level FROM class_list ORDER BY level ASC");
							while ($row = $level_qry->fetch_assoc()):
								?>
								<option value="<?php echo $row['level'] ?>"><?php echo $row['level'] ?></option>
							<?php endwhile; ?>
						</select>
					</div>
					<div class="col-md-3">
						<label>Filter by Section:</label>
						<select id="filter_section" class="form-control form-control-sm">
							<option value="">All Sections</option>
							<?php
							$section_qry = $conn->query("SELECT DISTINCT section FROM class_list ORDER BY section ASC");
							while ($row = $section_qry->fetch_assoc()):
								?>
								<option value="<?php echo $row['section'] ?>"><?php echo $row['section'] ?></option>
							<?php endwhile; ?>
						</select>
					</div>
					<div class="col-md-3">
						<label>&nbsp;</label>
						<button id="btn_filter" class="btn btn-primary btn-sm btn-block"><i class="fa fa-filter"></i> Apply
							Filter</button>
					</div>
				</div>
				<!-- Bulk Update Section -->
				<div class="row mb-3" id="bulk_update_section" style="display:none;">
					<div class="col-md-12">
						<div class="card bg-light">
							<div class="card-body">
								<h5>Bulk Update Selected Students</h5>
								<div class="row">
									<div class="col-md-8">
										<label>Update Class:</label>
										<select id="bulk_class_id" class="form-control form-control-sm">
											<option value="">- Select Class to Update -</option>
											<?php
											$classes_bulk = $conn->query("SELECT id,concat(curriculum,' ',level,' - ',section) as class FROM class_list ORDER BY curriculum, level, section");
											while ($row = $classes_bulk->fetch_assoc()):
												?>
												<option value="<?php echo $row['id'] ?>">
													<?php echo $row['class'] ?>
												</option>
											<?php endwhile; ?>
										</select>
									</div>
									<div class="col-md-2">
										<label>&nbsp;</label>
										<button id="btn_bulk_update" class="btn btn-success btn-sm btn-block"><i
												class="fa fa-save"></i> Update</button>
									</div>
									<div class="col-md-2">
										<label>&nbsp;</label>
										<button id="btn_cancel_bulk"
											class="btn btn-secondary btn-sm btn-block">Cancel</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<table class="table tabe-hover table-bordered" id="list">
					<thead>
						<tr>
							<th class="text-center"><input type="checkbox" id="select_all"></th>
							<th class="text-center">#</th>
							<th>School ID</th>
							<th>Name</th>
							<th>Username</th>
							<th>Current Class</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$i = 1;
						$class = array();
						$classes = $conn->query("SELECT id,concat(curriculum,' ',level,' - ',section) as `class` FROM class_list");
						while ($row = $classes->fetch_assoc()) {
							$class[$row['id']] = $row['class'];
						}
						$qry = $conn->query("SELECT s.*, c.curriculum, c.level, c.section, concat(s.firstname,' ',COALESCE(NULLIF(s.middlename,''),''),' ',s.lastname) as name FROM student_list s LEFT JOIN class_list c ON s.class_id = c.id order by concat(s.firstname,' ',s.lastname) asc");
						while ($row = $qry->fetch_assoc()):
							?>
							<tr class="student-row" data-curriculum="<?php echo $row['curriculum'] ?>"
								data-level="<?php echo $row['level'] ?>" data-section="<?php echo $row['section'] ?>">
								<td class="text-center"><input type="checkbox" class="student_checkbox"
										value="<?php echo $row['id'] ?>"></td>
								<th class="text-center"><?php echo $i++ ?></th>
								<td><b><?php echo $row['school_id'] ?></b></td>
								<td><b><?php echo ucwords($row['name']) ?></b></td>
								<td><b><?php echo $row['username'] ?></b></td>
								<td><b><?php echo isset($class[$row['class_id']]) ? $class[$row['class_id']] : "N/A" ?></b></td>
								<td class="text-center">
									<button type="button"
										class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle"
										data-toggle="dropdown" aria-expanded="true">
										Action
									</button>
									<div class="dropdown-menu" style="">
										<a class="dropdown-item view_student" href="javascript:void(0)"
											data-id="<?php echo $row['id'] ?>">View</a>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item"
											href="./index.php?page=edit_student&id=<?php echo $row['id'] ?>">Edit</a>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item delete_student" href="javascript:void(0)"
											data-id="<?php echo $row['id'] ?>">Delete</a>
									</div>
								</td>
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
			// Initialize DataTable
			var table = $('#list').DataTable();

			$('.view_student').click(function () {
				uni_modal("<i class='fa fa-id-card'></i> student Details", "<?php echo $_SESSION['login_view_folder'] ?>view_student.php?id=" + $(this).attr('data-id'))
			})
			$('.delete_student').click(function () {
				_conf("Are you sure to delete this student?", "delete_student", [$(this).attr('data-id')])
			})

			// Select All checkbox
			$('#select_all').click(function () {
				$('.student_checkbox').prop('checked', this.checked);
				toggleBulkUpdateSection();
			})

			// Individual checkbox
			$(document).on('change', '.student_checkbox', function () {
				toggleBulkUpdateSection();
			})

			// Custom filter function for DataTables
			$.fn.dataTable.ext.search.push(
				function (settings, data, dataIndex) {
					var curriculum = $('#filter_curriculum').val();
					var level = $('#filter_level').val();
					var section = $('#filter_section').val();

					var row = table.row(dataIndex).node();
					var rowCurriculum = $(row).data('curriculum');
					var rowLevel = $(row).data('level');
					var rowSection = $(row).data('section');

					if (curriculum && rowCurriculum != curriculum) {
						return false;
					}
					if (level && rowLevel != level) {
						return false;
					}
					if (section && rowSection != section) {
						return false;
					}

					return true;
				}
			);

			// Filter button
			$('#btn_filter').click(function () {
				table.draw();
			})

			// Bulk update button
			$('#btn_bulk_update').click(function () {
				var selected_students = [];
				$('.student_checkbox:checked').each(function () {
					selected_students.push($(this).val());
				});

				var class_id = $('#bulk_class_id').val();

				if (selected_students.length == 0) {
					alert_toast("Please select students to update", 'warning');
					return;
				}

				if (!class_id) {
					alert_toast("Please select a class to update", 'warning');
					return;
				}

				var class_text = $('#bulk_class_id option:selected').text();
				var updateMsg = "Update " + selected_students.length + " student(s) to class: " + class_text + "?";

				_conf(updateMsg, "bulk_update_students", [selected_students, class_id])
			})

			// Cancel bulk update
			$('#btn_cancel_bulk').click(function () {
				$('.student_checkbox').prop('checked', false);
				$('#select_all').prop('checked', false);
				$('#bulk_class_id').val('');
				toggleBulkUpdateSection();
			})

			function toggleBulkUpdateSection() {
				if ($('.student_checkbox:checked').length > 0) {
					$('#bulk_update_section').slideDown();
				} else {
					$('#bulk_update_section').slideUp();
				}
			}
		})

		function delete_student($id) {
			start_load()
			$.ajax({
				url: 'ajax.php?action=delete_student',
				method: 'POST',
				data: { id: $id },
				success: function (resp) {
					if (resp == 1) {
						alert_toast("Data successfully deleted", 'success')
						setTimeout(function () {
							location.reload()
						}, 1500)

					}
				}
			})
		}

		function bulk_update_students(student_ids, class_id) {
			start_load()
			$.ajax({
				url: 'ajax.php?action=bulk_update_students',
				method: 'POST',
				data: {
					student_ids: JSON.stringify(student_ids),
					class_id: class_id
				},
				success: function (resp) {
					if (resp == 1) {
						alert_toast("Students successfully updated", 'success')
						setTimeout(function () {
							location.reload()
						}, 1500)
					} else {
						// Try to parse JSON error response
						try {
							var error_obj = JSON.parse(resp);
							alert_toast(error_obj.message || "An error occurred", 'error')
						} catch (e) {
							alert_toast("An error occurred: " + resp, 'error')
						}
						end_load()
					}
				},
				error: function (xhr, status, error) {
					alert_toast("AJAX Error: " + error, 'error')
					end_load()
				}
			})
		}
	</script>