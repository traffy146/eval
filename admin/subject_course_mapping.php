<?php include 'db_connect.php'; ?>
<div class="col-lg-12">
    <div class="card card-outline">
        <div class="card-header">
            <h3 class="card-title">Subject-Course Mapping</h3>
            <div class="card-tools">
                <button class="btn btn-sm btn-primary" id="btn_add_mapping"><i class="fa fa-plus"></i> Add Subject to
                    Course/Year</button>
            </div>
        </div>
        <div class="card-body">
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
                        <option value="1">1st Year</option>
                        <option value="2">2nd Year</option>
                        <option value="3">3rd Year</option>
                        <option value="4">4th Year</option>
                        <option value="5">5th Year</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>&nbsp;</label>
                    <button id="btn_filter" class="btn btn-primary btn-sm btn-block"><i class="fa fa-filter"></i> Apply
                        Filter</button>
                </div>
            </div>
            <table class="table table-hover table-bordered" id="mapping_table">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Year</th>
                        <th>Subject Code</th>
                        <th>Subject Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $qry = $conn->query("SELECT scm.*, s.code, s.subject, s.description 
						FROM subject_course_mapping scm 
						INNER JOIN subject_list s ON scm.subject_id = s.id 
						ORDER BY scm.curriculum, scm.level, s.code");
                    while ($row = $qry->fetch_assoc()):
                        ?>
                        <tr class="mapping-row" data-curriculum="<?php echo $row['curriculum'] ?>"
                            data-level="<?php echo $row['level'] ?>">
                            <td><b><?php echo $row['curriculum'] ?></b></td>
                            <td><b><?php echo $row['level'] ?></b></td>
                            <td><?php echo $row['code'] ?></td>
                            <td><?php echo $row['subject'] ?></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-danger delete_mapping" data-id="<?php echo $row['id'] ?>">
                                    <i class="fa fa-trash"></i> Remove
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Mapping Modal -->
<div class="modal fade" id="add_mapping_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Subject to Course/Year</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="mapping_form">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Course</label>
                        <select name="curriculum" class="form-control" required>
                            <option value="">Select Course</option>
                            <?php
                            $curriculum_qry = $conn->query("SELECT DISTINCT curriculum FROM class_list ORDER BY curriculum ASC");
                            while ($row = $curriculum_qry->fetch_assoc()):
                                ?>
                                <option value="<?php echo $row['curriculum'] ?>"><?php echo $row['curriculum'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Year Level</label>
                        <select name="level" class="form-control" required>
                            <option value="">Select Year</option>
                            <option value="1">1st Year</option>
                            <option value="2">2nd Year</option>
                            <option value="3">3rd Year</option>
                            <option value="4">4th Year</option>
                            <option value="5">5th Year</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Subject</label>
                        <select name="subject_id" class="form-control" required>
                            <option value="">Select Subject</option>
                            <?php
                            $subjects = $conn->query("SELECT * FROM subject_list ORDER BY code ASC");
                            while ($row = $subjects->fetch_assoc()):
                                ?>
                                <option value="<?php echo $row['id'] ?>"><?php echo $row['code'] ?> -
                                    <?php echo $row['subject'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Mapping</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
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
        $('#btn_add_mapping').click(function () {
            $('#add_mapping_modal').modal('show');
        });

        $('#mapping_form').submit(function (e) {
            e.preventDefault();
            start_load();
            $.ajax({
                url: 'ajax.php?action=save_subject_mapping',
                method: 'POST',
                data: $(this).serialize(),
                success: function (resp) {
                    if (resp == 1) {
                        alert_toast("Mapping added successfully", 'success');
                        setTimeout(function () {
                            location.reload();
                        }, 1500);
                    } else if (resp == 2) {
                        alert_toast("This subject is already mapped to this course/year", 'warning');
                        end_load();
                    } else {
                        alert_toast("An error occurred", 'error');
                        end_load();
                    }
                }
            });
        });

        $('.delete_mapping').click(function () {
            _conf("Are you sure to remove this mapping?", "delete_mapping", [$(this).attr('data-id')]);
        });

        // Filter functionality
        $('#btn_filter').click(function () {
            var curriculum = $('#filter_curriculum').val();
            var level = $('#filter_level').val();

            $('.mapping-row').each(function () {
                var show = true;

                if (curriculum && $(this).data('curriculum') != curriculum) {
                    show = false;
                }
                if (level && $(this).data('level') != level) {
                    show = false;
                }

                if (show) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });

    function delete_mapping(id) {
        start_load();
        $.ajax({
            url: 'ajax.php?action=delete_subject_mapping',
            method: 'POST',
            data: {
                id: id
            },
            success: function (resp) {
                if (resp == 1) {
                    alert_toast("Mapping removed successfully", 'success');
                    setTimeout(function () {
                        location.reload();
                    }, 1500);
                } else {
                    alert_toast("An error occurred", 'error');
                    end_load();
                }
            }
        });
    }
</script>