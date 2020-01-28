let timer1, timer2, timer3, timer4, timer5, timer6;
let pseudo = document.getElementById('pseudo');
let pseudoFeedback = document.getElementById('pseudoFeedback');
let forename = document.getElementById('forename');
let forenameFeedback = document.getElementById('forenameFeedback');
let name = document.getElementById('name');
let nameFeedback = document.getElementById('nameFeedback');
let email = document.getElementById('email');
let emailFeedback = document.getElementById('emailFeedback');
let password = document.getElementById('password');
let passwordFeedback = document.getElementById('passwordFeedback');
let password_conf = document.getElementById('password_conf');
let password_confFeedback = document.getElementById('password_confFeedback');

pseudo.addEventListener('input', function () {
    clearTimeout(timer1);
    pseudo.className = 'form-control is-pending';
    pseudo.setCustomValidity('');
    timer1 = setTimeout(checkPseudo, 1000);
});
name.addEventListener('input', function () {
    clearTimeout(timer2);
    name.className = 'form-control is-pending';
    name.setCustomValidity('');
    timer2 = setTimeout(checkName, 1000);
});
forename.addEventListener('input', function () {
    clearTimeout(timer3);
    forename.className = 'form-control is-pending';
    forename.setCustomValidity('');
    timer3 = setTimeout(checkForename, 1000);
});
email.addEventListener('input', function () {
    clearTimeout(timer4);
    email.className = 'form-control is-pending';
    email.setCustomValidity('');
    timer4 = setTimeout(checkEmail, 1000);
});
password.addEventListener('input', function () {
    clearTimeout(timer5);
    password.className = 'form-control is-pending';
    password.setCustomValidity('');
    password_conf.className = 'form-control';
    password_conf.setCustomValidity('');
    timer5 = setTimeout(checkPassword, 1000);
});
password_conf.addEventListener('input', function () {
    clearTimeout(timer6);
    password_conf.className = 'form-control is-pending';
    password_conf.setCustomValidity('');
    timer6 = setTimeout(checkConfirm, 1000);
});

function checkPassword() {
    if (password.value.length < 8) {
        password.className = 'form-control is-invalid';
        passwordFeedback.innerText = 'Veuillez utiliser au moins 8 caractères.';
        password.setCustomValidity(passwordFeedback.innerText);
    } else {
        password.className = 'form-control is-valid';
    }
}

function checkConfirm() {
    if (password.value !== password_conf.value && password_conf.value) {
        password_confFeedback.innerText = '';
        password_conf.className = 'form-control is-invalid';
        password_confFeedback.innerText = 'Les deux mots de passe ne correspondent pas.';
        password_conf.setCustomValidity(password_confFeedback.innerText);
    } else {
        password_conf.className = 'form-control is-valid';
    }
}

function toggleVisibility($input) {
    $input = document.getElementById($input);
    if ($input.type === 'password') {
        $input.type = 'text';
        $input.nextElementSibling.firstElementChild.firstElementChild.className = 'fas fa-eye-slash';
    } else {
        $input.type = 'password';
        $input.nextElementSibling.firstElementChild.firstElementChild.className = 'fas fa-eye';
    }
}

function checkPseudo() {
    fetch('validator?method=pseudo&input=' + encodeURIComponent(pseudo.value))
        .then(response => {
            return response.json();
        })
        .then(data => {
            if (data.valid) {
                pseudo.className = 'form-control is-valid';
            } else {
                pseudo.className = 'form-control is-invalid';
                pseudoFeedback.innerText = data.error;
                pseudo.setCustomValidity(data.error);
            }
        })
}

function checkName() {
    fetch('validator?method=name&input=' + encodeURIComponent(name.value))
        .then(response => {
            return response.json();
        })
        .then(data => {
            if (data.valid) {
                name.className = 'form-control is-valid';
            } else {
                name.className = 'form-control is-invalid';
                nameFeedback.innerText = data.error;
                name.setCustomValidity(data.error);
            }
        })
}

function checkForename() {
    fetch('validator?method=forename&input=' + encodeURIComponent(forename.value))
        .then(response => {
            return response.json();
        })
        .then(data => {
            if (data.valid) {
                forename.className = 'form-control is-valid';
            } else {
                forename.className = 'form-control is-invalid';
                forenameFeedback.innerText = data.error;
                forename.setCustomValidity(data.error);
            }
        })
}

function checkEmail() {
    fetch('validator?method=email&input=' + encodeURIComponent(email.value))
        .then(response => {
            return response.json();
        })
        .then(data => {
            if (data.valid) {
                email.className = 'form-control is-valid';
            } else {
                email.className = 'form-control is-invalid';
                emailFeedback.innerText = data.error;
                email.setCustomValidity(data.error);
            }
        })
}