<div class="container">

	<div class="row">
		<div class="col-md-12">
			<div class="widget">
				<div class="widget-header">
					<h3><?php echo $text_search_referers_results; ?></h3>
				</div>
				<div class="widget-content">
					<div class="pagination">
						<?php echo $pagination->getPageLinks(); ?>
					</div>
					<form action="<?php echo $this->url->getUrl('administrator/Pages', 'delete'); ?>" method="post">
						<table class="table">
							<tr>
								<th nowrap="nowrap"><?php echo $text_created; ?> <?php echo $pagination->getSortUrls('created'); ?></th>
								<th nowrap="nowrap"><?php echo $text_domain; ?> <?php echo $pagination->getSortUrls('domain'); ?></th>
								<th nowrap="nowrap"><?php echo $text_url; ?> <?php echo $pagination->getSortUrls('url'); ?></th>
								<th nowrap="nowrap"><?php echo $text_hit_count; ?></th>
								<th></th>
							</tr>
							<?php foreach ($referers as $referer) { ?>
							<tr>
								<td><?php echo date('Y-m-d H:i:sP', strtotime($referer->created)); ?></td>
								<td><?php echo htmlspecialchars($referer->domain); ?></td>
								<td><?php echo htmlspecialchars($referer->url); ?></td>
								<td><?php echo htmlspecialchars($referer->getHitCount()); ?></td>
								<td>
									<a href="<?php echo $this->url->getUrl('administrator/Analytics', 'viewReferer', [$referer->id]); ?>" class="btn btn-primary"><i class="fa fa-eye" title="<?php echo $text_view; ?>"></i></a>
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
