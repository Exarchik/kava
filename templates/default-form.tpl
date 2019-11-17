<form id="admin-form" data-path="{$path}">
<div class="default-admin-form row">
    {foreach $data as $key => $value}
        {$value}
    {/foreach}
</div>
</form>
<div class="form-buttons">
    <div class="btn btn-success send-form">Зберегти</div>
    <div class="btn btn-warning close-form">Відмінити</div>
</div>