<div class="container">

	<div class="row">
		<div class="col-md-12">
			<div class="widget">
				<div class="widget-header">
					<h3><?php echo $text_session; ?>: <?php echo $session->php_id; ?></h3>
				</div>
				<div class="widget-content">
					<div class="pagination">
						<?php echo $pagination->getPageLinks(); ?>
					</div>
					<form action="<?php echo $this->url->getUrl('administrator/Pages', 'delete'); ?>" method="post">
						<table class="table">
							<tr>
								<th nowrap="nowrap"><?php echo $text_time; ?> <?php echo $pagination->getSortUrls('created'); ?></th>
								<th nowrap="nowrap"><?php echo $text_url; ?></th>
								<th nowrap="nowrap"><?php echo $text_customer; ?></th>
								<th nowrap="nowrap"><?php echo $text_administrator; ?></th>
								<th nowrap="nowrap"><?php echo $text_response_code; ?> <?php echo $pagination->getSortUrls('response_code'); ?></th>
								<th nowrap="nowrap"><?php echo $text_response_time; ?> <?php echo $pagination->getSortUrls('response_time'); ?></th>
								<th></th>
							</tr>
							<?php foreach ($session->getRequests($pagination->getOrdering(), $pagination->getLimitOffset()) as $request) { ?>
							<tr>
								<td><?php echo htmlspecialchars($request->created); ?></td>
								<td><?php echo $this->url->getRelativeUrl($request->controller, $request->method, json_decode($request->params)); ?></td>
								<td><?php echo htmlspecialchars($request->customer_id); ?></td>
								<td><?php echo htmlspecialchars($request->administrator_id); ?></td>
								<td><?php echo htmlspecialchars($request->response_code); ?></td>
								<td><?php echo htmlspecialchars($request->response_time); ?></td>
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
