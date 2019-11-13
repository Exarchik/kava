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
                <a class="edit-row" data-path="{$path}" data-id="{$values.id}" href="#"><i style="color:blue;" class="fa kava-icon fa-edit"></i></a>&nbsp;
                <a class="delete-row" data-path="{$path}" data-id="{$values.id}" href="#"><i style="color:red;" class="fa kava-icon fa-remove"></i></a>
            </td>
        </tr>    
        {/foreach}
    </tbody>
</table>