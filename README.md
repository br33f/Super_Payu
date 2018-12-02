# Super_Payu
Moduł płatności PayU dla Magento

## Wymagania
Moduł testowany na wersji 1.9.x jednak powinien działać z Magento w wersji 1.6+.  
Do prawidłowego działania modułu wymagane są rozszerzenia hash oraz CURL.

## Instalacja
Instalacja typowa dla modułu Magento. 

## Konfiguracja
Konfiguracja modułu dostępna jest w panelu administracyjnym Magento pod ścieżką System > Konfiguracja > Sposoby płatności (zakładka Sprzedaż).

W obrębie modułu funkcjonują dwa komponenty Super_Payments - zawierający ekrany i funkcje, które mogą zostać wykorzystane przez inne bramki płatności oraz Super_Payu - zawierający implementację konkretnej bramki (tj. PayU).

### Super_Payments
Konfiguracja modułu Super_Payments dostępna jest w Sposoby płatności > Sekcja Super Payments.

| Nazwa pola | Opis |
| --- | --- |
| Hasło zabezpieczeń | Hasło służące do zabezpieczenia dostępu do transakcji zamówień złożonych jako gość. |


### Super_Payu

Konfiguracja modułu Super_Payments dostępna jest w Sposoby płatności > Sekcja Super Payu.

| Nazwa pola | Opis |
| --- | --- |
| Włączone | Pole służące do wskazania czy metoda płatności powinna być dostępna. |
| Tytuł | Nazwa metody płatności, widoczna dla klienta. |
| Kolejność sortowania | Określa kolejność wyświetlania metody płatności przy składaniu zamówienia. |
| Sandbox | Jeśli wybrane tak, żądania kierowane będą pod adres środowiska testowego PayU, zamiast produkcyjnego. |
| Identyfikator POS | Uzupełnić zgodnie z danymi uzyskanymi w punkcie płatności PayU. |
| Drugi klucz MD5 | Uzupełnić zgodnie z danymi uzyskanymi w punkcie płatności PayU. |
| Identyfikator klienta OAuth | Uzupełnić zgodnie z danymi uzyskanymi w punkcie płatności PayU. |
| Klucz klienta OAuth | Uzupełnić zgodnie z danymi uzyskanymi w punkcie płatności PayU. |

## Opis
Super_Payu umożliwa dokonanie płatności za zamówienie w systemie PayU.
Moduł pozwala na ponowne rozpoczęcię płatności, jeżeli wcześniejsza została zakończona niepowodzeniem. Użytkownik może w dowolnym momencie anulować płatność i zacząć od nowa. 

Widok panelu płatności:
![payu1](https://user-images.githubusercontent.com/14214995/49338252-42352b80-f61f-11e8-8a3f-be2ac8103e6c.png)

Widok ekranu, do którego użytkownik jest przekierowywany po dokonaniu płatności:
![payu2](https://user-images.githubusercontent.com/14214995/49338353-3433da80-f620-11e8-9c82-13d81c357971.png)


