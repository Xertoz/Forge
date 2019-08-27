<div id="text-content" class="text content">
	<?php echo $text->mobile_enabled && \forge\Context::isMobile() ? $text->mobile_content : $text->text_content; ?>
</div>