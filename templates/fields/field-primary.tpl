<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="input-{$field}">{$params.caption}:</label>
    <div class="col-sm-10" style="text-align: left; font-size: 22px;">
        <span class="errors error-{$field}"></span>
        <span id="input-{$field}" >{$value}</span>
        <input class="form-control" name="input-{$field}" type="hidden" value="{$value}"/>
    </div>
</div>