<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="input-{$field}">{$params.caption}:</label>
    <div class="col-sm-10">
        <span class="errors error-{$field}"></span>
        <input class="form-control" autocomplete="off" id="input-{$field}" type="text" placeholder="{$params.caption}" value="{$value}"/>
    </div>
</div>