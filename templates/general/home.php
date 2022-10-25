<h1 class="text-center">Nazwa aplikacji</h1>

<div class = "mb-3">
   <div class = "fw-bold">Do czego służy aplikacja?</div>
   <div class = "description"> Aplikacji służy do zarządzanie swoimi subskrypcjami w serwisie youtube.</div>
</div>

<div class = "mb-3">
   <div class = "fw-bold">Co to znaczy?</div>
   <div class = "description"> Aplikacja daje ci możliwość grupowania swoich subskrybentów według tematyki kanału.</div>
</div>

<div class = "mb-3">
   <div class = "fw-bold">Podczas korzystania z serwisu youtube doświadczyłeś któregokolwiek z poniższych problemów?</div>
   <ul>
      <li>Masz zbyt dużą liczbę subskrypcji</li>
      <li>Przeglądanie nowych filmów od twórców zajmuje ci całą wieczność</li>
      <li>Trudno ci odnaleźć filmy od konkretnych twórców</li>
      <li>Chciałbyć ułatwić sobie przeglądania ulubionych kanałów</li>
   </ul>
   <div>Dotknął cię którykolwiek z powyższych problemów? Jeżeli tak to aplikacja jest właśnie dla ciebie!</div>
</div>

<div>
   <div class = "fw-bold">Co oferujemy?</div>
   <ul>
      <li>Tworzenie własnych grup tematycznych</li>
      <li>Segregacja swoich subskrypcji</li>
      <li>Przyjemny intefejs graficzny</li>
      <li>Szybki dostęp do wybranej tematyki</li>
   </ul>
</div>

<?php if ($user == null): ?>
   <div id = "loginBox" class="d-flex">
      <img id = "google" src="public/images/google.png">
      <a href = "<?=$google_login_url?>">Zaloguj się przez Google</a>
   </div>
<?php endif;?>
