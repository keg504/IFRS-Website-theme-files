<!-- Form that gets loaded if JavaScript has not loaded and user requests search page. 
This form is loaded onto the search page -->

<form class="search-form" method="get" action="<?php echo esc_url(site_url('/')); ?>">
    <label class="headline headline--medium" for="s">Site-wide search</label>
    <div class="search-form-row">
        <input placeholder="What are you looking for?" class="s" id="s" type="search" name="s">
        <input class="search-submit" type="submit" value="Search">
    </div>
</form>
