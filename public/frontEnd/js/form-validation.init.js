$(document).ready(function(){
    if($(".parsley-examples").length > 0) {
        $(".parsley-examples").parsley();
    }
});

$(function(){
    var demoForm = $("#demo-form");
    if(demoForm.length > 0) {
        demoForm.parsley().on("field:validated",function(){
            var e = 0 === $(".parsley-error").length;
            $(".alert-info").toggleClass("d-none",!e);
            $(".alert-warning").toggleClass("d-none",e);
        }).on("form:submit",function(){
            return !1;
        });
    }
});