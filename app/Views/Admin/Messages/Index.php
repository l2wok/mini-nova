<div class="row">
	<h1><?= __('Messages'); ?></h1>
	<ol class="breadcrumb">
		<li><a href='<?= site_url('admin/dashboard'); ?>'><i class="fa fa-dashboard"></i> <?= __('Dashboard'); ?></a></li>
		<li><?= __('Messages'); ?></li>
	</ol>
</div>

<?= View::fetch('Partials/Messages'); ?>

<!-- Main content -->
<div class="row">
	<div>
	<?= $messages->links(); ?>
	</div>

<?php
if (! $messages->isEmpty()) {
	$count = 0;

	$total = $messages->count();

	foreach($messages as $message) {
		$count++;

		// Calculate the number of unread replies on the current message.
		$unread = $message->replies->where('receiver_id', $authUser->id)->where('is_read', 0)->count();

		// If the parent message was not read yet by the receiver, count it too.
		if (($message->sender_id !== $authUser->id) && ($message->is_read === 0)) {
			$unread++;
		}
?>
	<!-- Statuses -->
	<div class="media" style="margin-top: 0;">
		<div class="pull-left">
			<img class="media-object img-responsive" style="height: 65px; width: 65px" alt="<?= $message->sender->name(); ?>" src="<?= asset('images/users/no-image.png'); ?>">
		</div>
		<div class="media-body">
			<div class="col-md-8 no-padding">
				<h4 class="media-heading"><a href="<?= site_url('admin/messages/' .$message->id); ?>"><?= e($message->subject); ?></a> <?php if ($unread >  0) echo '<small class="label label-warning">' .$unread .'</small>'; ?></h4>
				<p class="no-margin"><?= __('By <b>{0}</b>', $message->sender->name()); ?></p>
				<ul class="list-inline text-muted no-margin">
					<li><?= __('{0, plural, one{# reply} other{# replies}}', $message->replies->count()); ?></li>
					<li><?= $message->created_at->diffForHumans(); ?></li>
				</ul>
			</div>
			<div class="col-md-4 no-padding">
				<a class="btn btn-sm btn-primary pull-right" title="<?= __('View this message and its replies'); ?>" href="<?= site_url('admin/messages/' .$message->id); ?>"><i class='fa fa-envelope'></i> <?= __('View the Message(s)'); ?></a>
			</div>
		</div>
	</div>

<?php if ($count < $total) { ?>
	<hr style="margin-top: 10px; margin-bottom: 10px;">
<?php } ?>

<?php } ?>
<?php } else { ?>
	<p><strong><?= __('You have no messages sent or received.'); ?></strong></p>
	<br>
<?php } ?>

</div>

<div class="row">
	<hr>
	<a class='btn btn-success col-sm-2' href='<?= site_url('admin/messages/create'); ?>'><i class='fa fa-send'></i> <?= __('Send a new Message'); ?></a>
</div>