<div class="row">
    <div class="col col-sm-10">
        <h2 class="head-caption">{$caption}</h2>
    </div>
    <div class="col col-sm-2" style="text-align: right;" id="buttons-in-head">
        {foreach $buttons as $actionType => $params}
            {if $params['for-head'] is set}
            {set $ajaxed = (isset($params['not-ajax']) ? 'false' : 'true')}
            {set $_action = (isset($params['action']) ? $params['action'] : $actionType)}
        <div class="btn btn-success action-row" data-action="{$_action}" data-path="{$path}" data-ajaxed="{$ajaxed}">{$params.caption}</div>
            {/if}
        {/foreach}
    </div>
</div>
<table id="generated-table" class="table table-bordered">
    <thead>
        <tr>
        <th> # </th>
        {foreach $fields as $key => $value}
            {if $value.type in ['primary','hidden']}
                {continue}
            {/if}
            <th> {$value.caption} </th>
        {/foreach}
        {if !empty($buttons)}<th> Керування </th>{/if}
        </tr>
    </thead>
    <tbody>
        {set $iterator=0}
        {foreach $data as $key => $values}
        <tr>
            <td> {(++$iterator)} </td>
            {foreach $fields as $field => $value}
                {if $value.type in ['primary','hidden']}
                    {continue}
                {/if}
                <td> {$values[$field]} </td>
            {/foreach}
            {if !empty($buttons)}
            <td>
                {foreach $buttons as $actionType => $params}
                    {if $params['for-head'] is not set}
                    {set $ajaxed = (isset($params['not-ajax']) ? 'false' : 'true')}
                    {set $_action = (isset($params['action']) ? $params['action'] : $actionType)}
                <a class="action-row" data-action="{$_action}" data-path="{$path}" data-ajaxed="{$ajaxed}" data-index="{$values[$indexField]}" href="#"><i style="{if $params.color}color:{$params.color};{/if}" class="fa kava-icon {if $params.icon}{$params.icon}{else}fa-info{/if}"></i></a>&nbsp;
                    {/if}
                {/foreach}
            </td>
            {/if}
        </tr>    
        {/foreach}
    </tbody>
</table>