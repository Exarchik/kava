<h2>{$caption}</h2>
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
        <th> Керування </th>
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
            <td>
                {foreach $buttons as $actionType => $params}
                {if $params.hide}
                    {continue}
                {/if}
                <a class="action-row" data-action="{$actionType}" data-path="{$path}" data-id="{$values.id}" href="#"><i style="{if $params.color}color:{$params.color};{/if}" class="fa kava-icon {if $params.icon}{$params.icon}{else}fa-info{/if}"></i></a>&nbsp;
                {/foreach}
            </td>
        </tr>    
        {/foreach}
    </tbody>
</table>