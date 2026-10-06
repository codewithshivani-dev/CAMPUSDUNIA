@extends('layouts.homelayout')
@section('title', 'Cloud Computing')
@section('content')
<style>
        body
{
    background: #fff;
}
#form
{
    width:450px;
    margin:10vh auto 0 auto;
    padding: 30px;
    background-color: whitesmoke;
    border-radius:5px;
    font-size:16px;
    margin-bottom:50px;
}
#form h1
{
    color: #0f2027;
    text-align: center;
    font-size:30px;
}
#form button
{
    padding: 10px;
    margin-top: 10px;
    width: 100%;
    color:#fff;
    background-color: #001b5b;
    border:none;
    border-radius:4px;
}
.input-control 
{
    display: flex;
    flex-direction: column;
}
.input-control input
{
    border:2px solid #E7E7E7;
    border-radius: 4px;
    display: block;
    font-size: 12px;
    padding: 10px;
    width:100%;
}
.input-control input:focus
{
    outline:0;
}
.input-control .success input
{
    border-color:#09c372;
}
.input-control .error input
{
    border-color:#ff3860;
}
.input-control .error
{
    color: #ff3860;
    font-size: 9px;
    height:13px;
}
.input-control textarea
{
    border:2px solid #E7E7E7;
    border-radius:4px;
}
    </style>
    <div class="container">
        <form id="form" action="/">
            <h1>Request Quote</h1>
        
            <div class="input-control">
                <label for="username">Username:</label>
                <input id="username" name="username" type="text">
                <div class="error"></div>
            </div>
            <div class="input-control">
                <label for="email">Email:</label>
                <input id="email" name="username" type="email">
                <div class="error"></div>
            </div>
            <div class="input-control">
                <label for="password">Password:</label>
                <input id="password" name="password" type="password">
                <div class="error"></div>
            </div>
            <div class="input-control">
                <label for="password">Password Again:</label>
                <input id="password2" name="password" type="password">
                <div class="error"></div>
            </div>
            <div class="input-control">
                <label for="password">Message:</label>
                <textarea name="" id="messagebox" placeholder=""></textarea>
                <div class="error"></div>
            </div>
            <button type="submit">Submit</button>
        </form>
    </div>
    <script>
const form = document.getElementById('form');
const username = document.getElementById('username');
const email = document.getElementById('email');
const password = document.getElementById('password');
const password2 = document.getElementById('password2');
const messagebox = document.getElementById('messagebox');

form.addEventListener('submit', e => {
    e.preventDefault();

    validateInputs();
});

const setError = (element, message) => {
    const inputControl = element.parentElement;
    const errorDisplay = inputControl.querySelector('.error');

    errorDisplay.innerText = message;
    inputControl.classList.add('error');
    inputControl.classList.remove('success')
}

const setSuccess = element => {
    const inputControl = element.parentElement;
    const errorDisplay = inputControl.querySelector('.error');

    errorDisplay.innerText = '';
    inputControl.classList.add('success');
    inputControl.classList.remove('error');
};

const isValidEmail = email => {
    const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

const validateInputs = () => {
    const usernameValue = username.value.trim();
    const emailValue = email.value.trim();
    const passwordValue = password.value.trim();
    const password2Value = password2.value.trim();
    const messageboxValue = messagebox.value.trim();
    if(usernameValue === '') {
        setError(username, 'Username is required');
    } else {
        setSuccess(username);
    }

    if(emailValue === '') {
        setError(email, 'Email is required');
    } else if (!isValidEmail(emailValue)) {
        setError(email, 'Provide a valid email address');
    } else {
        setSuccess(email);
    }

    if(passwordValue === '') {
        setError(password, 'Password is required');
    } else if (passwordValue.length < 8 ) {
        setError(password, 'Password must be at least 8 character.')
    } else {
        setSuccess(password);
    }

    if(password2Value === '') {
        setError(password2, 'Please confirm your password');
    } else if (password2Value !== passwordValue) { 
        setError(password2, "Passwords doesn't match");
    } else {
        setSuccess(password2);
    }

    if(messageboxValue === '') {
        setError(messagebox, 'Please enter your message');
    } else {
        setSuccess(messagebox);
    }
};

    </script>
    @stop