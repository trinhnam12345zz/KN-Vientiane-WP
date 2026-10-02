<form id="searchform" class="form" method="get" action="<?php echo esc_url(home_url('/')); ?>">
    <div class="form-group">
        <label>
            <input id="search" name="s"
                class="js-search" type="text"
                placeholder="<?php echo esc_attr_x('Tìm kiếm', 'placeholder', 'monamedia'); ?>" required/>

            <button id="button-search" type="submit" value="<?php _e('Tìm kiếm', 'monamedia'); ?>"></button>
        </label>
    </div>
</form>