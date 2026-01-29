<?php include 'db_connect.php'; ?>
<div class="col-lg-12">
    <div class="card card-outline">
        <div class="card-header">
            <h3 class="card-title">Manage Irregular Student Subjects</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label>Select Irregular Student:</label>
                <select id="irregular_student_select" class="form-control select2">
                    <option value="">-- Select Student --</option>
                    <?php
                    $students = $conn->query("SELECT s.id, s.school_id, concat(s.firstname,' ',COALESCE(NULLIF(s.middlename,''),''),' ',s.lastname) as name, concat(c.curriculum,' ',c.level,' - ',c.section) as class 
						FROM student_list s 
						LEFT JOIN class_list c ON s.class_id = c.id 
						WHERE s.is_irregular = 1 
						ORDER BY s.firstname, s.lastname");
                    while ($row = $students->fetch_assoc()):
                        ?>
                        <option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?>
                            (<?php echo $row['school_id'] ?>) - <?php echo $row['class'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div id="student_subjects_panel" style="display:none;">
                <hr>
                <h5>Additional Subjects for <span id="student_name_display"></span></h5>
                <p class="text-muted">Note: Regular subjects from the course curriculum are auto-assigned. Add extra
                    subjects here for irregular students.</p>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Add Subject:</label>
                        <select id="add_subject_select" class="form-control form-control-sm">
                            <option value="">-- Select Subject --</option>
                            <?php
                            $subjects = $conn->query("SELECT * FROM subject_list ORDER BY code");
                            while ($row = $subjects->fetch_assoc()):
                                ?>
                                <option value="<?php echo $row['id'] ?>"><?php echo $row['code'] ?> -
                                    <?php echo $row['subject'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <button id="btn_add_subject" class="btn btn-primary btn-sm btn-block">
                            <i class="fa fa-plus"></i> Add Subject
                        </button>
                    </div>
                </div>

                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Subject Code</th>
                            <th>Subject Name</th>
                            <th>Added On</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="irregular_subjects_tbody">
                        <tr>
                            <td colspan="4" class="text-center text-muted">Select a student first</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .card,
    .card-body {
        color: #efeaeaff;
        background: transparent !important;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-radius: 15px;
        border: none;
    }
</style>

<script>
    $(document).ready(function () {
        $('.select2').select2();

        $('#irregular_student_select').change(function () {
            var student_id = $(this).val();
            if (student_id) {
                $('#student_name_display').text($('#irregular_student_select option:selected').text().split(' (')[0]);
                $('#student_subjects_panel').show();
                load_irregular_subjects(student_id);
            } else {
                $('#student_subjects_panel').hide();
            }
        });

        $('#btn_add_subject').click(function () {
            var student_id = $('#irregular_student_select').val();
            var subject_id = $('#add_subject_select').val();

            if (!student_id) {
                alert_toast('Please select a student first', 'warning');
                return;
            }
            if (!subject_id) {
                alert_toast('Please select a subject to add', 'warning');
                return;
            }

            start_load();
            $.ajax({
                url: 'ajax.php?action=add_irregular_subject',
                method: 'POST',
                data: {
                    student_id: student_id,
                    subject_id: subject_id
                },
                success: function (resp) {
                    if (resp == 1) {
                        alert_toast('Subject added successfully', 'success');
                        load_irregular_subjects(student_id);
                        $('#add_subject_select').val('').trigger('change');
                    } else if (resp == 2) {
                        alert_toast('This subject is already assigned to this student', 'warning');
                    } else {
                        alert_toast('An error occurred', 'error');
                    }
                    end_load();
                }
            });
        });
    });

    function load_irregular_subjects(student_id) {
        start_load();
        $.ajax({
            url: 'ajax.php?action=get_irregular_subjects',
            method: 'POST',
            data: {
                student_id: student_id
            },
            success: function (resp) {
                $('#irregular_subjects_tbody').html(resp);
                end_load();
            }
        });
    }

    function remove_irregular_subject(id, student_id) {
        _conf("Are you sure to remove this subject?", "confirm_remove_subject", [id, student_id]);
    }

    function confirm_remove_subject(id, student_id) {
        start_load();
        $.ajax({
            url: 'ajax.php?action=remove_irregular_subject',
            method: 'POST',
            data: {
                id: id
            },
            success: function (resp) {
                if (resp == 1) {
                    alert_toast('Subject removed successfully', 'success');
                    load_irregular_subjects(student_id);
                } else {
                    alert_toast('An error occurred', 'error');
                }
                end_load();
            }
        });
    }
</script>