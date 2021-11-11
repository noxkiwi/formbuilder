<?php declare(strict_types = 1);
namespace noxkiwi\formbuilder;

/** @var \noxkiwi\formbuilder\Form $data */

use noxkiwi\core\Helper\WebHelper;

?>
<form enctype="multipart/form-data"
      accept-charset="UTF-8"
      id="<?= $data->getConfig()->get('form_id') ?>"
      action="<?= $data->getConfig()->get('form_action') ?>"
      method="<?= $data->getConfig()->get('form_method', WebHelper::METHOD_POST) ?>"
      data-target="<?= $data->getConfig()->get('form_target', '') ?>"
      class="rabidForm separate-sections"
>
    <div class="rabidFormResult">
    </div>
    <?php
    foreach ($data->getFields() as $field) {
        echo $field->output();
    }
    ?>
</form>
<script type="text/javascript">
    $(document).ready(function () {
        $(".rabidForm").bind("submit", function () {
            // I am the HTML form element.
            let myForm = this;

            // I am the response container.
            let myResponseContainer = $(myForm).find(".rabidFormResult");

            // I am the pointer to the target.
            let myTargetPointer = $(myForm).data("target");

            // If you added a differing target, use it.
            if (myTargetPointer.length !== 0) {
                myResponseContainer = $(myTargetPointer);
            }

            // I am the form data we will send.
            var formData = new FormData(myForm);
            formData.append("template", "json");
            formData.append("formSent", 1);

            $(myForm).find("input, textarea, select, button").prop("disabled", true);

            $.ajax({
                async       : true,
                url         : $(myForm).attr("action"),
                type        : $(myForm).attr("method"),
                data        : formData,
                cache       : false,
                contentType : false,
                dataType    : "json",
                processData : false,
                success     : function (data) {
                    $(myResponseContainer).empty().append(data.content);
                    $(myResponseContainer).show(1000, function () {
                        $(myForm).find("input, textarea, select, button").prop("disabled", false);
                    });
                }
            });
            return false;
        });
    });

</script>
