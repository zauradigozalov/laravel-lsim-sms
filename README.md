![Image of social preview](https://repository-images.githubusercontent.com/144467857/69592b80-3cff-11ea-87da-adc82565af68)

# Laravel LSIM SMS

[![Latest Stable Version](https://poser.pugx.org/samirmh-dev/laravel-lsim-sms/v/stable)](https://packagist.org/packages/samirmh-dev/laravel-lsim-sms)
[![License](https://poser.pugx.org/samirmh-dev/laravel-lsim-sms/license)](https://packagist.org/packages/samirmh-dev/laravel-lsim-sms)

[![Total Downloads](https://poser.pugx.org/samirmh-dev/laravel-lsim-sms/downloads)](https://packagist.org/packages/samirmh-dev/laravel-lsim-sms)
[![Monthly Downloads](https://poser.pugx.org/samirmh-dev/laravel-lsim-sms/d/monthly)](https://packagist.org/packages/samirmh-dev/laravel-lsim-sms)
[![Daily Downloads](https://poser.pugx.org/samirmh-dev/laravel-lsim-sms/d/daily)](https://packagist.org/packages/samirmh-dev/laravel-lsim-sms)

[![composer.lock](https://poser.pugx.org/samirmh-dev/laravel-lsim-sms/composerlock)](https://packagist.org/packages/samirmh-dev/laravel-lsim-sms)
[![Latest Unstable Version](https://poser.pugx.org/samirmh-dev/laravel-lsim-sms/v/unstable)](https://packagist.org/packages/samirmh-dev/laravel-lsim-sms)

Laravel frameworkdə LSIM SMS provayderi (sendsms.az) ilə SMS göndərmək üçün paket. Paketdən istifadə etmək üçün LSIM provayderindən müvaffiq məlumatları (istifadəçi adı, şifrə və s.) əldə etməlisiniz.

### Funksionallıqlar
Bir funksiya ilə:

* SMS göndərilməsi
* Balansın yoxlanılması
* Balans bitməsi haqda avtomatik məlumatlandırma
* Reportların əldə edilməsi
* Çox dilli report yaratmaq imkanı
* **(TEZLİKLƏ)** BulkSMS
* **(TEZLİKLƏ)** Zengimcell (Azercell)
* **(TEZLİKLƏ)** MOPOS
* **(TEZLİKLƏ)** Peer-to-Peer

### İstifadəçi təlimatları

* [Demo](#demo)
* [Versiya tələbləri](#requirements)
* [Yükləmə](#installation)
* [Konfiqurasiya](#configuration)

## <a href='#demo' id='installation-guide' class='anchor' aria-hidden='true'>Demo</a>

**1. SMS Göndərilməsi**

```php
$message = 'Salam!';
$to = '+994XXYYYZZHH';
return LaravelLsimSms::sendSms($to,$message);
```

Sorğu nəticəsində sizə əməliyyat uğurlu isə tranzaksiya nömrəsi qaytarılır əks halda isə xəta kodu və məlumat verilir:

```json
{
    "response": {
    "successMessage": null,
    "obj": 123456789,
    "errorMessage": null,
    "errorCode": null
  },
  "status_code": 200
}
```

*Qeyd: Burada 123456789 tranzaksiya kodudur.*

**2. LSIM balansının yoxlanılması**

```php
return LaravelLsimSms::checkBalance();
```
Sorğu nəticəsində sizə balansınız, məsələn 3 qaytarılır. Yəni 3 ədəd SMS göndərə bilərsiniz:

```json
{
    "response": {
    "successMessage": null,
    "obj": 5,
    "errorMessage": null,
    "errorCode": null
  },
  "status_code": 200
}
```

*Qeyd: Burada 5 qalan balansdır.*

**3. Operatorun yoxlanılması**

```php
$number = '+994012345678';
return LaravelLsimSms::checkOperator($number);
```
Sorğu nəticəsində sizə string formatında operatorun adı (Azercell,Bakcell və ya Azerfon) qaytarılır:

```json
{
  "response": "Azercell",
  "status_code": 200
}
```

**4. Əməliyyat məlumatlarının əldə edilməsi**

```php
return LaravelLsimSms::getDetails('123456789');
```
Sorğu nəticəsində sizə əməliyyatın hazırkı statusu (gözləmədə, çatdırılıb, ləğv edilib və s. Bütün əməliyyat statusları ilə [konfiqurasiya](#configuration) hissəsində tanış ola bilərsiniz. Siz həmçinin onları dəyişdirə və ya yeni dil dəstəyi əlavə edə bilərsiniz.) qaytarılacaq:

```json
{
  "message": "Doğru olmayan tranzaksiya nömrəsi"
}
```

## <a href='#requirements' id='installation-guide' class='anchor' aria-hidden='true'>Versiya tələbləri</a>

 |||
 | --- | ---  |
 | PHP |\>= 5.5.0|
 | Laravel  |\>= 5.0|
 
## <a href='#installation' id='installation-guide' class='anchor' aria-hidden='true'>Yükləmə</a>

**1. Paketi yükləyin**

Paketi aşağıdakı komanda ilə proyektinizin composer.json faylına əlavə edin

```console
foo@bar:/home/laravel-lsim-sms-demo# composer require samirmh-dev/laravel-lsim-sms
```

**2. Xidmət provayderini əlavə edin**

Əgər istifadə etdiyiniz Laravel Framework versiyasi +5.5 isə bu mərhələni ötürə bilərsiniz.

Daha sonra xidmət provayderini və paket üçün qısa adı ```app/config/app.php``` faylında əlavə edin

```php
'providers' => [
    ...
    \samirmhdev\LaravelLsimSms\LsimServiceProvider::class,
],

'aliases' => [
    ...
    "LaravelLsimSms"=> \samirmhdev\LaravelLsimSms\Facades\LsimFacade::class,
]
```

**3.LSIM istifadəçi məlumatlarını əlavə edin**

```.env``` faylına aşağıdakı məlumatları əlavə edin:

```dotenv
LSIM_LOGIN=lorem
LSIM_PASSWORD=password
LSIM_SENDER=ipsum
```
*Qeyd edilən istifadəçi adı, şifrə və göndərici adını LSIM tərəfindən təqdim edilən məlumatlar ilə əvəz edin*
*Paketin əlavə funksionallıqları üçün əlavə edilməli məlumatlar ilə [konfiqurasiya](#configuration) bölməsində tanış ola bilərsiniz*

## <a href='#configuration' id='installation-guide' class='anchor' aria-hidden='true'>Konfiqurasiya</a>

Ehtiyac yaranarsa konfiqurasiya faylını dərc edə bilərsiniz:

```console
foo@bar:/home/laravel-lsim-sms-demo# php artisan vendor:publish --tag=laravel-lsim-config
```
Nəticədə paketin konfiqurasiya faylı redaktə edilmək üçün ```config/laravel-lsim-sms.php``` faylına dərc ediləcək.

**1. Balans bitməsi haqda avtomatik məlumatlandırma**

LaravelLsimSms paketi avtomatik olaraq balans bitdikdə qeyd edilən nömrəyə qeyd edilən mektubun göndərilməsi funksionallığına sahibdir. 

Funksionallıq standart olaraq deaktiv vəziyyətdədir. Aktiv etmək üçün ```.env``` faylında aşağıdakı əlavələr edilməlidir:

```php
LSIM_NOTIFIABLE_ENABLED = true
LSIM_NOTIFIABLE_NUMBER = "+994012345678"
```

Göndəriləcək mesaj standart olaraq ```Sizin LSIM balansınız bitmişdir!``` təyin edilmişdir. Redaktə etmək üçün ```.env``` faylında aşağıdakı əlavə edilməlidir:

```php
LSIM_NOTIFIABLE_MESSAGE = "Balans bitdi!"
```

**2. Çox dilli report imkanı**

LaravelLsimSms paketi report məlumatların (əməliyyat məlumatları) əldə edilərkən seçdiyiniz dilə uyğun əldə edilməsi imkanına sahibdir. Standart olaraq 2 dildə (Azərbaycan və İngilis dillərində) tərcümələr paket ilə bərabər təqdm edilmşdir. 

Növbəti dilin əlavə edilməsi üçün dil fayllarını dərc etməlisiniz:

```console
foo@bar:/home/laravel-lsim-sms-demo# php artisan vendor:publish --tag=laravel-lsim-translations
```

Nəticədə tərcümə faylları ```resources/lang/vendor/laravel_lsim``` qovluğuna əlavə ediləcək. (Müvaffiq olaraq hər dil üçün alt qovluqlar yaradılmalıdır: ```resources/lang/vendor/laravel_lsim/tr```, ```resources/lang/vendor/laravel_lsim/ru``` və s.)

Qovluq daxilindəki ```report.php``` faylı özündə tərcümələri saxlayır. Yeni yaradılacaq dil üçün həmin faylı əlavə edib daxilindəki məlumatları dəyişdirməyiniz kifayətdir.

**3. Debug mod**

Göndəriən sorğulara gələn cavablarla birlikdə sorğu məlumatlarınında əldə edilməsi üçün debug mod ```.env``` faylında aktiv edilməlidir:

```php
LSIM_DEBUG = true
```

*Debug mod aktiv edildiyi halda sorğu nəticələri string formatında qaytarılacaq. (Debug mod deaktiv olduqda JSON formatında qaytarılır)*
