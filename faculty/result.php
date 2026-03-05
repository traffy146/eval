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
				<button class="btn btn-sm btn-success bg-gradient-success" id="print-btn"><i class="fa fa-print"></i>
					Print</button>
			</div>
		</div>
	</div>
	<div class="row">
		<!-- Removed the col-md-3 class list selector for privacy -->
		<div class="col-md-12">
			<div class="callout callout-info"
				style="background:transparent !important; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border-radius: 15px; border:none;color:black"
				id="printable">
				<div>
					<h3 class="text-center">Overall Evaluation Report</h3>
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
					</table>
					<p class=""><b>Total Student Evaluated: <span id="tse"></span></b></p>
					<p class=""><b>Overall Average (per item): <span id="avg_per_item"></span></b> <small>(<span
								id="avg_verbal"></span>)</small></p>
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
			<!-- Removed Additional Feedback Section for Privacy -->
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
			color: #000000;
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
		load_overall_report()
	})

	function load_overall_report() {
		start_load()
		$.ajax({
			url: 'ajax.php?action=get_overall_report',
			method: "POST",
			data: { faculty_id: <?php echo $faculty_id ?> },
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
					} else {
						$('#tse').text(resp.tse)
						if (typeof resp.avg_per_item !== 'undefined' && resp.avg_per_item !== null) {
							$('#avg_per_item').text(resp.avg_per_item)
						} else {
							$('#avg_per_item').text('N/A')
						}
						$('#avg_verbal').text(resp.verbal_rating || '')

						$('.rates').text('-')
						var data = resp.data
						Object.keys(data).map(q => {
							Object.keys(data[q]).map(r => {
								console.log($('.rate_' + r + '_' + q), data[q][r])
								$('.rate_' + r + '_' + q).text(parseFloat(data[q][r]).toFixed(2) + '%')
							})
						})
					}

				}
				end_load()
			}
		})
	}

	function performPrint() {
		start_load();
		var ns = $('noscript').clone();
		var $clone = $('#printable').clone();
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
		performPrint();
	});
</script>