<?php
$statistics = $args['statistics'] ?? null;

if (is_array($statistics)) : ?>
  <div class="count-list" data-aos="fade-up" data-aos-delay="400">
    <?php foreach ($statistics as $item) : ?>
      <div class="count-item">
        <?php if (!empty($item['number'])) : ?>
          <div class="statis-count" data-module="countup">
            <p class="number" data-countup-number="<?php echo esc_attr($item['number']); ?>">
              <?php echo esc_html($item['number']); ?>
            </p>
            <p class="mark">+</p>
          </div>
        <?php endif; ?>

        <?php if (!empty($item['title'])) : ?>
          <p class="desc"><?php echo esc_html($item['title']); ?></p>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>