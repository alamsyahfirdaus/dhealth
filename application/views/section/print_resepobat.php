<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $title ?></title>
	<link rel="icon" type="image/x-icon" href="<?= base_url('assets/dist/img/dhealth.jpg') ?>">
</head>
<body>
	<table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
		<tr>
			<td style="width: 25%; border-bottom: 2px solid black;">
				<img src="<?= base_url('assets/dist/img/dhelath-landscape.png') ?>" alt="">
			</td>
			<td style="border-bottom: 2px solid black;">
				<p style="font-weight: bold; font-size: 20px; margin-bottom: 0px;">D'Health</p>
				<p style="font-size: 16px; margin-top: 0px;"><?= $title ?></p>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="padding-top: 24px;">
				<table border="0" cellpadding="3" cellspacing="0" style="width: 100%;">
					<tr>
						<td style="width: 35%;">Kode</td>
						<td style="width: 5%;">:</td>
						<td><?= $row->kode ? $row->kode : '-' ?></td>
					</tr>
					<?php if (count($racikan) > 0): ?>
						<tr>
							<td colspan="2" style="width: 40%; border: 1px solid black; font-weight: bold; text-align: center;">Nama Obat</td>
							<td style="border: 1px solid black; font-weight: bold; text-align: center;">Jumlah Obat</td>
						</tr>
						<?php foreach ($racikan as $r): ?>
							<tr>
								<td colspan="2" style="width: 40%; border: 1px solid black; padding-left: 12px;"><?= $r->obatalkes_nama ?></td>
								<td style="border: 1px solid black; text-align: center;"><?= $r->qty ?></td>
							</tr>
						<?php endforeach ?>
					<?php else: ?>
						<tr>
							<td>Nama Obat</td>
							<td>:</td>
							<td><?= $row->obatalkes_nama ? $row->obatalkes_nama : '-' ?></td>
						</tr>
						<tr>
							<td>Jumlah Obat</td>
							<td>:</td>
							<td><?= $row->qty ? $row->qty : '-' ?></td>
						</tr>
					<?php endif ?>
					<tr>
						<td>Signa / Ketentuan</td>
						<td>:</td>
						<td><?= $row->signa_nama ? $row->signa_nama : '-' ?></td>
					</tr>
					<tr>
						<td>Keterangan</td>
						<td>:</td>
						<td><?= $row->keterangan ? $row->keterangan : '-' ?></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>