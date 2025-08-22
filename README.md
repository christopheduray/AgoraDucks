# AgoraDucks

## Description

This project was a quick work for deploying a dedicated
rubber-duck-buying platform for an event (duck-race)
organised by the Agora (a local Ladies club, here in Ath).

Main requirements were
* as simple as possible
* dynamic or automatic choice of the ducks
* using Payconiq (almost) exclusively to avoid BO-work

## Code organization

The code is MVC-ish, organized in the following directories

| folder | content |
| --- | --- |
| app | definition of the routes |
| classes | all base classes (including a pseudo ORM and a poor man's router 'cause I'm lazy) |
| css | well ... the name is pretty self-explanatory |
| images | I think you guessed... |
| jobs | when Payconiq transactions are left hanging, the "reap" process takes care of them |
| storage | folder holding the generated QRs, sent as CIDs |
| trash | should be empty by now |
| views | views + duck-certificate template |
| sql | initialization of your mysql DB |

## Dependancies

The code had to work on a 7.3 version of PHP, so I took as few dependancies as possible.
At the time of writing this readme, I only have:
* guzzleHTTP + ca-bundle (for payconiq API calls)
* php-qrcode
* phpmailer

## configuration & initialization

Configuration has to occur in
* .env: fill in your payconiq api key, specify wether you're in production or not, talk about your DB
* classes/Config.php: price, number of ducks, event date and so on
* don't forget to run your composer-update
* the "storage" folder has to be writable by your server
* run job/initial_load.php once after creating the tables
* run the job/reap.php periodically


## License

Do-whatever-you-want-if-it-helps-you

If it helped you in any way, feel free to pay me a beer ;-)
