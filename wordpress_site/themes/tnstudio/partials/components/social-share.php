<?php
    $link = urlencode(get_the_permalink());
?>

<!-- Mail -->
<a href="mailto:?body=<?php echo $link; ?>" target="_blank"></a>

<!-- facebook -->
<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $link; ?>" target="_blank"></a>

<!-- Twitter -->
<a href="https://x.com/intent/tweet?url=<?php echo $link; ?>" target="_blank"></a>

<!-- linkedin -->
<a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $link; ?>" target="_blank"></a>

<!-- pinterest -->
<a href="https://pinterest.com/pin/create/button/?url=<?php echo $link; ?>" target="_blank"></a>

<!-- reddit -->
<a href="https://reddit.com/submit?url=<?php echo $link; ?>" target="_blank"></a>

<!-- whatsapp -->
<a href="https://api.whatsapp.com/send?text=<?php echo $link; ?>" target="_blank"></a>

<!-- tumblr -->
<a href="https://www.tumblr.com/widgets/share/tool?canonicalUrl=<?php echo $link; ?>" target="_blank"></a>

<!-- Telegram -->
<a href="https://t.me/share/url?url=<?php echo $link; ?>" target="_blank"></a>

<!-- weibo -->
<a href="https://service.weibo.com/share/share.php?url=<?php echo $link; ?>" target="_blank"></a>

<!-- VK -->
<a href="https://vk.com/share.php?url=<?php echo $link; ?>" target="_blank"></a>
