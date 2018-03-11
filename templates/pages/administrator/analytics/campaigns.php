<div class="container">

	<div class="row">
		<div class="col-md-12">
			<div class="widget">
				<div class="widget-header">
					<h3><?php echo $text_search_campaigns_results; ?></h3>
				</div>
				<div class="widget-content">
					<div class="pagination">
						<?php echo $pagination->getPageLinks(); ?>
					</div>
					<form action="<?php echo $this->url->getUrl('administrator/Pages', 'delete'); ?>" method="post">
						<table class="table">
							<tr>
								<th nowrap="nowrap"><?php echo $text_name; ?> <?php echo $pagination->getSortUrls('name'); ?></th>
								<th nowrap="nowrap"><?php echo $text_created; ?> <?php echo $pagination->getSortUrls('created'); ?></th>
								<th nowrap="nowrap"><?php echo $text_utm_source; ?> <?php echo $pagination->getSortUrls('utm_source'); ?></th>
								<th nowrap="nowrap"><?php echo $text_utm_medium; ?> <?php echo $pagination->getSortUrls('utm_medium'); ?></th>
								<th nowrap="nowrap"><?php echo $text_utm_campaign; ?> <?php echo $pagination->getSortUrls('utm_campaign'); ?></th>
								<th nowrap="nowrap"><?php echo $text_utm_term; ?> <?php echo $pagination->getSortUrls('utm_term'); ?></th>
								<th nowrap="nowrap"><?php echo $text_hit_count; ?></th>
								<th></th>
							</tr>
							<?php foreach ($campaigns as $campaign) { ?>
							<tr>
								<td><?php echo htmlspecialchars($campaign->name); ?></td>
								<td><?php echo date('Y-m-d H:i:sP', strtotime($campaign->created)); ?></td>
								<td><?php echo htmlspecialchars($campaign->source); ?></td>
								<td><?php echo htmlspecialchars($campaign->medium); ?></td>
								<td><?php echo htmlspecialchars($campaign->campaign); ?></td>
								<td><?php echo htmlspecialchars($campaign->term); ?></td>
								<td><?php echo htmlspecialchars($campaign->getHitCount()); ?></td>
								<td>
									<a href="<?php echo $this->url->getUrl('administrator/Analytics', 'editCampaign', [$campaign->id]); ?>" class="btn btn-primary"><i class="fa fa-edit" title="<?php echo $text_edit; ?>"></i></a>
									<a href="<?php echo $this->url->getUrl('administrator/Analytics', 'viewCampaign', [$campaign->id]); ?>" class="btn btn-primary"><i class="fa fa-eye" title="<?php echo $text_view; ?>"></i></a>
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
