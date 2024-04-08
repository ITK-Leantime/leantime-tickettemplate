@extends($layout)

@section('content')

    <x-global::pageheader :icon="'fa fa-puzzle-piece'">
        <h1>TicketTemplate: Create</h1>
    </x-global::pageheader>

    <div class="maincontent">
        <?php echo $tpl->displayNotification(); ?>
        <div class="maincontentinner">
            <div class="row" style="margin-left: 0; margin-right: 0;">
                <div class="column">
                    <h4 class="widgettitle title-light"><span class="fa fa-cog"></span>
                        Create template
                    </h4>
                </div>
                <div class="column">
                    <div class="" style="float:right;">
                        <a href="<?php echo BASE_URL; ?>/TicketTemplate/listTemplates" class="btn btn-primary"><i class="fa-solid fa-xmark"></i> Cancel</a>
                    </div>
                </div>
            </div>

            <form method="post" action="<?php echo BASE_URL; ?>/TicketTemplate/createTemplate">
                <div>
                    <label for="title">
                        Title
                    </label>
                    <input type="text" id="title" name="title" required/> <br>
                    <label for="content">
                        Content
                    </label>
                    <textarea name="content" id="content" style="height: 220px; width: 1000px;" required></textarea> <br>
                    <input type="submit" value="Save" id="saveBtn" />
                </div>
            </form>
        </div>
    </div>
@endsection
