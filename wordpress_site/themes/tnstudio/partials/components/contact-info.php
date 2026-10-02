<?php
$contact_info = $args['contact-info'] ?? null;

if (! empty($contact_info)): ?>

  <div class="container">
    <ul class="fi-list" data-aos="fade-up" data-aos-delay="1200">
      <?php foreach ($contact_info as $index => $item) : ?>
        <?php if (empty($item['icon']) && empty($item['label']) && empty($item['link'])) continue; ?>
        <li class="fi-item">
          <?php
          if ($item['link']) {
          ?>
            <a class="fi-ic" href="<?php echo esc_url($item['link']); ?>">
              <?php echo wp_get_attachment_image($item['icon'], 'full', false, ['loading' => 'lazy']); ?>
              <p><?php echo esc_html($item['label']); ?></p>
            </a>
          <?php
          } else {
          ?>
            <div class="fi-ic">
              <?php echo wp_get_attachment_image($item['icon'], 'full', false, ['loading' => 'lazy']); ?>
              <p><?php echo esc_html($item['label']); ?></p>
            </div>
          <?php
          }
          ?>

        </li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>