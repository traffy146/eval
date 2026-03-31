<?php include 'db_connect.php'; ?>
<div class="col-lg-12">
    <div class="card card-outline">
        <div class="card-header">
            <h3 class="card-title">Evaluation Status</h3>
        </div>
        <div class="card-body">
            <!-- Filter Form -->
            <form id="filter-form" class="form-inline mb-3">
                <select id="filter-student" class="form-control mr-2">
                    <option value="">All Students</option>
                    <?php
                    $students = $conn->query("SELECT id, CONCAT(firstname,' ',COALESCE(NULLIF(middlename,''),''),' ',lastname) as name FROM student_list");
                    $student_arr = [];
                    while ($s = $students->fetch_assoc()) {
                        $student_arr[$s['id']] = $s['name'];
                        echo '<option value="' . $s['id'] . '">' . $s['name'] . '</option>';
                    }
                    ?>
                </select>
                <select id="filter-subject" class="form-control mr-2">
                    <option value="">All Subjects</option>
                    <?php
                    $subjects = $conn->query("SELECT id, subject FROM subject_list");
                    $subject_arr = [];
                    while ($sub = $subjects->fetch_assoc()) {
                        $subject_arr[$sub['id']] = $sub['subject'];
                        echo '<option value="' . $sub['id'] . '">' . $sub['subject'] . '</option>';
                    }
                    ?>
                </select>
                <select id="filter-faculty" class="form-control mr-2">
                    <option value="">All Faculties</option>
                    <?php
                    $faculty = $conn->query("SELECT id, CONCAT(firstname,' ',COALESCE(NULLIF(middlename,''),''),' ',lastname) as name FROM faculty_list");
                    $faculty_arr = [];
                    while ($f = $faculty->fetch_assoc()) {
                        $faculty_arr[$f['id']] = $f['name'];
                        echo '<option value="' . $f['id'] . '">' . $f['name'] . '</option>';
                    }
                    ?>
                </select>
                <select id="filter-section" class="form-control mr-2">
                    <option value="">All Sections</option>
                    <?php
                    $sections = $conn->query("SELECT DISTINCT section FROM class_list WHERE section IS NOT NULL AND section != '' ORDER BY section ASC");
                    while ($sec = $sections->fetch_assoc()) {
                        echo '<option value="' . htmlspecialchars($sec['section']) . '">' . htmlspecialchars($sec['section']) . '</option>';
                    }
                    ?>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
            <table class="table table-bordered table-hover" id="eval-status-table">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Section</th>
                        <th>Subject</th>
                        <th>Faculty</th>
                        <th>Status</th>
                        <th>Date Evaluated</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Get current academic period
                    $current_academic = $conn->query("SELECT id FROM academic_list WHERE is_default = 1")->fetch_assoc();
                    $academic_id = $current_academic ? $current_academic['id'] : 0;

                    // Get all evaluated rows
                    $evals = $conn->query("SELECT e.*, 
    CONCAT(s.firstname,' ',COALESCE(NULLIF(s.middlename,''),''),' ',s.lastname) as student_name, 
    c.section as section_name,
    sub.subject as subject_name, 
    CONCAT(f.firstname,' ',COALESCE(NULLIF(f.middlename,''),''),' ',f.lastname) as faculty_name
    FROM evaluation_list e
    INNER JOIN student_list s ON e.student_id = s.id
    LEFT JOIN class_list c ON s.class_id = c.id
    INNER JOIN subject_list sub ON e.subject_id = sub.id
    INNER JOIN faculty_list f ON e.faculty_id = f.id
    WHERE e.academic_id = $academic_id
");

                    while ($row = $evals->fetch_assoc()) {
                        $section_name = !empty($row['section_name']) ? $row['section_name'] : 'N/A';
                        echo '<tr data-student="' . $row['student_id'] . '" data-subject="' . $row['subject_id'] . '" data-faculty="' . $row['faculty_id'] . '" data-section="' . htmlspecialchars($section_name, ENT_QUOTES) . '">
        <td>' . $row['student_name'] . '</td>
        <td>' . htmlspecialchars($section_name) . '</td>
        <td>' . $row['subject_name'] . '</td>
        <td>' . $row['faculty_name'] . '</td>
        <td><span class="badge badge-success">Evaluated</span></td>
    <td>' . date('M d, Y h:i A', strtotime($row['date_taken'])) . '</td>
    </tr>';
                    }
                    ?>
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
        $('#eval-status-table').dataTable();

        // Only one filter active at a time
        $('#filter-student, #filter-subject, #filter-faculty, #filter-section').on('change', function () {
            var id = $(this).attr('id');
            // Reset other filters
            $('#filter-student, #filter-subject, #filter-faculty, #filter-section').not('#' + id).val('');
            var value = $(this).val();
            var type = id.replace('filter-', '');

            $('#eval-status-table tbody tr').each(function () {
                var show = true;
                if (value) {
                    show = ($(this).data(type) == value);
                }
                $(this).toggle(show);
            });
        });

        // Prevent form submit from doing anything
        $('#filter-form').on('submit', function (e) {
            e.preventDefault();
        });
    });
</script>