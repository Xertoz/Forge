<div class="admin files">
    <h1><?php echo _('Files'); ?></h1>
    <?php
        echo $matrix->drawTable(
            array(
                'name' => _('Name'),
                'date' => _('Date'),
                'size' => _('Size'),
                'actions' => null
            ),
            array(
                'size' => function($r) {
                    return \forge\String::bytesize($r['size']);
                }
            )
        );
    ?>
</div>
