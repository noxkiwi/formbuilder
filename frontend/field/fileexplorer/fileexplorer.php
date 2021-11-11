<?php declare(strict_types = 1);
namespace noxkiwi\formbuilder;

use noxkiwi\core\Helper\JsonHelper;
use noxkiwi\core\Path;
use function uniqid;

/** @var array $data */
?>
<script type="text/javascript"
        src="<?= Path::$resourceHost ?>/assets/plugins/jqueryfiletree/jQueryFileTree.min.js"></script>
<link rel="stylesheet" type="text/css"
      href="<?= Path::$resourceHost ?>/assets/plugins/jqueryfiletree/jQueryFileTree.min.css"/>
<style>
    .fileexplorer {
        border     : 1px solid #ccc;
        padding    : 6px;
        box-shadow : inset 1px 2px 3px rgba(0, 0, 0, 0.1);
    }
</style>
<div class="form-group">
    <label
            title="<?= $data['field_description'] ?>"
            for="<?= $data['field_id'] ?>"
            class="control-label col-lg-3">
        <?= $data['field_title'] ?><?php if ($data['field_required']) { ?> <abbr
                title="Dieses Feld muss angegeben werden">(*)</abbr><?php } ?>
    </label>
    <div class="col-lg-9">
        <div id="fileexplorer-data-<?= $data['field_id'] ?>">
            <!--[CDATA[
          <?php
            if (is_callable($data['field_fileexplorerdata'])) {
                $explorerData = $data['field_fileexplorerdata']();
            } else {
                $explorerData = $data['field_fileexplorerdata'];
            }
            echo JsonHelper::encode($explorerData);
            ?>
          ]]-->
        </div>
        <div id="fileexplorer-<?= $data['field_id'] ?>" class="fileexplorer">
        </div>
        <script>
            $(document).ready(function () {
                var fileexplorerJson = getCDATAfromElement('#fileexplorer-data-<?=$data['field_id']?>');
                var fileexplorerData = JSON.parse(fileexplorerJson);
                $('#fileexplorer-<?=$data['field_id']?>').fileTree({
                    root          : "/",
                    script        : function (data) {
                        /*
                         echo "<li class='directory collapsed'>{$checkbox}<a rel='" .$htmlRel. "/'>" . $htmlName . "</a></li>";
                         else if (!$onlyFolders || $onlyFiles)
                         echo "<li class='file ext_{$ext}'>{$checkbox}<a rel='" . $htmlRel . "'>" . $htmlName . "</a></li>";
                         */
                        // data.dir is request dirname.
                        console.log("scriptcall tree/dir: " + data.dir);
                        if (typeof fileexplorerData[data.dir] === "undefined") {
                            return "";
                        }
                        var dirdata  = fileexplorerData[data.dir];
                        var result   = "<ul class='jqueryFileTree'>";
                        var checkbox = "";
                        if (data.multiSelect) {
                            checkbox = ""; //"<input type='checkbox' name='<?=$data['field_name']?>[]' />";
                        }
                        $.each(dirdata, function (index, value) {
                            //
                            if (data.multiSelect) {
                                checkbox = "<input type='checkbox' name='<?=$data['field_name']?>[]' value='" + value.name + "' />";
                            }
                            console.log("data.onlyFolders: " + data.onlyFolders + " data.onlyFiles: " + data.onlyFiles + " value:" + value.name + " (" + value.type + ")");
                            if (! data.onlyFiles && value.type === "dir") {
                                result += "<li class='directory collapsed'>" + checkbox + "<a rel='" + data.dir + value.name + "/'>" + value.name + "</a></li>";
                            }
                            if (! data.onlyFolders && value.type === "file") {
                                var extSplit = value.name.split(".");
                                var ext      = "";
                                if (extSplit.length > 1) {
                                    ext = extSplit[extSplit.length - 1];
                                }
                                result += "<li class='file ext_" + ext + "'>" + checkbox + "<a rel='" + value.url + "'>" + value.name + "</a></li>";
                            }
                        });
                        result += "</ul>";
                        return result;
                    },
                    expandSpeed   : 1000,
                    collapseSpeed : 1000,
                    multiFolder   : false,
                    multiSelect   : true
                }, function (file) {
                    // WHAT TO DO ON FILE CLICK ?
                    // alert(file);
                    var modalSelector = '#filemodal-<?=$data['field_id']?>';
                    $(modalSelector).find("object").attr("data", file);
                    $(modalSelector).find("a.download-link").attr("href", file + '&downloadoption=<?=Bucket::DOWNLOAD_FORCE?>');
                    $(modalSelector).modal("show");
                }).on("filetreeclicked", function (e, data) {
                    console.log(data);
                });

                function getCDATAfromElement(selector) {
                    // Add additional components
                    // with Formbuilder.registerField();
                    var cdataStart = "<!--[CDATA[";
                    var cdataEnd   = "]]-->";
                    var cdata      = $(selector).html();
                    var first      = cdata.indexOf(cdataStart);
                    var cdata      = cdata.substring(first + cdataStart.length);
                    var last       = cdata.lastIndexOf(cdataEnd);
                    var content    = cdata.substring(0, last).trim();
                    return content;
                }
            });
        </script>
        <script>
            function sizeModal_<?=uniqid($data['field_id'], false)?>(mode, animate) {
                animate       = typeof animate === "undefined" ? true : animate;
                var modal     = $('#filemodal-<?=$data['field_id']?>');
                var dialog    = modal.find(".modal-dialog");
                var oldWidth  = dialog.width();
                var oldHeight = dialog.height();
                if (mode === "larger") {
                    if (animate) {
                        dialog.animate({
                            width  : "+=10%",
                            height : "+=10%"
                        }, 300, function () {
                            // complete
                        });
                    } else {
                        dialog.width(oldWidth * 1.1);
                        dialog.height(oldHeight * 1.1);
                    }
                } else {
                    if (animate) {
                        dialog.animate({
                            width  : "-=10%",
                            height : "-=10%"
                        }, 300, function () {
                            // complete
                        });
                    } else {
                        dialog.width(oldWidth / 1.1);
                        dialog.height(oldHeight / 1.1);
                    }
                }
            }
        </script>
        <!-- Modal -->
        <div class="modal fade" id="filemodal-<?= $data['field_id'] ?>" tabindex="-1" role="dialog"
             aria-labelledby="filemodal-<?= $data['field_id'] ?>-title" aria-hidden="true">
            <div class="modal-dialog" style="width: 80%; height: 80%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="filemodal-<?= $data['field_id'] ?>-title">Preview</h4>
                    </div>
                    <div class="modal-body">
                        <p style="text-align:center; font-size:2em;">
                            <a class="icon-minus-sign"
                               onclick="sizeModal_<?= uniqid($data['field_id'], false) ?>('smaller');"></a>
                            <a class="download-link btn btn-primary" href="">Download</a>
                            <a class="icon-plus-sign" onclick="sizeModal_<?= uniqid($data['field_id'], false) ?>('larger');"></a>
                        </p>
                        <object data="" style="width:100%; height:100%;"></object>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
