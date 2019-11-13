<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="input-{$field}">{$params.caption}:</label>
    <div class="col-sm-10 form-check" style="text-align: left; font-size: 22px;">
        <span class="errors error-{$field}"></span>
        <input class="form-check-input" id="input-{$field}" name="input-{$field}" type="checkbox" {if $value == true}checked{/if} value="1"/>
    </div>
</div>