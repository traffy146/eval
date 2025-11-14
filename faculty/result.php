<?php $faculty_id = $_SESSION['login_id'] ?>
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
			<div class="callout callout-info"
				style="background:transparent !important; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border-radius: 15px; border:none;color:white"
				id="printable">
				<div>
					<h3 class="text-center">Evaluation Report</h3>
					<hr>
					<table width="100%">
						<tr>
							<td width="50%">
								<p><b>Academic Year: <span
											id="ay"><?php echo $_SESSION['academic']['year'] . ' ' . (ordinal_suffix($_SESSION['academic']['semester'])) ?>
											Semester</span></b></p>
							</td>
							<td></td>
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
					<legend class="w-auto">Criteria for Evaluation</legend>
					<p>5 = Outstanding, 4 = Very satisfactory, 3 = Satisfactory, 2 = Fair, 1 = Needs improvement</p>
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
			<!-- Anonymous Additional Feedback Section -->
			<hr>
			<div id="additional-feedback-wrapper" class="mt-4">
				<h4>Additional Feedback</h4>
				<div class="d-flex flex-wrap align-items-center mb-2" id="feedback-filters">
					<label class="mr-2 mb-0">Filter by Rating:</label>
					<div class="btn-group btn-group-sm" role="group" aria-label="Feedback Filters">
						<button type="button" class="btn btn-outline-secondary feedback-filter active"
							data-rating="all">All <span class="badge badge-pill badge-secondary ml-1"
								id="count-all">0</span></button>
						<button type="button" class="btn btn-outline-success feedback-filter" data-rating="good">Good
							<span class="badge badge-pill badge-success ml-1" id="count-good">0</span></button>
						<button type="button" class="btn btn-outline-warning feedback-filter"
							data-rating="neutral">Neutral <span class="badge badge-pill badge-warning ml-1"
								id="count-neutral">0</span></button>
						<button type="button" class="btn btn-outline-danger feedback-filter" data-rating="bad">Bad <span
								class="badge badge-pill badge-danger ml-1" id="count-bad">0</span></button>
					</div>
				</div>
				<div id="feedback-list" class="border rounded p-2" style="max-height:300px; overflow:auto;">
					<p class="text-muted mb-0" id="no-feedback-msg" style="display:none">No feedback available for the
						selected rating.</p>
					<ul class="list-unstyled mb-0" id="feedback-ul"></ul>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<style>
	.list-group-item:hover {
		color: black !important;
		font-weight: 700 !important;
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
		load_class()
	})
	function load_class() {
		start_load()
		$.ajax({
			url: "ajax.php?action=get_class",
			method: 'POST',
			data: { fid: <?php echo $faculty_id ?> },
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
			window.history.pushState({}, null, './index.php?page=result&rid=' + data.id);
			load_report(<?php echo $faculty_id ?>, data.sid, data.id);
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
						var data = resp.data
						Object.keys(data).map(q => {
							Object.keys(data[q]).map(r => {
								console.log($('.rate_' + r + '_' + q), data[q][r])
								$('.rate_' + r + '_' + q).text(data[q][r] + '%')
							})
						})
						// load anonymous feedback after report
						load_feedback($faculty_id, $subject_id, $class_id, 'all')
					}

				}
			},
			complete: function () {
				end_load()
			}
		})
	}
	function load_feedback($faculty_id, $subject_id, $class_id, $rating = 'all') {
		$.ajax({
			url: 'ajax.php?action=get_additional_feedback',
			method: 'POST',
			data: { faculty_id: $faculty_id, subject_id: $subject_id, class_id: $class_id, rating: $rating },
			success: function (resp) {
				if (!resp) return;
				try { resp = JSON.parse(resp) } catch (e) { return; }
				// cache full feedback dataset for printing
				if (resp.filter === 'all') {
					window._allFeedbackCache = resp;
					window._allFeedbackContext = { faculty_id: $faculty_id, subject_id: $subject_id, class_id: $class_id };
				}
				$('#feedback-ul').empty();
				if (resp.feedback && resp.feedback.length) {
					$('#no-feedback-msg').hide();
					resp.feedback.forEach(function (f) {
						var ratingLower = (f.rating || '').toString().toLowerCase();
						var badgeClass = 'secondary';
						if (ratingLower === 'good') badgeClass = 'success';
						else if (ratingLower === 'neutral') badgeClass = 'warning';
						else if (ratingLower === 'bad') badgeClass = 'danger';
						$('#feedback-ul').append('<li class="mb-2"><span class="badge badge-' + badgeClass + ' text-uppercase mr-2">' + (ratingLower || 'n/a') + '</span><span>' + (f.comment ? $('<div/>').text(f.comment).html() : '<em>No comment provided</em>') + '</span></li>');
					});
				} else {
					$('#no-feedback-msg').show();
				}
				// update counts
				if (resp.summary) {
					$('#count-good').text(resp.summary.good || 0);
					$('#count-neutral').text(resp.summary.neutral || 0);
					$('#count-bad').text(resp.summary.bad || 0);
					var totalAll = (resp.summary.good || 0) + (resp.summary.neutral || 0) + (resp.summary.bad || 0);
					$('#count-all').text(totalAll);
				}
				$('.feedback-filter').removeClass('active');
				$('.feedback-filter[data-rating="' + resp.filter + '"]').addClass('active');
			}
		})
	}
	// filter button handler
	$(document).on('click', '.feedback-filter', function () {
		if (!$(this).hasClass('active')) {
			var rating = $(this).data('rating') || 'all';
			var activeClass = $('.show-result.active').attr('data-json');
			if (activeClass) {
				var data = JSON.parse(activeClass);
				load_feedback(<?php echo $faculty_id ?>, data.sid, data.id, rating);
			}
		}
	})
	function buildPrintableFeedback() {
		var cache = window._allFeedbackCache;
		if (!cache || !cache.feedback) {
			return '<p><em>No feedback available.</em></p>';
		}
		var totalAll = (cache.summary.good || 0) + (cache.summary.neutral || 0) + (cache.summary.bad || 0);
		var html = '';
		html += '<h4>Additional Feedback (All Ratings)</h4>';
		html += '<p>Summary: Good: ' + (cache.summary.good || 0) + ', Neutral: ' + (cache.summary.neutral || 0) + ', Bad: ' + (cache.summary.bad || 0) + ' | Total: ' + totalAll + '</p>';
		html += '<table class="wborder" width="100%"><thead><tr><th width="15%">Rating</th><th>Comment</th></tr></thead><tbody>';
		cache.feedback.forEach(function (f) {
			var ratingLower = (f.rating || '').toString().toLowerCase();
			html += '<tr><td style="text-transform:uppercase">' + ratingLower + '</td><td>' + (f.comment ? $('<div/>').text(f.comment).html() : '<em>No comment</em>') + '</td></tr>';
		});
		html += '</tbody></table>';
		return html;
	}
	function performPrint() {
		start_load();
		var ns = $('noscript').clone();
		var $clone = $('#printable').clone();
		// replace feedback section content
		if (window._allFeedbackCache) {
			var printableFeedback = buildPrintableFeedback();
			$clone.find('#additional-feedback-wrapper').html(printableFeedback);
		}
		ns.append($clone.html());
		var nw = window.open('Report', '_blank', 'width=900,height=700');
		nw.document.write(ns.html());
		nw.document.close();
		nw.print();
		setTimeout(function () {
			nw.close();
			end_load();
		}, 750);
	}
	$('#print-btn').off('click').on('click', function () {
		// ensure all feedback cached
		if (!window._allFeedbackCache) {
			var activeClass = $('.show-result.active').attr('data-json');
			if (activeClass) {
				var data = JSON.parse(activeClass);
				$.ajax({
					url: 'ajax.php?action=get_additional_feedback',
					method: 'POST',
					data: { faculty_id: <?php echo $faculty_id ?>, subject_id: data.sid, class_id: data.id, rating: 'all' },
					success: function (resp) {
						try { resp = JSON.parse(resp); window._allFeedbackCache = resp; } catch (e) { }
						performPrint();
					},
					error: performPrint
				});
			} else {
				performPrint();
			}
		} else {
			performPrint();
		}
	});
</script>