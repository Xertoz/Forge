<?php
	$lookup = function($class) use ($page) {
		return '/'.$page->page_url.'/Documentation/lookup?class='.urlencode($class);
	};

	\forge\components\Templates::addStyleFile('/components/Documentation/css/code.css');
?><?php if ($ref->getNamespaceName()): ?>namespace <strong><?=$ref->getNamespaceName()?></strong>;

<?php endif;?><strong><?=$ref->getShortName()?></strong><?php if ($ref->getParentClass()): ?> extends <a href="<?=$lookup($ref->getParentClass()->getName())?>"><?=$ref->getParentClass()->getShortName()?></a><?php endif; ?><?php if (count($ref->getInterfaces())): ?> implements<?php foreach($ref->getInterfaces() as $key => $interface): ?> <a href="<?=$lookup($interface->getName())?>"><?=$interface->getShortName()?></a><?php if ($key != array_key_last($ref->getInterfaces())) echo ','; ?><?php endforeach; ?><?php endif; ?> {
<?php if (count($ref->getTraits())): ?>
<?php foreach ($ref->getTraits() as $trait): ?>	use <a href="<?=$lookup($trait->getName())?>"><?=$trait->getShortName()?></a>
<?php endforeach; ?>

<?php endif; ?>
<?php if (count($ref->getConstants())): ?>
	<comment>/* <?=self::l('Constants')?> */</comment>
<?php foreach ($ref->getConstants() as $constant => $value): ?>	<a href="#"><?php echo $constant; ?></a> = <?php echo self::html($value); ?>

<?php endforeach; ?>

<?php endif; ?>
<?php if (count($ref->getDefaultProperties())): ?>
	<comment>/* <?=self::l('Properties')?> */</comment>
<?php foreach ($ref->getProperties() as $property): ?>	<?php if ($property->isPrivate()): ?>private <?php endif; if ($property->isProtected()): ?>protected <?php endif; if ($property->isPublic()): ?>public <?php endif; if ($property->isStatic()): ?>static <?php endif; ?><a href="#<?php echo $property->name; ?>">$<?php echo $property->name; ?></a><?php if (is_string($ref->getDefaultProperties()[$property->name])): ?> = <?php echo self::html(strval($ref->getDefaultProperties()[$property->name])); ?><?php endif; ?>

<?php endforeach; ?>

<?php endif; ?>
<?php if (count($ref->getMethods())): ?>
	<comment>/* <?=self::l('Methods')?> */</comment>
<?php foreach ($ref->getMethods() as $method): ?>	<?php if ($method->isAbstract()): ?>abstract <?php endif; if ($method->isFinal()): ?>final <?php endif; if ($method->isPrivate()): ?>private <?php endif; if ($method->isProtected()): ?>protected <?php endif; if ($method->isPublic()): ?>public <?php endif; if ($method->isStatic()): ?>static <?php endif; ?><a href="#<?php echo $method->name; ?>"><?php echo $method->name; ?></a>
<?php endforeach; ?>
<?php endif; ?>}