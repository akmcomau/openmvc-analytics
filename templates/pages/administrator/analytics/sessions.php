<div class="container">

	<div class="row">
		<div class="col-md-12">
			<div class="widget">
				<div class="widget-header">
					<h3><?php echo $text_search_sessions_results; ?></h3>
				</div>
				<div class="widget-content">
					<div class="pagination">
						<?php echo $pagination->getPageLinks(); ?>
					</div>
					<form action="<?php echo $this->url->getUrl('administrator/Pages', 'delete'); ?>" method="post">
						<table class="table">
							<tr>
								<th nowrap="nowrap"><?php echo $text_last_hit; ?> <?php echo $pagination->getSortUrls('last_hit'); ?></th>
								<th nowrap="nowrap"><?php echo $text_created; ?> <?php echo $pagination->getSortUrls('created'); ?></th>
								<th nowrap="nowrap"><?php echo $text_ip_address; ?> <?php echo $pagination->getSortUrls('ip'); ?></th>
								<th nowrap="nowrap"><?php echo $text_referer; ?> <?php echo $pagination->getSortUrls('referer'); ?></th>
								<th nowrap="nowrap"><?php echo $text_campaign; ?> <?php echo $pagination->getSortUrls('campaign'); ?></th>
								<th nowrap="nowrap"><?php echo $text_hit_count; ?></th>
								<th></th>
							</tr>
							<?php foreach ($sessions as $session) { ?>
							<tr>
								<td><?php echo htmlspecialchars($session->last_hit); ?></td>
								<td><?php echo htmlspecialchars($session->created); ?></td>
								<td><?php echo htmlspecialchars($session->ip); ?></td>
								<td><?php echo htmlspecialchars($session->getRefererHost()); ?></td>
								<td><?php if ($session->getCampaignName() !== FALSE) echo htmlspecialchars(empty($session->getCampaignName()) ? $text_unknown : $session->getCampaignName()); ?></td>
								<td><?php echo htmlspecialchars($session->getHitCount()); ?></td>
								<td>
									<a href="<?php echo $this->url->getUrl('administrator/Analytics', 'viewSession', [$session->id]); ?>" class="btn btn-primary"><i class="fa fa-eye" title="<?php echo $text_view; ?>"></i></a>
								</td>
							</tr>
							<?php } ?>
						</table>
					</form>
					<div class="pagination">
						<?php echo $pagination->getPageLinks(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	<?php /* echo $form->getJavascriptValidation(); */ ?>
	<?php /* echo $message_js; */ ?>

</script>
