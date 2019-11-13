<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="input-{$field}">{$params.caption}:</label>
    <div class="col-sm-2">
        <img src="{$params.params.baselink}{$value}" />
    </div>
    <div class="col-sm-8">
        <span class="errors error-{$field}"></span>
        <input class="form-control" autocomplete="off" id="input-{$field}" type="text" placeholder="{$params.caption}" value="{$value}"/>
    </div>
</div>