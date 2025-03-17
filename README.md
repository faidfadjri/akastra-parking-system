![Alt text](https://akastra.id/assets/images/article/d3841a529cdd7462c0c0f4f7b4bc35fe.jpg?raw=true "Title")

# **E Parking Akastra**

Akastra Toyota adalah sebuah bengkel resmi toyota di kawasan Jalan Raya Kebayoran Lama, Jl. Palmerah Barat VII No.26, RT.1/RW.2, North Sukabumi, Kebonjeruk, Jakarta.

Akastra Toyota merupakan satu satunya bengkel resmi toyota yang hanya mengandalkan **After Sales** artinya **Tidak ada Penjualan**.

Akastra mempunyai permasalahan dari sisi area parkir kendaraan yang crowded. Oleh Karena itu salah satu improvement yang dilakukan adalah membangun sistem e parking yang mana sistem ini nantinya akan digunakan oleh security untuk memonitoring kendaraan di area parkir.

## Framework & Library

Framework ini dibangun dengan `Codeigniter 4`, `Bootstrap 5` dan `JQuery`

## Requirement

| Tools      | Type    | Description               |
| :--------- | :------ | :------------------------ |
| `DATABASE` | `MySQL` | Laragon or Xampp or Lampp |
| `PHP`      | `8.1.0` | > 7.4                     |

## Installation in local

Buat Database dengan nama _parkir_

Install _composer package_

```bash
composer install
```

_Table_ Migration

```bash
php spark migrate
```

User Seeding

```bash
php spark db:seed UserSeeder
```

Vehicle Model Seeding

```bash
php spark db:seed Model
```

Parking Capacity Seeding

```bash
php spark db:seed Capacity
```

Default Account

```bash
username : admin
password : admin
```

Running Spark

```bash
php spark serve
```

Yeay! You read to go 🎉

## Developer

- [Mohamad Faid Fadjri](https://faidfadjri.github.io)

## Feedback

If you have any feedback, please reach out to me at faidfadjri@gmail.com
