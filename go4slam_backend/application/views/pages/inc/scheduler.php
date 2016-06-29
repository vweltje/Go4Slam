<div class="well" id="scheduler">
    <input type="checkbox" class="form-control" name="instant_publish" value="false" <?= set_value('instant_publish', isset($scheduled) ? $scheduled ? 'checked' : '' : 'checked'); ?>><label style="margin-left:5px;">scheduler</label>
    <div id="scheduler-fields">
        <div class="form-group">
            <label for="short_description">Publish from</label>
            <input type="text" class="form-control datetimepicker" name="publish_from" value="<?= set_value('publish_from', isset($publish_from) ? $publish_from : ''); ?>">
        </div>
        <div class="form-group">
            <label for="short_description">Publish till</label>
            <input type="text" class="form-control datetimepicker" name="publish_till" value="<?= set_value('publish_till', isset($publish_till) ? $publish_till : ''); ?>">
        </div>
    </div>
</div>