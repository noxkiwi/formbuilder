<?php declare(strict_types = 1);
namespace noxkiwi\formbuilder;
?>
<style>
    .diginput {
        width      : 100%;
        text-align : center;
    }

    .diginput > input {
        width         : 30px;
        height        : 30px;
        text-align    : center;
        border-radius : 3px;
        border        : 1px solid #CCC;
    }
</style>

<div class="diginput">
    <input maxlength="1"/>
    <input maxlength="1"/>
    <input maxlength="1"/>
    <input maxlength="1"/>
    <input maxlength="1"/>
    <input maxlength="1"/>
    <input maxlength="1"/>
    <input maxlength="1"/>
</div>
<script>
    var kp   = false;
    var lock = 0;
    var atc  = "";
    var whi  = "0123456789";
    $(".diginput > input").keydown(function () {
        if (kp) {
            return;
        }
        kp = true;
        lock++;
    });
    $(".diginput > input").focus(function () {
        $(this).select();
    });
    $(".diginput > input").keyup(function () {
        kp = false;
        lock--;
        if (lock > 0) {
            return "Other keys pressed down currently";
        }
        let myContent = $(this).val();

        if (typeof (myContent) !== "string") {
            return "content remains empty";
        }

        if (myContent.Length == 0) {
            return "content remains empty";
        }

        if (! whi.includes(myContent)) {
            $(this).val("");
            return "not allowed char";
        }
        atc = atc + myContent.toString();

        let nextInput = $(this).next("input");

        if (typeof (nextInput) !== "object") {
            return "";
        }

        if (nextInput.length > 0) {
            nextInput.focus();
            return;
        }

        login();
    });

    function login() {
        if (atc === "13041991") {
            $(".diginput").remove();
            return;
        }
        location.reload();
    }
</script>
