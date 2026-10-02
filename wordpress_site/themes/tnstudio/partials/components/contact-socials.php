<?php
$contact_socials = $args['socials'] ?? null;

if (! empty($contact_socials)): ?>

  <ul class="ct-ss-list"> 
      <?php foreach ($contact_socials as $index => $item) : ?>
        <li class="ct-ss-item"> 
            <a href="<?php echo esc_url($item['link']); ?>">
                <div class="ct-ss-ic">
                    <?php echo wp_get_attachment_image($item['icon'], 'full', false, ['loading' => 'lazy']); ?>
                </div>
            </a>
        </li>
      <?php endforeach; ?>
  </ul>
<?php endif; ?>