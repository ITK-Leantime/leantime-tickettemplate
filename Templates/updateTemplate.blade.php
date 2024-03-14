@extends($layout)

@section('content')

    <x-global::pageheader :icon="'fa fa-puzzle-piece'">
        <h1>{{ __("tickettemplate.update.page_header") }}</h1>
    </x-global::pageheader>

    <div class="maincontent">
        <?php echo $tpl->displayNotification(); ?>
        <div class="maincontentinner">
            <div class="row" style="margin-left: 0; margin-right: 0;">
                <div class="column">
                    <h4 class="widgettitle title-light"><span class="fa fa-cog"></span>
                        {{ __("tickettemplate.update.widget_title") }}
                    </h4>
                </div>
                <div class="column">
                    <div class="" style="float:right;">
                        <a href="<?php echo BASE_URL; ?>/DefaultTicketTemplate/listTemplates" class="btn btn-primary"><i class="fa-solid fa-xmark"></i> {{ __('buttons.cancel') }}</a>
                    </div>
                </div>
            </div>

            <form method="post" action="<?php echo BASE_URL; ?>/DefaultTicketTemplate/updateTemplate/<?php echo $tpl->get('template')['id']; ?>">
                <div>
                    <input type="hidden" id="id" name="id" value="<?php echo $tpl->get('template')['id']; ?>" required/> <br>

                    <label for="title">
                        {{__('tickettemplate.create.template_title')}}
                    </label>
                    <input type="text" id="title" name="title" value="<?php echo $tpl->get('template')['title']; ?>" required/> <br>

                    <label for="content">
                        {{__('tickettemplate.create.template_content')}}
                    </label>
                    <textarea name="content" id="content" style="height: 220px; width: 1000px;" required><?php echo $tpl->get('template')['content']; ?></textarea> <br>

                    <input type="submit" value="{{ __('buttons.save') }}" id="saveBtn" />
                </div>
            </form>
        </div>
    </div>
@endsection
