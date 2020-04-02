"use strict";
$(function () {
    let levelbar = $('.progress-bar');
    if (levelbar.length) {
        levelbar.css('width', '0');
        let itemWidth = levelbar.attr('aria-valuenow') + "%";
        levelbar.animate({
            width: itemWidth
        }, 1500);
    }
    let sidebar = $(".sidebar");
    $("#sidebarToggle, #sidebarToggleTop").on('click', function () {
        $("body").toggleClass("sidebar-toggled");
        sidebar.toggleClass("toggled");
        if (sidebar.hasClass("toggled")) {
            $('.sidebar .collapse').collapse('hide');
        }
    });

    bsCustomFileInput.init();
    let is = $("input[type='number']");
    if (is.length) {
        is.inputSpinner();
    }
    let sw = $('#smartWizard');
    if (sw.length) {
        sw.smartWizard();
    }
    let track = $('.selectpicker');
    if (track.length) {
        $('.selectpicker').selectpicker();
    }
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover({html: true});
    document.querySelectorAll("#buttonAddMail").forEach(function (e) {
        let i = 0;
        let legMail = document.createElement("legend");
        let fieldset = document.getElementById(String("fieldsetMails" + e.dataset.value));

        e.addEventListener('click', function () {
            let mail = document.getElementById(String("emailInput" + e.dataset.value));
            let divInput = document.createElement("div");
            let divDelete = document.createElement("div");
            let buttonDelete = document.createElement("button");
            let input = document.createElement("input");

            if (mail.value !== "") {
                if (legMail.textContent === "") {
                    if (document.querySelectorAll("#buttonAddMail").length !== 0) {
                        legMail.setAttribute("class", "lead");
                        legMail.textContent = "Adresses mails ajout‚s :";
                    } else {
                        legMail.textContent = "";
                    }
                }

                divInput.setAttribute("id", String(i + 1));
                divInput.setAttribute("class", "input-group mb-3");

                divDelete.setAttribute("class", "input-group-append");

                buttonDelete.setAttribute("class", "btn btn-outline-secondary");
                buttonDelete.type = "button";
                buttonDelete.innerHTML = "<i class=\"fas fa-trash-alt\"></i>";

                input.setAttribute("class", "form-control");
                input.setAttribute("name", "mailsDest[]");
                //input.setAttribute("disabled",true);
                input.value = mail.value;
                mail.value = "";

                fieldset.appendChild(divInput);
                divInput.appendChild(input);
                divInput.appendChild(divDelete);
                divDelete.appendChild(buttonDelete);

                buttonDelete.addEventListener('click', function (e) {
                    divInput.remove();
                });
            }
        });
        fieldset.appendChild(legMail);
    });
});

function clipboard(item) {
    let copyText = document.getElementById(item.id);
    copyText.select();
    document.execCommand("copy");
}

$('#userModal').on('show.bs.modal', function (event) {
    let button = $(event.relatedTarget);
    let id = button.data('id');
    let pseudo = button.data('pseudo');
    let email = button.data('email');
    let nom = button.data('nom');
    let prenom = button.data('prenom');
    let adresse = button.data('adresse');
    let modal = $(this);
    modal.find('.modal-title').text(pseudo + ' - Modification');
    modal.find('#userId').val(id);
    modal.find('#pseudo').val(pseudo);
    modal.find('#email').val(email);
    modal.find('#name').val(nom);
    modal.find('#forename').val(prenom);
    modal.find('#address').val(adresse);
});