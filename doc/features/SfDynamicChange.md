# Dynamically change Symfony Environment

Back in the old days of Symfony we had `app.php` and `app_dev.php`. But now we only have `index.php` and no way of seeing the difference between `dev` and `prod` locally without clearing the cache in between.

If you check the `public/index.php` you will see that we tweaked it a bit.

If you want to be able to switch env, you need to declare an env variable called `APP_SWITCH_ENV` and set it to `true`. It acts as a security lock. If it is not present or is equal to `false` only the `APP_ENV` is used.

If you set it to `true` you can pass the following headers:
```
APP-ENV: prod
APP-DEBUG: false
```

and override dynamically the `APP_ENV` and `APP_DEBUG` variables
