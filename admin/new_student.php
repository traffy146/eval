<?php
?>
<div class="col-lg-12">
	<div class="card" style="background:pink;">
		<div class="card-body" style="background:pink;">
			<form action="" id="manage_student">
				<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
				<div class="row">
					<div class="col-md-6 border-right">
						<div class="form-group">
							<label for="" class="control-label">School ID</label>
							<input type="text" name="school_id" class="form-control form-control-sm" required
								value="<?php echo isset($school_id) ? $school_id : '' ?>">
						</div>
						<div class="form-group">
							<label for="" class="control-label">First Name</label>
							<input type="text" name="firstname" class="form-control form-control-sm" required
								value="<?php echo isset($firstname) ? $firstname : '' ?>">
						</div>
						<div class="form-group">
							<label for="" class="control-label">Middle Name</label>
							<input type="text" name="middlename" class="form-control form-control-sm"
								value="<?php echo isset($middlename) ? $middlename : '' ?>">
							<small class="form-text text-muted">Optional</small>
						</div>
						<div class="form-group">
							<label for="" class="control-label">Last Name</label>
							<input type="text" name="lastname" class="form-control form-control-sm" required
								value="<?php echo isset($lastname) ? $lastname : '' ?>">
						</div>
						<div class="form-group"> <label for="" class="control-label">Username</label>
							<input type="text" name="username" class="form-control form-control-sm" required
								value="<?php echo isset($username) ? $username : '' ?>">
						</div>
						<div class="form-group"> <label for="" class="control-label">Class</label>
							<select name="class_id" id="class_id" class="form-control form-control-sm select2">
								<option value=""></option>
								<?php
								$classes = $conn->query("SELECT id,concat(curriculum,' ',level,' - ',section) as class FROM class_list");
								while ($row = $classes->fetch_assoc()):
									?>
									<option value="<?php echo $row['id'] ?>" <?php echo isset($class_id) && $class_id == $row['id'] ? "selected" : "" ?>><?php echo $row['class'] ?></option>
								<?php endwhile; ?>
							</select>
						</div>
						<div class="form-group">
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" id="is_irregular"
									name="is_irregular" value="1" <?php echo isset($is_irregular) && $is_irregular == 1 ? 'checked' : '' ?>>
								<label class="custom-control-label" for="is_irregular">Irregular Student</label>
								<small class="form-text text-muted">Check this if student has different subjects than
									regular curriculum</small>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="control-label">Avatar</label>
							<div class="custom-file">
								<input type="file" class="custom-file-input" id="customFile" name="img"
									onchange="displayImg(this,$(this))">
								<label class="custom-file-label" for="customFile">Choose file</label>
							</div>
						</div>
						<div class="form-group d-flex justify-content-center align-items-center">
							<img src="<?php echo isset($avatar) ? 'assets/uploads/' . $avatar : '' ?>" alt="Avatar"
								id="cimg" class="img-fluid img-thumbnail ">
						</div>
					</div>
					<div class="col-md-6">

						<div class="form-group">
							<label class="control-label">Password</label>
							<input type="password" class="form-control form-control-sm" name="password" <?php echo !isset($id) ? "required" : '' ?>>
							<small><i><?php echo isset($id) ? "Leave this blank if you dont want to change you password" : '' ?></i></small>
						</div>
						<div class="form-group">
							<label class="label control-label">Confirm Password</label>
							<input type="password" class="form-control form-control-sm" name="cpass" <?php echo !isset($id) ? 'required' : '' ?>>
							<small id="pass_match" data-status=''></small>
						</div>
					</div>
				</div>
				<hr>
				<div class="col-lg-12 text-right justify-content-center d-flex">
					<button class="btn btn-primary mr-2">Save</button>
					<button class="btn btn-secondary" type="button"
						onclick="location.href = 'index.php?page=student_list'">Cancel</button>
				</div>
			</form>
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

	.form-control,
	.custom-select {
		background: rgba(255, 255, 255, 0.1);
		/* transparent glass look */
		border: 1px solid rgba(0, 0, 0, 0.6);
		/* white border */
		color: black;
		/* white text */
		border-radius: 8px;
	}

	.form-control[readonly] {
		background: rgba(200, 200, 200, 0.3);
		cursor: not-allowed;
		opacity: 0.7;
	}

	img#cimg {
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}
</style>
<script>
	$('[name="password"],[name="cpass"]').keyup(function () {
		var pass = $('[name="password"]').val()
		var cpass = $('[name="cpass"]').val()
		if (cpass == '' || pass == '') {
			$('#pass_match').attr('data-status', '')
		} else {
			if (cpass == pass) {
				$('#pass_match').attr('data-status', '1').html('<i class="text-success">Password Matched.</i>')
			} else {
				$('#pass_match').attr('data-status', '2').html('<i class="text-danger">Password does not match.</i>')
			}
		}
	})
	function displayImg(input, _this) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$('#cimg').attr('src', e.target.result);
			}

			reader.readAsDataURL(input.files[0]);
		}
	}
	$('#manage_student').submit(function (e) {
		e.preventDefault()
		$('input').removeClass("border-danger")
		start_load()
		$('#msg').html('')
		if ($('[name="password"]').val() != '' && $('[name="cpass"]').val() != '') {
			if ($('#pass_match').attr('data-status') != 1) {
				if ($("[name='password']").val() != '') {
					$('[name="password"],[name="cpass"]').addClass("border-danger")
					end_load()
					return false;
				}
			}
		}
		$.ajax({
			url: 'ajax.php?action=save_student',
			data: new FormData($(this)[0]),
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			success: function (resp) {
				if (resp == 1) {
					alert_toast('Data successfully saved.', "success");
					setTimeout(function () {
						location.replace('index.php?page=student_list')
					}, 750)
				} else if (resp == 2) {
					$('#msg').html("<div class='alert alert-danger'>Username already exist.</div>");
					$('[name="username"]').addClass("border-danger")
						end_load()
					} else if (resp == 4) {
						$('#msg').html("<div class='alert alert-danger'>Only admin can change the username.</div>");
						$('[name="username"]').addClass("border-danger")
					end_load()
				}
			}
		})
	})
</script>