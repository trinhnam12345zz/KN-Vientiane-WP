<?php

/**
 * Template part: breadcrumb
 */

$links = $args['links'];
?>

<div class="breadcrumb">
    <div class="container">
        <div class="breadcrumb-wrap">
            <ul class="breadcrumb-list">
                <?php foreach ($links as $index => $link) : ?>
                    <li class="breadcrumb-item <?php echo $link['is-active'] ? 'is-current' : ''; ?>">
                        <?php if (! $link['is-active']) : ?>
                            <a href="<?php echo $link['url']; ?>" target="_self" class="text-14">
                                <?php echo $link['title']; ?>
                            </a>
                        <?php else : ?>
                            <span class="text-14"><?php echo $link['title']; ?></span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>