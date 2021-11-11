<?php declare(strict_types = 1);
namespace noxkiwi\formbuilder;
use noxkiwi\core\Path;

/** @var array $data */
?>
<script type="text/javascript" src="<?= Path::$resourceHost ?>/assets/plugins/dropzone/dropzone.js"></script>
<script type="text/javascript"
        src="<?= Path::$resourceHost ?>/assets/plugins/jquery.query-object/jquery.query-object.js"></script>
<link rel="stylesheet" type="text/css" href="<?= Path::$resourceHost ?>/assets/plugins/dropzone/dropzone.css"/>
<div class="form-group">
    <label
            title="<?= $data['field_description'] ?>"
            for="<?= $data['field_id'] ?>"
            class="control-label col-lg-3">
        <?= $data['field_title'] ?><?php if ($data['field_required']) { ?> <abbr
                title="Dieses Feld muss angegeben werden">(*)</abbr><?php } ?>
    </label>
    <div class="col-lg-9">
        <!--
      class="validate[<?= $data['field_validator'] ?>] form-control <?= $data['field_class'] ?>"
      data-datatype="<?= $data['field_validator'] ?>"
    -->
        <!--
        <input
            id="<?= $data['field_id'] ?>"
            name="<?= $data['field_name'] ?>"
            value="<?= $data['value'] ?>"
            type="file"
            title="<?= $data['field_title'] ?>"
            class="dropzone"
            data-placeholder="<?= $data['field_placeholder'] ?>"
            data-description="<?= $data['field_description'] ?>"
            data-prompt-position="topLeft"
            <?php if ($data['field_readonly']) {
            echo 'readonly';
        } ?>
            <?php if ($data['field_multiple']) {
            echo 'multiple';
        } ?>
        />
-->
        <div class="">
            <div class="dropzone" id="<?= $data['field_id'] ?>">
                <span class="resetwarning"></span>
            </div>
            <br/>
            <span class="label label-success">Dateiformate</span>
            <span><?= $data['field_fileformats'] ?></span>
            <br/>
            <span>Max. Dateigröße</span>
            <span><?= $data['field_filesizemax'] ?> MB</span>
        </div>
        <!--
        'field_filecountmax' => $this->config->fieldOptions['file_maxcount'],
        'field_filesizemax' => $this->config->fieldOptions['file_maxsize'],
        'field_fileformats' => $this->config->fieldOptions['file_formats'],
        -->
        <script>
            $(document).ready(function () {
                initDropzone();
                // <?php echo var_export($data, true); ?>
                function initDropzone() {
                    Dropzone.autoDiscover = false;
                    var tempArr           = [],
                        exts              = "<?=$data['field_fileformats']?>".split(",");
                    for (var i = 0, l = exts.length; i < l; i++) {
                        tempArr[i] = "." + exts[i];
                    }
                    var acceptedFileExts = tempArr.join(",");
                    var dzUrl            = $('[id="<?=$data['field_id']?>"]').closest("form").attr("action");
                    <?php if ($data['field_ajax'] === true) {
                    ?>
                    dzUrl = dzUrl.split("?").length > 1 ? dzUrl.split("?")[1] : "";
                    dzUrl = $.query.parseNew(dzUrl, 'action=updatecomponent&field_id=<?=$data['field_name']?>');
                    <?php
                        } ?>;console.log("dzUrl: " + dzUrl);
                    var dz_instance               = new Dropzone('[id="<?=$data['field_id']?>"]', {
                        url                          : dzUrl, // paramName: "contact[file]",
                        paramName                    : "<?=$data['field_name']?>",
                        maxFilesize                  : <?=$data['field_filesizemax']?>, // Megabyte
                        addRemoveLinks               : true,
                        uploadMultiple               : true, // depending on multiple setting...
                        autoProcessQueue             : <?php echo $data['field_ajax'] === true ? 'true' : 'false'; ?>,
                        maxFiles                     : <?php if ($data['field_multiple']) {
                            echo $data['field_filecountmax'];
                        } else {
                            echo '1';
                        } ?>,
                        acceptedFiles                : acceptedFileExts,
                        fallbackSubmitButton         : false,
                        dictDefaultMessage           : "Dateien zum Hochladen hier ablegen",
                        dictFallbackMessage          : "Ihr Browser unterstützt keine Drag'n'Drop Dateiuploads.",
                        dictFallbackText             : "Bitte benutzen Sie das Datei-Auswahlfeld.",
                        dictInvalidFileType          : "Ungültiges Dateiformat",
                        dictFileTooBig               : "Datei zu groß",
                        dictResponseError            : "Fehler beim Hochladen",
                        dictCancelUpload             : "Abbrechen",
                        dictCancelUploadConfirmation : "entfernt",
                        dictRemoveFile               : "Entfernen",
                        dictMaxFilesExceeded         : "Maximale Dateianzahl erreicht!"
                    });
                    var dz_instance_progressIndicator;
                    dz_instance_progressIndicator = dz_instance_progressIndicator || (function () {
                        var pleaseWaitDiv = $("<div class=\"modal fade bs-example-modal-sm\" id=\"myPleaseWait\" tabindex=\"-1\" role=\"dialog\" aria-hidden=\"true\" data-backdrop=\"static\"><div class=\"modal-dialog modal-sm\"><div class=\"modal-content\"><div class=\"modal-header\"><h4 class=\"modal-title\"><span class=\"glyphicon glyphicon-time\"></span>&nbsp;Bitte warten...</h4></div><div class=\"modal-body\"><div class=\"progress\"><div id=\"upload-progressbar\" class=\"progress-bar progress-bar-info progress-bar-striped active\" style=\"width: 100%\"></div></div></div></div></div></div>");
                        return {
                            showPleaseWait : function () {
                                pleaseWaitDiv.modal();
                            },
                            hidePleaseWait : function () {
                                pleaseWaitDiv.modal("hide");
                            }
                        };
                    })();
                    try {
                        dz_instance.on("sending", function (file, xhr, formData) {
                            console.log("Form:dz::on::sending");
                            console.log(xhr);
                            console.log(formData);
                            <?php if ($data['field_ajax'] !== true) { ?>
                            appendFormData_dz_instance(formData);
                            <?php } ?>
                        });
                        dz_instance.on("success", function (file, responseText) {
                            console.log("Form:dz::on::success");
                            <?php if ( $data['field_ajax'] !== true ) { ?>
                            success_instance(responseText);
                            <?php } ?>
                        });
                        dz_instance.on("addedfile", function (file) {
                            $("#<?=$data['field_id']?> span.resetwarning").html("");
                        });
                        dz_instance.on("uploadprogress", function (file, progress, bytesSent) {
                            $("#upload-progressbar").css("width", progress + "%");
                        });
                    } catch (e) {
                        console.log(e);
                    }
                    ;
                    <?php if ($data['field_ajax'] !== true) { ?>
                    $("input[type=\"submit\"]").on("click", function (e) {
                        e.preventDefault();
                        dz_instance_progressIndicator.showPleaseWait();
                        if (typeof dz_instance.getQueuedFiles !== "function") {
                            console.log("typeof dz_instance.getQueuedFiles !== 'function'");
                        }
                        if (typeof dz_instance.getQueuedFiles !== "function" || (dz_instance.getQueuedFiles().length === 0 && dz_instance.getUploadingFiles().length === 0)) {
                            console.log("Form:std::sendFormData");
                            sendFormData_instance(this);
                        } else {
                            console.log("Form:dz::processQueue");
                            dz_instance.processQueue();
                        }
                    });
                    <?php } ?>
                    function appendFormData_dz_instance(fdata) {
                        var inputs = $('[id="<?=$data['field_id']?>"]').closest("form").serialize[];
                        $.each(inputs, function (i, input) {
                            console.log("appendFormData_dz_instance: appending formData input " + input.name + ", " + input.value);
                            fdata.append(input.name, input.value);
                        });
                    }

                    function sendFormData_instance(sender) {
                        var form = $(sender).closest("form");
                        // var form = $('[id="<?=$data['field_id']?>"]').closest('form');
                        var url = form.attr("action");
                        var fd  = new FormData(form[0]);
                        fd.append(sender.name, sender.value);
                        console.log(form[0]);
                        if (dz_instance.getQueuedFiles !== "function") {
                            $.each(form.find("input[type=file]"), function (i, obj) {
                                var name = $(obj).attr("name");
                                $.each(obj.files, function (j, file) {
                                    console.log("sendFormData_instance: appending formData file " + name + "[" + j + "]");
                                    fd.append(name + "[" + j + "]", file);
                                });
                            });
                        } else {
                            console.log("WARNING: dz_instance.getQueuedFiles === 'function'");
                        }
                        $.ajax({
                            url         : url,
                            data        : fd,
                            processData : false,
                            contentType : false,
                            type        : "POST",
                            success     : function (data) {
                                success_instance(data);
                            },
                            error       : function (jqXHR, textStatus, errorThrown) {
                                dz_instance_progressIndicator.hidePleaseWait();
                            }
                        });
                    }

                    function success_instance(response) {
                        // console.log(response);
                        // console.log($(response).find("div.main-content"));
                        if (typeof $("#content", response)[0] === "undefined") {
                            var newDoc = document.open("text/html", "replace");
                            newDoc.write(response);
                            newDoc.close();
                        } else {
                            var newContent = $("#content", response)[0].innerHTML;
                            if (newContent !== null) {
                                $("#content")[0].innerHTML = newContent;
                                if ($("#<?=$data['field_id']?>").length) {
                                    initDropzone();
                                    $(".chzn-select").select2();
                                    // init other stuff?
                                    $("#<?=$data['field_id']?> span.resetwarning").html("<span class=\"label label-danger\">Feld wurde zurückgesetzt</span>");
                                    $("#<?=$data['field_id']?>").one("click", function () {
                                        $("#<?=$data['field_id']?> span.resetwarning").html("");
                                    });
                                }
                            } else {
                                console.log("Form::EmptyResponseContentWarning");
                            }
                            dz_instance_progressIndicator.hidePleaseWait();
                            console.log("success. what to do now?");
                        }
                    }
                };
            });
        </script>
    </div>
</div>
