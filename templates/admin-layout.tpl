<body style="padding-bottom: 50px;">
<div class="darkness">&nbsp;</div>
<div class="kava-loader" style="display:none;" ><img src='elements-images/bg/img_loader.gif'></div>
<div class="kava-msg kava-admin-form" style="display:none;">
	<div class="kava-msg-block">
		<div id="basic-form"></div>
	</div>
</div>
<div class="admin container-fluid">
{$menu}
{$content}
{if ($.get.test is set)}
    <pre>
    {$info}
    </pre>
{/if}
</div>
<script type="text/javascript" >
    var admin_base_link = '{$.const.ADMIN_LINK}';
</script>
<script src="js/jquery-2.1.4.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/air-datepicker.js"></script>
<script src="js/admin.js"></script>
<script type="text/javascript" src="js/datatables.min.js"></script>
<!-- Optional theme -->
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/font-awesome.css">
<link rel="stylesheet" href="css/kava.css">
<link rel="stylesheet" href="css/admin.css">
<link rel="stylesheet" href="css/jquery-ui.css">
<link rel="stylesheet" href="css/air-datepicker.css">

<link rel="stylesheet" type="text/css" href="js/datatables.min.css"/>
<link rel="stylesheet" type="text/css" href="js/DataTables-1.10.20/css/dataTables.bootstrap.min.css"/>


<!-- Latest compiled and minified JavaScript -->
<script src="js/bootstrap.js"></script>

<script type="text/javascript">
{ignore}
    jQuery(document).ready(function() {
        if (jQuery('#generated-table').length) {
            jQuery('#generated-table').DataTable({
                stateSave: true,
                pageLength: 12,
                lengthMenu: [[10, 12, 15, 25, 50, -1], [10, 12, 15, 25, 50, "All"]],
                "language": {
                    "search" : "Пошук:",
                    "lengthMenu": "Показувати _MENU_ позицій на сторінку",
                    "zeroRecords": "Нажаль нічого не знайдено",
                    "info": "Сторінка _PAGE_ з _PAGES_",
                    "infoEmpty": "Немає доступних записів",
                    "infoFiltered": "(відфільтровано з _MAX_ записів)",
                    "paginate": {
                        "first": "Перший",
                        "last": "Останній",
                        "next": "<i class='fa fa-chevron-right'></i>",
                        "previous": "<i class='fa fa-chevron-left'></i>"
                    },
                }
            });
        }
    });
{/ignore}
</script>
</body>