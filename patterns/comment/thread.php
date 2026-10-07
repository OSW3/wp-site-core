<?php
if (post_password_required()) {
    return;
}
?>
<div id="comments" class="comments__thread">
    <?php if (have_comments()) : ?>
        <ol class="comments__list">
            <?php
            wp_list_comments([
                'style' => 'ol',
                'format' => 'html5',
                'avatar_size' => 48,
                'short_ping' => true,
            ]);
            ?>
        </ol>
        <?php
        the_comments_pagination([
            'prev_text' => __('Commentaires précédents', 'wp-theme-test'),
            'next_text' => __('Commentaires suivants', 'wp-theme-test'),
            'screen_reader_text' => __('Navigation des commentaires', 'wp-theme-test'),
        ]);
        ?>
    <?php elseif (comments_open()) : ?>
        <p class="comments__empty"><?php esc_html_e('Aucun commentaire pour le moment. Soyez le premier à participer !', 'wp-theme-test'); ?></p>
    <?php endif; ?>

    <?php if (comments_open()) : ?>
        <?php
        comment_form([
            'format' => 'html5',
            'title_reply' => __('Laisser un commentaire', 'wp-theme-test'),
            'label_submit' => __('Publier le commentaire', 'wp-theme-test'),
            'class_submit' => 'btn btn--primary',
        ]);
        ?>
    <?php else : ?>
        <p class="comments__closed"><?php esc_html_e('Les commentaires sont fermés.', 'wp-theme-test'); ?></p>
    <?php endif; ?>
</div>
