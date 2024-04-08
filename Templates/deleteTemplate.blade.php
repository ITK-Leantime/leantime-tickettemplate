@extends($layout)

@section('content')

    <x-global::pageheader :icon="'fa fa-puzzle-piece'">
        <h1>TicketTemplate: Delete</h1>
    </x-global::pageheader>

    <div class="maincontent">
        <div class="maincontentinner">
            <?php echo $tpl->displayNotification() ?>
            <h4 class="widget widgettitle"><span class="fa fa-trash"></span>
                Delete template
            </h4>
            <div class="widgetcontent">
                <form method="post">
                    <p>Are you sure you want to delete this template? Any projects using this template will have its ticket template reset.</p><br />
                    <input type="submit" value="Yes" name="del" class="button" />
                    <a class="btn btn-primary" href="<?php echo BASE_URL; ?>/TicketTemplate/listTemplates">Back</a>
                </form>
            </div>
        </div>
    </div>

@endsection
