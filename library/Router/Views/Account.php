<?php $user = Util::getCurrentUser(); ?>
<div class="card">
	<h5 class="card-header">Mein Konto</h5>

	<table class="table my-0">
		<tbody>
			<tr>
				<td style="width: 50%;"><b>Account ID</b></td>
				<td style="width: 50%;">#<?= $user->getId(); ?></td>
			</tr>
			<tr>
				<td style="width: 50%;"><b>Nutzername</b></td>
				<td style="width: 50%;"><?= $user->getUsername(); ?></td>
			</tr>
			<tr>
				<td style="width: 50%;"><b>Email Adresse</b></td>
				<td style="width: 50%;"><?= $user->getEmail(); ?></td>
			</tr>
			<tr>
				<td style="width: 50%;"><b>Registrierungsdatum</b></td>
				<td style="width: 50%;"><?= Util::timeago($user->getRegisterDate()); ?></td>
			</tr>
			<tr>
				<td style="width: 50%;">&nbsp;</td>
				<td style="width: 50%;"><b>Um dein Konto weiter zu verwalten, besuche die <a href="https://gigadrivegroup.com/account" target="_blank">Gigadrive Webseite</a>.</b></td>
			</tr>
		</tbody>
	</table>
</div>