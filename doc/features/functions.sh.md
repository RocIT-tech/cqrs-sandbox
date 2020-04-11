# About the `functions.sh` file

You will find this file in the [cqrs-sandbox-infra](https://github.com/RocIT-tech/cqrs-sandbox-infra) repository.

The goal is to ease the use of containers. Back when I learned symfony 2.3 there was a good practice to create aliases like so:

```bash
$ alias dev='./app/console --env=dev'
$ alias prod='./app/console --env=prod'
```

But with the rise of docker it became:

```bash
$ alias dev='docker exec -it my_container_name app/console --env=dev'
# or
$ alias dev='docker-compose exec my_service_name app/console --env=dev'
```

1. Some use `docker` vs `docker-compose`
2. The name of the service or the container can be changed at any time.
3. aliases are evil and functions provide the same features added some extensibility

# How to use it ?

```bash
$ source ../cqrs-sandbox-infra/functions.sh
```

now you can execute all commands without worrying of containers like such:

```bash
# instead of `docker exec -it my_container_name app/console --env=dev c:c`
$ dev c:c

# instead of `docker exec -ti symfony_postgres sh -c "psql -U symfony -d symfony -l"`
$ pgsql -l
```

# Auto source the file

When we get used to this file we rarely forget to "source" it.
But at first it may be hard.

You can use a tool like [ondir](https://github.com/alecthomas/ondir) to help you.

## Install
```bash
# using Brew (Mac / Brew for linux)
$ brew install ondir

# using apt-get (linux)
$ sudo apt-get install -y ondir
```

## Configure it

First of all create the config itself:

```bash
# ~/.ondirrc
enter ~/my-project
    source ~/my-project-infra/functions.sh
```

Then tell your shell how to use this config:

I use zsh so I used [scripts.zsh](https://github.com/alecthomas/ondir/blob/master/scripts.zsh). Beware to add the content of the script at the end of your `~/.zshrc`.

Althoug it doesn't work when opening a new shell directly in the project directory.
To be able to get it working just add the following line at the end of `~/.zshrc`:

```bash
# ~/.zshrc

# ....
# rest of the file
# ...

eval "`ondir /`"
```
