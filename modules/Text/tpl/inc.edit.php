<?php \forge\components\Templates\Engine::forgeJS('forge'); ?>
<h2><?php echo _('Text'); ?></h2>
<h3><?php echo _('PC'); ?></h3>
<?php echo self::editor(array('name'=>'plugin[text_content]','value'=>$text->text_content)); ?>
<h3><?php echo _('Mobile'); ?></h3>
<input type="checkbox" name="plugin[mobile_enabled]" id="textMobileEnabled" value="1"<?php if ($text->mobile_enabled): ?> checked="checked"<?php endif; ?> />
<label for="textMobileEnabled"><?php echo _('Show different content to mobile users.'); ?></label>
<?php echo self::editor(array('name'=>'plugin[mobile_content]', 'id' => 'textMobileContent','value'=>$text->mobile_content)); ?>