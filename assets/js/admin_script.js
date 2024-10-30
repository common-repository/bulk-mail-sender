jQuery(document).ready(function ($) {

    // close pro banner 
    jQuery("#closeButton").click(function (e) {
        e.preventDefault;
        var imageToShow = document.getElementById('pro_baneer');
        imageToShow.style.display = 'none';
    });

    //Display contact_category
    var cont_category = jQuery('#cont_category_form').DataTable({
        "searching": true,
        "filter": true,
        "processing": true,
        "serverSide": true,
        "bInfo": false,
        "ajax": {
            "url": BMSI_object.ajax_url,
            "type": 'POST',
            "datatype": 'JSON',
            "data": function (d) {
                d.action = 'BMSI_disply_category';
                d.start_date = jQuery('#start').val();
                d.end_date = jQuery('#end').val();
                d.display_contact_category_nonce = BMSI_object.nonce;
            },
            "complete":function(res){
                contact_category_edit_btn();
            },
        },
        language: {
            searchPlaceholder: "Search By Name",
            sLengthMenu: "Show  _MENU_  Entries",
            search: "",
            paginate: {
                "first": "<i class='fa-solid fa-backward'></i>",
                "last": "<i class='fa-solid fa-forward'></i>",
                "next": "<i class='fa-solid fa-caret-right'></i>",
                "previous": "<i class='fa-solid fa-caret-left'></i>"
            },
        },
        "columns": [
            { "data": 'id', sortable: true },
            { "data": 'category_name', sortable: true },
            { "data": 'parent_id', sortable: true },
            {
                data: null,
                className: 'dt-center editor-edit',
                sortable: false,
                render: function (data, type, row) {
                    return '<div id="editbtn_1"><button id="btn_edit"  class="btn_edit" data-eid=' + data.id + '><i class="fa-solid fa-pen"></i></button><button id="btn_delete" class="btn_delete"  data-id=' + data.id + '><i class="fa-solid fa-trash-can"></i></button></div>'
                },

            },

        ],
        success: function (response) {
            jQuery.confirm({
                title: response.title,
                content: response.content,
                theme: 'modern',
                buttons: {
                    ok: {
                        text: 'OK',
                        btnClass: 'add-button',
                        action: function () {
                            window.location.href = BMSI_object.admin_url + "admin.php?page=contact_category";
                        }
                    }
                }
            });
            jQuery(".add-button").css("display", "block");
            jQuery(".spinn").removeClass("add_custome_loader");
            jQuery("#submit_category_form").removeAttr('disabled');
        },

        paging: true,
        pageLength: 10,
        select: true,
        order: [[1, 'asc']],
    });

    //delete data from contact category (ajax)
    jQuery(document).on("click", "#btn_delete", function (e){
        e.preventDefault();
        var taskId = jQuery(this).data('id');
        var element = this;
        jQuery.confirm({
            title: "Alert!",
            content: " Do you really want to delete this data?",
            theme: 'modern',
            buttons: {
                ok: {
                    text: 'OK',
                    btnClass: 'add-button',
                    action: function () { 
                        jQuery.ajax({
                            type: 'POST',
                            url: BMSI_object.ajax_url,
                            data: {
                                id: taskId,
                                action: 'BMSI_delete_category',
                                delete_contact_category_nonce : BMSI_object.nonce,
                            },
                            success: function (data) {
                                if (data == 1) {
                                    jQuery(element).closest("tr").fadeOut();
                                    table.ajax.reload();
                                } else {
                                    alert("Data Not Deleted");
                                }
                                jQuery.confirm({
                                    title: "Congratulations!",
                                    content: "Deleted Successfully",
                                    theme: 'modern',
                                    buttons: {
                                        ok: {
                                            text: 'OK',
                                            btnClass: 'add-button',
                                            action: function () {
                                                return
                                            }
                                        }
                                    }
                                });
                                jQuery(".add-button").css("display", "block");
                                jQuery(".spinn").removeClass("add_custome_loader");
                                jQuery("#btn_delete").removeAttr('disabled');
                            },
                        });
                    }
                },
                cancel: {
                    text: 'Cancel',
                    btnClass: 'add-button',
                }
            }
        })
    });


    //edit ajax for contact category
   
      function  contact_category_edit_btn(){
        jQuery('.btn_edit').click(function (e) {
            e.preventDefault();
            var cont_id = jQuery(this).data("eid");
            location.replace(BMSI_object.admin_url+"/admin.php?page=file_contact_category&&eid=" + cont_id);
        })
    }
   

    //update ajax for contact category

    jQuery("#update_category_form").click(function (e) {
        e.preventDefault();

        jQuery(".btn-txt").css("display", "none");
        jQuery(".spinn").addClass("add_custome_loader");
        jQuery(".add-button").attr('disabled', 'disabled');

        var parent_id = jQuery("#parent_category").val();
        var category_name = jQuery("#category_name").val();
        var eid = jQuery("#category_id").val();
        $.ajax({
            type: 'POST',
            url: BMSI_object.ajax_url,
            datatype: 'JSON',
            data: {
                action: "BMSI_add_contact_category",
                category_id: eid,
                parent_category: parent_id,
                category_name: category_name,
                create_contact_category_nonce: BMSI_object.nonce,
            },
            success: function (response) {
                jQuery.confirm({
                    title: "Congratulations!",
                    content: "Category Updated Successfully ",
                    theme: 'modern',
                    buttons: {
                        ok: {
                            text: 'OK',
                            btnClass: 'add-button',
                            action: function () {
                                window.location.href = BMSI_object.admin_url + "admin.php?page=contact_category";
                            }
                        }
                    }
                });
                jQuery(".add-button").css("display", "block");
                jQuery(".spinn").removeClass("add_custome_loader");
                jQuery("#update_category_form").removeAttr('disabled');
            }, error: function (jqXHR, textStatus, errorThrown) {
                if (textStatus === 'timeout') {
                    jQuery.confirm({
                        title: "Alert!",
                        content: "The request timed out. Please try again",
                        theme: 'modern',
                        buttons: {
                            ok: {
                                text: 'OK',
                                btnClass: 'add-button',
                                action: function () {
                                    return
                                }
                            }
                        }
                    });
                } else {
                    jQuery.confirm({
                        title: "Alert!",
                        content: "Something Went Wrong.",
                        theme: 'modern',
                        buttons: {
                            ok: {
                                text: 'OK',
                                btnClass: 'add-button',
                                action: function () {
                                    return
                                }
                            }
                        }
                    });
                }
                jQuery("#sub_main .btn-txt").css("display", "block");
                jQuery("#sub_main .spinn").removeClass("add_custome_loader");
                jQuery("#btn-change").removeAttr('disabled');

            },
        });
    });


    // Display all user's data
    jQuery('#checkAll').change(function () {
        jQuery(':checkbox', '#get_all_users').prop('checked', this.checked);
    });

    var col1 = (BMSI_object.BMSI_Option[0] !== null && BMSI_object.BMSI_Option[0] !== undefined) ? BMSI_object.BMSI_Option[0].trim() : "id";
    var col2 = (BMSI_object.BMSI_Option[1] !== null && BMSI_object.BMSI_Option[1] !== undefined) ? BMSI_object.BMSI_Option[1].trim() : "uname";
    var col3 = (BMSI_object.BMSI_Option[2] !== null && BMSI_object.BMSI_Option[2] !== undefined) ? BMSI_object.BMSI_Option[2].trim() : "email";
    var col4 = (BMSI_object.BMSI_Option[3] !== null && BMSI_object.BMSI_Option[3] !== undefined) ? BMSI_object.BMSI_Option[3].trim() : "Contact Category";
    var col5 = (BMSI_object.BMSI_Option[4] !== null && BMSI_object.BMSI_Option[4] !== undefined) ? BMSI_object.BMSI_Option[4].trim() : "Status";

    jQuery('#role_filter').on('change', function () {
        user_table.draw();
    });

    var user_table = jQuery('#get_all_users').DataTable({
        "searching": true,
        "filter": true,
        "processing": true,
        "serverSide": true,
        "info": false,
        "ajax": {
            "url": BMSI_object.ajax_url,
            "type": "POST",
            "data": function (d) {
                d.action = "BMSI_display_users",
                d.start_date = jQuery("#start").val(),
                d.end_date = jQuery("#end").val(),
                d.role = jQuery("#role_filter").val(),
                d.contact_nonce = BMSI_object.nonce
            },
            "complete": function (res) {
                contact_edit_button();
                contact_delete_button();
            },
        },
        language: {
            searchPlaceholder: "Search By Name",
            sLengthMenu: "Show  _MENU_  Entries",
            search: "",
            paginate: {
                "first": "<i class='fa-solid fa-backward'></i>",
                "last": "<i class='fa-solid fa-forward'></i>",
                "next": "<i class='fa-solid fa-caret-right'></i>",
                "previous": "<i class='fa-solid fa-caret-left'></i>"
            },
        },
        "columns": [
            { data: "id", sortable: true },
            { data: "name", sortable: true },
            { data: "email", sortable: true },
            { data: "category", sortable: true },
            { data: "tag", sortable: true },
            { data: "status", sortable: true },
            {
                data: null,
                render: function (data, type, row) {
                    return '<div class="del"><button type="button" id="edit_btn" class="edit_btn" data-cid=' + data.id + '><i class="fa-solid fa-user-pen"></i></button><button type="button" id="delete_btn" class="delete_btn" data-cid=' + data.id + '><i class="fa-solid fa-trash"></i></button><button type="button" id="btn_view" class="btn_view" onClick="view_record(' + data.id + ')" ><i class="fa fa-eye"></i></button></div>'
                }
            },
        ],
        "paging": true,
        "pageLength": 10,
        "select": true,
        "order": [
            [1, 'asc'],
        ],
    });


    //version 1.2.0     delete user
    function contact_delete_button(){
        jQuery('.delete_btn').click(function(e){
            e.preventDefault();
            var con_id = jQuery(this).data("cid");
            var del =this;
            jQuery.confirm({
                title: "Delete Contact",
                content: "Do you really want to delete this data?",
                theme: 'modern',
                buttons: {
                    ok: {
                        text: 'OK',
                        btnClass: 'add-button',
                        action: function () {
                            jQuery.ajax({
                                url: BMSI_object.ajax_url,
                                type:'POST',
                                data: {
                                    "id":con_id,
                                    "action" : "BMSI_delete_user",
                                    "delete_contact_nonce" : BMSI_object.nonce
                                },
                                success:function(data){
                                    if(data == 1){
                                        jQuery(del).closest("tr").fadeOut();
                                        table.ajax.reload();
                                    }else{
                                        alert("Data not deleted");
                                    }
                                    jQuery.confirm({
                                        title: "Congratulations!",
                                        content: "Contact Deleted Successfully",
                                        theme: 'modern',
                                        buttons: {
                                            ok: {
                                                text: 'OK',
                                                btnClass: 'add-button',
                                                action: function () {
                                                    return
                                                }
                                            }
                                        }
                                    });
                                },  
                            });
                        }
                    },
                    cancel : {
                        text: 'cancel',
                        btnClass: 'cancel-button',
                        action: function () {
                            return
                        }
                    }
                }
            })
        });
    }

    //version 1.2.0     edit redirect
    function contact_edit_button() {
        console.log("two");
        jQuery('.edit_btn').click(function (e) {
            e.preventDefault();
            var con_id = jQuery(this).data("cid");
            location.replace(BMSI_object.admin_url + "/admin.php?page=create_user&&cid=" + con_id);
        });
    }

    jQuery('#get_all_contact_tags').DataTable({
        "searching": true,
        "filter": true,
        "processing": true,
        "serverSide": true,
        "bInfo": false,
        "ajax": {
            "url": BMSI_object.ajax_url,
            "type": "POST",
            "data": function (d) {
                d.action = "BMSI_display_contact_tags",
                    d.start_date = jQuery("#start").val(),
                    d.end_date = jQuery("#end").val(),
                    d.tag_nonce = BMSI_object.nonce
            },
            "complete": function(res) {
                contact_tag_edit_button();
                contact_tag_delete_button();    
            },
           
        },
        language: {
            searchPlaceholder: "Search By Name",
            sLengthMenu: "Show  _MENU_  Entries",
            search: "",
            paginate: {
                "first": "<i class='fa-solid fa-backward'></i>",
                "last": "<i class='fa-solid fa-forward'></i>",
                "next": "<i class='fa-solid fa-caret-right'></i>",
                "previous": "<i class='fa-solid fa-caret-left'></i>"
            },
        },
        "columns": [
            { data: "id", sortable: true },
            { data: "contact_tag", sortable: true },
            {
                data: null,
                render: function (data, type, row) {
                    return '<div class="del"><button type="button" id="edit_tag_btn" class="edit_tag_btn" data-tid=' + data.id + '><i class="fa-solid fa-user-pen"></i></button><button type="button" id="delete_tag_btn" class="delete_tag_btn" data-tid=' + data.id + '><i class="fa-solid fa-trash"></i></button></div>'
                }
            },
        ],
        "paging": true,
        "pageLength": 10,
        "select": true,
        "order": [
            [1, 'asc'],
        ],
    });
    
    //version 1.2.0     delete tag
    function contact_tag_delete_button(){
        jQuery('.delete_tag_btn').click(function(e){
            e.preventDefault();
            var tag_id = jQuery(this).data("tid");
            var del =this;
            jQuery.confirm({
                title: "Delete",
                content: "Do you really want to delete this data?",
                theme: 'modern',
                buttons: {
                    ok: {
                        text: 'OK',
                        btnClass: 'add-button',
                        action: function () {  
                            jQuery.ajax({
                                url: BMSI_object.ajax_url,
                                type:'POST',
                                data: {
                                    "id":tag_id,
                                    "action" : "BMSI_delete_contact_tag",
                                    "delete_contact_tag_nonce" : BMSI_object.nonce 
                                },
                                success:function(data){
                                    if(data == 1){
                                        jQuery(del).closest("tr").fadeOut();
                                        table.ajax.reload();
                                    }else{
                                        alert("Data not deleted");
                                    }
                                    jQuery.confirm({
                                        title: "Congratulations!",
                                        content: "Tag Deleted Successfully",
                                        theme: 'modern',
                                        buttons: {
                                            ok: {
                                                text: 'OK',
                                                btnClass: 'add-button',
                                                action: function () {
                                                    return
                                                }
                                            }
                                        }
                                    });
                                },  
                            });
                        }
                    },
                    cancel: {
                        text: 'Cancel',
                        btnClass: 'cancel-button',
                        action: function () {
                            return
                        }
                    }
                }
            })
        });
    }
    

    //version 1.2.0     edit redirect
    function contact_tag_edit_button(){
        jQuery('.edit_tag_btn').click(function(e){
            e.preventDefault();
            var tag_id = jQuery(this).data("tid");
            location.replace(BMSI_object.admin_url +"/admin.php?page=add_contact_tag&&tid="+tag_id);
        });
    }


    toggleForm(jQuery('#connect_smtp').is(':checked'));

    jQuery('#connect_smtp').change(function () {
        toggleForm(jQuery(this).is(':checked'));
    });

    function toggleForm(isChecked) {
        if (isChecked) {
            jQuery('.smtp_form_data').show();
        } else {
            jQuery('.smtp_form_data').hide();
        }
    }

    jQuery("#btn-change").click(function (e) {
        e.preventDefault();
        jQuery("#sub_main .btn-txt").css("display", "none");
        jQuery("#sub_main .spinn").addClass("add_custome_loader");
        jQuery("#btn-change").attr('disabled', 'disabled');
        var connect_smtp = jQuery("#connect_smtp").is(":checked") ? "true" : "false";
        var theme_color = jQuery("#theme_color").val();

        jQuery.ajax({
            "url": BMSI_object.ajax_url,
            "type": "POST",

            "dataType": "JSON",
            "data": {
                "action": "BMSI_manage_setting",
                "connect_smtp": connect_smtp,
                "theme_color": theme_color
            },
            success: function (response) {
                jQuery.confirm({
                    title: response.title,
                    content: response.message,
                    theme: 'modern',
                    buttons: {
                        ok: {
                            text: 'OK',
                            btnClass: 'add-button',
                            action: function () {
                                if (response.status == true) {
                                    location.reload();
                                }
                            }
                        }
                    }
                });
                jQuery("#sub_main .btn-txt").css("display", "block");
                jQuery("#sub_main .spinn").removeClass("add_custome_loader");
                jQuery("#btn-change").removeAttr('disabled');

            }, error: function (jqXHR, textStatus, errorThrown) {
                if (textStatus === 'timeout') {
                    jQuery.confirm({
                        title: "Alert!",
                        content: "The request timed out. Please try again",
                        theme: 'modern',
                        buttons: {
                            ok: {
                                text: 'OK',
                                btnClass: 'add-button',
                                action: function () {
                                    return
                                }
                            }
                        }
                    });
                } else {
                    jQuery.confirm({
                        title: "Alert!",
                        content: "Something Went Wrong.",
                        theme: 'modern',
                        buttons: {
                            ok: {
                                text: 'OK',
                                btnClass: 'add-button',
                                action: function () {
                                    return
                                }
                            }
                        }
                    });
                }
                jQuery("#sub_main .btn-txt").css("display", "block");
                jQuery("#sub_main .spinn").removeClass("add_custome_loader");
                jQuery("#btn-change").removeAttr('disabled');

            },
        });
    });

    jQuery('.use_template_form').hide();

    jQuery('input[type=radio][name=template_radio]').change(function (e) {
        e.preventDefault();
        var selected_option = jQuery('input[type=radio][name=template_radio]:checked').val();


        if (selected_option == "user_template") {
            jQuery('.manual_form').hide();
            jQuery('.use_template_form').show();
        } else {
            jQuery('.manual_form').show();
            jQuery('.use_template_form').hide();
        }

    });

    // contact category
    function validate_contact_category_form() {
        return jQuery(".category_form").validate({
            rules: {
                category_name: "required",
            },
            messages: {

                category_name: {
                    required: "category name is required."
                },
            },
        }).form();
    }

    jQuery("#submit_category_form").click(function (e) {
        e.preventDefault();

        if (!validate_contact_category_form()) {
            return;
        } else {

            jQuery(".btn-txt").css("display", "none");
            jQuery(".spinn").addClass("add_custome_loader");
            jQuery(".add-button").attr('disabled', 'disabled');

            var parent_category = jQuery("#parent_category").val();
            var category_name = jQuery("#category_name").val();


            $.ajax({
                type: 'POST',
                url: BMSI_object.ajax_url,
                datatype: 'JSON',
                data: {
                    action: "BMSI_add_contact_category",
                    parent_category: parent_category,
                    category_name: category_name,
                    create_contact_category_nonce: BMSI_object.nonce,
                },
                success: function (response) {
                    jQuery(".btn-txt").css("display", "block");
                    jQuery(".spinn").removeClass("add_custome_loader");
                    jQuery(".btn_add_user").removeAttr('disabled');
                    jQuery.confirm({
                        title: "Congratulations!",
                        content: "Category Created Successfully",
                        theme: 'modern',
                        buttons: {
                            ok: {
                                text: 'OK',
                                btnClass: 'add-button',
                                action: function () {
                                    window.location.href = BMSI_object.admin_url + "admin.php?page=contact_category";
                                }
                            }
                        }
                    });
                }, error: function (jqXHR, textStatus, errorThrown) {
                    if (textStatus === 'timeout') {
                        jQuery.confirm({
                            title: "Alert!",
                            content: "The request timed out. Please try again",
                            theme: 'modern',
                            buttons: {
                                ok: {
                                    text: 'OK',
                                    btnClass: 'add-button',
                                    action: function () {
                                        return
                                    }
                                }
                            }
                        });
                    } else {
                        jQuery.confirm({
                            title: "Alert!",
                            content: "Something Went Wrong.",
                            theme: 'modern',
                            buttons: {
                                ok: {
                                    text: 'OK',
                                    btnClass: 'add-button',
                                    action: function () {
                                        return
                                    }
                                }
                            }
                        });
                    }
                    jQuery("#sub_main .btn-txt").css("display", "block");
                    jQuery("#sub_main .spinn").removeClass("add_custome_loader");
                    jQuery("#btn-change").removeAttr('disabled');

                },
            });

        }

    });
    // user Form validation
    function validate_user_form() {
        return jQuery(".form_data").validate({
            rules: {
                fname: "required",
                lname: "required",
                email: "required",
                mob: "required",
            },
            messages: {
                fname: {
                    required: "First name is required."
                },
                lname: {
                    required: "Last name is required."
                },
                email: {
                    required: "Email is required."
                },
                mob: {
                    required: "Mobile Number is required."
                }
            },
        }).form();
    }

    // user Form validation
    function validate_send_mail_template_form() {
        return jQuery(".use_template_form").validate({
            rules: {
                template_name: "required",
            },
            messages: {
                template_name: {
                    required: "Template name is required."
                },
            },
        }).form();
    }


    //send mail form validation
    function validate_send_mail_form() {
        var isValid = jQuery(".send_mail_form").validate({
            rules: {
                subject: "required",
                mail_from: "required",
                admin_mail: "required",
                bmsi_mail_box_content: "required",
            },
            messages: {
                subject: {
                    required: "Subject is required."
                },
                mail_from: {
                    required: "Sender name is required."
                },
                admin_mail: {
                    required: "Sender email is required."
                },
                bmsi_mail_box_content: {
                    required: "Mail body is required."
                }
            },
        }).form();

        var editor = tinymce.get('bmsi_mail_box');

        if (editor && editor.getContent() == "<p>add some text</p>") {
            isValid = false;
            jQuery(".bmsi_mail_box_error").text("Mail body is required.");
        } else {
            jQuery(".bmsi_mail_box_error").text("");
        }

        return isValid;
    }


    function validate_send_mail_manual_form() {
        var isValid = jQuery(".manual_form").validate({
            rules: {
                subject: "required",
                mail_from: "required",
                admin_mail: "required",
                bmsi_mail_box_content: "required",
            },
            messages: {
                subject: {
                    required: "Subject is required."
                },
                mail_from: {
                    required: "Sender name is required."
                },
                admin_mail: {
                    required: "Sender email is required."
                },
                bmsi_mail_box_content: {
                    required: "Mail body is required."
                }
            },
        }).form();

        var bmsi_mail_box_content = tinymce.get('bmsi_mail_box').getContent();

        if (bmsi_mail_box_content == "<p>add some text</p>") {
            isValid = false;
            jQuery(".bmsi_mail_box_error").text("Mail body is required.");
        } else {
            jQuery("#bmsi_mail_box").text("");

        }

        return isValid;
    }

    jQuery(".btn_add_user").click(function (e) {
        e.preventDefault();

        if (!validate_user_form()) {
            return;
        }
        else {
            jQuery(".btn-txt").css("display", "none");
            jQuery(".spinn").addClass("add_custome_loader");
            jQuery(".btn_add_user").attr('disabled', 'disabled');

            //version 1.2.0
            var cid = jQuery("#cid").val();
            var email = jQuery("#email").val();
            var category = jQuery("#category").val();
            var tag_name = jQuery("#contact_category_tag").val();
            var status = jQuery("#status").val();
            var fname = jQuery("#fname").val();
            var lname = jQuery("#lname").val();
            var job = jQuery("#job").val();
            var company = jQuery("#company").val();
            var bdate = jQuery("#bdate").val();
            var adate = jQuery("#adate").val();
            var tag = JSON.stringify(tag_name);
            jQuery.ajax({
                "url": BMSI_object.ajax_url,
                "type": "POST",
                "dataType": "JSON",
                "data": {
                    "action": "BMSI_create_new_user",
                    "cid": cid,
                    "email": email,
                    "category": category,
                    "tag": tag,
                    "status": status,
                    "fname": fname,
                    "lname": lname,
                    "job": job,
                    "company":company,
                    "bdate":bdate,
                    "adate":adate,
                    "create_contact_nonce": BMSI_object.nonce,
                },
                success: function (response) {
                    jQuery.confirm({
                        title: response.title,
                        content: response.message,
                        theme: 'modern',
                        buttons: {
                            ok: {
                                text: 'OK',
                                btnClass: 'add-button',
                                action: function () {
                                    window.location.href = BMSI_object.admin_url + "admin.php?page=users";
                                }
                            }
                        }
                    });
                    jQuery(".btn-txt").css("display", "block");
                    jQuery(".spinn").removeClass("add_custome_loader");
                    jQuery(".btn_add_user").removeAttr('disabled');


                }, error: function (jqXHR, textStatus, errorThrown) {
                    if (textStatus === 'timeout') {
                        jQuery.confirm({
                            title: "Alert!",
                            content: "The request timed out. Please try again",
                            theme: 'modern',
                            buttons: {
                                ok: {
                                    text: 'OK',
                                    btnClass: 'add-button',
                                    action: function () {
                                        return
                                    }
                                }
                            }
                        });
                    } else {
                        jQuery.confirm({
                            title: "Alert!",
                            content: "Something Went Wrong.",
                            theme: 'modern',
                            buttons: {
                                ok: {
                                    text: 'OK',
                                    btnClass: 'add-button',
                                    action: function () {
                                        return
                                    }
                                }
                            }
                        });
                    }
                    jQuery("#tab4 .btn-txt").css("display", "block");
                    jQuery("#tab4 .spinn").removeClass("add_custome_loader");
                    jQuery(".btn_add_user").removeAttr('disabled');

                },
            })
        }
    })

    // for select2 contact tag
    jQuery("#contact_category_tag").select2({
        placeholder: 'Select Contact Tag',
        closeOnSelect: true,
    });

    //version 1.2.0
    jQuery(".btn_add_tag").click(function (e) {
        e.preventDefault();

        jQuery(".btn-txt").css("display", "none");
        jQuery(".spinn").addClass("add_custome_loader");
        jQuery(".btn_add_tag").attr('disabled', 'disabled');

        var tid = jQuery("#tid").val();
        var contactTag = jQuery("#contactTag").val();

        //version 1.2.0
        jQuery.ajax({
            "url": BMSI_object.ajax_url,
            "type": "POST",
            "dataType": "JSON",
            "data": {
                "action": "BMSI_create_contact_tag",
                "tid": tid,
                "contactTag": contactTag,
                "create_contact_tag_nonce" : BMSI_object.nonce
            },
            success: function (response) {
                jQuery.confirm({
                    title: response.title,
                    content: response.message,
                    theme: 'modern',
                    buttons: {
                        ok: {
                            text: 'OK',
                            btnClass: 'add-button',
                            action: function () {
                                window.location.href = BMSI_object.admin_url + "admin.php?page=contact_tag";
                            }
                        }
                    }
                });
                jQuery(".btn-txt").css("display", "block");
                jQuery(".spinn").removeClass("add_custome_loader");
                jQuery(".btn_add_user").removeAttr('disabled');


            }, error: function (jqXHR, textStatus, errorThrown) {
                if (textStatus === 'timeout') {
                    jQuery.confirm({
                        title: "Alert!",
                        content: "The request timed out. Please try again",
                        theme: 'modern',
                        buttons: {
                            ok: {
                                text: 'OK',
                                btnClass: 'add-button',
                                action: function () {
                                    return
                                }
                            }
                        }
                    });
                } else {
                    jQuery.confirm({
                        title: "Alert!",
                        content: "Something Went Wrong.",
                        theme: 'modern',
                        buttons: {
                            ok: {
                                text: 'OK',
                                btnClass: 'add-button',
                                action: function () {
                                    return
                                }
                            }
                        }
                    });
                }
                jQuery("#tab4 .btn-txt").css("display", "block");
                jQuery("#tab4 .spinn").removeClass("add_custome_loader");
                jQuery(".btn_add_tag").removeAttr('disabled');
            },
        })
    }
    )


    jQuery(".btn-send-mail").click(function (e) {
        e.preventDefault();
        if (!validate_send_mail_form()) {
            return;
        }
        else {
            jQuery(".btn-txt").css("display", "none");
            jQuery(".spinn").addClass("add_custome_loader");
            jQuery(".btn-send-mail").attr('disabled', 'disabled');

            var category = jQuery("#category").val();
            var subject = jQuery("#mail_sub").val();
            var sender_name = jQuery("#mail_from").val();
            var sender_id = jQuery("#admin_mail").val();
            var mail_contant = tinymce.get('bmsi_mail_box').getContent({ format: 'html' });
            jQuery.ajax({
                "url": BMSI_object.ajax_url,
                "type": "POST",
                "dataType": "JSON",
                "data": {
                    "action": "BMSI_User_send_mail",
                    "category": category,
                    "subject": subject,
                    "sender": sender_name,
                    "sender_id": sender_id,
                    "body": mail_contant,
                    "send_mail_user_nonce": BMSI_object.nonce,
                },
                success: function (response) {
                    
                    jQuery.confirm({
                        title: response.title,
                        content: response.message,
                        theme: 'modern',
                        buttons: {
                            ok: {
                                text: 'OK',
                                btnClass: 'add-button',
                                action: function () {
                                    if (response.status == true) {
                                        window.location.href = BMSI_object.admin_url + "admin.php?page=users";
                                    }
                                }
                            }
                        }
                    });
                    jQuery(".btn-txt").css("display", "block");
                    jQuery(".spinn").removeClass("add_custome_loader");
                    jQuery(".btn-send-mail").removeAttr('disabled');


                }, error: function (jqXHR, textStatus, errorThrown) {
                    if (textStatus === 'timeout') {
                        jQuery.confirm({
                            title: "Alert!",
                            content: "The request timed out. Please try again",
                            theme: 'modern',
                            buttons: {
                                ok: {
                                    text: 'OK',
                                    btnClass: 'add-button',
                                    action: function () {
                                        return
                                    }
                                }
                            }
                        });
                    } else {
                        jQuery.confirm({
                            title: "Alert!",
                            content: "Something Went Wrong.",
                            theme: 'modern',
                            buttons: {
                                ok: {
                                    text: 'OK',
                                    btnClass: 'add-button',
                                    action: function () {
                                        return
                                    }
                                }
                            }
                        });
                    }
                    jQuery("#tab4 .btn-txt").css("display", "block");
                    jQuery("#tab4 .spinn").removeClass("add_custome_loader");
                    jQuery(".btn-send-mail").removeAttr('disabled');

                },
            });
        }
    });


    jQuery(".send-test-mail").click(function (e) {
        e.preventDefault();
            jQuery("#test-mail .btn-txt").css("display", "none");
            jQuery("#test-mail .spinn").addClass("add_custome_loader");
            jQuery(".send-test-mail").attr('disabled', 'disabled');

            var mailfrom = jQuery("#mailfrom").val();
            var mailto = jQuery("#mailto").val();
            jQuery.ajax({
                "url": BMSI_object.ajax_url,
                "type": "POST",
                "dataType": "JSON",
                "data": {
                    "action": "BMSI_send_test_mail",
                    "mailfrom": mailfrom,
                    "mailto": mailto,  
                    "teat_send_mail_user_nonce": BMSI_object.nonce,
                },
                success: function (response) {
                    jQuery.confirm({
                        title: response.title,
                        content: response.message,
                        theme: 'modern',
                        buttons: {
                            ok: {
                                text: 'OK',
                                btnClass: 'add-button',
                                action: function () {
                                    if (response.status == true) {
                                        window.location.href = BMSI_object.admin_url + "admin.php?page=settings";
                                    }
                                }
                            }
                        }
                    });
                    jQuery("#test-mail .btn-txt").css("display", "block");
                    jQuery("#test-mail .spinn").removeClass("add_custome_loader");
                    jQuery(".send-test-mail").removeAttr('disabled');


                }, error: function (jqXHR, textStatus, errorThrown) {
                    if (textStatus === 'timeout') {
                        jQuery.confirm({
                            title: "Alert!",
                            content: "The request timed out. Please try again",
                            theme: 'modern',
                            buttons: {
                                ok: {
                                    text: 'OK',
                                    btnClass: 'add-button',
                                    action: function () {
                                        return
                                    }
                                }
                            }
                        });
                    } else {
                        jQuery.confirm({
                            title: "Alert!",
                            content: "Something Went Wrong.",
                            theme: 'modern',
                            buttons: {
                                ok: {
                                    text: 'OK',
                                    btnClass: 'add-button',
                                    action: function () {
                                        return
                                    }
                                }
                            }
                        });
                    }
                    jQuery("#test-mail .btn-txt").css("display", "block");
                    jQuery("#test-mail .spinn").removeClass("add_custome_loader");
                    jQuery(".send-test-mail").removeAttr('disabled');

                },
            });
        });


    jQuery(".btn-pro-send-mail").click(function (e) {
        e.preventDefault();
        var selected_option = jQuery('input[type=radio][name=template_radio]:checked').val();
        if (selected_option == "user_template") {
            if (!validate_send_mail_template_form()) {
                return;
            } else {
                jQuery(".btn-txt").css("display", "none");
                jQuery(".spinn").addClass("add_custome_loader");
                jQuery(".btn-pro-send-mail").attr('disabled', 'disabled');


                var template_name = jQuery("#template_name").val();
                jQuery.ajax({
                    "url": BMSI_object.ajax_url,
                    "type": "POST",
                    "data": {
                        "action": "BMSI_User_send_mail_template",
                        "template_name": template_name,
                        "send_mail_user_nonce": BMSI_object.nonce,
                    },
                    success: function (response) {
                        jQuery.confirm({
                            title: response.title,
                            content: response.message,
                            theme: 'modern',
                            buttons: {
                                ok: {
                                    text: 'OK',
                                    btnClass: 'add-button',
                                    action: function () {
                                        if (response.status == true) {
                                            window.location.href = BMSI_object.admin_url + "admin.php?page=users";
                                        }
                                    }
                                }
                            }
                        });
                        jQuery(".btn-txt").css("display", "block");
                        jQuery(".spinn").removeClass("add_custome_loader");
                        jQuery(".btn-pro-send-mail").removeAttr('disabled');

                    }, error: function (jqXHR, textStatus, errorThrown) {
                        if (textStatus === 'timeout') {
                            jQuery.confirm({
                                title: "Alert!",
                                content: "The request timed out. Please try again",
                                theme: 'modern',
                                buttons: {
                                    ok: {
                                        text: 'OK',
                                        btnClass: 'add-button',
                                        action: function () {
                                            return
                                        }
                                    }
                                }
                            });
                        } else {
                            jQuery.confirm({
                                title: "Alert!",
                                content: errorThrown,
                                theme: 'modern',
                                buttons: {
                                    ok: {
                                        text: 'OK',
                                        btnClass: 'add-button',
                                        action: function () {
                                            return
                                        }
                                    }
                                }
                            });
                        }
                        jQuery(".btn-txt").css("display", "block");
                        jQuery(".spinn").removeClass("add_custome_loader");
                        jQuery(".btn-pro-send-mail").removeAttr('disabled');

                    },
                });
            }
        }
        else {
            if (!validate_send_mail_form()) {
                return;
            }
            else {
                jQuery(".btn-txt").css("display", "none");
                jQuery(".spinn").addClass("add_custome_loader");
                jQuery(".btn-pro-send-mail").attr('disabled', 'disabled');


                var subject = jQuery("#mail_sub").val();
                var sender_name = jQuery("#mail_from").val();
                var sender_mail = jQuery("#admin_mail").val();
                var mail_contant = tinymce.get('bmsi_mail_box').getContent({ format: 'html' });


                jQuery.ajax({
                    "url": BMSI_object.ajax_url,
                    "type": "POST",
                    "dataType": "JSON",
                    "data": {
                        "action": "BMSI_pro_User_send_mail",
                        "subject": subject,
                        "sender": sender_name,
                        "sender_mail": sender_mail,
                        "body": mail_contant,
                        "send_mail_user_nonce": BMSI_object.nonce,
                    },
                    success: function (response) {
                        jQuery.confirm({
                            title: response.title,
                            content: response.message,
                            theme: 'modern',
                            buttons: {
                                ok: {
                                    text: 'OK',
                                    btnClass: 'add-button',
                                    action: function () {
                                        if (response.status == true) {
                                            window.location.href = BMSI_object.admin_url + "admin.php?page=users";
                                        }
                                    }
                                }
                            }
                        });
                        jQuery(".btn-txt").css("display", "block");
                        jQuery(".spinn").removeClass("add_custome_loader");
                        jQuery(".btn-pro-send-mail").removeAttr('disabled');


                    }, error: function (jqXHR, textStatus, errorThrown) {
                        if (textStatus === 'timeout') {
                            jQuery.confirm({
                                title: "Alert!",
                                content: "The request timed out. Please try again",
                                theme: 'modern',
                                buttons: {
                                    ok: {
                                        text: 'OK',
                                        btnClass: 'add-button',
                                        action: function () {
                                            return
                                        }
                                    }
                                }
                            });
                        } else {
                            jQuery.confirm({
                                title: "Alert!",
                                content: "Something Went Wrong.",
                                theme: 'modern',
                                buttons: {
                                    ok: {
                                        text: 'OK',
                                        btnClass: 'add-button',
                                        action: function () {
                                            return
                                        }
                                    }
                                }
                            });
                        }
                        jQuery(".btn-txt").css("display", "block");
                        jQuery(".spinn").removeClass("add_custome_loader");
                        jQuery(".btn-pro-send-mail").removeAttr('disabled');

                    },
                });
            }
        }

    });


    // smtp setup 

    function validate_smtp_form() {
        return jQuery(".smtp_form_data").validate({
            rules: {
                smtpH: "required",
                smtpPort: "required",
                smtpU: "required",
                smtpPass: "required",
            },
            messages: {
                smtpH: {
                    required: "SMTP Host is required."
                },
                smtpPort: {
                    required: "SMTP Port is required."
                },
                smtpU: {
                    required: "SMTP Username is required."
                },
                smtpPass: {
                    required: "SMTP Password is required."
                }
            },
        }).form();
    }

    jQuery(".btn-Connect").click(function (e) {
        e.preventDefault();
        if (!validate_smtp_form()) {
            return;
        }
        else {
            jQuery("#tab4 .btn-txt").css("display", "none");
            jQuery("#tab4 .spinn").addClass("add_custome_loader");
            jQuery(".btn-Connect").attr('disabled', 'disabled');


            var smtpH = jQuery("#smtpH").val();
            var smtpPort = jQuery("#smtpPort").val();
            var smtpU = jQuery("#smtpU").val();
            var smtpPass = jQuery("#smtpPass").val();
            var Secure = jQuery("input[type='radio'][name='Secure']:checked").val();
            var Authentication = jQuery("input[type='radio'][name='Authentication']:checked").val();
            var connect_smtp = jQuery("#connect_smtp").is(":checked") ? "true" : "false";



            jQuery.ajax({
                "url": BMSI_object.ajax_url,
                "type": "POST",
                "dataType": "JSON",
                "data": {
                    "action": "BMSI_SMTP_Connect",
                    "smtpH": smtpH,
                    "smtpPort": smtpPort,
                    "smtpU": smtpU,
                    "smtpPass": smtpPass,
                    "Secure": Secure,
                    "Authentication": Authentication,
                    "connect_smtp": connect_smtp,
                    "smtp_nonce": BMSI_object.nonce,
                },
                success: function (response) {
                    jQuery.confirm({
                        title: response.title,
                        content: response.message,
                        theme: 'modern',
                        buttons: {
                            ok: {
                                text: 'OK',
                                btnClass: 'add-button',
                                action: function () {
                                    if (response.status == true) {
                                        return
                                    }
                                }
                            }
                        }
                    });
                    jQuery("#tab4 .btn-txt").css("display", "block");
                    jQuery("#tab4 .spinn").removeClass("add_custome_loader");
                    jQuery(".btn-Connect").removeAttr('disabled');


                },
                error: function (jqXHR, textStatus, errorThrown) {
                    jQuery.confirm({
                        title: "Alert!",
                        content: "SMTP Error: Could not authenticate.",
                        theme: 'modern',
                        buttons: {
                            ok: {
                                text: 'OK',
                                btnClass: 'add-button',
                                action: function () {
                                    return
                                }
                            }
                        }
                    });
                    jQuery("#tab4 .btn-txt").css("display", "block");
                    jQuery("#tab4 .spinn").removeClass("add_custome_loader");
                    jQuery(".btn-Connect").removeAttr('disabled');

                },
                complete: function () {
                    jQuery("#tab4 .btn-txt").css("display", "block");
                    jQuery("#tab4 .spinn").removeClass("add_custome_loader");
                    jQuery(".btn-Connect").removeAttr('disabled');

                }
            });
        }
    });


    // Read excel
    document.getElementById('importSheet').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            const data = new Uint8Array(e.target.result);
            const workbook = XLSX.read(data, {type: 'array'});
            const firstSheet = workbook.Sheets[workbook.SheetNames[0]];

            // Get the range of the first row
            const range = firstSheet['!ref'];
            const columnNames = [];
            for (let col = 0; col <= XLSX.utils.decode_range(range).e.c; col++) {
                const cell = firstSheet[XLSX.utils.encode_cell({c: col, r: 0})]; // Get first row cells
                if (cell) {
                    columnNames.push(cell.v); // Push the value of the first row cell
                }
            }
            populateDropdowns(columnNames);
        };
        reader.readAsArrayBuffer(file);
    });

    function populateDropdowns(columnNames) {
        const dropdowns = ['emailField', 'fnameField', 'lnameField', 'categoryField'];
        dropdowns.forEach(dropdownId => {
            const dropdown = document.getElementById(dropdownId);
            dropdown.innerHTML = '<option value="">Select Column</option>'; // Clear previous options
            columnNames.forEach(name => {
                dropdown.innerHTML += `<option value="${name}">${name}</option>`;
            });
        });
        document.getElementById('columnMapping').classList.remove('d-none'); // Show the mapping section
    }

    // Handle dropdown selection changes to ensure no duplicate selections
    const dropdowns = document.querySelectorAll('.form-select');
    dropdowns.forEach(dropdown => {
        dropdown.addEventListener('change', function() {
            const selectedValues = Array.from(dropdowns).map(d => d.value).filter(v => v);
            dropdowns.forEach(d => {
                // Enable all options first
                Array.from(d.options).forEach(option => {
                    option.disabled = false; // Reset disabled state
                });

                // Disable selected values in other dropdowns
                selectedValues.forEach(value => {
                    if (value && value !== d.value) {
                        Array.from(d.options).forEach(option => {
                            if (option.value === value) {
                                option.disabled = true; // Disable already selected options
                            }
                        });
                    }
                });
            });
        });
    });

    $('#importForm').on('submit', function(event) {
        event.preventDefault();
    
        // Create a FormData object
        const formData = new FormData(this); // 'this' refers to the form
    
        // Get column mappings from dropdowns
        const columnMappings = {
            email: $('#emailField').val(),
            fname: $('#fnameField').val(),
            lname: $('#lnameField').val(),
            category: $('#categoryField').val(),
        };
    
        // Append column mappings to the FormData object
        formData.append('action', 'BMSI_import_contacts');
        formData.append('columnMappings', JSON.stringify(columnMappings));
    
        $.ajax({
            url: BMSI_object.ajax_url,
            method: 'POST',
            data: formData,
            contentType: false, // Important: set this to false
            processData: false, // Important: set this to false
            success: function(response) {
                if (response.success) {
                    alert(response.data); // Show success message
                } else {
                    alert('Error: ' + response.data); // Show error message
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                alert('An error occurred while importing contacts.');
            }
        });
    });
    

});




function view_record(id) {
    jQuery.noConflict();
    jQuery('#exampleModal1').modal('show');
    jQuery.ajax({
        "url": BMSI_object.ajax_url,
        "type": "POST",
        "data": {
            "action": "BMSI_view_user",
            "id": id,
            "view_nonce": BMSI_object.nonce,
        },
        "success": function (response) {
            jQuery(".modal-body .id .Contact_id").text(response[0]['id']);
            jQuery(".modal-body .name .Contact_name").text(response[0]['name']);
            jQuery(".modal-body .email .Contact_email").text(response[0]['email']);
            jQuery(".modal-body .Category .Contact_Category").text(response[0]['category']);
            jQuery(".modal-body .tag .Contact_tag").text(response[0]['tag']);
            jQuery(".modal-body .Status .Contact_Status").text(response[0]['status']);
        },
    });
}

