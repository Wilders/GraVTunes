let timer1, timer2, timer3, timer4, timer5, timer6;
const finishTyping = 1000;
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
let meter = document.getElementById('password-strength-meter');
let security = document.getElementById('password-security');
let icon = document.getElementById('icon');
const strength = {
    0: {text: 'Très faible', color: 'darkred'},
    1: {text: 'Faible', color: 'orangered'},
    2: {text: 'Moyen', color: 'orange'},
    3: {text: 'Fort', color: 'yellowgreen'},
    4: {text: 'Très fort', color: 'limegreen'}
};

pseudo.addEventListener('input', function () {
    clearTimeout(timer1);
    pseudo.className = 'form-control is-pending';
    pseudo.setCustomValidity('');
    timer1 = setTimeout(checkPseudo, finishTyping);
});
name.addEventListener('input', function () {
    clearTimeout(timer2);
    name.className = 'form-control is-pending';
    name.setCustomValidity('');
    timer2 = setTimeout(checkName, finishTyping);
});
forename.addEventListener('input', function () {
    clearTimeout(timer3);
    forename.className = 'form-control is-pending';
    forename.setCustomValidity('');
    timer3 = setTimeout(checkForename, finishTyping);
});
email.addEventListener('input', function () {
    clearTimeout(timer4);
    email.className = 'form-control is-pending';
    email.setCustomValidity('');
    timer4 = setTimeout(checkEmail, finishTyping);
});
password.addEventListener('input', function () {
    clearTimeout(timer5);
    meter.style.display = this.value ? 'inline' : 'none';
    meter.value = zxcvbn(this.value).score;
    security.style.display = this.value ? 'block' : 'none';
    security.innerText = 'Niveau de sécurité : ' + strength[meter.value].text;
    security.style.color = strength[meter.value].color;
    password.className = 'form-control is-pending';
    password.setCustomValidity('');
    password_conf.className = 'form-control';
    password_conf.setCustomValidity('');
    timer5 = setTimeout(checkPassword, finishTyping);
    timer5 = setTimeout(checkConfirm, finishTyping);
});
password_conf.addEventListener('input', function () {
    clearTimeout(timer6);
    password_conf.className = 'form-control is-pending';
    password_conf.setCustomValidity('');
    timer6 = setTimeout(checkConfirm, finishTyping);
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