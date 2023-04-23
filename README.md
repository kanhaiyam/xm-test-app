# About APP
- This app was created as part of the solution for the XM PHP Interview Tests




## Configuration

- Please replace the contents of `.env` with `.env.example`.
- I have added all the symbols data in a sqlite database assuming you have sqlite if not please set up a database of your choosing with the help of `.env`.
- To send mail in this exercise I have used `mailtrap.io`, please use the proper `.env` configuration for that.
- You will also have to update the Rapid Yahoo Finance API Key in the same `.env` under the key `RAPID_API_KEY`
- Please check the section `#Details You need to ADD` in the `.env` for ease.




## Set Up Instructions

- I have added all the symbols data in a sqlite database assuming you have sqlite if not please set up a database of your choosing with the help of `.env`.
- So you will have to run migrations and seed the data into the database.
- Please execute the commands below for that:

```php artisan migrate```

```php artisan db:seed```

- To run the application please use the command:
```php artisan serve```



## Assumptions and Decisions Made

- While tinkering the API I noticed that we only data for the year starting with this day a year back so the start date cannot be less than `today - 365 Days`




## License

The exercise is licensed under the [MIT license](https://opensource.org/licenses/MIT).
