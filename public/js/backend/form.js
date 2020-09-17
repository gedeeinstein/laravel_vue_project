;(function ($, document, window, undefined) {


    // Parsley
    $('[data-parsley]').parsley({
        uiEnabled: true,
        errorClass: 'is-invalid',
        successClass: 'is-valid'
    })

    // set validation for each items

    // $.validator.setDefaults({
    //     submitHandler: function () {
    //         alert("submitted!");
    //     }
    // });

    // $("#admin-form").validate({
    //     rules: {
    //         display_name: {
    //             required: true,
    //             minlength: 5
    //         },
    //         password: {
    //             required: true,
    //             minlength: 8
    //         },
    //         email: {
    //             required: true,
    //             email: true
    //         },
    //     },
    //     messages: {
    //         display_name: {
    //             required: "Please enter a name",
    //             minlength: "Your username must consist of at least 5 characters"
    //         },
    //         password: {
    //             required: "Please provide a password",
    //             minlength: "Your password must be at least 8 characters long"
    //         },
    //         email: "Please enter a valid email address",
    //     },
    //     errorElement: "em",
    //     errorPlacement: function (error, element) {
    //         // Add the `help-block` class to the error element
    //         error.addClass("help-block");

    //         // Add `has-feedback` class to the parent div.form-group
    //         // in order to add icons to inputs
    //         element.parents(".col-content").addClass("has-feedback");

    //         if (element.prop("type") === "checkbox") {
    //             error.insertAfter(element.parent("label"));
    //         } else {
    //             error.insertAfter(element);
    //         }

    //         console.log($(element).find("input").addClass('wwawan'));
    //         // Add the span element, if doesn't exists, and apply the icon classes to it.
    //         // if (!element.next("input")[0]) {
    //             // $("<span class='glyphicon glyphicon-remove form-control-feedback is-invalid'></span>").insertAfter(element);
    //         // }

    //     },
    //     success: function (label, element) {
    //         // Add the span element, if doesn't exists, and apply the icon classes to it.

    //         if (!$(element).next("input")[0]) {
    //             // $("<span class='glyphicon glyphicon-ok form-control-feedback'></span>").insertAfter($(element));
    //         }
    //     },
    //     highlight: function (element, errorClass, validClass) {
    //         $(element).parents(".col-content").addClass("has-error").removeClass("has-success");
    //         $(element).next(".col-content").find('.form-control').addClass("is-invalid");
    //         // $(element).next("input").addClass("is-invalid").removeClass("glyphicon-ok");
    //     },
    //     unhighlight: function (element, errorClass, validClass) {
    //         $(element).parents(".col-content").addClass("has-success").removeClass("has-error");
    //         $(element).next(".col-content").find('.form-control').addClass("is-invalid").removeClass("glyphicon-ok");
    //         // $(element).next("input").addClass("glyphicon-ok").removeClass("is-invalid");
    //     }
    // });

    // $("#signupForm1").validate({
    //     rules: {
    //         firstname1: "required",
    //         lastname1: "required",
    //         username1: {
    //             required: true,
    //             minlength: 2
    //         },
    //         password1: {
    //             required: true,
    //             minlength: 5
    //         },
    //         confirm_password1: {
    //             required: true,
    //             minlength: 5,
    //             equalTo: "#password1"
    //         },
    //         email1: {
    //             required: true,
    //             email: true
    //         },
    //         agree1: "required"
    //     },
    //     messages: {
    //         firstname1: "Please enter your firstname",
    //         lastname1: "Please enter your lastname",
    //         username1: {
    //             required: "Please enter a username",
    //             minlength: "Your username must consist of at least 2 characters"
    //         },
    //         password1: {
    //             required: "Please provide a password",
    //             minlength: "Your password must be at least 5 characters long"
    //         },
    //         confirm_password1: {
    //             required: "Please provide a password",
    //             minlength: "Your password must be at least 5 characters long",
    //             equalTo: "Please enter the same password as above"
    //         },
    //         email1: "Please enter a valid email address",
    //         agree1: "Please accept our policy"
    //     },
    //     errorElement: "em",
    //     errorPlacement: function (error, element) {
    //         // Add the `help-block` class to the error element
    //         error.addClass("help-block");
    //
    //         // Add `has-feedback` class to the parent div.form-group
    //         // in order to add icons to inputs
    //         element.parents(".col-sm-5").addClass("has-feedback");
    //
    //         if (element.prop("type") === "checkbox") {
    //             error.insertAfter(element.parent("label"));
    //         } else {
    //             error.insertAfter(element);
    //         }
    //
    //         // Add the span element, if doesn't exists, and apply the icon classes to it.
    //         if (!element.next("span")[0]) {
    //             $("<span class='glyphicon glyphicon-remove form-control-feedback'></span>").insertAfter(element);
    //         }
    //     },
    //     success: function (label, element) {
    //         // Add the span element, if doesn't exists, and apply the icon classes to it.
    //         if (!$(element).next("span")[0]) {
    //             $("<span class='glyphicon glyphicon-ok form-control-feedback'></span>").insertAfter($(element));
    //         }
    //     },
    //     highlight: function (element, errorClass, validClass) {
    //         $(element).parents(".col-sm-5").addClass("has-error").removeClass("has-success");
    //         $(element).next("span").addClass("glyphicon-remove").removeClass("glyphicon-ok");
    //     },
    //     unhighlight: function (element, errorClass, validClass) {
    //         $(element).parents(".col-sm-5").addClass("has-success").removeClass("has-error");
    //         $(element).next("span").addClass("glyphicon-ok").removeClass("glyphicon-remove");
    //     }
    // });


    // $('#admin-form').validationEngine('attach', {
    //     promptPosition:"inline",
    //     maxErrorsPerField: 1,
    //     showOneMessage : true ,
    //     scroll: true,
    //     scrollOffset: 200
    // });
    // $('#country-email-form').validationEngine('attach', {
    //     promptPosition:"inline",
    //     maxErrorsPerField: 1,
    //     showOneMessage : true ,
    //     scroll: true,
    //     scrollOffset: 200
    // });
    // $('#customer-experience-form').validationEngine('attach', {
    //     promptPosition:"inline",
    //     maxErrorsPerField: 1,
    //     showOneMessage : true ,
    //     scroll: true,
    //     scrollOffset: 200
    // })
    // $('#campaign-form').validationEngine('attach', {
    //     promptPosition:"inline",
    //     maxErrorsPerField: 1,
    //     showOneMessage : true ,
    //     scroll: true,
    //     url:'true',
    //     scrollOffset: 200
    // });

}(jQuery, document, window));
