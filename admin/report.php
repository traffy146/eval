<?php $faculty_id = isset($_GET['fid']) ? $_GET['fid'] : ''; ?>
<?php
function ordinal_suffix($num)
{
    $num = $num % 100; // protect against large numbers
    if ($num < 11 || $num > 13) {
        switch ($num % 10) {
            case 1:
                return $num . 'st';
            case 2:
                return $num . 'nd';
            case 3:
                return $num . 'rd';
        }
    }
    return $num . 'th';
}
?>
<div class="col-lg-12">
    <div class="callout "
        style="background:transparent !important; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border-radius: 15px; border:none;color:white">
        <div class="d-flex w-100 justify-content-center align-items-center">
            <label for="faculty">Select Faculty</label>
            <div class=" mx-2 col-md-4">
                <select name="" id="faculty_id" class="form-control form-control-sm select2">
                    <option value=""></option>
                    <?php
                    $faculty = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM faculty_list order by concat(firstname,' ',lastname) asc");
                    $f_arr = array();
                    $fname = array();
                    while ($row = $faculty->fetch_assoc()):
                        $f_arr[$row['id']] = $row;
                        $fname[$row['id']] = ucwords($row['name']);
                        ?>
                        <option value="<?php echo $row['id'] ?>" <?php echo isset($faculty_id) && $faculty_id == $row['id'] ? "selected" : "" ?>><?php echo ucwords($row['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mb-1">
            <div class="d-flex justify-content-end w-100">
                <button class="btn btn-sm btn-success bg-gradient-success" style="display:none" id="print-btn"><i
                        class="fa fa-print"></i> Print</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="callout "
                style="background:transparent !important; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border-radius: 15px; border:none;color:white">
                <div class="list-group" id="class-list">

                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="callout "
                style="background:transparent !important; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border-radius: 15px; border:none;color:white"
                id="printable">
                <div>
                    <h3 class="text-center">Evaluation Report</h3>
                    <hr>
                    <table width="100%">
                        <tr>
                            <td width="50%">
                                <p><b>Faculty: <span id="fname"></span></b></p>
                            </td>
                            <td width="50%">
                                <p><b>Academic Year: <span
                                            id="ay"><?php echo $_SESSION['academic']['year'] . ' ' . (ordinal_suffix($_SESSION['academic']['semester'])) ?>
                                            Semester</span></b></p>
                            </td>
                        </tr>
                        <tr>
                            <td width="50%">
                                <p><b>Class: <span id="classField"></span></b></p>
                            </td>
                            <td width="50%">
                                <p><b>Subject: <span id="subjectField"></span></b></p>
                            </td>
                        </tr>
                    </table>
                    <p class=""><b>Total Student Evaluated: <span id="tse"></span></b></p>
                    <p class=""><b>Overall Average (per item): <span id="avg_per_item"></span></b> <small>(<span
                                id="avg_verbal"></span>)</small></p>
                    <!-- Final report table for teacher across all classes/subjects -->
                    <div id="final-report-wrapper" class="mt-2">
                        <h5>Final Report (All Evaluations)</h5>
                        <table class="table table-sm wborder">
                            <thead>
                                <tr>
                                    <th>Total Evaluations</th>
                                    <th>Numerical Rating</th>
                                    <th>Adjectival Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td id="final_total">-</td>
                                    <td id="final_num">-</td>
                                    <td id="final_adj">-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <fieldset class="border border-info p-2 w-100">
                    <legend class="w-auto">Rating Legend</legend>
                    <p>5 = Strongly Agree, 4 = Agree, 3 = Uncertain, 2 = Disagree, 1 = Strongly Disagree</p>
                </fieldset>
                <?php
                $q_arr = array();
                $criteria = $conn->query("SELECT * FROM criteria_list where id in (SELECT criteria_id FROM question_list where academic_id = {$_SESSION['academic']['id']} ) order by abs(order_by) asc ");
                while ($crow = $criteria->fetch_assoc()):
                    ?>
                    <table class="table table-condensed wborder">
                        <thead>
                            <tr class="bg-gradient-secondary">
                                <th class=" p-1"><b><?php echo $crow['criteria'] ?></b></th>
                                <th width="5%" class="text-center">1</th>
                                <th width="5%" class="text-center">2</th>
                                <th width="5%" class="text-center">3</th>
                                <th width="5%" class="text-center">4</th>
                                <th width="5%" class="text-center">5</th>
                            </tr>
                        </thead>
                        <tbody class="tr-sortable">
                            <?php
                            $questions = $conn->query("SELECT * FROM question_list where criteria_id = {$crow['id']} and academic_id = {$_SESSION['academic']['id']} order by abs(order_by) asc ");
                            while ($row = $questions->fetch_assoc()):
                                $q_arr[$row['id']] = $row;
                                ?>
                                <tr class="bg-white">
                                    <td class="p-1" width="40%">
                                        <?php echo $row['question'] ?>
                                    </td>
                                    <?php for ($c = 1; $c <= 5; $c++): ?>
                                        <td class="text-center">
                                            <span class="rate_<?php echo $c . '_' . $row['id'] ?> rates"></span>
                        </div>
                        </td>
                    <?php endfor; ?>
                    </tr>
                <?php endwhile; ?>
                </tbody>
                </table>
            <?php endwhile; ?>
            <div class="mt-4">
                <fieldset class="border border-info p-2 w-100">
                    <legend class="w-auto">Student Feedback & Ratings</legend>
                    <div id="feedback-section">

                    </div>
                </fieldset>
            </div>
        </div>
    </div>
</div>
<style>
    .list-group-item:hover {
        color: black !important;
        font-weight: 700 !important;
    }

    .card {
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .badge {
        font-size: 0.8em;
        padding: 0.4em 0.6em;
    }

    .badge-success {
        background-color: #28a745;
    }

    .badge-warning {
        background-color: #ffc107;
        color: #212529;
    }

    .badge-danger {
        background-color: #dc3545;
    }

    #feedback-section .card-body {
        padding: 1rem;
    }
</style>
<noscript>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table.wborder tr,
        table.wborder td,
        table.wborder th {
            border: 1px solid gray;
            padding: 3px;
        }

        table.wborder thead tr {
            background: #6c757d linear-gradient(180deg, #828a91, #6c757d) repeat-x !important;
            color: #fff;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }
    </style>
</noscript>
<script>
    $(document).ready(function () {
        $('#faculty_id').change(function () {
            if ($(this).val() > 0)
                window.history.pushState({}, null, './index.php?page=report&fid=' + $(this).val());
            load_class()
        })
        if ($('#faculty_id').val() > 0)
            load_class()
    })
    function load_class() {
        start_load()
        var fname = <?php echo json_encode($fname) ?>;
        $('#fname').text(fname[$('#faculty_id').val()])
        $.ajax({
            url: "ajax.php?action=get_class",
            method: 'POST',
            data: { fid: $('#faculty_id').val() },
            error: function (err) {
                console.log(err)
                alert_toast("An error occured", 'error')
                end_load()
            },
            success: function (resp) {
                if (resp) {
                    resp = JSON.parse(resp)
                    if (Object.keys(resp).length <= 0) {
                        $('#class-list').html('<a href="javascript:void(0)" class="list-group-item list-group-item-action disabled">No data to be display.</a>')
                    } else {
                        $('#class-list').html('')
                        Object.keys(resp).map(k => {
                            $('#class-list').append('<a href="javascript:void(0)" data-json=\'' + JSON.stringify(resp[k]) + '\' data-id="' + resp[k].id + '" class="list-group-item list-group-item-action show-result">' + resp[k].class + ' - ' + resp[k].subj + '</a>')
                        })

                    }
                }
            },
            complete: function () {
                end_load()
                anchor_func()
                if ('<?php echo isset($_GET['rid']) ?>' == 1) {
                    $('.show-result[data-id="<?php echo isset($_GET['rid']) ? $_GET['rid'] : '' ?>"]').trigger('click')
                } else {
                    $('.show-result').first().trigger('click')
                }
            }
        })
    }
    function anchor_func() {
        $('.show-result').click(function () {
            var vars = [], hash;
            var data = $(this).attr('data-json')
            data = JSON.parse(data)
            var _href = location.href.slice(window.location.href.indexOf('?') + 1).split('&');
            for (var i = 0; i < _href.length; i++) {
                hash = _href[i].split('=');
                vars[hash[0]] = hash[1];
            }
            window.history.pushState({}, null, './index.php?page=report&fid=' + vars.fid + '&rid=' + data.id);
            load_report(vars.fid, data.sid, data.id);
            $('#subjectField').text(data.subj)
            $('#classField').text(data.class)
            $('.show-result.active').removeClass('active')
            $(this).addClass('active')
        })
    }

    function load_report($faculty_id, $subject_id, $class_id) {
        if ($('#preloader2').length <= 0)
            start_load()
        $.ajax({
            url: 'ajax.php?action=get_report',
            method: "POST",
            data: { faculty_id: $faculty_id, subject_id: $subject_id, class_id: $class_id },
            error: function (err) {
                console.log(err)
                alert_toast("An Error Occured.", "error");
                end_load()
            },
            success: function (resp) {
                if (resp) {
                    resp = JSON.parse(resp)
                    if (Object.keys(resp).length <= 0) {
                        $('.rates').text('')
                        $('#tse').text('')
                        $('#avg_per_item').text('')
                        $('#avg_verbal').text('')
                        $('#final_total').text('-')
                        $('#final_num').text('-')
                        $('#final_adj').text('-')
                        $('#feedback-section').html('<p class="text-muted">No feedback available.</p>')
                        $('#print-btn').hide()
                    } else {
                        $('#print-btn').show()
                        $('#tse').text(resp.tse)
                        if (typeof resp.avg_per_item !== 'undefined' && resp.avg_per_item !== null) {
                            $('#avg_per_item').text(resp.avg_per_item)
                        } else {
                            $('#avg_per_item').text('N/A')
                        }
                        $('#avg_verbal').text(resp.verbal_rating || '')
                        // populate final report (teacher-wide)
                        if (resp.final_report) {
                            $('#final_total').text(resp.final_report.total_evaluations || 0)
                            $('#final_num').text(typeof resp.final_report.avg_per_item !== 'undefined' ? resp.final_report.avg_per_item : '-')
                            $('#final_adj').text(resp.final_report.verbal_rating || '-')
                        } else {
                            $('#final_total').text('-')
                            $('#final_num').text('-')
                            $('#final_adj').text('-')
                        }
                        $('.rates').text('-')

                        // Load evaluation data
                        var data = resp.data
                        Object.keys(data).map(q => {
                            Object.keys(data[q]).map(r => {
                                $('.rate_' + r + '_' + q).text(data[q][r] + '%')
                            })
                        })

                        // Load feedback data
                        loadFeedbackData(resp.feedback, resp.rating_summary)
                    }
                }
            },
            complete: function () {
                end_load()
            }
        })
    }

    function loadFeedbackData(feedback, ratingSummary) {
        let feedbackHtml = '';

        // Rating Summary
        if (ratingSummary) {
            let total = ratingSummary.Good + ratingSummary.Neutral + ratingSummary.Bad;
            if (total > 0) {
                feedbackHtml += `
                <div class="row mb-3">
                    <div class="col-md-12">
                        <h5>Overall Rating Summary</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h4>${ratingSummary.Good}</h4>
                                        <small>Good (${((ratingSummary.Good / total) * 100).toFixed(1)}%)</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-warning text-white">
                                    <div class="card-body text-center">
                                        <h4>${ratingSummary.Neutral}</h4>
                                        <small>Neutral (${((ratingSummary.Neutral / total) * 100).toFixed(1)}%)</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-danger text-white">
                                    <div class="card-body text-center">
                                        <h4>${ratingSummary.Bad}</h4>
                                        <small>Bad (${((ratingSummary.Bad / total) * 100).toFixed(1)}%)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            }
        }

        // Individual Feedback Comments
        if (feedback && feedback.length > 0) {
            feedbackHtml += '<h5>Student Comments</h5>';
            feedback.forEach(function (item, index) {
                let badgeClass = '';
                switch (item.rating) {
                    case 'Good': badgeClass = 'badge-success'; break;
                    case 'Neutral': badgeClass = 'badge-warning'; break;
                    case 'Bad': badgeClass = 'badge-danger'; break;
                }

                feedbackHtml += `
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="badge ${badgeClass}">${item.rating}</span>
                                <small class="text-muted ml-2">by ${item.student_name}</small>
                            </div>
                        </div>
                        <p class="mt-2 mb-0">${item.comment}</p>
                    </div>
                </div>
            `;
            });
        } else {
            feedbackHtml += '<p class="text-muted">No additional comments provided.</p>';
        }

        $('#feedback-section').html(feedbackHtml);
    }
    $('#print-btn').click(function () {
        start_load()
        var ns = $('noscript').clone()
        var content = $('#printable').html()
        ns.append(content)
        var nw = window.open("Report", "_blank", "width=900,height=700")
        nw.document.write(ns.html())
        nw.document.close()
        nw.print()
        setTimeout(function () {
            nw.close()
            end_load()
        }, 750)
    })
</script>