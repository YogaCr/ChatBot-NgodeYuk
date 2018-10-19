<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>
		<?= $title;?>
	</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="https://d.line-scdn.net/liff/1.0/sdk.js"></script>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
	 crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
	 crossorigin="anonymous"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
	 crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp"
	 crossorigin="anonymous">
	<link rel="stylesheet" href="<?= base_url()?>asset/style.css">
</head>

<body>
	<div class="container">
		<center>
			<h3>Tanya</h3>
		</center>
		<p id="name"></p>
		<div class="row">
			<div class="form-group col-md-10">
				<label>Judul</label>
				<input type="text" class="form-control" id="judul" <?php if($this->input->get('id_tanya')){echo
				'value="'.$pertanyaan->judul.'"';}?>>
			</div>
			<div class="form-group col-md-2">
				<label>Bahasa Pemrograman</label>
				<select class="js-example-basic-single form-control" id="bahasa">
					<?php foreach($bahasa as $b){?>
					<option value="<?= $b->id_bahasa?>">
						<?= $b->bahasa?>
					</option>
					<?php }?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label>Pertanyaan</label>
			<textarea class="form-control" rows="10" id="pertanyaan"><?php if($this->input->get('id_tanya')){echo $pertanyaan->pertanyaan;}?></textarea>
		</div>
		<button type="button" class="btn btn-default" id="kirim">Kirim</button>
	</div>
</body>
<script>
	var id_user;
	var nama;
	$(document).ready(function () {
		$('.js-example-basic-single').select2();
		liff.init(data => {

			liff.getProfile().then((profile) => {
				id_user = data.context.userId;
				nama = profile.displayName;
			}).catch((err) => alert(err));

		}, err => {
			alert(err.message);
		});

	});
	$('#kirim').click(function () {
		<?php if($this->input->get('id_tanya')){?>
		$.ajax({
			type: 'POST',
			data: {
				id_tanya: '<?= $id_tanya?>',
				id_user: id_user,
				judul: $('#judul').val(),
				bahasa: $('#bahasa').val(),
				pertanyaan: $('#pertanyaan').val(),
				nama: nama
			},
			url: '<?= base_url()?>index.php/Webhook/EditPertanyaan',
			success: function () {
				location.href = '<?=base_url()?>index.php/Tanyajawab/PageTanya?id_tanya=<?= $id_tanya;?>';
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);
			}
		});
		<?php } else { ?>
		$.ajax({
			type: 'POST',
			data: {
				id_user: id_user,
				judul: $('#judul').val(),
				bahasa: $('#bahasa').val(),
				pertanyaan: $('#pertanyaan').val(),
				nama: nama
			},
			url: '<?= base_url()?>index.php/Webhook/InsertPertanyaan',
			success: function () {
				liff.closeWindow();
			},
			error: function (xhr, status, error) {
				var err = eval("(" + xhr.responseText + ")");
				alert(err.Message);
			}
		});
		<?php } ?>
	});
</script>

</html>